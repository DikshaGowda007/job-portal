<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $table = 'email_template';

    protected $fillable = [
        'template_code',
        'template_name',
        'content',
        'status',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    public $timestamps = false;
}
