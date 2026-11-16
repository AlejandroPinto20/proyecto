<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\UserSkill;
use App\Services\MarkovRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    protected $recommendationService;

    public function __construct(MarkovRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Mostrar el formulario de configuración inicial de habilidades.
     */
    public function setup()
    {
        $user = Auth::user();

        // Si ya completó el setup, redirigir al dashboard
        if ($user->hasCompletedSkillsSetup()) {
            return redirect()->route('dashboard')->with('info', 'Ya has completado la configuración de habilidades.');
        }

        // Obtener todas las habilidades disponibles agrupadas por categoría
        $skillsByCategory = Skill::active()
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('skills.setup', compact('skillsByCategory'));
    }

    /**
     * Guardar la configuración inicial de habilidades del usuario.
     */
    public function storeSetup(Request $request)
    {
        $request->validate([
            'skills' => 'required|array|min:3|max:10',
            'skills.*' => 'exists:skills,id',
            'levels' => 'required|array',
            'levels.*' => 'integer|min:1|max:5',
        ]);

        $user = Auth::user();

        // Verificar que no haya completado el setup ya
        if ($user->hasCompletedSkillsSetup()) {
            return redirect()->route('dashboard')->with('error', 'Ya has completado la configuración de habilidades.');
        }

        $skills = $request->skills;
        $levels = $request->levels;

        // Crear las relaciones usuario-habilidad
        foreach ($skills as $skillId) {
            $level = $levels[$skillId] ?? 1;
            $experiencePoints = ($level - 1) * 100; // 100 puntos base por nivel

            UserSkill::create([
                'user_id' => $user->id,
                'skill_id' => $skillId,
                'level' => $level,
                'experience_points' => $experiencePoints,
                'last_updated' => now(),
            ]);
        }

        // Marcar como completado el setup
        $user->update(['skills_completed' => true]);

        // Actualizar o crear el ranking del usuario para reflejar los logros inmediatamente
        $ranking = $user->getOrCreateRanking();
        $ranking->updateStats();

        return redirect()->route('dashboard')->with('success', '¡Configuración de habilidades completada! Ahora recibirás recomendaciones personalizadas.');
    }

    /**
     * Mostrar el perfil de habilidades del usuario.
     */
    public function profile()
    {
        $user = Auth::user();

        // Obtener habilidades del usuario con sus niveles
        $userSkills = $user->skills()
            ->withPivot('level', 'experience_points', 'last_updated')
            ->orderBy('name')
            ->get();

        // Obtener estadísticas de evolución
        $evolutionStats = $this->recommendationService->getSkillEvolutionStats($user);

        // Obtener recomendaciones
        $recommendations = $this->recommendationService->getRecommendationsForUser($user, 3);

        return view('skills.profile', compact('userSkills', 'evolutionStats', 'recommendations'));
    }

    /**
     * Mostrar recomendaciones de actividades.
     */
    public function recommendations()
    {
        $user = Auth::user();
        $recommendations = $this->recommendationService->getRecommendationsForUser($user, 10);

        return view('skills.recommendations', compact('recommendations'));
    }

    /**
     * API endpoint para obtener recomendaciones en formato JSON.
     */
    public function apiRecommendations(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 5);

        $recommendations = $this->recommendationService->getRecommendationsForUser($user, $limit);

        return response()->json([
            'recommendations' => $recommendations->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'description' => $activity->description,
                    'start_date' => $activity->start_date?->format('d/m/Y H:i'),
                    'location' => $activity->location,
                    'skills' => $activity->skills->pluck('name'),
                ];
            })
        ]);
    }

    /**
     * Mostrar todas las habilidades disponibles (para admin).
     */
    public function index()
    {
        $this->authorize('viewAny', Skill::class);

        $skills = Skill::orderBy('category')->orderBy('name')->paginate(20);

        return view('admin.skills.index', compact('skills'));
    }

    /**
     * Mostrar formulario para crear nueva habilidad.
     */
    public function create()
    {
        $this->authorize('create', Skill::class);

        return view('admin.skills.create');
    }

    /**
     * Crear nueva habilidad.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Skill::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:skills',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        Skill::create($request->all());

        return redirect()->route('admin.skills.index')->with('success', 'Habilidad creada exitosamente.');
    }

    /**
     * Mostrar habilidad específica.
     */
    public function show(Skill $skill)
    {
        $this->authorize('view', $skill);

        // Obtener usuarios que tienen esta habilidad
        $usersWithSkill = $skill->users()
            ->withPivot('level', 'experience_points')
            ->orderBy('name')
            ->get();

        // Obtener actividades que desarrollan esta habilidad
        $activities = $skill->activities()
            ->withPivot('development_weight')
            ->orderBy('name')
            ->get();

        return view('admin.skills.show', compact('skill', 'usersWithSkill', 'activities'));
    }

    /**
     * Mostrar formulario para editar habilidad.
     */
    public function edit(Skill $skill)
    {
        $this->authorize('update', $skill);

        return view('admin.skills.edit', compact('skill'));
    }

    /**
     * Actualizar habilidad.
     */
    public function update(Request $request, Skill $skill)
    {
        $this->authorize('update', $skill);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:skills,slug,' . $skill->id,
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $skill->update($request->all());

        return redirect()->route('admin.skills.index')->with('success', 'Habilidad actualizada exitosamente.');
    }

    /**
     * Eliminar habilidad.
     */
    public function destroy(Skill $skill)
    {
        $this->authorize('delete', $skill);

        $skill->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Habilidad eliminada exitosamente.');
    }
}
