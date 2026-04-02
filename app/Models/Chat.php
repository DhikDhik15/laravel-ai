<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Chat extends Model
{
    protected $table = 'chats';

    protected $fillable = [
        'user_id',
        'title',
    ];

    protected $hidden = ['created_at', 'updated_at'];
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
