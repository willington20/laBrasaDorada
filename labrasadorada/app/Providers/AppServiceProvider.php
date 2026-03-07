<?php
namespace App\Providers;
use App\Interfaces\ReservaRepositoryInterface;
use App\Repositories\ReservaRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    public function register(): void {
        $this->app->bind(
            ReservaRepositoryInterface::class,
            ReservaRepository::class
        );
    }

    public function boot(): void {}
}
