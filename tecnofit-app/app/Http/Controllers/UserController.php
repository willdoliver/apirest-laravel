<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }
        
        $users = User::all();

        return response()->json([
            'status' => true,
            'data' => $users,
            'count' => count($users) ?? 0
        ], 200);
    }

}
