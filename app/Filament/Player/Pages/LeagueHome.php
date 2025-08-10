<?php

namespace App\Filament\Player\Pages;

use Filament\Pages\Page;

class LeagueHome extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.player.pages.league-home';

    protected static ?string $title = 'DipShit Invitational'; // leave empty, or put your custom text

    public function getViewData(): array
    {
        return [
            'leagueData' => auth()->user()->getLeagueData(),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'League'; // keep sidebar name
    }
}
