<?php

namespace App\Traits;

trait GetsLeagueTeams
{
    private $user;
    
    public function __construct() {
        $this->user = auth()->user();
    }

    /**
     * Get the two teams for the current user's league.
     */
    public function getLeagueTeams(): array
    {
        $teams = $this->user?->league?->teams;

        return [
            'team_one' => $teams?->first(),
            'team_two' => $teams?->last(),
        ];
    }

    public function getLeagueTeamsWithPlayers(): array
    {
        $teams = $this->user?->league?->teams;

        return [
            'team_one' => $teams?->first(),
            'team_two' => $teams?->last(),
        ];
    }
}
