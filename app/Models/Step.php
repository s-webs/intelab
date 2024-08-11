<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = [
        'lesson_id',
        'type'
    ];

    public function lesson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function content(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(StepContent::class);
    }

    public function matching(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MatchingTest::class);
    }

    public function quiz(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    public function written(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Written::class);
    }

    public function stepUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StepUser::class);
    }
}
