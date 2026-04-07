<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Chat extends Model
{
    protected $table = 'chats';

    protected $fillable = [
        'user_id',
        'title',
        'summary',
        'last_message_at',
    ];

    protected $hidden = ['updated_at'];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function summaryPreview(): string
    {
        if ($this->summary) {
            return $this->summary;
        }

        $message = $this->relationLoaded('latestMessage')
            ? $this->latestMessage
            : $this->latestMessage()->first();

        $content = trim((string) ($message?->content ?? ''));

        if ($content !== '') {
            return Str::limit($content, 140);
        }

        $files = $message?->files ?? [];

        if (is_array($files) && count($files) > 0) {
            return 'Chat terakhir berisi lampiran file.';
        }

        return 'Chat baru dimulai.';
    }
}
