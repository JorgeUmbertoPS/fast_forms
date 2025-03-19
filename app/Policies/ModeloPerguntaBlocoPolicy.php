<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ModeloPerguntaBloco;
use App\Models\User;

class ModeloPerguntaBlocoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any ModeloPerguntaBloco');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModeloPerguntaBloco $modeloperguntabloco): bool
    {
        return $user->can('view ModeloPerguntaBloco');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ModeloPerguntaBloco');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModeloPerguntaBloco $modeloperguntabloco): bool
    {
        return $user->can('update ModeloPerguntaBloco');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModeloPerguntaBloco $modeloperguntabloco): bool
    {
        return $user->can('delete ModeloPerguntaBloco');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModeloPerguntaBloco $modeloperguntabloco): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModeloPerguntaBloco $modeloperguntabloco): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
