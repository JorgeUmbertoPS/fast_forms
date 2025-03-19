<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ModeloRespostaPontuacao;
use App\Models\User;

class ModeloRespostaPontuacaoPolicy
{
    public function before($user, $ability){
        return $user->hasRole("SuperAdmin");
    }
    
    public function viewAny(User $user): bool
    {
        return $user->can('view-any ModeloRespostaPontuacao');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ModeloRespostaPontuacao $modelorespostapontuacao): bool
    {
        return $user->can('view ModeloRespostaPontuacao');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ModeloRespostaPontuacao');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModeloRespostaPontuacao $modelorespostapontuacao): bool
    {
        return $user->can('update ModeloRespostaPontuacao');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModeloRespostaPontuacao $modelorespostapontuacao): bool
    {
        return $user->can('delete ModeloRespostaPontuacao');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModeloRespostaPontuacao $modelorespostapontuacao): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModeloRespostaPontuacao $modelorespostapontuacao): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
