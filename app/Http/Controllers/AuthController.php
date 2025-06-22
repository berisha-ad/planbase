<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Auth::login($user);

        return response()->json($user);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $login_successful = Auth::attempt($request->only('email', 'password'));

        if(!$login_successful) {
            $error = [
                'message' => 'Die Eingaben sind ungültig.',
                'errors' => [
                    'email' => ['Die E-Mail-Adresse oder das Passwort ist falsch.'],
                ]
                ];

                return response()->json(status:422, data: $error);
        }

        $request->session()->regenerate();

        return response()->json(Auth::user());
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Erfolgreich abgemeldet.']);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json(status: 200, data: $request->user());
    }
}
