<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ModeloPergunta;
use App\Models\User;

class ModeloPerguntaPolicy
{
    public function before($user, $ability){
        return $user->hasRole(["SuperAdmin"]);       
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any ModeloPergunta');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModeloPergunta $modelopergunta): bool
    {
        return $user->can('view ModeloPergunta');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ModeloPergunta');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModeloPergunta $modelopergunta): bool
    {
        return $user->can('update ModeloPergunta');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModeloPergunta $modelopergunta): bool
    {
        return $user->can('delete ModeloPergunta');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModeloPergunta $modelopergunta): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModeloPergunta $modelopergunta): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
