<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'user_id',
        'job_post_id',
        'resume_path',
        'cover_letter',
        'expected_salary',
        'expected_salary_currency',
        'notice_period',
        'experience_years',
        'status',
        'recruiter_notes',
        'reviewed_by_user_id',
        'reviewed_at',
        'viewed',
        'viewed_at',
        'viewed_by',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'expected_salary' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'viewed' => 'boolean',
        'viewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobPost(): BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    public function getTableName(): string
    {
        return $this->table;
    }
}
