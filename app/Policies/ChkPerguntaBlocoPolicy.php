<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\FormularioPerguntaBloco;
use App\Models\User;

class FormularioPerguntaBlocoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any FormularioPerguntaBloco');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormularioPerguntaBloco $FormularioPerguntabloco): bool
    {
        return $user->can('view FormularioPerguntaBloco');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create FormularioPerguntaBloco');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormularioPerguntaBloco $FormularioPerguntabloco): bool
    {
        return $user->can('update FormularioPerguntaBloco');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormularioPerguntaBloco $FormularioPerguntabloco): bool
    {
        return $user->can('delete FormularioPerguntaBloco');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormularioPerguntaBloco $FormularioPerguntabloco): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormularioPerguntaBloco $FormularioPerguntabloco): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
