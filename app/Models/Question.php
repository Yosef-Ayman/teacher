<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    protected $table = 'questions';
    protected $fillable = [
        'assessment_id',
        'title',
        'type',
        'points',
        'position',
    ];
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position');
    }
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
