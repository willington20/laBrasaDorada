<?php
namespace App\Http\Controllers;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller {

    public function __construct(
        private AuthService $authService
    ) {}

    public function registro(Request $request): JsonResponse {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ]);
        $resultado = $this->authService->registrar($request->all());
        return response()->json($resultado, 201);
    }

    public function login(Request $request): JsonResponse {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);
        $resultado = $this->authService->login($request->only('email', 'password'));
        $status = $resultado['success'] ? 200 : 401;
        return response()->json($resultado, $status);
    }

    public function logout(): JsonResponse {
        $resultado = $this->authService->logout();
        return response()->json($resultado);
    }

    public function me(): JsonResponse {
        return response()->json([
            'success' => true,
            'user'    => auth()->user()
        ]);
    }
}