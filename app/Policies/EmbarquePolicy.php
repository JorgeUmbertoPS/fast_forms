<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Embarque;
use App\Models\User;

class EmbarquePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function before($user, $ability){
        return $user->hasRole(['root','admin', 'users']);
    }
    
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Embarque');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Embarque $embarque): bool
    {
        return $user->checkPermissionTo('view Embarque');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Embarque');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Embarque $embarque): bool
    {
        return $user->checkPermissionTo('update Embarque');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Embarque $embarque): bool
    {
        return $user->checkPermissionTo('delete Embarque');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Embarque $embarque): bool
    {
        return $user->checkPermissionTo('restore Embarque');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Embarque $embarque): bool
    {
        return $user->checkPermissionTo('force-delete Embarque');
    }
}
