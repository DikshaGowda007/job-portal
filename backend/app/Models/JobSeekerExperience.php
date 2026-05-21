<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSeekerExperience extends Model
{
    protected $fillable = [
        'job_seeker_profile_id',
        'job_title',
        'company_name',
        'employment_type',
        'location',
        'work_mode',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'responsibilities',
        'achievements',
        'skills_used',
        'is_deleted',
    ];

    public $timestamps = false;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'responsibilities' => 'array',
        'achievements' => 'array',
        'skills_used' => 'array',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(JobSeekerProfile::class, 'job_seeker_profile_id');
    }

    public function getTableName(): string
    {
        return $this->table;
    }
}
