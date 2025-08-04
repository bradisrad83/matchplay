<?php

namespace App\Models;

use App\Traits\GetsLeagueTeams;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scorecard extends Model
{
    use GetsLeagueTeams, HasFactory;

    /**
     * Creating default hole data when a model is created
     */
    protected static function booted()
    {
        static::creating(function ($scorecard) {
            ['team_one' => $team_one, 'team_two' => $team_two] = $scorecard->getLeagueTeams();
            $scorecard->hole_data = collect(range(1, 18))->mapWithKeys(fn ($i) => [
                "hole_{$i}_data" => [
                    $team_one->name => null,
                    $team_two->name => null,
                    'winner' => null,
                ],
            ]);
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hole_data' => 'array',
        ];
    }

    /**
     * Get the users that belong to the scrorecard
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['points', 'winner']);
    }

    /**
     * Get the format associated with the match (scorecard)
     */
    public function format(): BelongsTo
    {
        return $this->belongsTo(Format::class);
    }

    /**
     * Returns the date of the tee time
     */
    public function getDateAttribute(): string
    {
        $date = Carbon::parse($this->tee_time);

        return $date->format('m-d');
    }

    /**
     * Returns the time of the tee time
     */
    public function getTimeAttribute(): string
    {
        $date = Carbon::parse($this->tee_time);

        return $date->format('H:i');
    }

    /**
     * Mutator to make filament friendly
     */
    public function getHoleDataAttribute($value)
    {

        ['team_one' => $team_one, 'team_two' => $team_two] = $this->getLeagueTeams();

        $json = json_decode($value, true);

        return collect(range(1, 18))->map(function ($i) use ($json, $team_one, $team_two) {

            $key = "hole_{$i}_data";
            $hole = $json[$key] ?? [];

            return [
                'hole_number' => $i,
                'label' => "Hole $i",
                'ramrod_score' => $hole[$team_one->name] ?? null,
                'roostah_score' => $hole[$team_two->name] ?? null,
                'winner' => $hole['winner'] ?? null,
            ];
        })->toArray();
    }

    /**
     * setter mutator to make things work
     */
    public function setHoleDataAttribute($value)
    {
        ['team_one' => $team_one, 'team_two' => $team_two] = $this->getLeagueTeams();

        $formatted = [];
        foreach ($value as $hole) {
            $key = "hole_{$hole['hole_number']}_data";

            $formatted[$key] = [
                $team_one->name => $hole[$team_one->name] ?? null,
                $team_two->name => $hole[$team_two->name] ?? null,
                'winner' => $hole['winner'] ?? null,
            ];
        }
        $this->attributes['hole_data'] = json_encode($formatted);
    }
}
