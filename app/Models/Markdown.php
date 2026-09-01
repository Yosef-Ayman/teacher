<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Markdown extends Model
{
    use HasFactory;
    protected $table = 'markdowns';
    protected $fillable = [
        'title',
        'body',
        'hidden',
        'created_by',
    ];
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
