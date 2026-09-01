<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $table = 'enrolled_courses';
    protected $fillable = [
        'user_id',
        'course_id',
        'expires_at',
        'created_by',
    ];
    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function (Builder $subQuery) {
            $subQuery->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now());
        });
    }
    public function scopeForUserAndCourse(Builder $query, int $courseId): Builder
    {
        return $query->where('user_id', auth()->id())
            ->where('course_id', $courseId);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
