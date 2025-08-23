<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScorecardResource\Pages;
use App\Models\Scorecard;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ScorecardResource extends Resource
{
    protected static ?string $model = Scorecard::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $teams = auth()->user()?->getLeagueTeams() ?? ['team_one' => null, 'team_two' => null];

        return $form
            ->schema([
                DateTimePicker::make('tee_time')->required(),
                Checkbox::make('finalized'),
                Select::make('team_id')
                    ->label('Winner')
                    ->options([
                        $teams['team_one']?->id => $teams['team_one']?->name,
                        $teams['team_two']?->id => $teams['team_two']?->name,
                        'PUSH' => 'PUSH',
                    ])->nullable(),
                Select::make('format_id')->label('Match Format')
                    ->relationship('format', 'name')
                    ->required(),
                Select::make('users')->label('Players on Card')
                    ->multiple()
                    ->relationship('users', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->team_id === $teams['team_one']?->id
                         ? "{$record->name} - {$teams['team_one']?->name}"
                         : "{$record->name} - {$teams['team_two']?->name}"
                    )
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
                Repeater::make('hole_data')
                    ->collapsible()
                    ->label('Hole Data')
                    ->columnSpanFull()
                    ->itemLabel(fn (array $state) => $state['label'])
                    ->schema([
                        TextInput::make($teams['team_one']->slug)
                            ->numeric()
                            ->nullable()
                            ->label($teams['team_one']->name),

                        TextInput::make($teams['team_two']->slug)
                            ->numeric()
                            ->nullable()
                            ->label($teams['team_two']->name),

                        Select::make('winner')
                            ->options([
                                $teams['team_one']?->id => $teams['team_one']?->name,
                                $teams['team_two']?->id => $teams['team_two']?->name,
                                'push' => 'PUSH',
                            ])
                            ->label('Winner')
                            ->nullable(),
                    ])
                    ->columns(4)
                    ->reorderable(false)
                    ->deletable(false)
                    ->addable(false)
                    ->default(fn () => collect(range(1, 18))->map(fn ($i) => [
                        'label' => "Hole $i",
                        'hole_number' => $i,
                        $teams['team_one']->slug => null,
                        $teams['team_two']->slug => null,
                        'winner' => null,
                    ])->toArray()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('format.name'),
                TextColumn::make('date'),
                TextColumn::make('time')->label('Tee Time'),
                TextColumn::make('players')
                    ->label('Players')
                    ->getStateUsing(fn ($record) => $record->users->map(fn ($user) => $user->name)->join(', ')
                    )
                    ->wrap(),
                CheckboxColumn::make('finalized'),
                TextColumn::make('winner.Team.name')->label('Winner'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScorecards::route('/'),
            'create' => Pages\CreateScorecard::route('/create'),
            'edit' => Pages\EditScorecard::route('/{record}/edit'),
        ];
    }
}
