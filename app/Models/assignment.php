<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'cu_id',
        'assignment_name',
        'description',
        'due_date',
        'attachment', // Changed from attachment to file_path
    ];
    public function cuActivity()
    {
        return $this->belongsTo(CUActivity::class,'cu_id','id');
    }
    public function assignmentSubmissions()
    {
        return $this->hasMany(assignmentSubmit::class, 'assignment_id');
    }
}
