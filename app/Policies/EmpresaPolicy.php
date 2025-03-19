<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Empresa;
use App\Models\User;

class EmpresaPolicy
{
    public function before($user, $ability){
        return $user->hasRole(["SuperAdmin"]);
    }
    
    public function viewAny(User $user): bool
    {
        return $user->hasRole("SuperAdmin");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Empresa $empresa): bool
    {
        return $user->can('view Empresa');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create Empresa');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Empresa $empresa): bool
    {
        return $user->can('update Empresa');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Empresa $empresa): bool
    {
        return $user->can('delete Empresa');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Empresa $empresa): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Empresa $empresa): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
