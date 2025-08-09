<?php

namespace App\Filament\Player\Pages;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $slug = 'profile'; // ← explicit

    protected static string $view = 'filament.player.pages.profile';

    protected static bool $shouldRegisterNavigation = false;

    public User $user;

    // 🔑 Filament stores form state here
    public ?array $data = [];

    public function mount(): void
    {
        $this->user = auth()->user();

        $this->form->fill($this->user->only([
            'avatar',
            'name',
            'nickname',
            'email',
            'phone_number',
            'handicap',
            'allow_sms',
        ]));
    }

    // v3-style form definition (recommended)
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar')
                    ->label('Avatar')
                    ->directory('avatars')
                    ->image()
                    ->avatar(),

                TextInput::make('name')->label('Full Name')->required(),
                TextInput::make('nickname')->label('Nickname'),
                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    // ignore the current user's email on unique rule
                    ->unique(ignoreRecord: true),
                TextInput::make('phone_number')->label('Phone Number')->tel(),
                TextInput::make('handicap')->label('Handicap')->numeric(),
                Toggle::make('allow_sms')->label('Allow Text Messages'),
            ])
            // bind the form to your state array
            ->statePath('data');
    }

    public function submit(): void
    {
        // write state back to the model
        $this->user->fill($this->form->getState())->save();
    }
}
