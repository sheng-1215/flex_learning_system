<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assignment extends Model
{
    use HasFactory;
    public function cuActivity()
    {
        return $this->belongsTo(CUActivity::class);
    }
    public function assignmentSubmissions()
    {
        return $this->hasMany(assignmentSubmit::class, 'assignment_id');
    }
}
