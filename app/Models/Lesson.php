<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;
    protected $table = 'lessons';
    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'position',
        'hidden',
        'created_by',
    ];
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('hidden', false);
    }
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position');
    }
    public function getUrl(): string
    {
        $course = Course::find($this->course_id);
        return route('lessons.show', ['course_slug' => $course->slug, 'lesson_slug' => $this->slug]);
    }
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
