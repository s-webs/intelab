<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleUser extends Model
{
    protected $fillable = [
        'module_id',
        'user_id',
        'completed',
        'started_at',
        'completed_at',
        'created_at',
        'updated_at'
    ];
}
