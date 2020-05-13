<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimentacao_nfe extends Model
{
    protected $table = 'movimentacao_nfe';
    public $timestamps = false;

    protected $fillable = [
        'cd_movimentacao',
        'cd_emitente_destinatario',
        'chave',
        'mod',
        'serie',
        'natOp',
        'indPag',
        'dhEmi',
        'dhSaiEnt',
        'tpEmis',
        'procEmi',
        'finNFe',
        'vDesc',
        'vOutro',
        'vFrete',
        'vSeg',
        'vNF'
    ];
}
