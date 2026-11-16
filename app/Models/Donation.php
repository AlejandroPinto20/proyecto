<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'amount',
        'type',
        'status',
        'donation_date',
        'notes',
        'payment_method',
        'qr_code_data',
        'bank_account_info',
        'verification_code',
        'verified_at',
        'verified_by',
        'verification_notes',
        'validation_status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'donation_date' => 'date',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the donation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who verified the donation.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if donation is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if donation is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if donation is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Calculate points based on donation amount.
     */
    public function calculatePoints(): int
    {
        $amount = (float) $this->amount;
        
        if ($amount >= 1001) {
            return 200; // Corporativa
        } elseif ($amount >= 501) {
            return 100; // Grande
        } elseif ($amount >= 201) {
            return 50;  // Mediana
        } elseif ($amount >= 51) {
            return 25;  // Pequeña
        } elseif ($amount >= 1) {
            return 10;  // Micro
        }
        
        return 0;
    }

    /**
     * Get donation category based on amount.
     */
    public function getCategory(): string
    {
        $amount = (float) $this->amount;
        
        if ($amount >= 1001) {
            return 'Corporativa';
        } elseif ($amount >= 501) {
            return 'Grande';
        } elseif ($amount >= 201) {
            return 'Mediana';
        } elseif ($amount >= 51) {
            return 'Pequeña';
        } elseif ($amount >= 1) {
            return 'Micro';
        }
        
        return 'Sin categoría';
    }

    /**
     * Generate verification code for donation.
     */
    public function generateVerificationCode(): string
    {
        $code = 'DON-' . strtoupper(substr(md5($this->id . $this->amount . time()), 0, 8));
        $this->update(['verification_code' => $code]);
        return $code;
    }

    /**
     * Mark donation as verifying.
     */
    public function markAsVerifying(): void
    {
        $this->update([
            'validation_status' => 'verifying',
            'status' => 'pending'
        ]);
    }

    /**
     * Approve donation with verification.
     */
    public function approve(User $verifier, string $notes = null): void
    {
        $this->update([
            'validation_status' => 'approved',
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => $verifier->id,
            'verification_notes' => $notes
        ]);
    }

    /**
     * Reject donation with reason.
     */
    public function reject(User $verifier, string $reason): void
    {
        $this->update([
            'validation_status' => 'rejected',
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => $verifier->id,
            'verification_notes' => $reason
        ]);
    }

    /**
     * Check if donation is verified and approved.
     */
    public function isVerifiedAndApproved(): bool
    {
        return $this->validation_status === 'approved' && $this->status === 'approved';
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->validation_status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'verifying' => 'bg-blue-100 text-blue-800', 
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
