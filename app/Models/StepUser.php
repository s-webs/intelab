<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StepUser extends Model
{
    protected $fillable = ['user_id', 'step_id', 'completed', 'answers', 'completed_at'];
    protected $casts = [
        'answers' => 'array',
    ];
}
