<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;
    protected $table = 'answers';
    protected $fillable = [
        'submission_id',
        'question_id',
        'question_type',
        'option_id',
        'answer_text',
        'is_correct',
        'earned_points',
        'question_points',
    ];
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
