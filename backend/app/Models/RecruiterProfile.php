<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruiterProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_logo_path',
        'company_about',
        'company_website',
        'company_size',
        'industry',
        'company_phone',
        'city',
        'state',
        'country',
        'linkedin_url',
        'is_deleted',
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTableName(): string
    {
        return $this->table;
    }
}
