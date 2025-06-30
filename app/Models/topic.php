<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
