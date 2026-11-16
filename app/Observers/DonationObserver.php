<?php

namespace App\Observers;

use App\Models\Donation;
use App\Models\Ranking;

class DonationObserver
{
    /**
     * Handle the Donation "updated" event.
     */
    public function updated(Donation $donation): void
    {
        // Update ranking when donation is verified and approved
        if ($donation->isDirty('validation_status') && $donation->validation_status === 'approved') {
            $this->updateUserRanking($donation);
        }
        
        // Log status change for transparency
        if ($donation->isDirty('validation_status')) {
            \Log::info('Donation status changed', [
                'donation_id' => $donation->id,
                'user_id' => $donation->user_id,
                'old_status' => $donation->getOriginal('validation_status'),
                'new_status' => $donation->validation_status,
                'verified_by' => $donation->verified_by
            ]);
        }
    }

    /**
     * Handle the Donation "created" event.
     */
    public function created(Donation $donation): void
    {
        // Generate verification code for new donations
        if (!$donation->verification_code) {
            $donation->generateVerificationCode();
        }
        
        // Update ranking if donation is already approved (rare case)
        if ($donation->validation_status === 'approved') {
            $this->updateUserRanking($donation);
        }
    }

    /**
     * Update ranking for the donation user
     */
    private function updateUserRanking(Donation $donation): void
    {
        if ($donation->user && $donation->isVerifiedAndApproved()) {
            $ranking = $donation->user->ranking ?? Ranking::create(['user_id' => $donation->user->id]);
            $ranking->updateStats();
        }
    }
}