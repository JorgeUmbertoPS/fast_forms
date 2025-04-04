<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\User;
use Filament\Widgets;
use App\Models\Perfil;
use Filament\PanelProvider;
use App\Filament\Pages\Login;
use Filament\Enums\ThemeMode;
use Filament\Pages\Dashboard;
use App\Filament\Pages\NovoLogin;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\PerfilResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\EmpresaResource;
use App\Filament\Resources\PermissaoResource;
use App\Filament\Resources\FormularioResource;
use App\Filament\Resources\PermissoesResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Resources\ModeloFormularioResource;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Filament\Resources\ModeloRespostaTipoResource;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource;
use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel

            ->default()
            ->id('admin')
            ->path('admin')
            ->login(NovoLogin::class)
            ->passwordReset() 
            ->emailVerification()
            ->globalSearch(false)
            ->brandLogo(asset('logo.jpeg'))
            ->brandLogoHeight('3.5em')
            ->defaultThemeMode(ThemeMode::Dark)
            ->databaseNotifications()
            ->colors([
                'primary' => Color::Green,
                'confirma' => Color::Yellow,
                'error' => Color::Red,
            ])
            ->font('Inter')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,

            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
            ])
            ->collapsibleNavigationGroups(true)
            ->sidebarFullyCollapsibleOnDesktop(true)
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
            ]);
    }
}
