<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopicProgress extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'topic_id',
        'progress',
        'last_watched_at',
    ];

    protected $casts = [
        'last_watched_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(topic::class);
    }
}
