<?php
namespace App\Interfaces;

interface ReservaRepositoryInterface {
    public function crear(array $datos): object;
    public function todas(): object;
    public function buscarPorId(int $id): ?object;
}