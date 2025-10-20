<?php

use App\Http\Controllers\Unit\ApplicationController;
use App\Http\Controllers\Unit\DashboardController;
use App\Http\Controllers\Unit\ListenerController;
use App\Http\Controllers\Unit\RouteController;
use Illuminate\Support\Facades\Route;

Route::prefix('unit')->name('api.unit.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::apiResource('applications', ApplicationController::class)->except(['create', 'edit']);
    Route::post('applications/{application}/toggle', [ApplicationController::class, 'toggle'])->name('applications.toggle');

    Route::apiResource('listeners', ListenerController::class)->except(['create', 'edit']);
    Route::post('listeners/{listener}/toggle', [ListenerController::class, 'toggle'])->name('listeners.toggle');

    Route::apiResource('routes', RouteController::class)->except(['create', 'edit']);
    Route::post('routes/{route}/toggle', [RouteController::class, 'toggle'])->name('routes.toggle');
});
