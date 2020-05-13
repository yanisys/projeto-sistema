<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class QueryLog extends Authenticatable
{
    use Notifiable;

    protected $connection = "mysql2";
    protected $table = 'query_log';
    protected $primaryKey = 'cd_pessoa';
    public $timestamps = false;

    public function pessoa()
    {
        return $this->belongsTo('App\Models\User','id','id_user');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'id_user',
        'data',
        'tipo',
        'tabela',
        'query',
        'params',
        'time',
    ];

}
