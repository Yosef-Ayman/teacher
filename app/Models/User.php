<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'username', 'email', 'is_blocked', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'super' => $this->isSuper(),
            'admin' => $this->isAdmin(),
            default => false,
        };
    }
    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }
    public function super(): HasOne
    {
        return $this->hasOne(Admin::class);
    }
    public function isSuper(): bool
    {
        if (Super::where('user_id', $this->id)->exists()) {
            return true;
        } else {
            return false;
        }
    }
    public function isAdmin(): bool
    {
        if (Admin::where('user_id', $this->id)->exists()) {
            return true;
        } else {
            return false;
        }
    }
    public function isStudent(): bool
    {
        if (Student::where('user_id', $this->id)->exists()) {
            return true;
        } else {
            return false;
        }
    }
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
    public function isEnrolledToCourse(int $courseId): bool
    {
        return $this->enrollments()
            ->forUserAndCourse($courseId)
            ->notExpired()
            ->exists();
    }
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
    public function markdowns(): HasMany
    {
        return $this->hasMany(Markdown::class);
    }
}
