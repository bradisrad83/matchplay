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

    /**
     * Get the Captains for each team
     */
    public function getTeamCaptains(): array
    {
        $user = auth()->user();
        $teams = $user?->league?->teams;

        return [
            'team_one' => $teams?->first()->captain,
            'team_two' => $teams?->last()->captain,
        ];
    }

    /**
     * Get the players for each team
     */
    public function getTeamPlayers(): array
    {
        $user = auth()->user();
        $teams = $user?->league?->teams;

        return [
            'team_one' => $teams?->first()->users,
            'team_two' => $teams?->last()->users,
        ];
    }

    /**
     * Get all data
     */
    public function getLeagueData(): array
    {
        $user = auth()->user();
        $teams = $user?->league?->teams;

        $teamOne = $teams?->first();
        $teamTwo = $teams?->last();

        return [
            'team_one' => [
                'team' => $teamOne,
                'captain' => $teamOne?->captain,
                'players' => $teamOne?->users->filter(fn ($player) => $player->id !== $teamOne->captain->id),
            ],
            'team_two' => [
                'team' => $teamTwo,
                'captain' => $teamTwo?->captain,
                'players' => $teamTwo?->users->filter(fn ($player) => $player->id !== $teamTwo->captain->id),
            ],
        ];
    }
}
