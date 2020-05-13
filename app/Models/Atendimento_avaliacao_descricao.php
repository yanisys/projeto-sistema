<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atendimento_avaliacao_descricao extends Model
{
    protected $table = 'atendimento_avaliacao_descricao';
    public $timestamps = false;

    protected $fillable = [
        'cd_prontuario', 'id_user', 'descricao_avaliacao'
    ];

}
