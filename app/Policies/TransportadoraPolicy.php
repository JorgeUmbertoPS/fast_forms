<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Transportadora;
use App\Models\User;

class TransportadoraPolicy
{
    public function before($user, $ability){
        return $user->hasRole(['root','admin']);
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Transportadora');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transportadora $transportadora): bool
    {
        return $user->checkPermissionTo('view Transportadora');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Transportadora');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transportadora $transportadora): bool
    {
        return $user->checkPermissionTo('update Transportadora');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transportadora $transportadora): bool
    {
        return $user->checkPermissionTo('delete Transportadora');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Transportadora $transportadora): bool
    {
        return $user->checkPermissionTo('restore Transportadora');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Transportadora $transportadora): bool
    {
        return $user->checkPermissionTo('force-delete Transportadora');
    }
}
