<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplicationHistory extends Model
{
    protected $table = 'job_application_histories';

    protected $fillable = [
        'job_application_id',
        'previous_status',
        'new_status',
        'changed_by',
        'notes',
        'interview_scheduled_at',
        'interview_location',
        'created_at',
        'updated_at',
    ];

    public $timestamps = false;

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
