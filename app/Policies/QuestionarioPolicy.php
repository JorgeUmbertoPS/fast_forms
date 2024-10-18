<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Questionario;
use App\Models\User;

class QuestionarioPolicy
{
    public function before($user, $ability){
        return $user->hasRole(['root','admin']);
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Questionario');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Questionario $questionario): bool
    {
        return $user->checkPermissionTo('view Questionario');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Questionario');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Questionario $questionario): bool
    {
        return $user->checkPermissionTo('update Questionario');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Questionario $questionario): bool
    {
        return $user->checkPermissionTo('delete Questionario');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Questionario $questionario): bool
    {
        return $user->checkPermissionTo('restore Questionario');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Questionario $questionario): bool
    {
        return $user->checkPermissionTo('force-delete Questionario');
    }
}
