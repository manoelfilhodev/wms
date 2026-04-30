<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemandaController;

Route::post('/demandas/import', [DemandaController::class, 'import'])
    ->middleware(['auth', 'demanda.perfil:sala'])
    ->name('demandas.import');

Route::get('/demandas/import', function () {
    return view('demandas.import');
})->middleware(['auth', 'demanda.perfil:sala'])->name('demandas.import.view');



Route::prefix('demandas')->middleware(['auth'])->group(function () {
    Route::get('/', [DemandaController::class, 'index'])->name('demandas.index');
    Route::get('/operacional', [DemandaController::class, 'operacional'])->middleware('demanda.perfil:operacional')->name('demandas.operacional');
    Route::get('/dashboard-operacional', [DemandaController::class, 'dashboardOperacional'])->middleware('demanda.perfil:operacional')->name('demandas.dashboardOperacional');
    Route::get('/relatorios', [DemandaController::class, 'relatoriosOperacional'])->middleware('demanda.perfil:operacional')->name('demandas.relatorios');
    Route::get('/create', [DemandaController::class, 'create'])->middleware('demanda.perfil:sala')->name('demandas.create');
    Route::post('/store', [DemandaController::class, 'store'])->middleware('demanda.perfil:sala')->name('demandas.store.manual');
    Route::post('/{id}/iniciar-separacao', [DemandaController::class, 'iniciarSeparacao'])->middleware('demanda.perfil:operacional')->name('demandas.iniciarSeparacao');
    Route::post('/{id}/finalizar-separacao', [DemandaController::class, 'finalizarSeparacao'])->middleware('demanda.perfil:operacional')->name('demandas.finalizarSeparacao');
    Route::post('/{id}/finalizar-separador', [DemandaController::class, 'finalizarSeparador'])->middleware('demanda.perfil:operacional')->name('demandas.finalizarSeparador');
    Route::post('/{id}/distribuir', [DemandaController::class, 'distribuirDt'])->middleware('demanda.perfil:operacional')->name('demandas.distribuir');
});

Route::resource('demandas', DemandaController::class)->except(['show'])->middleware(['auth']);
Route::get('/demandas/export', [DemandaController::class, 'export'])->middleware(['auth'])->name('demandas.export');
Route::patch('/demandas/{id}/status', [DemandaController::class, 'updateStatus'])->middleware(['auth'])->name('demandas.updateStatus');
Route::patch('/demandas/update-multiple', [DemandaController::class, 'updateMultiple'])->middleware(['auth'])->name('demandas.updateMultiple');
