<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchingTest extends Model
{
    protected $fillable = [
        'description',
        'question',
        'answers',
        'step_id'
    ];
}
