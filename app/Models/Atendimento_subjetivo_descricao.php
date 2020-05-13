<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atendimento_subjetivo_descricao extends Model
{
    protected $table = 'atendimento_subjetivo_descricao';
    public $timestamps = false;

    protected $fillable = [
        'cd_prontuario', 'id_user', 'descricao_subjetivo'
    ];

}
