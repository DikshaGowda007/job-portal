<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs'; // plural, lowercase

    protected $fillable = [
        'recruiter_id',
        'company_name',
        'job_title',
        'job_description',
        'location',
        'work_mode',
        'job_type',
        'salary_min',
        'salary_max',
        'salary_currency',
        'experience_level',
        'experience_min_years',
        'experience_max_years',
        'required_skills',
        'education_required',
        'openings_count',
        'status',
        'expiry_date',
        'posted_date',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'posted_date' => 'datetime',
        'expiry_date' => 'date',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];
}
