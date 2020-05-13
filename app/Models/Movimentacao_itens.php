<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimentacao_itens extends Model
{
    protected $table = 'movimentacao_itens';
    protected $primaryKey = 'cd_movimentacao_itens';
    public $timestamps = false;

    protected $fillable = [
        'cd_movimentacao',
        'cd_produto',
        'cd_fornecedor',
        'lote',
        'dt_fabricacao',
        'dt_validade',
        'quantidade',
        'valor_unitario',
        'cd_sala',
        'updated_at',
        'created_at',
        'id_user'
    ];
}
