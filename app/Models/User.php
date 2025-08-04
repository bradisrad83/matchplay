<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'nickname',
        'phone_number',
        'role',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }

    /**
     * give access to admin panel (me for now)
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email === 'brad.m.goldsmith@gmail.com';
    }

    /**
     * gets a full name for filament
     */
    public function getFilamentName(): string
    {
        return $this->name;
    }

    /**
     * Get the Team of the User
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
