<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseUser extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'paid',
        'payment_status',
        'payment_details',
        'progress',
        'points',
        'started_at',
        'completed_at',
        'certificate_issued',
        'certificate_path',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function userProgress(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserProgress::class);
    }
}
