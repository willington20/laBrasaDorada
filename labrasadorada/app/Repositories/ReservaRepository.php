<?php
namespace App\Repositories;
use App\Interfaces\ReservaRepositoryInterface;
use App\Models\Reserva;

class ReservaRepository implements ReservaRepositoryInterface {

    public function crear(array $datos): object {
        return Reserva::create($datos);
    }

    public function todas(): object {
        return Reserva::orderBy('fecha', 'desc')->get();
    }

    public function buscarPorId(int $id): ?object {
        return Reserva::find($id);
    }
}