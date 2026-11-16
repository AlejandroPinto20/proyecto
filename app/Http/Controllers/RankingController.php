<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    /**
     * Display the rankings leaderboard.
     */
    public function index()
    {
        // Update all rankings first
        $this->updateAllRankingsQuietly();
        
        $rankings = Ranking::getTopRankings(20);

        // Get current user's ranking
        $userRanking = null;
        if (Auth::check() && !Auth::user()->isAdmin()) {
            $userRanking = Auth::user()->ranking;
            if (!$userRanking) {
                // Create ranking if it doesn't exist
                $userRanking = Ranking::create(['user_id' => Auth::id()]);
                $userRanking->updateStats();
            }
            
            // Get user's position among all users
            $userPosition = Ranking::whereHas('user', function($query) {
                    $query->where('role', '!=', 'admin')
                          ->where('is_active', true);
                })
                ->where('total_points', '>', $userRanking->total_points)
                ->count() + 1;
            
            $userRanking->rank_position = $userPosition;
        }

        return view('rankings.index', compact('rankings', 'userRanking'));
    }

    /**
     * Show the form for creating a new ranking (Admin only).
     */
    public function create()
    {
        $this->authorize('create', Ranking::class);

        $users = User::where('role', '!=', 'admin')->get();
        return view('rankings.create', compact('users'));
    }

    /**
     * Store a newly created ranking in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Ranking::class);

        $request->validate([
            'user_id' => 'required|exists:users,id|unique:rankings,user_id',
        ]);

        $ranking = Ranking::create([
            'user_id' => $request->user_id,
        ]);

        $ranking->updateStats();

        return redirect()->route('rankings.index')->with('success', 'Ranking created successfully.');
    }

    /**
     * Display the specified ranking.
     */
    public function show(Ranking $ranking)
    {
        $this->authorize('view', $ranking);
        $ranking->updateStats();

        return view('rankings.show', compact('ranking'));
    }

    /**
     * Show the form for editing the specified ranking.
     */
    public function edit(Ranking $ranking)
    {
        $this->authorize('update', $ranking);

        $users = User::where('role', '!=', 'admin')->get();
        return view('rankings.edit', compact('ranking', 'users'));
    }

    /**
     * Update the specified ranking in storage.
     */
    public function update(Request $request, Ranking $ranking)
    {
        $this->authorize('update', $ranking);

        $request->validate([
            'user_id' => 'required|exists:users,id|unique:rankings,user_id,' . $ranking->id,
        ]);

        $ranking->update($request->only(['user_id']));
        $ranking->updateStats();

        return redirect()->route('rankings.index')->with('success', 'Ranking updated successfully.');
    }

    /**
     * Remove the specified ranking from storage.
     */
    public function destroy(Ranking $ranking)
    {
        $this->authorize('delete', $ranking);
        $ranking->delete();

        return redirect()->route('rankings.index')->with('success', 'Ranking deleted successfully.');
    }

    /**
     * Update all rankings (Admin utility).
     */
    public function updateAll()
    {
        $this->authorize('create', Ranking::class);

        $this->updateAllRankingsQuietly();

        return redirect()->route('rankings.index')->with('success', 'Todos los rankings han sido actualizados correctamente.');
    }

    /**
     * Update all rankings without authorization check (internal use).
     */
    private function updateAllRankingsQuietly()
    {
        $users = User::where('role', '!=', 'admin')
                    ->where('is_active', true)
                    ->get();

        foreach ($users as $user) {
            $ranking = $user->ranking ?? Ranking::create(['user_id' => $user->id]);
            $ranking->updateStats();
        }

        // Update all positions
        Ranking::getTopRankings(100); // Update top 100 positions
    }
}
