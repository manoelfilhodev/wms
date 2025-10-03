<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Setores\RecebimentoController;
use App\Http\Controllers\Api\RecebimentoApiController;
use App\Http\Controllers\Api\ConferenciaApiController;
use App\Http\Controllers\Auth\MicrosoftApiController;
use App\Http\Controllers\KitMontagemController;
use App\Http\Controllers\Api\DemandaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Setores\ArmazenagemController;
use App\Http\Controllers\ContagemLivreController;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


Route::post('/contagem-livre', [ContagemLivreController::class, 'store']);

Route::prefix('armazenagem')->group(function () {
    Route::get('/buscarDescricaoApi', [ArmazenagemController::class, 'buscarDescricaoApi']);
    Route::get('/buscarPosicoes', [ArmazenagemController::class, 'buscarPosicoes']);
    Route::post('/store', [ArmazenagemController::class, 'store']);
    Route::post('/store-api', [ArmazenagemController::class, 'storeApi']);


});



Route::prefix('contagem-livre')->group(function () {
    // Buscar SKU pelo EAN (ex: GET /api/contagem-livre/buscarDescricaoApi?ean=123...)
    Route::get('/buscarDescricaoApi', [ContagemLivreController::class, 'buscarDescricaoApi']);

    // Salvar contagem livre (POST /api/contagem-livre/store)
    Route::post('/store', [ContagemLivreController::class, 'store']);
});

Route::get('/apontamentos/hoje', function () {
    $hoje = Carbon::today();

    // 🔹 Meta = total de etiquetas/paletes gerados no dia
    $meta = DB::table('_tb_apontamentos_kits')
        ->whereDate('data', $hoje)
        ->count();

    // 🔹 Buscar apontamentos de hoje
    $apontamentos = DB::table('_tb_apontamentos_kits')
        ->whereDate('data', $hoje)
        ->orderBy('updated_at')
        ->get(['updated_at']);

    // Montar acumulado
    $acumulado = [];
    $count = 0;
    foreach ($apontamentos as $a) {
        $count++;
        $acumulado[] = [
            'hora' => Carbon::parse($a->updated_at)->format('H:i'),
            'acumulado' => $count,
        ];
    }

    return response()->json([
        'meta' => $meta,
        'produzido' => $count,
        'apontamentos' => $acumulado,
    ]);
});

Route::get('/apontamentos/ultimos', [KitMontagemController::class, 'apiUltimosApontamentos']);
Route::post('/apontamento', [KitMontagemController::class, 'apiApontarPorEtiqueta']);

Route::post('/login-microsoft', [MicrosoftApiController::class, 'loginFromApp']);

// Login API
Route::post('/login', [AuthController::class, 'apiLogin']);

// Rotas protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // ðŸ“¦ Rotas de Recebimento (API)
    Route::get('/recebimentos', [RecebimentoApiController::class, 'listar']);
    Route::get('/recebimentos/{id}', [RecebimentoApiController::class, 'detalhes']);
    Route::post('/recebimentos/{id}/foto-inicio', [RecebimentoApiController::class, 'uploadFotoInicio']);
    
    Route::post('/recebimentos/{id}/foto-fim', [RecebimentoApiController::class, 'uploadFotoFim']);
    
    Route::post('/recebimentos/{id}/assinatura-fim', [RecebimentoApiController::class, 'uploadAssinaturaFim']);
    
    Route::post('/recebimentos/{id}/finalizar', [RecebimentoApiController::class, 'finalizarConferencia']);




    // ðŸ“‹ Rotas de ConferÃªncia (API)
    Route::get('/recebimentos/{id}/itens', [ConferenciaApiController::class, 'listarItens']);
    Route::get('/conferencia/item/{id}', [ConferenciaApiController::class, 'detalheItem']);
    Route::post('/conferencia/item/{id}', [ConferenciaApiController::class, 'salvarItem']);
    Route::post('/recebimentos/{id}/fechar', [ConferenciaApiController::class, 'fecharConferencia']);
});

// Rotas para uso no painel web (nÃ£o precisam do Sanctum)
Route::get('/painel/recebimentos', [RecebimentoController::class, 'listar']);


// Rotas sem autenticação
Route::get('/demandas', [DemandaController::class, 'index']);
Route::get('/demandas/{id}', [DemandaController::class, 'show']);
Route::post('/demandas/{id}/status', [DemandaController::class, 'atualizarStatus']);
Route::get('/demandas/{id}/historico', [DemandaController::class, 'historico']);

Route::put('/demandas/{id}', [DemandaController::class, 'update']);