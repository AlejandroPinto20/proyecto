<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class NoPendingDonations implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Auth::check()) {
            return;
        }

        $hasPendingDonations = Auth::user()->donations()
            ->whereIn('validation_status', ['pending', 'verifying'])
            ->exists();

        if ($hasPendingDonations) {
            $fail('No puedes crear una nueva donación mientras tengas donaciones pendientes de verificación.');
        }
    }
}