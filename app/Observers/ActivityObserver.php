<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Ranking;

class ActivityObserver
{
    /**
     * Handle the Activity "updated" event.
     */
    public function updated(Activity $activity): void
    {
        // Update rankings when activity status changes to completed
        if ($activity->isDirty('status') && $activity->status === 'completed') {
            $this->updateParticipantsRankings($activity);
        }
    }

    /**
     * Update rankings for all participants of an activity
     */
    private function updateParticipantsRankings(Activity $activity): void
    {
        $participants = $activity->users()->wherePivot('status', 'joined')->get();
        
        foreach ($participants as $participant) {
            // Update pivot status to completed
            $activity->users()->updateExistingPivot($participant->id, [
                'status' => 'completed'
            ]);
            
            // Update or create ranking
            $ranking = $participant->ranking ?? Ranking::create(['user_id' => $participant->id]);
            $ranking->updateStats();
        }
    }
}