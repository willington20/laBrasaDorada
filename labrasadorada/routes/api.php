<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// ── Rutas públicas (sin token) ──
Route::prefix('auth')->group(function () {
    Route::post('/registro', [AuthController::class, 'registro']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// ── Rutas de reservas (públicas) ──
Route::post('/reservas', [ReservaController::class, 'store']);
Route::get('/reservas',  [ReservaController::class, 'index']);

// ── Rutas protegidas (requieren token JWT) ──
Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);
    Route::get('/pedidos',             [PedidoController::class, 'index']);
    Route::post('/pedidos',            [PedidoController::class, 'store']);
    Route::post('/pedidos/{id}/pagar', [PedidoController::class, 'pagar']);
});

// ── Rutas de Admin (JWT + rol admin) ──
Route::middleware(['auth:api', 'es.admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard',        [AdminController::class, 'dashboard']);
    Route::get('/reservas',         [AdminController::class, 'reservas']);
    Route::put('/reservas/{id}',    [AdminController::class, 'actualizarReserva']);
    Route::delete('/reservas/{id}', [AdminController::class, 'eliminarReserva']);
    Route::get('/pedidos',          [AdminController::class, 'pedidos']);
});