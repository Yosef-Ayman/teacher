<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'courses';
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'access_duration_days',
        'thumbnail_url',
        'publish_at',
        'created_by',
    ];
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('publish_at', '<=', now());
    }
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
    public function getUrl(): string
    {
        return route('courses.show', $this->slug);
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')->singleFile();
    }
    public function updateThumbnailFromDisk(array|string $path, string $disk = 'public'): void
    {
        if (is_array($path)) {
            $path = reset($path);
        }

        $media = $this->addMediaFromDisk($path, $disk)->toMediaCollection('thumbnail');

        $url = $media->getUrl();

        $relativeUrl = '/' . ltrim(parse_url($url, PHP_URL_PATH), '/');

        $this->forceFill([
            'thumbnail_url' => $relativeUrl,
        ])->saveQuietly();
    }
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}
