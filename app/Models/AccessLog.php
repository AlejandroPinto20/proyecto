<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessLog extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'user_agent',
        'action',
        'route',
        'request_data',
        'success',
        'session_id',
        'created_at'
    ];

    protected $casts = [
        'request_data' => 'array',
        'success' => 'boolean',
        'created_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function logAccess(string $action, ?User $user = null, array $data = []): void
    {
        $request = request();
        
        static::create([
            'user_id' => $user?->id,
            'email' => $user?->email ?? $request->input('email'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => $action,
            'route' => $request->route()?->getName(),
            'request_data' => $data,
            'success' => true,
            'session_id' => $request->session()?->getId(),
            'created_at' => now()
        ]);
    }
}