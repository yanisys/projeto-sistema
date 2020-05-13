<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produto';
    protected $primaryKey = 'cd_produto';
    public $timestamps = false;

    protected $fillable =
    [
        'cd_produto',
        'nm_produto',
        'nm_laboratorio',
        'ds_produto',
        'cd_ean',
        'cd_produto_divisao',
        'cd_produto_grupo',
        'cd_produto_sub_grupo',
        'cd_fracao_minima',
        'cd_unidade_comercial',
        'qtde_embalagem',
        'estoque_minimo',
        'aviso_vencimento',
        'prescricao_interna',
        'medicamento',
        'antimicrobiano',
        'controlado',
        'injetavel',
        'controle_lote_validade',
        'fracionamento',
        'estabilidade',
        'ncm',
        'pis',
        'cofins',
        'cssll',
        'icms',
        'substituicao_tributaria',
        'situacao',
        'cd_anvisa',
        'cd_tiss',
        'cd_kit_vinculado',
        'principio_ativo'
    ];
}
