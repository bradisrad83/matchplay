<?php

namespace App\Providers\Filament;

use App\Filament\Player\Pages\LeagueHome;
use App\Filament\Player\Pages\PlayerDashboard;
use App\Filament\Player\Pages\Profile;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PlayerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('player')
            ->path('/league') // not sure what I am going to call this yet
            ->colors([
                'primary' => Color::Amber,
            ])
            ->login()
            ->discoverResources(in: app_path('Filament/Player/Resources'), for: 'App\\Filament\\Player\\Resources')
            ->discoverPages(in: app_path('Filament/Player/Pages'), for: 'App\\Filament\\Player\\Pages')
            ->pages([
                LeagueHome::class,
                Profile::class,
                PlayerDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Player/Widgets'), for: 'App\\Filament\\Player\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Profile')
                    ->icon('heroicon-o-user')
                    ->url(fn () => Profile::getUrl(panel: 'player')) // or: route('filament.player.pages.profile')
                    ->sort(10),
            ]);
    }
}
