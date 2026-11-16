<?php

namespace App\Http\Controllers;

use App\Services\VolunteerAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class VolunteerAssistantController extends Controller
{
    public function ask(Request $request, VolunteerAssistantService $assistant): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        try {
            return response()->json([
                'answer' => $assistant->answer($validated['message'], $request->user()),
            ]);
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 503);
        }
    }
}
