<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\GetsLeagueTeams;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use GetsLeagueTeams, HasFactory, Notifiable;

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
        'handicap',
        'allow_sms',
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

    protected $appends = [
        'combined_name',
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

    /**
     * Get the scorecards for a user
     */
    public function scorecards(): BelongsToMany
    {
        return $this->belongsToMany(Scorecard::class);
    }

    /**
     * Get the current scorecard in which a user is currently on
     */
    public function getCurrentScorecard()
    {
        return $this->scorecards()
            // ->where('finalized', false)
            ->latest('tee_time')
            ->first();
    }

    public function league(): HasOneThrough
    {
        return $this->hasOneThrough(
            League::class,   // Final model
            Team::class,     // Intermediate
            'id',            // Foreign key on teams (teams.id)
            'id',            // Foreign key on leagues (leagues.id)
            'team_id',       // Foreign key on users (users.team_id)
            'league_id'      // Foreign key on teams (teams.league_id)
        );
    }

    /**
     * Get the team's slug (auto-generated from name).
     */
    protected function combinedName(): Attribute
    {
        return Attribute::get(function () {
            $nameParts = explode(' ', $this->name);
            $nickname = '"'.$this->nickname.'"';

            $first = array_shift($nameParts);
            $rest = implode(' ', $nameParts);

            return trim("{$first} {$nickname} {$rest}");
        });
    }
}
