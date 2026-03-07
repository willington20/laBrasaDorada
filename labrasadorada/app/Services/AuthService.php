<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService {

    // REGISTRO
    public function registrar(array $datos): array {
        $user = User::create([
            'name'     => $datos['name'],
            'email'    => $datos['email'],
            'password' => Hash::make($datos['password'])
        ]);

        $token = JWTAuth::fromUser($user);

        return [
            'success' => true,
            'mensaje' => '¡Registro exitoso!',
            'token'   => $token,
            'user'    => $user
        ];
    }

    // LOGIN
    public function login(array $credenciales): array {
        if (!$token = JWTAuth::attempt($credenciales)) {
            return [
                'success' => false,
                'mensaje' => 'Email o contraseña incorrectos'
            ];
        }

        return [
            'success' => true,
            'mensaje' => '¡Bienvenido!',
            'token'   => $token,
            'user'    => auth()->user()
        ];
    }

    // LOGOUT
    public function logout(): array {
        JWTAuth::invalidate(JWTAuth::getToken());
        return ['success' => true, 'mensaje' => 'Sesión cerrada'];
    }
}