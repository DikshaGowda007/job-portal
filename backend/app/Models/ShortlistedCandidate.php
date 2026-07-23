<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortlistedCandidate extends Model
{
    protected $fillable = [
        'recruiter_user_id',
        'candidate_user_id',
        'is_deleted',
        'created_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_user_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_user_id');
    }
}
