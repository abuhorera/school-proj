<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatConversation extends Model
{
    use HasFactory;

    protected $table = 'ai_chat_conversations';
    protected $guarded = [];

    protected $casts = [
        'messages_json' => 'array',
    ];
}

