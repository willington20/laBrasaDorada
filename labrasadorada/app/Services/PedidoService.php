<?php
namespace App\Services;
use App\Models\Pedido;

class PedidoService {

    // CREAR PEDIDO
    public function crearPedido(array $items, int $userId): array {
        $total = collect($items)->sum(function($item) {
            return $item['precio'] * $item['cantidad'];
        });

        $pedido = Pedido::create([
            'user_id' => $userId,
            'items'   => $items,
            'total'   => $total,
            'estado'  => 'pendiente'
        ]);

        return [
            'success' => true,
            'mensaje' => '¡Pedido creado!',
            'data'    => $pedido
        ];
    }

    // PAGAR PEDIDO (simulado)
    public function pagar(int $pedidoId, int $userId): array {
        $pedido = Pedido::where('id', $pedidoId)
                        ->where('user_id', $userId)
                        ->first();

        if (!$pedido) {
            return ['success' => false, 'mensaje' => 'Pedido no encontrado'];
        }

        $pedido->update(['estado' => 'pagado']);

        return [
            'success' => true,
            'mensaje' => '¡Pago confirmado! Gracias por tu compra 🎉',
            'data'    => $pedido
        ];
    }

    // MIS PEDIDOS
    public function misPedidos(int $userId): object {
        return Pedido::where('user_id', $userId)
                     ->orderBy('created_at', 'desc')
                     ->get();
    }
}