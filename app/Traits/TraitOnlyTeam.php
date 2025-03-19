<?php


namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait TraitOnlyTeam {

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('empresa_id', auth()->user()->empresa_id);
    }




}