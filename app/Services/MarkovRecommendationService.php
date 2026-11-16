<?php

namespace App\Services;

use App\Models\User;
use App\Models\Activity;
use App\Models\Skill;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MarkovRecommendationService
{
    private const CACHE_TTL = 3600; // 1 hour
    private const MIN_ACTIVITIES_FOR_PREDICTION = 3;
    private const MAX_RECOMMENDATIONS = 10;

    public function getRecommendationsForUser(User $user, int $limit = 5): Collection
    {
        return $this->calculateRecommendations($user, $limit);
    }

    private function calculateRecommendations(User $user, int $limit = 5): Collection
    {
        $userActivities = $this->getUserActivityHistory($user);
        
        // Si tiene menos de 3 actividades, usar recomendaciones basadas en skills
        if ($userActivities->count() < self::MIN_ACTIVITIES_FOR_PREDICTION) {
            return $this->getSkillBasedRecommendations($user, $limit);
        }
        
        // Construir matriz de transición
        $transitionMatrix = $this->buildSimpleTransitionMatrix($user);
        
        // Obtener estado actual del usuario
        $currentState = $this->getSimpleUserState($user);
        
        // Predecir próximas actividades
        $predictions = $this->predictFromMatrix($currentState, $transitionMatrix);
        
        // Filtrar y rankear
        return $this->rankActivities($user, $predictions, $limit);

        // Build enhanced transition matrix with skill progression
        $transitionMatrix = $this->buildEnhancedTransitionMatrix($user);
        
        // Get current user state with skill levels
        $currentState = $this->getEnhancedUserState($user);
        
        // Calculate recommendations using improved Markov chains
        $recommendations = $this->predictNextActivitiesEnhanced($currentState, $transitionMatrix, $user);
        
        // Filter and rank recommendations with ML-like scoring
        return $this->filterAndRankRecommendationsEnhanced($user, $recommendations, $limit);
    }

    private function getUserActivityHistory(User $user): Collection
    {
        return DB::table('activity_user')
            ->where('activity_user.user_id', $user->id)
            ->join('activities', 'activity_user.activity_id', '=', 'activities.id')
            ->select('activities.*', 'activity_user.created_at as participation_date')
            ->orderBy('activity_user.created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'activity' => Activity::find($item->id),
                    'created_at' => $item->participation_date,
                ];
            });
    }

    private function buildTransitionMatrix(User $user): array
    {
        $matrix = [];
        
        // Get all users with similar activity patterns
        $similarUsers = $this->findSimilarUsers($user);
        
        foreach ($similarUsers as $similarUser) {
            $activities = $this->getUserActivityHistory($similarUser);
            
            // Build sequences of activity categories and skills
            $sequences = $this->extractActivitySequences($activities);
            
            foreach ($sequences as $sequence) {
                for ($i = 0; $i < count($sequence) - 1; $i++) {
                    $current = $sequence[$i];
                    $next = $sequence[$i + 1];
                    
                    if (!isset($matrix[$current])) {
                        $matrix[$current] = [];
                    }
                    
                    if (!isset($matrix[$current][$next])) {
                        $matrix[$current][$next] = 0;
                    }
                    
                    $matrix[$current][$next]++;
                }
            }
        }
        
        // Normalize probabilities
        return $this->normalizeMatrix($matrix);
    }

    private function findSimilarUsers(User $user): Collection
    {
        // Find users with similar skill profiles and activity preferences
        $userSkills = $user->skills->pluck('id')->toArray();
        $userCategories = $this->getUserPreferredCategories($user);
        
        return User::whereHas('skills', function ($query) use ($userSkills) {
                $query->whereIn('skill_id', $userSkills);
            })
            ->where('id', '!=', $user->id)
            ->withCount(['skills' => function ($query) use ($userSkills) {
                $query->whereIn('skill_id', $userSkills);
            }])
            ->orderBy('skills_count', 'desc')
            ->limit(20)
            ->get();
    }

    private function getUserPreferredCategories(User $user): array
    {
        return DB::table('activity_user')
            ->where('activity_user.user_id', $user->id)
            ->join('activities', 'activity_user.activity_id', '=', 'activities.id')
            ->select('activities.category', DB::raw('count(*) as count'))
            ->groupBy('activities.category')
            ->orderBy('count', 'desc')
            ->pluck('category')
            ->toArray();
    }

    private function extractActivitySequences(Collection $activities): array
    {
        $sequences = [];
        
        // Group activities by time windows (e.g., weekly)
        $grouped = $activities->groupBy(function ($participation) {
            return $participation->created_at->format('Y-W');
        });
        
        foreach ($grouped as $weekActivities) {
            $sequence = [];
            
            foreach ($weekActivities as $participation) {
                $activity = $participation->activity;
                
                // Create state representation combining category and primary skill
                $primarySkill = $activity->skills->first();
                $state = $activity->category . '_' . ($primarySkill ? $primarySkill->name : 'general');
                
                $sequence[] = $state;
            }
            
            if (count($sequence) > 1) {
                $sequences[] = $sequence;
            }
        }
        
        return $sequences;
    }

    private function getCurrentUserState(User $user): string
    {
        $recentActivity = DB::table('activity_user')
            ->where('activity_user.user_id', $user->id)
            ->join('activities', 'activity_user.activity_id', '=', 'activities.id')
            ->orderBy('activity_user.created_at', 'desc')
            ->first();
        
        if (!$recentActivity) {
            // Default state based on user's strongest skill
            $strongestSkill = $user->skills()
                ->orderBy('pivot.level', 'desc')
                ->first();
            
            return 'general_' . ($strongestSkill ? $strongestSkill->name : 'beginner');
        }
        
        $activity = Activity::find($recentActivity->activity_id);
        $primarySkill = $activity->skills->first();
        
        return $activity->category . '_' . ($primarySkill ? $primarySkill->name : 'general');
    }

    private function predictNextActivities(string $currentState, array $transitionMatrix): array
    {
        if (!isset($transitionMatrix[$currentState])) {
            return [];
        }
        
        $predictions = $transitionMatrix[$currentState];
        
        // Sort by probability
        arsort($predictions);
        
        return array_slice($predictions, 0, self::MAX_RECOMMENDATIONS, true);
    }

    private function buildEnhancedTransitionMatrix(User $user): array
    {
        $matrix = [];
        $skillProgressionMatrix = [];
        
        // Get users with similar profiles using collaborative filtering
        $similarUsers = $this->findSimilarUsersEnhanced($user);
        
        foreach ($similarUsers as $similarUser) {
            $activities = $this->getUserActivityHistory($similarUser);
            $sequences = $this->extractEnhancedSequences($activities);
            
            foreach ($sequences as $sequence) {
                for ($i = 0; $i < count($sequence) - 1; $i++) {
                    $current = $sequence[$i];
                    $next = $sequence[$i + 1];
                    
                    // Build category transition matrix
                    $currentCategory = $current['category'];
                    $nextCategory = $next['category'];
                    
                    if (!isset($matrix[$currentCategory])) {
                        $matrix[$currentCategory] = [];
                    }
                    $matrix[$currentCategory][$nextCategory] = ($matrix[$currentCategory][$nextCategory] ?? 0) + 1;
                    
                    // Build skill progression matrix
                    foreach ($current['skills'] as $skill) {
                        foreach ($next['skills'] as $nextSkill) {
                            $key = $skill . '->' . $nextSkill;
                            $skillProgressionMatrix[$key] = ($skillProgressionMatrix[$key] ?? 0) + 1;
                        }
                    }
                }
            }
        }
        
        return [
            'categories' => $this->normalizeMatrix($matrix),
            'skills' => $this->normalizeMatrix($skillProgressionMatrix)
        ];
    }
    
    private function findSimilarUsersEnhanced(User $user): Collection
    {
        // Use cosine similarity for better user matching
        $userVector = $this->getUserFeatureVector($user);
        
        return User::where('id', '!=', $user->id)
            ->whereHas('activities')
            ->get()
            ->map(function ($otherUser) use ($userVector) {
                $otherVector = $this->getUserFeatureVector($otherUser);
                $similarity = $this->cosineSimilarity($userVector, $otherVector);
                return ['user' => $otherUser, 'similarity' => $similarity];
            })
            ->sortByDesc('similarity')
            ->take(15)
            ->pluck('user');
    }
    
    private function getUserFeatureVector(User $user): array
    {
        $vector = [];
        
        // Category preferences
        $categories = ['educacion', 'medio_ambiente', 'salud', 'social', 'comunitario'];
        foreach ($categories as $category) {
            $count = $user->activities()->where('category', $category)->count();
            $vector['category_' . $category] = $count;
        }
        
        // Skill levels
        foreach ($user->skills as $skill) {
            $vector['skill_' . $skill->id] = $skill->pivot->level ?? 1;
        }
        
        // Activity frequency
        $vector['total_activities'] = $user->activities()->count();
        $vector['recent_activities'] = $user->activities()->where('created_at', '>=', now()->subMonth())->count();
        
        return $vector;
    }
    
    private function cosineSimilarity(array $vectorA, array $vectorB): float
    {
        $allKeys = array_unique(array_merge(array_keys($vectorA), array_keys($vectorB)));
        
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;
        
        foreach ($allKeys as $key) {
            $a = $vectorA[$key] ?? 0;
            $b = $vectorB[$key] ?? 0;
            
            $dotProduct += $a * $b;
            $normA += $a * $a;
            $normB += $b * $b;
        }
        
        if ($normA == 0 || $normB == 0) return 0;
        
        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }
    
    private function extractEnhancedSequences(Collection $activities): array
    {
        $sequences = [];
        
        foreach ($activities as $participation) {
            $activity = $participation->activity;
            if (!$activity) continue;
            
            $sequences[] = [
                'category' => $activity->category,
                'skills' => $activity->skills->pluck('name')->toArray(),
                'difficulty' => $activity->getDifficultyLevel(),
                'points' => $activity->getEstimatedPoints(),
                'timestamp' => $participation->created_at
            ];
        }
        
        return [$sequences]; // Return as single sequence for temporal analysis
    }
    
    private function getEnhancedUserState(User $user): array
    {
        $recentActivities = $user->activities()
            ->latest()
            ->limit(3)
            ->get();
        
        $state = [
            'recent_categories' => $recentActivities->pluck('category')->unique()->values()->toArray(),
            'skill_levels' => $user->skills->mapWithKeys(function ($skill) {
                return [$skill->name => $skill->pivot->level ?? 1];
            })->toArray(),
            'activity_frequency' => $user->activities()->where('created_at', '>=', now()->subMonth())->count(),
            'preferred_difficulty' => $this->getUserPreferredDifficulty($user)
        ];
        
        return $state;
    }
    
    private function getUserPreferredDifficulty(User $user): string
    {
        $difficulties = $user->activities()
            ->get()
            ->map(function ($activity) {
                return $activity->getDifficultyLevel();
            })
            ->countBy()
            ->sortDesc();
        
        return $difficulties->keys()->first() ?? 'medium';
    }
    
    private function predictNextActivitiesEnhanced(array $currentState, array $transitionMatrix, User $user): array
    {
        $predictions = [];
        
        // Predict based on category transitions
        foreach ($currentState['recent_categories'] as $category) {
            if (isset($transitionMatrix['categories'][$category])) {
                foreach ($transitionMatrix['categories'][$category] as $nextCategory => $probability) {
                    $predictions[$nextCategory] = ($predictions[$nextCategory] ?? 0) + $probability * 0.6;
                }
            }
        }
        
        // Boost predictions based on skill development paths
        foreach ($currentState['skill_levels'] as $skill => $level) {
            $skillKey = $skill . '->';
            foreach ($transitionMatrix['skills'] as $transition => $probability) {
                if (strpos($transition, $skillKey) === 0) {
                    $targetSkill = substr($transition, strlen($skillKey));
                    // Find activities that develop this target skill
                    $activities = Activity::whereHas('skills', function ($query) use ($targetSkill) {
                        $query->where('name', 'like', "%{$targetSkill}%");
                    })->get();
                    
                    foreach ($activities as $activity) {
                        $predictions[$activity->category] = ($predictions[$activity->category] ?? 0) + $probability * 0.4;
                    }
                }
            }
        }
        
        arsort($predictions);
        return $predictions;
    }
    
    private function filterAndRankRecommendationsEnhanced(User $user, array $predictions, int $limit): Collection
    {
        $recommendations = collect();
        
        foreach ($predictions as $category => $probability) {
            $activities = Activity::where('category', $category)
                ->whereIn('status', ['active', 'abierta', 'proxima'])
                ->whereDoesntHave('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->with(['skills', 'user'])
                ->get();
            
            foreach ($activities as $activity) {
                $score = $this->calculateEnhancedActivityScore($user, $activity, $probability);
                
                if ($score > 30) { // Only recommend activities with decent scores
                    $recommendations->push($activity);
                }
            }
        }
        
        return $recommendations
            ->unique('id')
            ->take($limit)
            ->values();
    }
    
    private function calculateEnhancedActivityScore(User $user, Activity $activity, float $probability): float
    {
        $score = $probability * 100;
        
        // Skill alignment (30% weight)
        $userSkills = $user->skills->pluck('name')->toArray();
        $activitySkills = $activity->skills->pluck('name')->toArray();
        $skillOverlap = count(array_intersect($userSkills, $activitySkills));
        $skillScore = ($skillOverlap / max(count($activitySkills), 1)) * 30;
        
        // Difficulty match (20% weight)
        $userPreferredDifficulty = $this->getUserPreferredDifficulty($user);
        $activityDifficulty = $activity->getDifficultyLevel();
        $difficultyScore = ($userPreferredDifficulty === $activityDifficulty) ? 20 : 10;
        
        // Availability (15% weight)
        $availabilityScore = 0;
        if ($activity->max_participants) {
            $availabilityRatio = 1 - ($activity->current_participants / $activity->max_participants);
            $availabilityScore = $availabilityRatio * 15;
        } else {
            $availabilityScore = 15;
        }
        
        // Recency (10% weight)
        $daysSinceCreated = $activity->created_at->diffInDays(now());
        $recencyScore = max(0, 10 - ($daysSinceCreated * 0.5));
        
        // Diversity bonus (10% weight)
        $recentCategories = $user->activities()
            ->where('created_at', '>=', now()->subWeeks(2))
            ->pluck('category')
            ->unique()
            ->toArray();
        $diversityScore = in_array($activity->category, $recentCategories) ? 0 : 10;
        
        // Points potential (15% weight)
        $pointsScore = min(15, $activity->getEstimatedPoints() / 10);
        
        return $score + $skillScore + $difficultyScore + $availabilityScore + $recencyScore + $diversityScore + $pointsScore;
    }

    private function calculateActivityScore(User $user, Activity $activity, float $probability): float
    {
        $score = $probability * 100;
        
        // Boost score based on skill match
        $userSkills = $user->skills->pluck('id')->toArray();
        $activitySkills = $activity->skills->pluck('id')->toArray();
        $skillMatch = count(array_intersect($userSkills, $activitySkills)) / max(count($activitySkills), 1);
        $score += $skillMatch * 20;
        
        // Boost score for activities with available spots
        if ($activity->max_participants && $activity->current_participants < $activity->max_participants) {
            $availabilityRatio = 1 - ($activity->current_participants / $activity->max_participants);
            $score += $availabilityRatio * 10;
        }
        
        // Boost score for recent activities
        $daysSinceCreated = $activity->created_at->diffInDays(now());
        if ($daysSinceCreated < 7) {
            $score += (7 - $daysSinceCreated) * 2;
        }
        
        // Penalize if user has too many activities in the same category recently
        $recentSameCategoryCount = DB::table('activity_user')
            ->where('activity_user.user_id', $user->id)
            ->join('activities', 'activity_user.activity_id', '=', 'activities.id')
            ->where('activities.category', $activity->category)
            ->where('activity_user.created_at', '>=', now()->subWeeks(2))
            ->count();
        
        if ($recentSameCategoryCount > 2) {
            $score -= $recentSameCategoryCount * 5;
        }
        
        return max(0, $score);
    }

    private function generateRecommendationReason(User $user, Activity $activity, string $state): string
    {
        [$category, $skillName] = explode('_', $state, 2);
        
        $reasons = [
            "Basado en tu interés en actividades de {$category}",
            "Usuarios con perfil similar participaron en esta actividad",
            "Coincide con tus habilidades en {$skillName}",
            "Actividad popular entre voluntarios con tu experiencia",
        ];
        
        // Customize reason based on user's skill level
        $userSkill = $user->skills()->where('name', 'like', "%{$skillName}%")->first();
        if ($userSkill) {
            $level = $userSkill->pivot->level ?? 1;
            if ($level >= 4) {
                $reasons[] = "Perfecta para tu nivel avanzado en {$skillName}";
            } elseif ($level >= 2) {
                $reasons[] = "Te ayudará a mejorar tus habilidades en {$skillName}";
            } else {
                $reasons[] = "Excelente para comenzar en {$skillName}";
            }
        }
        
        return $reasons[array_rand($reasons)];
    }

    private function getSkillBasedRecommendations(User $user, int $limit = 5): Collection
    {
        $userSkills = $user->skills->pluck('id')->toArray();
        $activities = collect();
        
        // 1. Actividades con skills exactas del usuario (70% match)
        if (count($userSkills) > 0) {
            $exactMatch = Activity::whereIn('status', ['active', 'abierta', 'proxima'])
                ->whereDoesntHave('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereHas('skills', function ($q) use ($userSkills) {
                    $q->whereIn('skills.id', $userSkills);
                })
                ->with(['skills', 'user'])
                ->get()
                ->map(function ($activity) use ($userSkills) {
                    $matchCount = $activity->skills->pluck('id')->intersect($userSkills)->count();
                    $activity->match_score = ($matchCount / max(count($userSkills), 1)) * 100;
                    return $activity;
                })
                ->sortByDesc('match_score');
            
            $activities = $activities->merge($exactMatch);
        }
        
        // 2. Actividades de categorías que el usuario ha participado
        $userCategories = $user->participatingActivities()->pluck('category')->unique()->toArray();
        if (count($userCategories) > 0 && $activities->count() < $limit) {
            $categoryMatch = Activity::whereIn('status', ['active', 'abierta', 'proxima'])
                ->whereIn('category', $userCategories)
                ->whereDoesntHave('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereNotIn('id', $activities->pluck('id'))
                ->with(['skills', 'user'])
                ->get();
            
            $activities = $activities->merge($categoryMatch);
        }
        
        // 3. Actividades populares (más participantes)
        if ($activities->count() < $limit) {
            $popular = Activity::whereIn('status', ['active', 'abierta', 'proxima'])
                ->whereDoesntHave('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereNotIn('id', $activities->pluck('id'))
                ->withCount('users')
                ->orderBy('users_count', 'desc')
                ->with(['skills', 'user'])
                ->get();
            
            $activities = $activities->merge($popular);
        }
        
        return $activities->unique('id')->take($limit)->values();
    }
    
    private function buildSimpleTransitionMatrix(User $user): array
    {
        $matrix = [];
        $userActivities = $this->getUserActivityHistory($user);
        
        // Construir secuencias de categorías
        $categories = $userActivities->pluck('activity.category')->filter()->toArray();
        
        for ($i = 0; $i < count($categories) - 1; $i++) {
            $current = $categories[$i];
            $next = $categories[$i + 1];
            
            if (!isset($matrix[$current])) {
                $matrix[$current] = [];
            }
            
            $matrix[$current][$next] = ($matrix[$current][$next] ?? 0) + 1;
        }
        
        // Normalizar probabilidades
        foreach ($matrix as $state => $transitions) {
            $total = array_sum($transitions);
            if ($total > 0) {
                foreach ($transitions as $nextState => $count) {
                    $matrix[$state][$nextState] = $count / $total;
                }
            }
        }
        
        return $matrix;
    }
    
    private function getSimpleUserState(User $user): string
    {
        $lastActivity = $user->participatingActivities()->latest('activity_user.created_at')->first();
        return $lastActivity ? $lastActivity->category : 'general';
    }
    
    private function predictFromMatrix(string $currentState, array $matrix): array
    {
        if (!isset($matrix[$currentState])) {
            return [];
        }
        
        $predictions = $matrix[$currentState];
        arsort($predictions);
        
        return $predictions;
    }
    
    private function rankActivities(User $user, array $predictions, int $limit): Collection
    {
        $activities = collect();
        $userSkills = $user->skills->pluck('id')->toArray();
        
        foreach ($predictions as $category => $probability) {
            $categoryActivities = Activity::where('category', $category)
                ->whereIn('status', ['active', 'abierta', 'proxima'])
                ->whereDoesntHave('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->with(['skills', 'user'])
                ->get()
                ->map(function ($activity) use ($probability, $userSkills) {
                    $skillMatch = 0;
                    if (count($userSkills) > 0) {
                        $activitySkills = $activity->skills->pluck('id')->toArray();
                        $skillMatch = count(array_intersect($userSkills, $activitySkills)) / max(count($activitySkills), 1);
                    }
                    
                    $activity->recommendation_score = ($probability * 60) + ($skillMatch * 40);
                    return $activity;
                });
            
            $activities = $activities->merge($categoryActivities);
        }
        
        return $activities
            ->sortByDesc('recommendation_score')
            ->unique('id')
            ->take($limit)
            ->values();
    }

    private function normalizeMatrix(array $matrix): array
    {
        foreach ($matrix as $state => $transitions) {
            $total = array_sum($transitions);
            
            if ($total > 0) {
                foreach ($transitions as $nextState => $count) {
                    $matrix[$state][$nextState] = $count / $total;
                }
            }
        }
        
        return $matrix;
    }

    public function clearUserCache(User $user): void
    {
        $cacheKey = "markov_recommendations_user_{$user->id}";
        Cache::forget($cacheKey);
    }

    public function getRecommendationInsights(User $user): array
    {
        $userActivities = $this->getUserActivityHistory($user);
        $preferredCategories = $this->getUserPreferredCategories($user);
        
        return [
            'total_activities' => $userActivities->count(),
            'preferred_categories' => $preferredCategories,
            'skill_development' => $this->getSkillDevelopmentInsights($user),
            'activity_frequency' => $this->getActivityFrequencyInsights($user),
        ];
    }

    private function getSkillDevelopmentInsights(User $user): array
    {
        return $user->skills->map(function ($skill) use ($user) {
            return [
                'name' => $skill->name,
                'level' => $skill->pivot->level ?? 1,
                'activities_count' => $skill->activities()
                    ->whereHas('users', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->count(),
            ];
        })->toArray();
    }

    private function getActivityFrequencyInsights(User $user): array
    {
        $participations = DB::table('activity_user')
            ->where('activity_user.user_id', $user->id)
            ->selectRaw('DATE(activity_user.created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();
        
        return [
            'daily_average' => $participations->avg('count') ?? 0,
            'most_active_day' => $participations->sortByDesc('count')->first()?->date,
            'recent_trend' => $this->calculateTrend($participations),
        ];
    }

    private function calculateTrend(Collection $data): string
    {
        if ($data->count() < 2) return 'insufficient_data';
        
        $recent = $data->take(7)->avg('count');
        $previous = $data->skip(7)->take(7)->avg('count');
        
        if ($recent > $previous * 1.1) return 'increasing';
        if ($recent < $previous * 0.9) return 'decreasing';
        return 'stable';
    }
    
    public function getSkillEvolutionStats(User $user): array
    {
        $transitions = \App\Models\SkillTransition::where('user_id', $user->id)->get();
        
        $stats = [
            'total_transitions' => $transitions->count(),
            'level_ups' => $transitions->where('to_level', '>', 'from_level')->count(),
            'skill_changes' => $transitions->where('from_skill_id', '!=', 'to_skill_id')->count(),
            'total_experience_gained' => $transitions->sum('experience_gained'),
            'most_developed_skill' => null,
            'evolution_timeline' => [],
        ];
        
        // Habilidad más desarrollada
        $skillExperience = $transitions->groupBy('to_skill_id')
            ->map(fn($group) => $group->sum('experience_gained'))
            ->sortDesc();
        
        if ($skillExperience->isNotEmpty()) {
            $topSkillId = $skillExperience->keys()->first();
            $stats['most_developed_skill'] = Skill::find($topSkillId);
        }
        
        // Timeline de evolución por mes
        $stats['evolution_timeline'] = $transitions
            ->groupBy(fn($t) => $t->transition_date->format('F Y'))
            ->map(function($group, $month) {
                return [
                    'month' => $month,
                    'transitions' => $group->count(),
                    'experience_gained' => $group->sum('experience_gained'),
                ];
            })
            ->values()
            ->toArray();
        
        return $stats;
    }
}