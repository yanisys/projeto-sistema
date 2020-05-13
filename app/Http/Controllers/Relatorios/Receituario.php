<?php

namespace App\Http\Controllers\Relatorios;

use Illuminate\Support\Facades\DB;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class Receituario extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function receita($cdProntuario) {
        verificaEstabelecimentoProntuario($cdProntuario);
        $data['titulo'] = "MEDICAÇÃO";

        $data['prescricao'] = DB::table('atendimento_prescricao')->where('cd_prontuario',$cdProntuario)->where('tp_prescricao','RECEITUARIO')->orderByDesc('id_atendimento_prescricao')->first();
        $id_atendimento_prescricao = $data['prescricao']->id_atendimento_prescricao;


        $data['paciente'] = DB::table('prontuario as pr')
            ->leftJoin('beneficiario as b', 'b.id_beneficiario', 'pr.id_beneficiario')
            ->leftJoin('pessoa as p', 'p.cd_pessoa', 'b.cd_pessoa')
            ->where('pr.cd_prontuario',$cdProntuario)
            ->select('p.nm_pessoa as nm_paciente','p.endereco', 'p.endereco_nro', 'p.bairro', 'p.localidade', 'p.uf')
            ->first();
        $data['medico'] = DB::table('users as u')
            ->leftJoin('pessoa as p', 'p.cd_pessoa', 'u.cd_pessoa')
            ->leftJoin('profissional as pr', 'pr.cd_pessoa', 'u.cd_pessoa')
            ->leftJoin('ocupacao as o', 'o.cd_ocupacao', 'pr.cd_ocupacao')
            ->where('u.id', session()->get('id_user'))
            ->select('p.nm_pessoa as nm_medico','pr.conselho','pr.nr_conselho','o.nm_ocupacao')
            ->first();
        $data['estabelecimento'] = DB::table('estabelecimentos as e')
            ->leftJoin('pessoa as p', 'p.cd_pessoa', 'e.cd_pessoa')
            ->where('e.cd_estabelecimento', session()->get('estabelecimento'))
            ->select('p.endereco', 'p.endereco_nro', 'p.bairro', 'p.localidade', 'p.uf', 'p.nr_fone1', 'p.ds_email')
            ->first();
        $data['receita_medicacao'] = DB::table('atendimento_prescricao_medicacao as am')
            ->leftJoin('unidade_medida as um','um.cd_unidade_medida','am.tp_dose')
            ->leftJoin('produto as p','p.cd_produto','am.cd_medicamento')
            ->where('am.id_atendimento_prescricao',$id_atendimento_prescricao)
            ->where('p.controlado',0)
            ->where('am.status','<>','E')
            ->get();

        $pdf = PDF::loadView('relatorios/receita', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    public function receita_especial($cdProntuario) {
        verificaEstabelecimentoProntuario($cdProntuario);
        $data['titulo'] = "MEDICAÇÃO";

        $data['prescricao'] = DB::table('atendimento_prescricao')->where('cd_prontuario',$cdProntuario)->where('tp_prescricao','RECEITUARIO')->orderByDesc('id_atendimento_prescricao')->first();
        $id_atendimento_prescricao = $data['prescricao']->id_atendimento_prescricao;
        $data['paciente'] = DB::table('prontuario as pr')
            ->leftJoin('beneficiario as b', 'b.id_beneficiario', 'pr.id_beneficiario')
            ->leftJoin('pessoa as p', 'p.cd_pessoa', 'b.cd_pessoa')
            ->where('pr.cd_prontuario',$cdProntuario)
            ->select('p.nm_pessoa as nm_paciente','p.endereco', 'p.endereco_nro', 'p.bairro', 'p.localidade', 'p.uf')
            ->first();
        $data['medico'] = DB::table('users as u')
            ->leftJoin('pessoa as p', 'p.cd_pessoa', 'u.cd_pessoa')
            ->leftJoin('profissional as pr', 'pr.cd_pessoa', 'u.cd_pessoa')
            ->leftJoin('ocupacao as o', 'o.cd_ocupacao', 'pr.cd_ocupacao')
            ->where('u.id', session()->get('id_user'))
            ->select('p.nm_pessoa as nm_medico','pr.conselho','pr.nr_conselho','o.nm_ocupacao')
            ->first();
        $data['estabelecimento'] = DB::table('estabelecimentos as e')
            ->leftJoin('pessoa as p', 'p.cd_pessoa', 'e.cd_pessoa')
            ->where('e.cd_estabelecimento', session()->get('estabelecimento'))
            ->select('p.endereco', 'p.endereco_nro', 'p.bairro', 'p.localidade', 'p.uf', 'p.nr_fone1', 'p.ds_email')
            ->first();
        $data['receita_medicacao'] = DB::table('atendimento_prescricao_medicacao as am')
            ->leftJoin('unidade_medida as um','um.cd_unidade_medida','am.tp_dose')
            ->leftJoin('produto as p','p.cd_produto','am.cd_medicamento')
            ->where('am.id_atendimento_prescricao',$id_atendimento_prescricao)
            ->where('p.controlado',1)
            ->where('am.status','<>','E')
            ->get();

        $pdf = PDF::loadView('relatorios/receita-especial', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    public function exames_laboratoriais($cdProntuario) {
        verificaEstabelecimentoProntuario($cdProntuario);
        $data['titulo'] = "EXAMES LABORATORIAIS";
        $data['prescricao'] = DB::table('atendimento_prescricao')->where('cd_prontuario',$cdProntuario)->where('tp_prescricao','RECEITUARIO')->orderByDesc('id_atendimento_prescricao')->first();
        $id_atendimento_prescricao = $data['prescricao']->id_atendimento_prescricao;
        $data['paciente'] = DB::table('prontuario as pr')
            ->leftJoin('beneficiario as b', 'b.id_beneficiario', 'pr.id_beneficiario')
            ->leftJoin('pessoa as p', 'p.cd_pessoa', 'b.cd_pessoa')
            ->where('pr.cd_prontuario',$cdProntuario)
            ->select('p.nm_pessoa as nm_paciente','p.endereco', 'p.endereco_nro', 'p.bairro', 'p.localidade', 'p.uf')
            ->first();
        $data['medico'] = DB::table('users as u')
            ->leftJoin('pessoa as p', 'p.cd_pessoa', 'u.cd_pessoa')
            ->leftJoin('profissional as pr', 'pr.cd_pessoa', 'u.cd_pessoa')
            ->leftJoin('ocupacao as o', 'o.cd_ocupacao', 'pr.cd_ocupacao')
            ->where('u.id', session()->get('id_user'))
            ->select('p.nm_pessoa as nm_medico','pr.conselho','pr.nr_conselho','o.nm_ocupacao')
            ->first();
        $data['estabelecimento'] = DB::table('estabelecimentos as e')
            ->leftJoin('pessoa as p', 'p.cd_pessoa', 'e.cd_pessoa')
            ->where('e.cd_estabelecimento', session()->get('estabelecimento'))
            ->select('p.endereco', 'p.endereco_nro', 'p.bairro', 'p.localidade', 'p.uf', 'p.nr_fone1', 'p.ds_email')
            ->first();
        $data['receita_exame_laboratorial'] = DB::table('atendimento_prescricao_exame_laboratorial')
            ->where('id_atendimento_prescricao',$id_atendimento_prescricao)
            ->where('status','<>','E')
            ->get();

        $pdf = PDF::loadView('relatorios/exames_laboratoriais', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

}