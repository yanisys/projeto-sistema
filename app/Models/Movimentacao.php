<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    protected $table = 'movimentacao';
    protected $primaryKey = 'cd_movimentacao';
    public $timestamps = false;

    protected $fillable = [
        'cd_movimento',
        'tp_movimento',
        'tp_saldo',
        'tp_conta',
        'tp_nf',
        'cd_cfop',
        'nr_documento',
        'created_at',
        'id_user',
        'cd_estabelecimento',
        'cd_sala'
    ];
}
