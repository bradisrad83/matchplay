<?php

namespace App\Traits;

trait GetsLeagueTeams
{
    /**
     * Get the two teams for the current user's league.
     */
    public function getLeagueTeams(): array
    {
        $user = auth()->user();
        $teams = $user?->league?->teams;

        return [
            'team_one' => $teams?->first(),
            'team_two' => $teams?->last(),
        ];
    }
}
