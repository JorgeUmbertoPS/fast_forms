<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\FormularioResposta;
use App\Models\User;

class FormularioRespostaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any FormularioResposta');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormularioResposta $FormularioResposta): bool
    {
        return $user->can('view FormularioResposta');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create FormularioResposta');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormularioResposta $FormularioResposta): bool
    {
        return $user->can('update FormularioResposta');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormularioResposta $FormularioResposta): bool
    {
        return $user->can('delete FormularioResposta');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormularioResposta $FormularioResposta): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormularioResposta $FormularioResposta): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
