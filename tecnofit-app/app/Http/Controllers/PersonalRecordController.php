<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\PersonalRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class PersonalRecordController extends Controller
{
    const DEADLIFT = 'Deadlift';
    const BACKSQUAT = 'Back Squat';
    const BENCHPRESS = 'Bench Press';

    public static function getMovements(): array
    {
        return [
            self::DEADLIFT,
            self::BACKSQUAT,
            self::BENCHPRESS,
        ];
    }

    public function list($movement): JsonResponse
    {
        if (!Auth::user()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!in_array($movement, self::getMovements())) {
            return response()->json([
                "status" => false,
                "message" => 'Movement Not mapped',
                "data" => [],
                "count" => 0
            ], 404);
        }

        $movementExists = Movement::where(
            'name', $movement
        )->first();

        if (!$movementExists) {
            return response()->json([
                "status" => false,
                "message" => 'Movement Not Found',
                "data" => [],
                "count" => 0
            ], 404);
        }

        $records = PersonalRecord::where(
            'movement_id', $movementExists->id
        )
        ->leftJoin('users as u', 'u.id', '=', 'user_id')
        ->orderBy('value', 'DESC')
        ->get();

        $rank = 0;
        $recordsRank = [];
        $personalRecord = null;
        foreach ($records as $record) {
            if ($personalRecord !== $record->value) {
                $rank++;
            }

            $recordsRank[] = [
                'rank' => $rank,
                'user_name' => $record->name,
                'value' => $record->value,
                'date' => $record->date
            ];

            $personalRecord = $record->value;
        }

        return response()->json([
            'status' => true,
            'movement' => $movement,
            'data' => $recordsRank,
            'count' => count($recordsRank) ?? 0
        ], 200);
    }

}
