<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $table = 'pessoa';
    protected $primaryKey = 'cd_pessoa';
    public $timestamps = false;

    protected $fillable = [
        'cd_pessoa',
        'nm_pessoa',
        'id_situacao',
        'id_pessoa',
        'cnpj_cpf',
        'inscricao',
        'endereco',
        'endereco_nro',
        'endereco_compl',
        'bairro',
        'localidade',
        'cep',
        'uf',
        'nr_fone1',
        'nr_fone2',
        'ds_email',
        'observacoes',
        'dt_cadastro',
        'op_alter',
        'dt_alter',
        'end_aux',
        'end_aux_nro',
        'end_aux_compl',
        'bairro_aux',
        'localidade_aux',
        'cep_aux',
        'uf_aux',
        'nr_fone_aux',
        'cd_pessoa_vinc',
        'id_sexo',
        'id_civil',
        'dt_nasc',
        'ds_natural',
        'nm_pai',
        'nm_mae',
        'ds_trabalho',
        'ds_atividade',
        'senha',
        'ident_num',
        'ident_org',
        'nm_contato',
        'dt_nasc_contato',
        'id_regime',
        'nm_fantasia',
        'cd_regiao',
        'cd_rota',
        'id_contribuinte',
        'nm_responsavel1',
        'nr_fone_responsavel1',
        'nm_responsavel2',
        'nr_fone_responsavel2',
        'nm_medico_responsavel',
        'nr_fone_medico_responsavel',
        'id_raca_cor',
        'cd_etnia',
        'cd_nacionalidade'
    ];
}
