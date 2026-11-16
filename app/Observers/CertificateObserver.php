<?php

namespace App\Observers;

use App\Models\Certificate;
use App\Models\Ranking;

class CertificateObserver
{
    /**
     * Handle the Certificate "created" event.
     */
    public function created(Certificate $certificate): void
    {
        $this->updateUserRanking($certificate);
    }

    /**
     * Handle the Certificate "deleted" event.
     */
    public function deleted(Certificate $certificate): void
    {
        $this->updateUserRanking($certificate);
    }

    /**
     * Update ranking for the certificate user
     */
    private function updateUserRanking(Certificate $certificate): void
    {
        if ($certificate->user) {
            $ranking = $certificate->user->ranking ?? Ranking::create(['user_id' => $certificate->user->id]);
            $ranking->updateStats();
        }
    }
}