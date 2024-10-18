<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\QuestionarioPerguntas;
use App\Models\User;

class QuestionarioPerguntasPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any QuestionarioPerguntas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QuestionarioPerguntas $questionarioperguntas): bool
    {
        return $user->checkPermissionTo('view QuestionarioPerguntas');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create QuestionarioPerguntas');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QuestionarioPerguntas $questionarioperguntas): bool
    {
        return $user->checkPermissionTo('update QuestionarioPerguntas');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QuestionarioPerguntas $questionarioperguntas): bool
    {
        return $user->checkPermissionTo('delete QuestionarioPerguntas');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, QuestionarioPerguntas $questionarioperguntas): bool
    {
        return $user->checkPermissionTo('restore QuestionarioPerguntas');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, QuestionarioPerguntas $questionarioperguntas): bool
    {
        return $user->checkPermissionTo('force-delete QuestionarioPerguntas');
    }
}
