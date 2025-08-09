<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Players';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('nickname')->required()->maxLength(255),
                TextInput::make('email')->required()->maxLength(255),
                TextInput::make('phone_number')->required()->tel()->maxLength(255),
                Select::make('role')->options([
                    'player' => 'Player',
                    'captain' => 'Captain',
                    'superadmin' => 'Superadmin',
                ]),
                Select::make('team_id')->label('Team')->relationship('team', 'name'),
                Checkbox::make('active'),
                Checkbox::make('allow_sms'),
                FileUpload::make('avatar')->directory('avatars'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('nickname')->searchable(),
                ImageColumn::make('avatar'),
                ImageColumn::make('team.logo')->label('Team'),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone_number')->icon('heroicon-m-phone')->iconColor('primary'),
                TextColumn::make('role'),
                CheckboxColumn::make('active'),
                CheckboxColumn::make('allow_sms'),
                SelectColumn::make('role')->options([
                    'player' => 'Player',
                    'captain' => 'Captain',
                    'superadmin' => 'Superadmin',
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
