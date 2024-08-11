<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name',
        'description',
        'course_id'
    ];

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function moduleUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ModuleUser::class);
    }

    public function userProgress(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ModuleUser::class)->where('user_id', auth()->id());
    }
}
