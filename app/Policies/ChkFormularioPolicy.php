<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Formulario;
use App\Models\User;

class ChkFormularioPolicy
{
    public function before($user, $ability){
        return $user->hasRole("Admin");
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any Formulario');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Formulario $Formulario): bool
    {
        return $user->can('view Formulario');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create Formulario');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Formulario $Formulario): bool
    {
        return $user->can('update Formulario');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Formulario $Formulario): bool
    {
        return $user->can('delete Formulario');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Formulario $Formulario): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Formulario $Formulario): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
