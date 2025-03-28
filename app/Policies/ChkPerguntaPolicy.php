<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\FormularioPergunta;
use App\Models\User;

class FormularioPerguntaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any FormularioPergunta');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormularioPergunta $FormularioPergunta): bool
    {
        return $user->can('view FormularioPergunta');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create FormularioPergunta');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormularioPergunta $FormularioPergunta): bool
    {
        return $user->can('update FormularioPergunta');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormularioPergunta $FormularioPergunta): bool
    {
        return $user->can('delete FormularioPergunta');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormularioPergunta $FormularioPergunta): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormularioPergunta $FormularioPergunta): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
