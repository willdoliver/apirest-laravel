<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }
        
        $movements = Movement::all();

        return response()->json([
            'status' => true,
            'data' => $movements,
            'count' => count($movements) ?? 0
        ], 200);
    }

}
