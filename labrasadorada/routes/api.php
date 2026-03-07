<?php
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;

Route::post('/reservas', [ReservaController::class, 'store']);
Route::get('/reservas',  [ReservaController::class, 'index']);