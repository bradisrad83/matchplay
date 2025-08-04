<?php

namespace App\Filament\Resources\ScorecardResource\Pages;

use App\Filament\Resources\ScorecardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScorecards extends ListRecords
{
    protected static string $resource = ScorecardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
