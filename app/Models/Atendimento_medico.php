<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atendimento_medico extends Model
{
    protected $table = 'atendimento_medico';
    public $timestamps = false;

    protected $fillable = [
        'cd_prontuario', 'id_user', 'historia_clinica', 'adesao_tratamentos','acompanhante','cd_procedimento','cd_sala','descricao_plano','motivo_alta','descricao_objetivo','descricao_alta'
    ];

}
