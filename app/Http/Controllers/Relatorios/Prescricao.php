<?php

namespace App\Http\Controllers\Relatorios;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class Prescricao extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function prescricao_ambulatorial($cdProntuario, $cdPrescricao) {
        verificaEstabelecimentoProntuario($cdProntuario);
        $data = $this->busca_prescricao($cdProntuario, 'PRESCRICAO_AMBULATORIAL',$cdPrescricao);
        $data['titulo'] = "PRESCRIÇÃO AMBULATORIAL Nº ".$data['prescricao']->cd_prescricao. " - VALIDADE: ".formata_data_hora($data['prescricao']->created_at)." a ".formata_data_hora($data['prescricao']->expira_em);
        $pdf = PDF::loadView('relatorios/prescricao', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream();
    }

    public function prescricao_hospitalar($cdProntuario) {
        verificaEstabelecimentoProntuario($cdProntuario);
        $data = $this->busca_prescricao($cdProntuario, 'PRESCRICAO_HOSPITALAR');
        $data['titulo'] = "PRESCRIÇÃO HOSPITALAR";
        $pdf = PDF::loadView('relatorios/prescricao', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream();
    }

    function busca_prescricao($cdProntuario, $tipo, $cdPrescricao=1){
        $data['status'] = [0=>'C',1=>'A'];
        $data['contador'] = 0;
        $unidades_medida = DB::table('unidade_medida')->where('situacao','A')->get();
        foreach ($unidades_medida as $um)
            $data['unidades_medida'][$um->cd_unidade_medida] = $um->abreviacao;
        $data['prescricao'] = DB::table('atendimento_prescricao')
            ->where('cd_prontuario',$cdProntuario)
            ->where('tp_prescricao',$tipo)
            ->where('cd_prescricao',$cdPrescricao)
            ->orderByDesc('id_atendimento_prescricao')
            ->first();
        if(isset($data['prescricao']->id_atendimento_prescricao))
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
        $data['prescricao_medicacao'] = DB::table('atendimento_prescricao_medicacao as am')
            ->leftJoin('unidade_medida as um','um.cd_unidade_medida','am.tp_dose')
            ->leftJoin('produto as p','p.cd_produto','am.cd_medicamento')
            ->where('am.id_atendimento_prescricao',$id_atendimento_prescricao)->where('am.status','<>','E')
            ->select('am.*','p.nm_produto','p.ds_produto','p.nm_laboratorio','um.abreviacao')
            ->orderBy('am.id_atendimento_prescricao_medicacao')
            ->get();
        $data['prescricao_dieta'] = DB::table('atendimento_prescricao_dieta')
            ->where('id_atendimento_prescricao',$id_atendimento_prescricao)->where('status','<>','E')
            ->orderByDesc('status')->orderBy('id_atendimento_prescricao_dieta')
            ->get();
        $data['prescricao_csv'] = DB::table('atendimento_prescricao_csv')
            ->where('id_atendimento_prescricao',$id_atendimento_prescricao)->where('status','<>','E')
            ->orderByDesc('status')->orderBy('id_atendimento_prescricao_csv')
            ->get();
        $data['prescricao_outros_cuidados'] = DB::table('atendimento_prescricao_outros_cuidados')
            ->where('id_atendimento_prescricao',$id_atendimento_prescricao)->where('status','<>','E')
            ->orderByDesc('status')->orderBy('id_atendimento_prescricao_outros_cuidados')
            ->get();
        $data['prescricao_oxigenoterapia'] = DB::table('atendimento_prescricao_oxigenoterapia')
            ->where('id_atendimento_prescricao',$id_atendimento_prescricao)->where('status','<>','E')
            ->orderByDesc('status')->orderBy('id_atendimento_prescricao_oxigenoterapia')
            ->get();
        return $data;
    }

}