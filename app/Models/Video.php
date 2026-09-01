<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    use HasFactory;
    protected $table = 'videos';
    protected $fillable = [
        'title',
        'provider',
        'external_id',
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
    public function getEmbedUrl(): string
    {
        return match ($this->provider) {
            'youtube' => "https://www.youtube.com/embed/{$this->external_id}",
            'vimeo' => "https://player.vimeo.com/video/{$this->external_id}",
            'streamable' => "https://streamable.com/e/{$this->external_id}",
            default => '',
        };
    }
}
