<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /*public function loadUserCompleto($id){
        $this->where('id',$id);
    }*/

    public function pessoa()
    {
        return $this->belongsTo('App\Models\Pessoa','cd_pessoa','cd_pessoa');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cd_pessoa', 'email', 'password', 'cd_grupo_op', 'id_situacao'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

}
