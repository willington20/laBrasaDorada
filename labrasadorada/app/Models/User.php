<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {

    protected $fillable = ['name', 'email', 'password', 'rol'];
    protected $hidden   = ['password', 'remember_token'];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array {
        return [];
    }

    // Verifica si el usuario es admin
    public function esAdmin(): bool {
        return $this->rol === 'admin';
    }
}