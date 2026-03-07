<?php
namespace App\Http\Controllers;
use App\Services\ReservaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReservaController extends Controller {

    public function __construct(
        private ReservaService $reservaService
    ) {}

    public function store(Request $request): JsonResponse {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'fecha'    => 'required|date',
            'hora'     => 'required',
            'personas' => 'required|string',
        ]);
        $resultado = $this->reservaService->crearReserva($request->all());
        return response()->json($resultado, 201);
    }

    public function index(): JsonResponse {
        $reservas = $this->reservaService->obtenerTodas();
        return response()->json($reservas);
    }
}