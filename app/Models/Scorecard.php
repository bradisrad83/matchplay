<?php

namespace App\Models;

use App\Traits\GetsLeagueTeams;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Scorecard extends Model
{
    use GetsLeagueTeams, HasFactory;

    /**
     * Creating default hole data when a model is created
     */
    // protected static function booted()
    // {
    //     static::creating(function ($scorecard) {
    //         ['team_one' => $team_one, 'team_two' => $team_two] = $scorecard->getLeagueTeams();
    //         $scorecard->hole_data = collect(range(1, 18))->mapWithKeys(fn ($i) => [
    //             "hole_{$i}_data" => [
    //                 $team_one->slug => null,
    //                 $team_two->slug => null,
    //                 'winner' => null,
    //             ],
    //         ]);
    //     });
    // }

    /**
     * eager load the users
     */
    protected $with = ['users'];

    /**
     * The attributes that are not mass assignable.
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tee_time' => 'datetime',
            'finalized' => 'boolean',
            'hole_data' => 'array',
        ];
    }

    /**
     * Get the users that belong to the scrorecard
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the format associated with the match (scorecard)
     */
    public function format(): BelongsTo
    {
        return $this->belongsTo(Format::class);
    }

    /**
     * Get the winner of the match (null === push)
     */
    public function winner()
    {
        return $this->belongsTo(Team::class, 'team_id');
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
                // READ WITH SLUGS
                $team_one->slug => $hole[$team_one->slug] ?? null,
                $team_two->slug => $hole[$team_two->slug] ?? null,
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
        foreach ($value as $index => $hole) {
            $num = $hole['hole_number'] ?? ($index + 1);

            $formatted["hole_{$num}_data"] = [
                $team_one->slug => array_key_exists($team_one->slug, $hole) ? $hole[$team_one->slug] : null,
                $team_two->slug => array_key_exists($team_two->slug, $hole) ? $hole[$team_two->slug] : null,
                'winner' => $hole['winner'] ?? null,
            ];
        }
        $this->attributes['hole_data'] = json_encode($formatted);
    }

    /**
     * Get the meta data of the scorecard
     */
    public function getScorecardMeta(): Collection
    {
        $groups = $this->users()->with('team')->get()->groupBy('team_id');

        return $groups
            ->values()
            ->map(function (Collection $group) {
                $first = $group->first();

                return [
                    'users' => $group->values(),
                    'name' => data_get($first, 'team.name'),
                    'logo' => data_get($first, 'team.logo'),
                ];
            });
    }

    /**
     * Grab the Teams
     */
    public function teams(): Collection
    {
        // Pull users with their team in one query
        $users = $this->users()->with('team:id,name,logo')->get();

        // Grab the Team models, drop nulls, ensure uniqueness & stable order
        return $users->pluck('team')
            ->filter()
            ->unique('id')
            ->sortBy('slug')   // or 'id' if you prefer numeric stability
            ->values();
    }

    /**
     * Get Team One
     */
    public function teamOne(): Team
    {
        return $this->teams()->get(0);
    }

    /**
     * Get Team Two
     */
    public function teamTwo(): Team
    {
        return $this->teams()->get(1);
    }
}
