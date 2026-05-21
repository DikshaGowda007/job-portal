<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSeekerEducation extends Model
{
    protected $table = 'job_seeker_education';

    protected $fillable = [
        'job_seeker_profile_id',
        'degree',
        'field_of_study',
        'institution',
        'location',
        'start_year',
        'end_year',
        'is_current',
        'grade',
        'percentage',
        'cgpa',
        'description',
        'achievements',
        'is_deleted',
    ];

    public $timestamps = false;

    protected $casts = [
        'is_current' => 'boolean',
        'percentage' => 'decimal:2',
        'cgpa' => 'decimal:2',
        'achievements' => 'array',
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
