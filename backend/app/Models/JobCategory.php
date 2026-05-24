<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobCategory extends Model
{
    use HasFactory;

    protected $table = 'job_categories';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(JobPost::class, 'job_category_id');
    }
}
