<?php

use App\Filament\Resources\RoleResource;
use App\Filament\Resources\PermissionResource;

return [

    'resources' => [
        'PermissionResource' => PermissionResource::class,
        'RoleResource' => RoleResource::class,
    ],

    'preload_roles' => true,

    'preload_permissions' => true,

    'navigation_section_group' => 'Administração', // Default uses language constant

    'team_model' => \App\Models\Team::class,

    'scope_to_tenant' => true,

    /*
     * Set as false to remove from navigation.
     */
    'should_register_on_navigation' => [
        'permissions' => false,
        'roles' => false,
    ],

    /**
     * Set to true to redirect to the resource index instead of the view
     */
    'should_redirect_to_index' => [
        'permissions' => [
            'after_create' => false,
            'after_edit' => false
        ],
        'roles' => [
            'after_create' => false,
            'after_edit' => false
        ],
    ],

    /*
     * If you want to place the Resource in a Cluster, then set the required Cluster class.
     * Eg. \App\Filament\Clusters\Cluster::class
     */
    'clusters' => [
        'permissions' => null,
        'roles' => null,
    ],

    'guard_names' => [
        'web' => 'web',
       // 'api' => 'api',
    ],

    'toggleable_guard_names' => [
        'roles' => [
            'isToggledHiddenByDefault' => true,
        ],
        'permissions' => [
            'isToggledHiddenByDefault' => true,
        ],
    ],

    'default_guard_name' => null,

    // if false guard option will not be show on screen. You should set a default_guard_name in this case
    'should_show_guard' => true,

    'model_filter_key' => 'return \'%\'.$value;', // Eg: 'return \'%\'.$key.'\%\';'

    'user_name_column' => 'name',

    /*
     * Icons to use for navigation
     */
    'icons' => [
        'role_navigation' => 'heroicon-o-lock-closed',
        'permission_navigation' => 'heroicon-o-lock-closed',
    ],

    /*
     *  Navigation items order - int value, false  restores the default position
     */

    'sort' => [
        'role_navigation' => false,
        'permission_navigation' => false
    ],

    'generator' => [

        'guard_names' => [
            'web',
          //  'api',
        ],

        'permission_affixes' => [

            /*
             * Permissions Aligned with Policies.
             * DO NOT change the keys unless the genericPolicy.stub is published and altered accordingly
             */
            //'viewAnyPermission' => 'Ver Todas',
            'viewPermission'   => 'Ver',
            'createPermission' => 'Criar',
            'updatePermission' => 'Alterar',
            'deletePermission' => 'Excluir',
            'importPermission' => 'Importar',
        ],

        /*
         * returns the "name" for the permission.
         *
         * $permission which is an iteration of [permission_affixes] ,
         * $model The model to which the $permission will be concatenated
         *
         * Eg: 'permission_name' => 'return $permissionAffix . ' ' . Str::kebab($modelName),
         *
         * Note: If you are changing the "permission_name" , It's recommended to run with --clean to avoid duplications
         */
        'permission_name' => 'return $permissionAffix . \' \' . Str::kebab($modelName);',

        /*
         * Permissions will be generated for the models associated with the respective Filament Resources
         */
        'discover_models_through_filament_resources' => false,

        /*
         * Include directories which consists of models.
         */
        'model_directories' => [
           // app_path('Models'),
            app_path('Models'),
            //app_path('Domains/Forum')
        ],

        /*
         * Define custom_models
         */
        'custom_models' => [
           // App\Models\Pba\MaoObra::class,
        ],

        /*
         * Define excluded_models
         */
        'excluded_models' => [

            
            
        ],

        'excluded_policy_models' => [
            \App\Models\User::class,
            
        ],

        /*
         * Define any other permission that should be synced with the DB
         */
        'custom_permissions' => [
            //'view-log'
        ],

        'user_model' => \App\Models\User::class,

        'policies_namespace' => 'App\Policies',
    ],
];
