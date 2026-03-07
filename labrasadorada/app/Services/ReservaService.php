<?php
namespace App\Services;
use App\Interfaces\ReservaRepositoryInterface;

class ReservaService {
    public function __construct(
        private ReservaRepositoryInterface $reservaRepository
    ) {}

    public function crearReserva(array $datos): array {
        $reserva = $this->reservaRepository->crear($datos);
        return [
            'success' => true,
            'mensaje' => '¡Reserva confirmada! Te contactaremos pronto.',
            'data'    => $reserva
        ];
    }

    public function obtenerTodas(): object {
        return $this->reservaRepository->todas();
    }
}
