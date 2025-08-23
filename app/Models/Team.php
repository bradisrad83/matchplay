<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'league_id',
        'name',
        'color',
        'logo',
        'user_id',
    ];

    protected $appends = [
        'slug',
        'current_score',
    ];

    /**
     * The Leage which the team belongs to
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    /**
     * Retrieve the Captain of the team
     */
    public function captain(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all users on a team
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all scorecards
     */
    public function scorecards()
    {
        return $this->hasMany(Scorecard::class);
    }

    /**
     * Get the team's slug (auto-generated from name).
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $value ?? Str::slug($attributes['name'] ?? ''),

            set: fn ($value, $attributes) => $value ?? Str::slug($attributes['name'] ?? '')
        );
    }

    /**
     * Get the team's current score
     */
    protected function currentScore(): Attribute
    {
        return Attribute::make(
            get: function () {
                // If relation is eager-loaded, sum in-memory to avoid an extra query                
                // if ($this->relationLoaded('scorecards')) {
                //     return (int) $this->scorecards->sum(fn ($sc) => (int) ($sc->team_total ?? $sc->total ?? 0));
                // }

                // // Otherwise ask the DB to sum the column
                // return (int) $this->scorecards()->sum('team_total'); // <-- change to your column name

                return 0;
            }
        );
    }    
}
