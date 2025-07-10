<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CUActivity extends Model
{
    use HasFactory;

    protected $table = 'cu_activities';

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'due_date',
        
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function topics()
    {
        return $this->hasMany(topic::class, 'cu_id');
    }
    public function assignments()
    {
        return $this->hasMany(assignment::class, 'cu_id');
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'cu_id');
    }

   
}
