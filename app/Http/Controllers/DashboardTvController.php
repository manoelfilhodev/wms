<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardTvController extends Controller
{
    
    
    protected DashboardService $dashboardService;
    
    
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index() 
    {
        $ranking = $this->dashboardService->getRankingOperadores();
        $dadosMensais = $this->dashboardService->getDadosMensaisPorDia();
        $resumo = $this->dashboardService->getResumoDoDia();
        $kitsHoje = $this->dashboardService->getProducaoDeKitsHoje();
        $kitsMensal = $this->dashboardService->getProducaoDeKitsMensal();

    
        return view('dashboard.tv', [
            'topSeparacao' => $ranking['separacao'],
            'topArmazenagem' => $ranking['armazenagem'],
            'dias' => $dadosMensais['dias'],
            'armazenagemMes' => $dadosMensais['armazenagem'],
            'separacaoMes' => $dadosMensais['separacao'],
            'paletesMes' => $dadosMensais['paletes'],
            'kitsHoje' => $kitsHoje,
            'kitsMensal' => $kitsMensal,

        ]);
    }
    
    public function dados()
    {
        $ranking = $this->dashboardService->getRankingOperadores();
        $dadosMensais = $this->dashboardService->getDadosMensaisPorDia();
        $resumo = $this->dashboardService->getResumoDoDia();
    
        return response()->json([
            'topSeparacao' => $ranking['separacao'],
            'topArmazenagem' => $ranking['armazenagem'],
            'dias' => $dadosMensais['dias'],
            'separacaoMes' => $dadosMensais['separacao'],
            'armazenagemMes' => $dadosMensais['armazenagem'],
            'paletesMes' => $dadosMensais['paletes'],
            'resumo' => $resumo,
        ]);
    }
    
    public function painelTV()
    {
        $containers = [
            (object)['id' => 1, 'codigo' => 'A01', 'progresso' => 12],
            (object)['id' => 2, 'codigo' => 'A02', 'progresso' => 48],
            (object)['id' => 3, 'codigo' => 'A03', 'progresso' => 71],
            (object)['id' => 4, 'codigo' => 'A04', 'progresso' => 95],
            (object)['id' => 5, 'codigo' => 'A05', 'progresso' => 100],
        ];
    
        return view('tv', compact('containers'));
    }

}
