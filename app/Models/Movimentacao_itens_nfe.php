<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimentacao_itens_nfe extends Model
{
    protected $table = 'movimentacao_itens_nfe';
    public $timestamps = false;

    protected $fillable = [
        'cd_movimentacao_itens',
        'cd_produto_fornecedor',
        'dFab',
        'dVal',
        'nLote',
        'NCM',
        'CFOP',
        'uCom',
        'qCom',
        'vUnCom',
        'vProd',
        'icms_vICMS',
        'ipi_vIPI'
    ];
}
