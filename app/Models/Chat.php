<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = "chats";
    protected $guarded = ['id'];

    /**
     * Get all of the participants for the Chat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class, 'chat_id');
    }

    /**
     * Get all of the messages for the Chat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }

    /**
     * Get the lastMessage associated with the Chat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastMessage(): HasOne
    {
        return $this->hasOne(ChatMessage::class, 'chat_id')->latest('updated_at');
    }

    public function scopeHasParticipant($query, int $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });    
    }
}
