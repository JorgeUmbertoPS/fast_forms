<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ModeloRespostaTipo;
use App\Models\User;

class ModeloRespostaTipoPolicy
{
    public function before($user, $ability){
        return $user->hasRole(["SuperAdmin"]);       
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any ModeloRespostaTipo');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModeloRespostaTipo $modelorespostatipo): bool
    {
        return $user->can('view ModeloRespostaTipo');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ModeloRespostaTipo');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModeloRespostaTipo $modelorespostatipo): bool
    {
        return $user->can('update ModeloRespostaTipo');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModeloRespostaTipo $modelorespostatipo): bool
    {
        return $user->can('delete ModeloRespostaTipo');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModeloRespostaTipo $modelorespostatipo): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModeloRespostaTipo $modelorespostatipo): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
