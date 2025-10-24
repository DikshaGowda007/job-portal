<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllUserAccessRight extends Model
{
    protected $table = 'all_user_access_rights';

    protected $fillable = [
        'user_id',

        // Job Management
        'job_view',
        'job_create',
        'job_edit',
        'job_delete',
        'job_publish',
        'job_close',

        // Application Management
        'application_view',
        'application_shortlist',
        'application_reject',
        'application_download_resume',

        // Company / Recruiter
        'company_profile_view',
        'company_profile_edit',
        'recruiter_manage',

        // Admin
        'category_manage',
        'user_manage',
        'role_manage',
    ];

    public function getTableName(): string
    {
        return $this->table;
    }
    public $timestamps = false;
}
