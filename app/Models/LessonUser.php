<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonUser extends Model
{
    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed',
        'started_at',
        'completed_at'
    ];
}
