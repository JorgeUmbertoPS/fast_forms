<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ModeloFormulario;
use App\Models\User;

class ModeloFormularioPolicy
{
    
    public function before($user, $ability){
        return true;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any ModeloFormulario');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModeloFormulario $modeloformulario): bool
    {
        return $user->can('view ModeloFormulario');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ModeloFormulario');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModeloFormulario $modeloformulario): bool
    {
        return $user->can('update ModeloFormulario');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModeloFormulario $modeloformulario): bool
    {
        return $user->can('delete ModeloFormulario');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModeloFormulario $modeloformulario): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModeloFormulario $modeloformulario): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
