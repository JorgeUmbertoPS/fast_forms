<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ModeloResposta;
use App\Models\User;

class ModeloRespostaPolicy
{
    public function before($user, $ability){
        return $user->hasRole(["SuperAdmin"]);       
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any ModeloResposta');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModeloResposta $modeloresposta): bool
    {
        return $user->can('view ModeloResposta');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ModeloResposta');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModeloResposta $modeloresposta): bool
    {
        return $user->can('update ModeloResposta');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModeloResposta $modeloresposta): bool
    {
        return $user->can('delete ModeloResposta');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModeloResposta $modeloresposta): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModeloResposta $modeloresposta): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
