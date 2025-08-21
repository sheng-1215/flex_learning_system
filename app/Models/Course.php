<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'cover_image',
        'start_date',
        'end_date',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrollmentsCourse()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    public function studentEnrollments()
    {
        return $this->hasMany(Enrollment::class)->where('role', 'student');
    }

    public function lecturerEnrollments()
    {
        return $this->hasMany(Enrollment::class)->where('role', 'lecturer');
    }

    public function getStudentCountAttribute()
    {
        return $this->enrollments()->where('role', 'student')->count();
    }

    public function getLecturerCountAttribute()
    {
        return $this->enrollments()->where('role', 'lecturer')->count();
    }

    public function activities()
    {
        return $this->hasMany(CUActivity::class);
    }

    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('status', 'enrolled_at', 'role')
            ->withTimestamps();
    }

    public function cuActivities()
    {
        return $this->hasMany(\App\Models\CUActivity::class, 'course_id');
    }
}
