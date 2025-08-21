<?php

namespace App\Models;

use App\Models\CUActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class topic extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cu_id',
        'title',
        'type',
        'file_path',
    ];

    protected $casts = [
        'file_path' => 'array',
    ];

    public function cuActivity()
    {
        return $this->belongsTo(\App\Models\CUActivity::class, 'cu_id');
    }

    public function progress()
    {
        return $this->hasMany(TopicProgress::class);
    }

    public function getUserProgress($userId)
    {
        return $this->progress()->where('user_id', $userId)->first();
    }

    public function getProgressAttribute($value)
    {
        if (auth()->check()) {
            $userProgress = $this->getUserProgress(auth()->id());
            return $userProgress ? $userProgress->progress : 0;
        }
        return $value;
    }
}
