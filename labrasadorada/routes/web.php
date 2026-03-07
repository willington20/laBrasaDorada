<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReservaController;
Route::post('/reservas', [ReservaController::class, 'store']);
Route::get('/reservas', [ReservaController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});
