<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;
    protected $table = 'assessments';
    protected $fillable = [
        'course_id',
        'lesson_id',
        'title',
        'description',
        'slug',
        'type',
        'hidden',
        'duration_minutes',
        'total_points',
        'attempts',
        'passing_score',
        'shuffle_questions',
        'shuffle_answers',
        'show_result',
        'show_correct_answers',
        'starts_at',
        'ends_at',
    ];
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('hidden', false);
    }
    public function scopeInNow(Builder $query): Builder
    {
        return $query
            ->where(function (Builder $q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
