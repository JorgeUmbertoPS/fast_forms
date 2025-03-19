<?php

namespace App\Policies;

use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    public function before($user, $ability){
        return $user->hasRole(["SuperAdmin"]);       
    }

    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SpatiePermissionModelsPermission $spatiePermissionModelsPermission): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SpatiePermissionModelsPermission $spatiePermissionModelsPermission): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SpatiePermissionModelsPermission $spatiePermissionModelsPermission): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SpatiePermissionModelsPermission $spatiePermissionModelsPermission): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SpatiePermissionModelsPermission $spatiePermissionModelsPermission): bool
    {
        //
    }
}
