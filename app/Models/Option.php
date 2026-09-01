<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    use HasFactory;
    protected $table = 'options';
    protected $fillable = [
        'question_id',
        'title',
        'is_correct',
        'position',
    ];
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position');
    }
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
