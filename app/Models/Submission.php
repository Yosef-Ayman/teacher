<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'submissions';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'assessment_id',
        'student_id',
        'assessment_score',
        'score',
        'status',
        'started_at',
        'submitted_at',
    ];
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
