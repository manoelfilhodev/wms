<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demanda;
use App\Exports\DemandasExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DemandaHistory;
use Illuminate\Support\Facades\DB;

class DemandaController extends Controller
{
    // Listagem
    public function index(Request $request)
{
    $query = Demanda::query();

    if ($request->filled('fo')) {
        $query->where('fo', 'like', "%{$request->fo}%");
    }

    if ($request->filled('transportadora')) {
        $query->where('transportadora', 'like', "%{$request->transportadora}%");
    }

    if ($request->filled('cliente')) {
        $query->where('cliente', 'like', "%{$request->cliente}%");
    }

    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }

    if ($request->filled('data_inicio') && $request->filled('data_fim')) {
        $query->whereBetween('created_at', [$request->data_inicio, $request->data_fim]);
    }

    $demandas = $query->orderBy('created_at', 'desc')->paginate(20);

    return view('demandas.index', compact('demandas'));
}


    // Formulário de criação
    public function create()
    {
        return view('demandas.create');
    }

    // Salvar nova demanda
    public function store(Request $request)
    {
        $request->validate([
            'fo' => 'required|string|max:50',
            'cliente' => 'required|string|max:150',
            'tipo' => 'required|in:RECEBIMENTO,EXPEDICAO',
        ]);

        Demanda::create([
            'fo' => $request->fo,
            'cliente' => $request->cliente,
            'transportadora' => $request->transportadora,
            'doca' => $request->doca,
            'tipo' => $request->tipo,
            'quantidade' => $request->quantidade,
            'peso' => $request->peso,
            'valor_carga' => $request->valor_carga,
            'hora_agendada' => $request->hora_agendada,
            'entrada' => $request->entrada,
            'saida' => $request->saida,
            'status' => 'GERAR', // sempre inicia em GERAR
        ]);

        return redirect()->route('demandas.index')->with('success', 'Demanda lançada com sucesso!');
    }
    
    // Exibir formulário de edição
public function edit($id)
{
    $demanda = Demanda::findOrFail($id);
    return view('demandas.edit', compact('demanda'));
}

// Atualizar demanda
public function update(Request $request, $id)
{
    $request->validate([
        'fo'              => 'required|string|max:50',
        'cliente'         => 'required|string|max:150',
        'tipo'            => 'required|in:RECEBIMENTO,EXPEDICAO',
        'transportadora'  => 'nullable|string|max:150',
        'doca'            => 'nullable|string|max:10',
        'quantidade'      => 'nullable|integer|min:0',
        'peso'            => 'nullable|numeric',
        'valor_carga'     => 'nullable|numeric',
        'hora_agendada'   => 'nullable',
        'entrada'         => 'nullable',
        'saida'           => 'nullable',
        'veiculo'         => 'nullable|string|max:50',
        'modelo_veicular' => 'nullable|string|max:150',
        'motorista'       => 'nullable|string|max:150',
    ]);

    $demanda = Demanda::findOrFail($id);

    $demanda->update([
        'fo'              => $request->fo,
        'cliente'         => $request->cliente,
        'transportadora'  => $request->transportadora,
        'doca'            => $request->doca,
        'tipo'            => $request->tipo,
        'quantidade'      => $request->quantidade ?? 0,
        'peso'            => $request->peso ?? 0,
        'valor_carga'     => $request->valor_carga ?? 0,
        'hora_agendada'   => $request->hora_agendada,
        'entrada'         => $request->entrada,
        'saida'           => $request->saida,
        'veiculo'         => $request->veiculo,
        'modelo_veicular' => $request->modelo_veicular,
        'motorista'       => $request->motorista,
        // status não editamos aqui para não quebrar o fluxo
    ]);

    return redirect()->route('demandas.index')->with('success', 'Demanda atualizada com sucesso!');
}


public function destroy($id)
{
    $demanda = Demanda::findOrFail($id);
    $demanda->delete();

    return redirect()->route('demandas.index')->with('success', 'Demanda excluída com sucesso!');
}

public function export(Request $request)
{
    return Excel::download(new DemandasExport($request), 'demandas_filtradas.xlsx');
}

public function updateStatus(Request $request, $id)
{
    $request->validate(['status' => 'required|string']);

    $demanda = Demanda::findOrFail($id);
    $demanda->status = $request->status;
    $demanda->save();

    // Salvar histórico
    DemandaHistory::create([
        'demanda_id' => $demanda->id,
        'status' => $request->status,
        'changed_by' => auth()->user()->id_user ?? null,
    ]);

    return back()->with('success', "Status da FO {$demanda->fo} atualizado!");
}

public function updateMultiple(Request $request)
{
    $request->validate([
        'status' => 'required|string',
        'ids' => 'required|array'
    ]);

    foreach ($request->ids as $id) {
        $demanda = Demanda::find($id);
        if ($demanda) {
            $demanda->update(['status' => $request->status]);

            DemandaHistory::create([
                'demanda_id' => $demanda->id,
                'status' => $request->status,
                'changed_by' => auth()->user()->id_user ?? null,
            ]);
        }
    }

    return back()->with('success', 'Status atualizado em lote com sucesso!');
}

public function import(Request $request)
{
    if (!$request->filled('planilha')) {
        return back()->with('error', 'Nenhum dado foi enviado.');
    }

    $linhas = preg_split("/\r\n|\n|\r/", trim($request->planilha));

    $inseridos = 0;
    foreach ($linhas as $index => $linha) {
        if ($index === 0) continue; // pula cabeçalho

        $colunas = preg_split("/\t+/", trim($linha)); // separa por TAB do Excel

        // garante pelo menos 8 colunas (com valores nulos se faltar)
        $colunas = array_pad($colunas, 8, null);

        try {
            DB::table('_tb_demanda')->insert([
                'fo'              => $colunas[1],
                'cliente'         => null, // pode mapear depois
                'transportadora'  => $colunas[2],
                'veiculo'         => $colunas[3] ?? null,
                'modelo_veicular' => $colunas[4] ?? null,
                'quantidade'      => (int) ($colunas[5] ?? 0),
                'motorista'       => $colunas[6] ?? null,
                'peso'            => $this->converteNumero($colunas[7] ?? 0),
                'doca'            => null,
                'tipo'            => 'EXPEDICAO',
                'valor_carga'     => 0,
                'hora_agendada'   => $colunas[0],
                'entrada'         => null,
                'saida'           => null,
                'status'          => 'GERAR',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
            $inseridos++;
        } catch (\Exception $e) {
            // loga erro mas não quebra todo processo
            \Log::error("Erro ao importar demanda: ".$e->getMessage());
        }
    }

    return back()->with('success', "Foram importadas {$inseridos} demandas com sucesso!");
}

private function converteNumero($valor)
{
    if (!$valor || trim($valor) === '') {
        return 0;
    }

    // converte 10.291,34 → 10291.34
    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);

    return (float) $valor;
}


}



