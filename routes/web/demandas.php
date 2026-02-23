<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemandaController;

Route::post('/demandas/import', [DemandaController::class, 'import'])->name('demandas.import');

Route::get('/demandas/import', function () {
    return view('demandas.import');
})->name('demandas.import.view');



Route::prefix('demandas')->group(function () {
    Route::get('/', [DemandaController::class, 'index'])->name('demandas.index');
    Route::get('/create', [DemandaController::class, 'create'])->name('demandas.create');
    Route::post('/store', [DemandaController::class, 'store'])->name('demandas.store.manual');
});

Route::resource('demandas', DemandaController::class)->except(['show']);
Route::get('/demandas/export', [DemandaController::class, 'export'])->name('demandas.export');
Route::patch('/demandas/{id}/status', [DemandaController::class, 'updateStatus'])->name('demandas.updateStatus');
Route::patch('/demandas/update-multiple', [DemandaController::class, 'updateMultiple'])->name('demandas.updateMultiple');





