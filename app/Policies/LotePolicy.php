<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Lote;
use App\Models\User;

class LotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Lote');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lote $lote): bool
    {
        return $user->checkPermissionTo('view Lote');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Lote');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lote $lote): bool
    {
        return $user->checkPermissionTo('update Lote');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lote $lote): bool
    {
        return $user->checkPermissionTo('delete Lote');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lote $lote): bool
    {
        return $user->checkPermissionTo('restore Lote');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lote $lote): bool
    {
        return $user->checkPermissionTo('force-delete Lote');
    }
}
