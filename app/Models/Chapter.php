<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class Chapter extends Model
{
    use HasFactory;
    protected $table = 'chapters';
    protected $fillable = [
        'lesson_id',
        'video_id',
        'markdown_id',
        'assessment_id',
        'position',
        'created_by',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position');
    }
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function type(): string
    {
        if (is_null($this->assessment_id) && is_null($this->video_id) && ! is_null($this->markdown_id)) {
            return 'markdown';
        } elseif (is_null($this->assessment_id) && is_null($this->markdown_id) && ! is_null($this->video_id)) {
            return 'video';
        } elseif (is_null($this->video_id) && is_null($this->markdown_id) && ! is_null($this->assessment_id)) {
            return 'assessment';
        } else {
            return '';
        }
    }
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
    public function markdown(): BelongsTo
    {
        return $this->belongsTo(Markdown::class);
    }
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}
