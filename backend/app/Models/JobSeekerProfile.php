<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobSeekerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'headline',
        'summary',
        'phone',
        'date_of_birth',
        'gender',
        'city',
        'state',
        'country',
        'pincode',
        'current_job_title',
        'current_company',
        'total_experience_years',
        'total_experience_months',
        'expected_salary',
        'expected_salary_currency',
        'current_salary',
        'current_salary_currency',
        'preferred_job_types',
        'preferred_work_modes',
        'preferred_locations',
        'notice_period',
        'immediate_joiner',
        'skills',
        'resume_path',
        'resume_filename',
        'resume_uploaded_at',
        'photo_path',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'profile_completeness',
        'is_public',
        'open_to_opportunities',
        'is_deleted',
    ];

    public $timestamps = false;

    protected $casts = [
        'date_of_birth' => 'date',
        'expected_salary' => 'decimal:2',
        'current_salary' => 'decimal:2',
        'preferred_job_types' => 'array',
        'preferred_work_modes' => 'array',
        'preferred_locations' => 'array',
        'skills' => 'array',
        'immediate_joiner' => 'boolean',
        'is_public' => 'boolean',
        'open_to_opportunities' => 'boolean',
        'resume_uploaded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(JobSeekerExperience::class)->orderBy('start_date', 'desc');
    }

    public function education(): HasMany
    {
        return $this->hasMany(JobSeekerEducation::class)->orderBy('end_year', 'desc');
    }

    public function getTableName(): string
    {
        return $this->table;
    }
}
