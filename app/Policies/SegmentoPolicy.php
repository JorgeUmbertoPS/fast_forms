<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Segmento;
use App\Models\User;

class SegmentoPolicy
{
    public function before($user, $ability){
        return $user->hasRole("SuperAdmin");
    }
    
    public function viewAny(User $user): bool
    {
        return $user->can('view-any Segmento');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Segmento $segmento): bool
    {
        return $user->can('view Segmento');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create Segmento');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Segmento $segmento): bool
    {
        return $user->can('update Segmento');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Segmento $segmento): bool
    {
        return $user->can('delete Segmento');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Segmento $segmento): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Segmento $segmento): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}
