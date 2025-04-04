<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilPermissaoModel extends Model
{
    use HasFactory;

    protected $table = 'perfil_permissao';

    protected $fillable = [
        'perfil_id',
        'permissao_id',
        'empresa_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function perfil()
    {
        return $this->belongsTo(PerfilModel::class, 'perfil_id');
    }
    public function permissao()
    {
        return $this->belongsTo(PermissaoModel::class, 'permissao_id');
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
