<?php

namespace App\Providers;

use App\Models\User;

use Filament\Pages\Dashboard;
use Filament\Facades\Filament;
use App\Livewire\CreateQuestionario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\EmpresaResource;
use App\Filament\Resources\SegmentoResource;
use App\Filament\Resources\FormularioResource;
use App\Filament\Resources\QuestionarioResource;
use App\Filament\Resources\ModeloFormularioResource;
use App\Filament\Resources\ModeloRespostaTipoResource;
use App\Filament\Resources\QuestionarioConfigResource;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Notifications\Livewire\DatabaseNotifications;
use App\Filament\Resources\ModeloRespostaPontuacaoResource;
use App\Filament\Resources\ModelosFormulariosClientesResource;
use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource;
use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        DatabaseNotifications::trigger('filament.notifications.database-notifications-trigger');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['pt_BR','en','es']) // also accepts a closure
                ->visible(outsidePanels: true)
                ->circular()
                ->flags([
                    'pt_BR' => asset('flags/br.png'),
                    'es' => asset('flags/es.png'),
                    'en' => asset('flags/us.png'),
                ]);
        });

    
    }
}
