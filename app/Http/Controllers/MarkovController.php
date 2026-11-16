<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\MarkovRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkovController extends Controller
{
    protected $markovService;

    public function __construct(MarkovRecommendationService $markovService)
    {
        $this->markovService = $markovService;
    }

    public function insights()
    {
        $user = Auth::user();
        $insights = $this->markovService->getRecommendationInsights($user);
        $recommendations = $this->markovService->getRecommendationsForUser($user, 10);
        
        return view('markov.insights', compact('insights', 'recommendations'));
    }

    public function apiRecommendations(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 5);
        
        $recommendations = $this->markovService->getRecommendationsForUser($user, $limit);
        
        return response()->json([
            'recommendations' => $recommendations->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'category' => $activity->category,
                    'description' => $activity->description,
                    'estimated_points' => $activity->getEstimatedPoints(),
                    'difficulty' => $activity->getDifficultyLevel(),
                    'url' => route('activities.show', $activity)
                ];
            })
        ]);
    }

    public function refreshRecommendations()
    {
        $user = Auth::user();
        $this->markovService->clearUserCache($user);
        
        return response()->json([
            'success' => true,
            'message' => 'Recomendaciones actualizadas'
        ]);
    }
}