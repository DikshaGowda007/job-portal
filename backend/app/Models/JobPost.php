<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'modified_by_user_id',
        'company_name',
        'title',
        'job_description',
        'location',
        'salary',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_type',
        'job_category_id',
        'work_mode',
        'job_type',
        'roles_responsibility',
        'experience_level',
        'experience_min',
        'experience_max',
        'education',
        'skills',
        'status',
        'expires_at',
        'openings_count',
        'created_by_user_id',
        'is_deleted',
    ];

    public $timestamps = false;

    public function getTableName(): string
    {
        return $this->table;
    }
}
