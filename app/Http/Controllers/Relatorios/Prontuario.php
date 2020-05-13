<?php

namespace App\Http\Controllers\Relatorios;

use Illuminate\Support\Facades\DB;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Prontuario extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function prontuario($cdProntuario) {
        verificaEstabelecimentoProntuario($cdProntuario);
        $data['cd_prontuario'] = $cdProntuario;
        $data['estabelecimento'] = DB::table('estabelecimentos')
            ->where('cd_estabelecimento',session('estabelecimento'))
            ->select('tp_estabelecimento')
            ->first();

        $data['prontuario'] = DB::table('prontuario as pr')
            ->leftJoin('beneficiario as b', 'b.id_beneficiario','=','pr.id_beneficiario')
            ->leftJoin('pessoa as p', 'p.cd_pessoa','=','b.cd_pessoa')
            ->leftJoin('acolhimento as a', 'a.cd_prontuario','=','pr.cd_prontuario')
            ->leftJoin('users as u','u.id','a.id_user')
            ->leftJoin('pessoa as p2','p2.cd_pessoa','u.cd_pessoa')
            ->leftJoin('profissional as pro','pro.cd_pessoa','p2.cd_pessoa')
            ->leftJoin('ocupacao as o','o.cd_ocupacao','pro.cd_ocupacao')
            ->leftJoin('acolhimento_reclassificacao as ar','ar.cd_prontuario','pr.cd_prontuario')
            ->where('pr.cd_prontuario','=',$cdProntuario)
            ->select('p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'a.*','b.cd_beneficiario','p.nm_mae','pr.cd_prontuario', 'pr.created_at as prontuario_created_at', 'a.created_at','o.cd_ocupacao','o.nm_ocupacao','p2.nm_pessoa as nm_profissional','pro.conselho','pro.nr_conselho','ar.classificacao_anterior','ar.classificacao_nova','ar.motivo','ar.created_at as hora_alteracao')
            ->first();

        if (count($data['prontuario']) == 0) {
            return redirect('/home');
        }
        $data['titulo'] = 'Prontuário '.$data['prontuario']->nm_pessoa;

        //$data['avaliacao_descricao'] = array();
        $data['avaliacao_descricao'] = DB::table('atendimento_avaliacao_descricao as av')
            ->leftJoin('users as u','u.id','av.id_user')
            ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->leftJoin('profissional as pr','pr.cd_pessoa','p.cd_pessoa')
            ->leftJoin('ocupacao as o','o.cd_ocupacao','pr.cd_ocupacao')
            ->where('cd_prontuario', '=', $cdProntuario)
            ->select('p.nm_pessoa','av.created_at','av.descricao_avaliacao','av.id_user', 'o.nm_ocupacao','pr.cd_ocupacao','pr.conselho','pr.nr_conselho')
            ->orderBy('av.created_at')
            ->get();

        //$data['atendimento_medico'] = array();
        $data['atendimento_medico'] = DB::table('atendimento_medico as am')
            ->leftJoin('users as u','u.id','am.id_user')
            ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->leftJoin('profissional as pr','pr.cd_pessoa','p.cd_pessoa')
            ->leftJoin('ocupacao as o','o.cd_ocupacao','pr.cd_ocupacao')
            ->leftJoin('prontuario as pro','pro.cd_prontuario','am.cd_prontuario')
            ->where('am.cd_prontuario', '=', $cdProntuario)
            ->select('p.nm_pessoa','am.created_at','am.historia_clinica','am.adesao_tratamentos','am.acompanhante', 'o.nm_ocupacao',
                'pr.cd_ocupacao','am.descricao_objetivo','am.motivo_alta','am.descricao_alta','am.descricao_plano','pro.finished_at','pr.conselho','pr.nr_conselho')
            ->first();

        //$data['atendimento_avaliacao_cid'] = array();
        $data['atendimento_avaliacao_cid'] = DB::table('atendimento_avaliacao_cid as av')
            ->leftJoin('users as u','u.id','av.id_user')
            ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->leftJoin('profissional as pr','pr.cd_pessoa','p.cd_pessoa')
            ->leftJoin('ocupacao as o','o.cd_ocupacao','pr.cd_ocupacao')
            ->leftJoin('cid as cid','cid.id_cid','av.id_cid')
            ->where('cd_prontuario', '=', $cdProntuario)
            ->select('p.nm_pessoa','av.created_at','av.id_user', 'o.nm_ocupacao','pr.cd_ocupacao','cid.nm_cid', 'av.dt_primeiros_sintomas',
                'av.diagnostico_trabalho', 'av.diagnostico_transito', 'cid.cd_cid','cid.nm_cid', 'av.cid_principal','pr.conselho','pr.nr_conselho')
            ->orderByDesc('av.cid_principal','av.created_at')
            ->get();

        //$data['atendimento_subjetivo_descricao'] = array();
        $data['atendimento_subjetivo_descricao'] = DB::table('atendimento_subjetivo_descricao as asd')
            ->leftJoin('users as u','u.id','asd.id_user')
            ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->leftJoin('profissional as pr','pr.cd_pessoa','p.cd_pessoa')
            ->leftJoin('ocupacao as o','o.cd_ocupacao','pr.cd_ocupacao')
            ->where('cd_prontuario', '=', $cdProntuario)
            ->select('p.nm_pessoa','asd.descricao_subjetivo','o.nm_ocupacao','pr.cd_ocupacao','asd.created_at','pr.conselho','pr.nr_conselho')
            ->orderByDesc('asd.created_at')
            ->get();

        //$data['atendimento_procedimento'] = array();
        $data['atendimento_procedimento'] = DB::table('atendimento_procedimento as ap')
            //profissional solicitante
            ->leftJoin('users as us','us.id','ap.id_user_solicitante')
            ->leftJoin('pessoa as ps','ps.cd_pessoa','us.cd_pessoa')
            ->leftJoin('profissional as prs','prs.cd_pessoa','ps.cd_pessoa')
            ->leftJoin('ocupacao as os','os.cd_ocupacao','prs.cd_ocupacao')
            //profissional execucao
            ->leftJoin('users as ue','ue.id','ap.id_user_executante')
            ->leftJoin('pessoa as pe','pe.cd_pessoa','ue.cd_pessoa')
            ->leftJoin('profissional as pre','pre.cd_pessoa','pe.cd_pessoa')
            ->leftJoin('ocupacao as oe','oe.cd_ocupacao','pre.cd_ocupacao')

            ->leftJoin('procedimento as p','p.cd_procedimento','ap.cd_procedimento')
            ->where('cd_prontuario', '=', $cdProntuario)
            ->select('p.cd_procedimento','p.nm_procedimento','ap.dt_hr_solicitacao', 'ps.nm_pessoa as nm_solicitante', 'os.cd_ocupacao as cd_ocupacao_solicitante',
                'os.nm_ocupacao as nm_ocupacao_solicitante', 'pe.nm_pessoa as nm_executante', 'oe.cd_ocupacao as cd_ocupacao_executante',
                'oe.nm_ocupacao as nm_ocupacao_executante', 'ap.dt_hr_execucao','prs.conselho as conselho_solicitante','prs.nr_conselho as nr_conselho_solicitante','pre.conselho as conselho_executante','pre.nr_conselho as nr_conselho_executante','ap.descricao_solicitacao','ap.descricao_execucao')
            ->orderBy('ap.dt_hr_solicitacao')
            ->get();

        //$data['atendimento_evolucao'] = array();
        $data['atendimento_evolucao'] = DB::table('atendimento_evolucao as ae')
            ->leftJoin('users as u','u.id','ae.id_user')
            ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->leftJoin('profissional as pr','pr.cd_pessoa','p.cd_pessoa')
            ->leftJoin('ocupacao as o','o.cd_ocupacao','pr.cd_ocupacao')
            ->leftJoin('sala as s','s.cd_sala','ae.cd_sala')
            ->where('cd_prontuario',$cdProntuario)
            ->select('s.nm_sala','ae.cd_leito','ae.descricao_evolucao','ae.created_at','p.nm_pessoa','pr.conselho','pr.nr_conselho','o.nm_ocupacao')
            ->orderBy('ae.created_at')
            ->get();

        $data['alergias'] = DB::table('pessoa_alergia as pa')
            ->leftJoin('beneficiario as b', 'b.cd_pessoa', 'pa.cd_pessoa')
            ->leftJoin('alergia as a', 'a.cd_alergia','pa.cd_alergia')
            ->leftJoin('prontuario as p','p.id_beneficiario','b.id_beneficiario')
            ->where('p.cd_prontuario',$cdProntuario)
            ->select('a.nm_alergia')
            ->orderBy('a.nm_alergia')
            ->get();

        $data['historia_medica'] = DB::table('pessoa_historia_medica as phm')
            ->leftJoin('beneficiario as b', 'b.cd_pessoa', 'phm.cd_pessoa')
            ->leftJoin('cid as c', 'c.id_cid','phm.id_cid')
            ->leftJoin('prontuario as p','p.id_beneficiario','b.id_beneficiario')
            ->where('p.cd_prontuario',$cdProntuario)
            ->select('c.cd_cid','c.nm_cid')
            ->orderBy('c.cd_cid')
            ->get();

        $data['cirurgias_previas'] = DB::table('pessoa_cirurgia_previa as pcp')
            ->leftJoin('beneficiario as b', 'b.cd_pessoa', 'pcp.cd_pessoa')
            ->leftJoin('prontuario as p','p.id_beneficiario','b.id_beneficiario')
            ->where('p.cd_prontuario',$cdProntuario)
            ->select('pcp.dt_cirurgia','pcp.descricao_cirurgia')
            ->orderBy('pcp.dt_cirurgia')
            ->get();

        $data['medicamentos_em_uso'] = DB::table('pessoa_medicamentos_em_uso as pmu')
            ->leftJoin('beneficiario as b', 'b.cd_pessoa', 'pmu.cd_pessoa')
            ->leftJoin('prontuario as p','p.id_beneficiario','b.id_beneficiario')
            ->where('p.cd_prontuario',$cdProntuario)
            ->select('pmu.descricao_medicamento')
            ->orderBy('pmu.descricao_medicamento')
            ->get();

//----------------------------------------------------------------------------------------------------------------------
//--------------RECEITUÁRIO---------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
        $data['prescricao']['receituario'] = DB::table('atendimento_prescricao')
            ->where('cd_prontuario',$cdProntuario)
            ->where('tp_prescricao','RECEITUARIO')
            ->orderBy('id_atendimento_prescricao')
            ->get();
        if(isset($data['prescricao']['receituario'][0])) {
            foreach ($data['prescricao']['receituario'] as $key=>$receituario) {
                if(isset($receituario->id_atendimento_prescricao))
                    $data['prescricao']['receituario'][$key]->itens = $this->busca_itens_prescricao($receituario->id_atendimento_prescricao);
            }
        }

//----------------------------------------------------------------------------------------------------------------------
//--------------PRESCRIÇÃO HOSPITALAR-----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
        $data['prescricao']['hospitalar'] = DB::table('atendimento_prescricao')
            ->where('cd_prontuario',$cdProntuario)
            ->where('tp_prescricao','PRESCRICAO_HOSPITALAR')
            ->orderBy('id_atendimento_prescricao')
            ->get();
        if(isset($data['prescricao']['hospitalar'][0])) {
            foreach ($data['prescricao']['hospitalar'] as $key=>$prescricao_hospitalar) {
                if(isset($prescricao_hospitalar->id_atendimento_prescricao))
                    $data['prescricao']['hospitalar'][$key]->itens = $this->busca_itens_prescricao($prescricao_hospitalar->id_atendimento_prescricao);
            }
        }

//----------------------------------------------------------------------------------------------------------------------
//--------------PRESCRIÇÃO AMBULATORIAL---------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------
        $data['prescricao']['ambulatorial'] = DB::table('atendimento_prescricao')
            ->where('cd_prontuario',$cdProntuario)
            ->where('tp_prescricao','PRESCRICAO_AMBULATORIAL')
            ->orderBy('id_atendimento_prescricao')
            ->get();
        if(isset($data['prescricao']['ambulatorial'][0])) {
            foreach ($data['prescricao']['ambulatorial'] as $key=>$prescricao_ambulatorial) {
                if(isset($prescricao_ambulatorial->id_atendimento_prescricao))
                    $data['prescricao']['ambulatorial'][$key]->itens = $this->busca_itens_prescricao($prescricao_ambulatorial->id_atendimento_prescricao);
            }
        }

        $pdf = PDF::loadView('relatorios/prontuario', $data);
        return $pdf->stream();
    }

    function busca_itens_prescricao($id_atendimento_prescricao){
        $data['exame_laboratorial'] = DB::table('atendimento_prescricao_exame_laboratorial')
            ->where('id_atendimento_prescricao',$id_atendimento_prescricao)
            ->where('status','<>','E')
            ->get();
        $data['medicacao'] = DB::table('atendimento_prescricao_medicacao as am')
            ->leftJoin('unidade_medida as um','um.cd_unidade_medida','am.tp_dose')
            ->leftJoin('produto as p','p.cd_produto','am.cd_medicamento')
            ->where('am.id_atendimento_prescricao',$id_atendimento_prescricao)->where('am.status','<>','E')
            ->select('am.*','p.nm_produto','p.ds_produto','p.nm_laboratorio','um.abreviacao')
            ->orderBy('am.id_atendimento_prescricao_medicacao')
            ->get();
        $data['dieta'] = DB::table('atendimento_prescricao_dieta')
            ->where('id_atendimento_prescricao',$id_atendimento_prescricao)->where('status','<>','E')
            ->orderByDesc('status')->orderBy('id_atendimento_prescricao_dieta')
            ->get();
        $data['csv'] = DB::table('atendimento_prescricao_csv')
            ->where('id_atendimento_prescricao',$id_atendimento_prescricao)->where('status','<>','E')
            ->orderByDesc('status')->orderBy('id_atendimento_prescricao_csv')
            ->get();
        $data['outros_cuidados'] = DB::table('atendimento_prescricao_outros_cuidados')
            ->where('id_atendimento_prescricao',$id_atendimento_prescricao)->where('status','<>','E')
            ->orderByDesc('status')->orderBy('id_atendimento_prescricao_outros_cuidados')
            ->get();
        $data['oxigenoterapia'] = DB::table('atendimento_prescricao_oxigenoterapia')
            ->where('id_atendimento_prescricao',$id_atendimento_prescricao)->where('status','<>','E')
            ->orderByDesc('status')->orderBy('id_atendimento_prescricao_oxigenoterapia')
            ->get();
        return $data;
    }

}