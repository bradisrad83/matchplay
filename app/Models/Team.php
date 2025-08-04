<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * The Leage which the team belongs to
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    // public function scorecards()
    // {
    //     return $this->hasMany(Scorecard::class);
    // }
}
