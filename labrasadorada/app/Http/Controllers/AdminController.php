<?php
namespace App\Http\Controllers;
use App\Models\Reserva;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller {

    // GET /api/admin/dashboard
    public function dashboard(): JsonResponse {
        $hoy = now()->toDateString();

        return response()->json([
            'success'            => true,
            'reservas_hoy'       => Reserva::whereDate('fecha', $hoy)->count(),
            'reservas_total'     => Reserva::count(),
            'pedidos_pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'ingresos_hoy'       => Pedido::whereDate('created_at', $hoy)->where('estado','pagado')->sum('total'),
            'ingresos_total'     => Pedido::where('estado', 'pagado')->sum('total'),
            'usuarios_total'     => User::count(),
        ]);
    }

    // GET /api/admin/reservas
    public function reservas(): JsonResponse {
        $reservas = Reserva::orderBy('fecha', 'desc')->get();
        return response()->json(['success' => true, 'data' => $reservas]);
    }

    // PUT /api/admin/reservas/{id}
    public function actualizarReserva(int $id): JsonResponse {
        $reserva = Reserva::findOrFail($id);
        $reserva->update(request()->only(['estado']));
        return response()->json(['success' => true, 'mensaje' => 'Reserva actualizada', 'data' => $reserva]);
    }

    // DELETE /api/admin/reservas/{id}
    public function eliminarReserva(int $id): JsonResponse {
        Reserva::findOrFail($id)->delete();
        return response()->json(['success' => true, 'mensaje' => 'Reserva eliminada']);
    }

    // GET /api/admin/pedidos
    public function pedidos(): JsonResponse {
        $pedidos = Pedido::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $pedidos]);
    }
}