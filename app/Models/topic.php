<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class topic extends Model
{
    use HasFactory;
    protected $table = 'topics';
    protected $fillable = [
        'cu_id',
        'title',
        'type',
        'file_path',
    ];
    public function CUActivity()
    {
        return $this->belongsTo(CUActivity::class);
    }
}
