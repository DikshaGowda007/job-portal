<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationMessage extends Model
{
    protected $table = 'application_messages';

    public $timestamps = false;

    protected $fillable = [
        'application_id',
        'sender_id',
        'message',
        'read_at',
        'created_at',
        'updated_at',
        'is_deleted',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'application_id');
    }
}
