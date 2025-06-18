<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'cu_id',
        'student_id',
        'submission_date',
        'file_path',
        'status',
        'feedback',
        'mark'
    ];

    public function cuActivity()
    {
        return $this->belongsTo(CUActivity::class, 'cu_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
