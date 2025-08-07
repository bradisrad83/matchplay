<?php

namespace App\Filament\Player\Pages;

use Filament\Pages\Page;

class LeagueHome extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.player.pages.league-home';

    public function getViewData(): array
    {
        $user = auth()->user();
        $teams = $user->league->teams;
        return [
            'teams' => $teams,
        ];
    }    
}
