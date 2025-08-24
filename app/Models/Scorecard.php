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
                    'slug' => data_get($first, 'team.slug'),
                    'id' => data_get($first, 'team.id'),
                ];
            });
    }

    /**
     * Grab the Teams
     */
    public function teams(): Collection
    {
        $users = $this->users()->with('team:id,name,logo')->get();

        return $users->pluck('team')
            ->filter()
            ->unique('id')
            ->sortBy('id')
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

    public function getCurrentScore(): array
    {
        // Map teamId => name/logo from users->team
        $groups = $this->users()->with('team')->get()->groupBy('team_id');
        $teamNames = [];
        $teamLogos = [];
        foreach ($groups as $teamId => $users) {
            $first = $users->first();
            $tid = (int) $teamId;
            $teamNames[$tid] = data_get($first, 'team.name', "Team {$tid}");
            $teamLogos[$tid] = data_get($first, 'team.logo');
        }

        // Ensure two team IDs exist; infer from hole winners if needed
        $teamIds = array_values(array_map('intval', array_keys($teamNames)));
        if (count($teamIds) < 2) {
            $seen = [];
            foreach ((array) ($this->hole_data ?? []) as $r) {
                $w = $r['winner'] ?? null;
                if (is_numeric($w)) {
                    $wid = (int) $w;
                    $seen[$wid] = true;
                    $teamNames[$wid] = $teamNames[$wid] ?? "Team {$wid}";
                    if (! array_key_exists($wid, $teamLogos)) {
                        $teamLogos[$wid] = null;
                    }
                    if (count($seen) >= 2) {
                        break;
                    }
                }
            }
            $teamIds = array_values(array_map('intval', array_keys($seen)));
            if (count($teamIds) < 2) {
                $total = (int) (data_get($this, 'total_holes') ?? data_get($this, 'max_hole') ?? data_get($this, 'settings.total_holes') ?? 18);

                return [
                    'logo' => null,
                    'score' => 'All Square',
                    'holes_remaining' => $total,
                    'is_over' => false,
                    'name' => null,
                    'team_id' => null,   // NEW
                ];
            }
        }

        [$t1Id, $t2Id] = [$teamIds[0], $teamIds[1]];
        $wins = [$t1Id => 0, $t2Id => 0];
        $pushes = 0;

        foreach ((array) ($this->hole_data ?? []) as $row) {
            $w = $row['winner'] ?? null;
            if ($w === 'push') {
                $pushes++;

                continue;
            }
            if (is_numeric($w) && isset($wins[(int) $w])) {
                $wins[(int) $w]++;
            }
        }

        $t1 = (int) $wins[$t1Id];
        $t2 = (int) $wins[$t2Id];
        $lead = $t1 - $t2;
        $absLead = abs($lead);

        $total = (int) (
            data_get($this, 'total_holes')
            ?? data_get($this, 'max_hole')
            ?? data_get($this, 'settings.total_holes')
            ?? 18
        );

        $decided = $t1 + $t2 + $pushes;
        $holesRemaining = max(0, $total - $decided);
        $isOver = ($holesRemaining === 0) || ($absLead > $holesRemaining);

        if ($absLead === 0) {
            $text = $isOver ? 'Push' : 'All Square';

            return [
                'logo' => null,
                'score' => $text,
                'holes_remaining' => $holesRemaining,
                'is_over' => $isOver,
                'name' => null,
                'team_id' => null,
            ];
        }

        $leaderId = $lead > 0 ? $t1Id : $t2Id;
        $leaderName = $teamNames[$leaderId] ?? "Team {$leaderId}";
        $leaderLogo = $teamLogos[$leaderId] ?? null;

        // 18th-hole finish should be "X up", not "X & 0"
        if ($holesRemaining === 0) {
            $suffix = ($absLead === 1) ? '1 up' : "{$absLead} up";

            return [
                'logo' => $leaderLogo,
                'score' => "{$leaderName} {$suffix}",
                'holes_remaining' => $holesRemaining,
                'is_over' => true,
                'name' => $leaderName,
                'team_id' => $leaderId,
            ];
        }

        // Dormie: lead equals holes remaining (and match not over yet)
        if ($absLead === $holesRemaining) {
            return [
                'logo' => $leaderLogo,
                'score' => 'Dormie',
                'holes_remaining' => $holesRemaining,
                'is_over' => false,
                'name' => $leaderName,
                'team_id' => $leaderId,
            ];
        }

        // Match already over early
        if ($absLead > $holesRemaining) {
            return [
                'logo' => $leaderLogo,
                'score' => "{$absLead} & {$holesRemaining}",
                'holes_remaining' => $holesRemaining,
                'is_over' => true,
                'name' => $leaderName,
                'team_id' => $leaderId, 
            ];
        }

        // Ongoing match
        return [
            'logo' => $leaderLogo,
            'score' => "{$absLead} Up",
            'holes_remaining' => $holesRemaining,
            'is_over' => false,
            'name' => $leaderName,
            'team_id' => $leaderId, 
        ];
    }
}
