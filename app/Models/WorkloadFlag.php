<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkloadFlag extends Model
{
    protected $fillable = [
        'user_id',
        'pending_count',
        'due_soon_count',
        'is_at_risk',
    ];

    protected function casts(): array
    {
        return [
            'is_at_risk' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}