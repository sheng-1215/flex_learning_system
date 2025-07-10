<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assignmentSubmit extends Model
{
    use HasFactory;

    public function assignment()
    {
        return $this->belongsTo(assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
