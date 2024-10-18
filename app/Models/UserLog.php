<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $primaryKey   = 'id';

    public $timestamps      = true;

    public $incrementing    = true;  

    protected $table        = 'activity_log';

    protected $fillable = [
        'id',
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'created_at',
        'updated_at',
        'team_id',
        'event',
        'batch_uuid',
    ];

    public function causer(){
        return $this->hasOne(User::class, 'id', 'causer_id');
    }
}
