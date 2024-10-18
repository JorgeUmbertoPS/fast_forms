<?php

namespace App\Providers;

use App\Models\User;
use Filament\Pages\Dashboard;
use Filament\Facades\Filament;
use App\Livewire\CreateQuestionario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Filament\Pages\EmbarquesUsers;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\SetorResource;
use App\Filament\Resources\ClienteResource;
use App\Filament\Resources\EmpresaResource;
use App\Filament\Resources\UserLogResource;
use App\Filament\Resources\EmbarqueResource;
use App\Filament\Resources\LacracaoResource;
use App\Filament\Resources\ParceiroResource;
use App\Filament\Resources\SegmentoResource;
use App\Filament\Resources\FormularioResource;
use App\Filament\Resources\ModalidadeResource;
use App\Filament\Resources\QuestionarioResource;
use App\Filament\Resources\EmbarqueUsersResource;
use App\Filament\Resources\TransportadoraResource;
use App\Filament\Resources\FinalizaEmbarqueResource;
use App\Filament\Resources\ModeloFormularioResource;
use App\Filament\Resources\ParametroSistemaResource;
use App\Filament\Resources\ModeloRespostaTipoResource;

use App\Filament\Resources\QuestionarioConfigResource;
use App\Filament\Resources\EmbarqueContainerFotoResource;
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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        

        Filament::serving(function () {

            if(isset(auth()->user()->id))
                $user = User::find(auth()->user()->id);
            else
                $user = new User();

            Filament::registerNavigationItems([

                NavigationItem::make('Usuários')
                    ->icon('heroicon-o-users')
                    ->url(fn (): string => UserResource::getUrl())
                    ->hidden(!$user->hasRole(["root"]))
                    ->group('ADMINISTRAÇÃO'),   
                    
                    NavigationItem::make('Log de Usuários')
                    ->icon('heroicon-o-users')
                    ->url(fn (): string => UserLogResource::getUrl())
                    ->hidden(!$user->hasRole(["root"]))
                    ->group('ADMINISTRAÇÃO'),                       

                    NavigationItem::make('Setores')
                    ->icon('heroicon-o-home')
                    ->url(SetorResource::getUrl())
                    ->hidden(!$user->hasRole(["root"]))
                    ->group('ADMINISTRAÇÃO'),                         

                    NavigationItem::make('Perfil')
                    ->icon('heroicon-o-user-group')
                    ->url(RoleResource::getUrl())
                    ->hidden(!$user->hasRole(["root"]))
                    ->group('ADMINISTRAÇÃO'),

                    NavigationItem::make('Parametros')
                    ->icon('heroicon-o-cog-8-tooth')
                    ->url(ParametroSistemaResource::getUrl())
                    ->hidden(!$user->hasRole(["root", "admin"]))
                    ->group('CADASTRO'),                    

                NavigationItem::make('Permissões')
                    ->icon('heroicon-o-lock-open')
                    ->url(PermissionResource::getUrl())
                    ->hidden(!$user->hasRole(["root"]))
                    ->group('ADMINISTRAÇÃO'),   
                    
                    NavigationItem::make('Clientes')
                    ->icon('heroicon-o-user-plus')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/clientes'))
                    ->url(fn (): string => ClienteResource::getUrl())
                    ->visible($user->HasRole(["root", "admin"]))
                    ->group('CADASTRO'),    
                    
                    NavigationItem::make('Transportadoras')
                    ->icon('heroicon-o-building-office')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/transportadoras'))
                    ->url(fn (): string => TransportadoraResource::getUrl())
                    ->visible($user->HasRole(["root", "admin"]))
                    ->group('CADASTRO'),    
                    
                    NavigationItem::make('Questionários')
                    ->icon('heroicon-o-question-mark-circle')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/questionarios'))
                    ->url(fn (): string => QuestionarioResource::getUrl())
                    ->visible($user->HasRole(["root", "admin"]))
                    ->group('CADASTRO'),  

                    NavigationItem::make('Modalidade de Embarque')
                    ->icon('heroicon-o-list-bullet')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/modalidades'))
                    ->url(fn (): string => ModalidadeResource::getUrl())
                    ->visible($user->HasRole(["root", "admin"]))
                    ->group('CADASTRO'),  

                    NavigationItem::make('Lacrações - Roteiros')
                    ->icon('heroicon-o-link')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/clientes'))
                    ->url(fn (): string => LacracaoResource::getUrl())
                    ->visible($user->HasRole(["root", "admin"]))
                    ->group('CADASTRO'),                     
                    
                    NavigationItem::make('Embarque')
                    ->icon('heroicon-o-truck')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/modalidades'))
                    ->url(fn (): string => EmbarqueResource::getUrl())
                    ->visible($user->HasRole(["root", "admin"]))
                    ->group('CADASTRO'), 

                    NavigationItem::make('Banco de Imagens')
                    ->icon('heroicon-o-photo')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/modalidades'))
                    ->url(fn (): string => EmbarqueContainerFotoResource::getUrl())
                    ->visible($user->HasRole(["root", "admin"]))
                    ->group('CADASTRO'),                     
                    
                    NavigationItem::make('Iniciar Embarque')
                    ->icon('heroicon-o-play')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/modalidades'))
                    ->url(fn (): string => EmbarqueUsersResource::getUrl())
                    ->visible($user->HasRole(["root", "admin", 'users']))
                    ->group('EMBARQUES'), 
          
                    
                 
                    
                    

                    

/*
                NavigationItem::make('Empresas')
                    ->icon('heroicon-o-home-modern')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/empresas'))
                    ->url(fn (): string => EmpresaResource::getUrl())
                    ->visible($user->HasRole(["root"]))
                    ->group('Cadastro'),

                
                    

                    
                NavigationItem::make('Modelos de Formulários')
                    ->icon('heroicon-o-folder-open')
                    ->url(fn (): string => ModeloFormularioResource::getUrl())  
                    ->visible($user->HasRole(["root"]))
                    ->group('Clayton - Modelos'),   
                    
                NavigationItem::make('Pontuação')
                    ->icon('fas-ranking-star')
                    ->url(fn (): string => ModeloRespostaPontuacaoResource::getUrl())
                    ->visible($user->HasRole(["root"]))
                    ->group('Clayton - Modelos'), 

                NavigationItem::make('Tipo de Resposta')
                    ->icon('fas-list')
                    ->url(fn (): string => ModeloRespostaTipoResource::getUrl())  
                    ->visible($user->HasRole(["root"]))                  
                    ->group('Clayton - Modelos'), 

                NavigationItem::make('Segmentos')
                    ->icon('fas-ranking-star')
                    ->url(fn (): string => SegmentoResource::getUrl())
                    ->visible($user->HasRole(["root"]))
                    ->group('Clayton - Modelos'),                     

                NavigationItem::make('Modelos')
                    ->icon('heroicon-o-rectangle-stack')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/users'))
                    ->url(fn (): string => ModelosFormulariosClientesResource::getUrl())
                    ->hidden(!$user->hasRole(["root", "admin"]))
                    ->group('Configurar Formulários'),  
                    
                    NavigationItem::make('Formulários')
                    ->icon('heroicon-o-rectangle-stack')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/users'))
                    ->url(fn (): string => FormularioResource::getUrl())
                    ->hidden(!$user->hasRole(["root", "admin"]))
                    ->group('Configurar Formulários'),                     
                   
                  
                NavigationItem::make('Configurar Questionários')
                    ->icon('heroicon-o-rectangle-stack')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/users'))
                    ->url(fn (): string => QuestionarioConfigResource::getUrl())
                    ->group('Responder Questionários'),    
                    
                NavigationItem::make('Questionários')
                    ->icon('heroicon-o-rectangle-stack')
                    ->isActiveWhen(fn (): bool => request()->routeIs('admin/users'))
                    ->url(fn (): string => QuestionarioResource::getUrl())
                    ->group('Responder Questionários'),                     

*/
            ]); 

        });
    
    }
}
