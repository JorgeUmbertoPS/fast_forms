<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ModeloRespostaPontuacaoItem;
use App\Models\User;

class ModeloRespostaPontuacaoItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any ModeloRespostaPontuacaoItem');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModeloRespostaPontuacaoItem $modelorespostapontuacaoitem): bool
    {
        return $user->can('view ModeloRespostaPontuacaoItem');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ModeloRespostaPontuacaoItem');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModeloRespostaPontuacaoItem $modelorespostapontuacaoitem): bool
    {
        return $user->can('update ModeloRespostaPontuacaoItem');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModeloRespostaPontuacaoItem $modelorespostapontuacaoitem): bool
    {
        return $user->can('delete ModeloRespostaPontuacaoItem');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModeloRespostaPontuacaoItem $modelorespostapontuacaoitem): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModeloRespostaPontuacaoItem $modelorespostapontuacaoitem): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
