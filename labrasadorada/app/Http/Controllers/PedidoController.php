<?php
namespace App\Http\Controllers;
use App\Services\PedidoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PedidoController extends Controller {

    public function __construct(
        private PedidoService $pedidoService
    ) {}

    // POST /api/pedidos — crear pedido con carrito
    public function store(Request $request): JsonResponse {
        $request->validate([
            'items'             => 'required|array|min:1',
            'items.*.nombre'    => 'required|string',
            'items.*.precio'    => 'required|numeric',
            'items.*.cantidad'  => 'required|integer|min:1'
        ]);

        $resultado = $this->pedidoService->crearPedido(
            $request->items,
            auth()->id()
        );
        return response()->json($resultado, 201);
    }

    // POST /api/pedidos/{id}/pagar — pago simulado
    public function pagar(int $id): JsonResponse {
        $resultado = $this->pedidoService->pagar($id, auth()->id());
        $status = $resultado['success'] ? 200 : 404;
        return response()->json($resultado, $status);
    }

    // GET /api/pedidos — mis pedidos
    public function index(): JsonResponse {
        $pedidos = $this->pedidoService->misPedidos(auth()->id());
        return response()->json($pedidos);
    }
}
