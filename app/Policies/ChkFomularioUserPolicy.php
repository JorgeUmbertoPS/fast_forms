<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ChkFomularioUser;
use App\Models\User;

class ChkFomularioUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any ChkFomularioUser');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChkFomularioUser $chkfomulariouser): bool
    {
        return $user->can('view ChkFomularioUser');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ChkFomularioUser');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChkFomularioUser $chkfomulariouser): bool
    {
        return $user->can('update ChkFomularioUser');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChkFomularioUser $chkfomulariouser): bool
    {
        return $user->can('delete ChkFomularioUser');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ChkFomularioUser $chkfomulariouser): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ChkFomularioUser $chkfomulariouser): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
