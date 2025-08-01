<?php

use Illuminate\Support\Facades\Route;

// Vue SPA routes - all routes return the app view
Route::get('/', function () {
    return view('app');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
