<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ModalidadeRoteiro;
use App\Models\User;

class ModalidadeRoteiroPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ModalidadeRoteiro');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModalidadeRoteiro $modalidaderoteiro): bool
    {
        return $user->checkPermissionTo('view ModalidadeRoteiro');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ModalidadeRoteiro');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModalidadeRoteiro $modalidaderoteiro): bool
    {
        return $user->checkPermissionTo('update ModalidadeRoteiro');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModalidadeRoteiro $modalidaderoteiro): bool
    {
        return $user->checkPermissionTo('delete ModalidadeRoteiro');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModalidadeRoteiro $modalidaderoteiro): bool
    {
        return $user->checkPermissionTo('restore ModalidadeRoteiro');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModalidadeRoteiro $modalidaderoteiro): bool
    {
        return $user->checkPermissionTo('force-delete ModalidadeRoteiro');
    }
}
