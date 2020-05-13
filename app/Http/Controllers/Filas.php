<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Filas extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function index(){
        $data['headerText'] = 'Atendimento ao paciente';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Filas','href' => '#'];

        $data['pesquisa_beneficiario'] = true;

//------Aguardando acolhimento------------------------------------------------------------------------------------------
        $origem = DB::table('origem')->where('cd_estabelecimento',0)->orWhere('cd_estabelecimento',session('estabelecimento'))->select('cd_origem','nm_origem')->get();
        if(isset($origem)){
            foreach ($origem as $o)
                $data['origem'][$o->cd_origem] = $o->nm_origem;
        }
        $data['novo_prontuario'] = true;
        $data['aguardando_acolhimento'] =
            DB::table('prontuario as pr')
                ->leftJoin('acolhimento as a', 'a.cd_prontuario','=','pr.cd_prontuario')
                ->where('pr.status','=','A')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('a.created_at','=',null)
                ->count();

//------Aguardando atendimento médico-----------------------------------------------------------------------------------
        $data['aguardando_atendimento_medico'] =
            DB::table('acolhimento as ac')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','ac.cd_prontuario')
                ->leftJoin('atendimento_medico as am','pr.cd_prontuario','=','am.cd_prontuario')
                ->where('pr.status','=','A')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('am.created_at','=',null)
                ->where('ac.classificacao','<',6)
                ->count();

//------Pacientes atendidos - Em atendimento interno--------------------------------------------------------------------
        $atendimento_interno =
            DB::table('atendimento_medico as am')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','am.cd_prontuario')
                ->where('pr.status','<>','C')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('am.created_at','<>',null)
                ->count();
        $atendimentos_direto_procedimentos =
            DB::table('acolhimento as ac')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','ac.cd_prontuario')
                ->where('pr.status','<>','C')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('ac.classificacao',7)
                ->count();
        $data['atendimento_interno'] = $atendimento_interno + $atendimentos_direto_procedimentos;

//------Pacientes atendidos - Aguardando procedimentos(Exceto radiológicos)---------------------------------------------
        $data['procedimentos'] =
            DB::table('atendimento_procedimento as ap')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','ap.cd_prontuario')
                ->where('pr.status','<>','C')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('ap.id_status','=','A')
                ->whereNotBetween('ap.cd_procedimento',[204000000, 204999999])
                ->distinct('ap.cd_prontuario')
                ->count();

//------Pacientes atendidos - Aguardando procedimentos radiológicos-----------------------------------------------------
        $data['procedimentos_radiologicos'] =
            DB::table('atendimento_procedimento as ap')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','ap.cd_prontuario')
                ->where('pr.status','<>','C')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('ap.id_status','=','A')
                ->whereBetween('ap.cd_procedimento',[204000000, 204999999])
                ->distinct('ap.cd_prontuario')
                ->count();

//------Pacientes atendidos - Atendimento concluído---------------------------------------------------------------------
        $data['atendimentos_concluidos'] =
            DB::table('prontuario as pr')
                ->leftJoin('atendimento_medico as am', 'pr.cd_prontuario','=','am.cd_prontuario')
                ->leftJoin('acolhimento as ac', 'pr.cd_prontuario','=','ac.cd_prontuario')
                ->where('pr.status','=','C')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('ac.classificacao','<>',6) //elimina evasão no acolhimento
                ->whereDate('pr.finished_at','=',Carbon::today())
                ->count();

        return view('filas/filas',$data);
    }

    public function aguardando_acolhimento(){
        verficaPermissao('recurso.atendimentos');
        $data['headerText'] = 'Atendimento ao paciente';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Filas','href' => route('filas/filas')];
        $data['breadcrumbs'][] = ['titulo' => 'Pacientes aguardando consulta de enfermagem/ acolhimento','href' => route('filas/acolhimento')];

        $where = [];
        if (!empty($_REQUEST['paciente'])) {
            $where[] = ['p.nm_pessoa', 'like', '%' . $_REQUEST['paciente'] . '%'];
        }
        if (!empty($_REQUEST['responsavel'])) {
            $where[] = ['e.nm_pessoa',  'like', '%' . $_REQUEST['responsavel'] . '%'];
        }

//------Aguardando acolhimento------------------------------------------------------------------------------------------
        $data['prontuario'] =
            DB::table('prontuario as pr')
                ->leftJoin('beneficiario as b', 'b.id_beneficiario','=','pr.id_beneficiario')
                ->leftJoin('pessoa as p', 'p.cd_pessoa','=','b.cd_pessoa')
                ->leftJoin('acolhimento as a', 'a.cd_prontuario','=','pr.cd_prontuario')
                ->leftJoin('painel as pa','pa.cd_prontuario','=','pr.cd_prontuario')
                ->leftJoin('sala as sa','pa.cd_sala','=','sa.cd_sala')
                ->leftJoin('users as u','u.id','=','pr.id_user_abriu')
                ->leftJoin('pessoa as e','e.cd_pessoa','=','u.cd_pessoa')
                ->where('pr.status','=','A')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('a.created_at','=',null)
                ->where($where)
                ->select('pr.created_at', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'pr.cd_prontuario','sa.nm_sala', 'e.nm_pessoa as recepcionista')
                ->orderBy('pr.created_at')
                ->get();
       return view('filas/acolhimento',$data);
    }

    public function aguardando_atendimento_medico(){
        verficaPermissao('recurso.atendimentos');
        $data['headerText'] = 'Atendimento ao paciente';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Filas','href' => route('filas/filas')];
        $data['breadcrumbs'][] = ['titulo' => 'Pacientes aguardando consulta médica','href' => route('filas/acolhimento')];

        $where = [];
        if (!empty($_REQUEST['paciente'])) {
            $where[] = ['p.nm_pessoa', 'like', '%' . $_REQUEST['paciente'] . '%'];
        }
        if (!empty($_REQUEST['responsavel'])) {
            $where[] = ['e.nm_pessoa',  'like', '%' . $_REQUEST['responsavel'] . '%'];
        }

//------Aguardando atendimento médico-----------------------------------------------------------------------------------
        $data['prontuario'] =
            DB::table('acolhimento as ac')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','ac.cd_prontuario')
                ->leftJoin('beneficiario as b', 'b.id_beneficiario','=','pr.id_beneficiario')
                ->leftJoin('pessoa as p', 'p.cd_pessoa','=','b.cd_pessoa')
                ->leftJoin('atendimento_medico as am','pr.cd_prontuario','=','am.cd_prontuario')
                ->leftJoin('painel as pa','pa.cd_prontuario','=','ac.cd_prontuario')
                ->leftJoin('sala as sa','pa.cd_sala','=','sa.cd_sala')
                ->leftJoin('users as u','u.id','=','ac.id_user')
                ->leftJoin('pessoa as e','e.cd_pessoa','=','u.cd_pessoa')
                ->where('pr.status','=','A')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('am.created_at','=',null)
                ->where('ac.classificacao','<',6)
                ->where($where)
                ->select('ac.created_at', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'ac.cd_prontuario', 'ac.classificacao','sa.nm_sala','pa.horario','e.nm_pessoa as enfermeiro')
                ->orderByDesc('ac.classificacao')
                ->orderBy('ac.created_at')
                ->get();
        return view('filas/atendimento-medico',$data);
    }

    public function medicina_interna(){
        verficaPermissao('recurso.atendimentos');
        $data['headerText'] = 'Atendimento ao paciente';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Filas','href' => route('filas/filas')];
        $data['breadcrumbs'][] = ['titulo' => 'Pacientes atendidos/ medicina interna','href' => route('filas/acolhimento')];

        $where = [];
        if (!empty($_REQUEST['paciente'])) {
            $where[] = ['p.nm_pessoa', 'like', '%' . $_REQUEST['paciente'] . '%'];
        }
        if (!empty($_REQUEST['responsavel'])) {
            $where[] = ['m.nm_pessoa',  'like', '%' . $_REQUEST['responsavel'] . '%'];
        }

//------Pacientes atendidos - Em atendimento interno--------------------------------------------------------------------
        $data['atendimento_interno'] =
            DB::table('atendimento_medico as am')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','am.cd_prontuario')
                ->leftJoin('beneficiario as b', 'b.id_beneficiario','=','pr.id_beneficiario')
                ->leftJoin('pessoa as p', 'p.cd_pessoa','=','b.cd_pessoa')
                ->leftJoin('users as u','u.id','=','am.id_user')
                ->leftJoin('pessoa as m','m.cd_pessoa','=','u.cd_pessoa')
                ->where('pr.status','<>','C')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('am.created_at','<>',null)
                ->where($where)
                ->select('am.created_at', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'am.cd_prontuario','m.nm_pessoa as medico')
                ->orderByDesc('am.created_at')
                ->get();
        $data['atendimentos_direto_procedimentos'] =
            DB::table('acolhimento as ac')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','ac.cd_prontuario')
                ->leftJoin('beneficiario as b', 'b.id_beneficiario','=','pr.id_beneficiario')
                ->leftJoin('pessoa as p', 'p.cd_pessoa','=','b.cd_pessoa')
                ->leftJoin('users as u','u.id','=','ac.id_user')
                ->leftJoin('pessoa as m','m.cd_pessoa','=','u.cd_pessoa')
                ->where('pr.status','<>','C')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('ac.classificacao',7)
                ->where($where)
                ->select('ac.created_at', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'ac.cd_prontuario','m.nm_pessoa as medico')
                ->orderByDesc('ac.created_at')
                ->get();
        return view('filas/medicina-interna',$data);
    }

    public function aguardando_procedimentos(){
        verficaPermissao('recurso.atendimentos');
        $data['headerText'] = 'Atendimento ao paciente';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Filas','href' => route('filas/filas')];
        $data['breadcrumbs'][] = ['titulo' => 'Pacientes aguardando realização de procedimentos','href' => route('filas/acolhimento')];

        $where = [];
        if (!empty($_REQUEST['paciente'])) {
            $where[] = ['p.nm_pessoa', 'like', '%' . $_REQUEST['paciente'] . '%'];
        }
        if (!empty($_REQUEST['responsavel'])) {
            $where[] = ['m.nm_pessoa',  'like', '%' . $_REQUEST['responsavel'] . '%'];
        }

//------Pacientes atendidos - Aguardando procedimentos(Exceto radiológicos)---------------------------------------------
        $data['prontuario'] =
            DB::table('atendimento_procedimento as ap')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','ap.cd_prontuario')
                ->leftJoin('beneficiario as b', 'b.id_beneficiario','=','pr.id_beneficiario')
                ->leftJoin('pessoa as p', 'p.cd_pessoa','=','b.cd_pessoa')
                ->leftJoin('procedimento as pc','pc.cd_procedimento','=','ap.cd_procedimento')
                ->leftJoin('users as u','u.id','=','ap.id_user_solicitante')
                ->leftJoin('pessoa as m','m.cd_pessoa','=','u.cd_pessoa')
                ->where('pr.status','<>','C')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('ap.id_status','=','A')
                ->whereNotBetween('ap.cd_procedimento',[204000000, 204999999])
                ->where($where)
                ->select('ap.cd_prontuario', 'ap.dt_hr_solicitacao', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo','pc.nm_procedimento','ap.id_atendimento_procedimento','m.nm_pessoa as nm_medico')
                ->distinct('ap.cd_prontuario')
                ->orderByDesc('ap.dt_hr_solicitacao')
                ->get();

        return view('filas/procedimentos',$data);
    }

    public function aguardando_procedimentos_radiologicos(){
        verficaPermissao('recurso.atendimentos');
        $data['headerText'] = 'Atendimento ao paciente';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Filas','href' => route('filas/filas')];
        $data['breadcrumbs'][] = ['titulo' => 'Pacientes aguardando realização de procedimentos radiológicos','href' => route('filas/acolhimento')];

        $where = [];
        if (!empty($_REQUEST['paciente'])) {
            $where[] = ['p.nm_pessoa', 'like', '%' . $_REQUEST['paciente'] . '%'];
        }
        if (!empty($_REQUEST['responsavel'])) {
            $where[] = ['m.nm_pessoa',  'like', '%' . $_REQUEST['responsavel'] . '%'];
        }

//------Pacientes atendidos - Aguardando procedimentos radiológicos-----------------------------------------------------
        $data['prontuario'] =
            DB::table('atendimento_procedimento as ap')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','ap.cd_prontuario')
                ->leftJoin('beneficiario as b', 'b.id_beneficiario','=','pr.id_beneficiario')
                ->leftJoin('pessoa as p', 'p.cd_pessoa','=','b.cd_pessoa')
                ->leftJoin('procedimento as pc','pc.cd_procedimento','=','ap.cd_procedimento')
                ->leftJoin('users as u','u.id','=','ap.id_user_solicitante')
                ->leftJoin('pessoa as m','m.cd_pessoa','=','u.cd_pessoa')
                ->where('pr.status','<>','C')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                ->where('ap.id_status','=','A')
                ->whereBetween('ap.cd_procedimento',[204000000, 204999999])
                ->where($where)
                ->select('ap.cd_prontuario', 'ap.dt_hr_solicitacao', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo','pc.nm_procedimento','ap.id_atendimento_procedimento','m.nm_pessoa as nm_medico')
                ->distinct('ap.cd_prontuario')
                ->orderByDesc('ap.dt_hr_solicitacao')
                ->get();

        return view('filas/procedimentos-radiologicos',$data);
    }

    public function atendimentos_finalizados(){
        verficaPermissao('recurso.atendimentos');
        $data['headerText'] = 'Atendimento ao paciente';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Filas','href' => route('filas/filas')];
        $data['breadcrumbs'][] = ['titulo' => "Atendimentos finalizados no dia ".formata_data(Carbon::now()),'href' => route('filas/acolhimento')];

        $where = [];
        if (!empty($_REQUEST['paciente'])) {
            $where[] = ['p.nm_pessoa', 'like', '%' . $_REQUEST['paciente'] . '%'];
        }
        if (!empty($_REQUEST['responsavel'])) {
            $where[] = ['m.nm_pessoa',  'like', '%' . $_REQUEST['responsavel'] . '%'];
        }

//------Pacientes atendidos - Atendimento concluído---------------------------------------------------------------------
        $data['prontuario'] =
            DB::table('prontuario as pr')
                ->leftJoin('atendimento_medico as am', 'pr.cd_prontuario','=','am.cd_prontuario')
                ->leftJoin('acolhimento as ac', 'pr.cd_prontuario','=','ac.cd_prontuario')
                ->leftJoin('beneficiario as b', 'b.id_beneficiario','=','pr.id_beneficiario')
                ->leftJoin('pessoa as p', 'p.cd_pessoa','=','b.cd_pessoa')
                ->leftJoin('users as u','u.id','=','pr.id_user_fechou')
                ->leftJoin('pessoa as m','m.cd_pessoa','=','u.cd_pessoa')
                ->where('pr.status','=','C')
                ->where('pr.cd_estabelecimento','=',session()->get('estabelecimento'))
                //->where('am.created_at','<>',null)
                ->where('ac.classificacao','<>',6) //elimina evasão no acolhimento
                ->whereDate('pr.finished_at','=',Carbon::today())
                ->where($where)
                ->select('pr.finished_at', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'pr.cd_prontuario','pr.created_at','am.motivo_alta','m.nm_pessoa as medico')
                ->orderBy('p.nm_pessoa')
                ->get();
        return view('filas/atendimentos-finalizados',$data);
    }
}