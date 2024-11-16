<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $casts = [
        'is_banned' => 'boolean',
        'is_wfa' => 'boolean',
    ];

    protected $fillable = [
        "user_id",
        "shift_id",
        "office_id",
        "is_wfa",
        "is_banned",
    ];
    /**
     * Get the user that owns the Schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
