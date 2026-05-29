<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllUserAccessRight extends Model
{
    protected $table = 'all_user_access_rights';

    protected $fillable = [
        'user_id',
        'job_view',
        'job_edit',
        'job_delete',
        'job_publish',
        'job_close',
        'job_apply',
        'application_view',
        'application_status_update',
        'application_shortlist',
        'application_reject',
        'application_withdraw',
        'application_download_resume',
        'company_profile_view',
        'company_profile_edit',
        'category_view',
        'category_add',
        'category_edit',
        'category_delete',
        'user_edit',
        'user_add',
        'role_manage',
        'created_date',
        'modified_date',
        'status',
        'is_deleted',
    ];

    public function getTableName(): string
    {
        return $this->table;
    }

    public $timestamps = false;
}
