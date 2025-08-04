<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class League extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_path',
        'settings',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * Get the teams for the league
     */
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
