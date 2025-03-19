<?php


namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait TraitSomenteSuperUser {

    public static function canViewAny():bool{
        return User::SuperAdmin();
    }



}