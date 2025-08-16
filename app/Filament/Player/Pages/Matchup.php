<?php

namespace App\Filament\Player\Pages;

use Filament\Pages\Page;

class Matchup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.player.pages.matchup';

    public function getViewData(): array
    {
        $scorecard = auth()->user()->getCurrentScorecard();
        return [
            'scorecard' => $scorecard,
        ];
    }
}
