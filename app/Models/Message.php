<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'chat_id',
        'role',
        'content',
        'files',
    ];

    protected $casts = [
        'files' => 'array',
    ];

    protected $hidden = ['updated_at'];

    
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
