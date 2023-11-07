<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'task',
        'user_id',
        'status',
    ];
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


