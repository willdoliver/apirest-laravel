<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
