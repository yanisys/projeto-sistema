<?php

namespace App\Http\Controllers;

use App\Mail\mailTeste;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;

class Prontuarios extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function listar(){
        verficaPermissao('recurso.prontuarios');

        $data['headerText'] = 'Prontuários';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Prontuários', 'href' => route('prontuarios/lista')];

        $data['intervalos_datas'] = ['pr' => 'Data/ hora de chegada', 'ac' => 'Data/ hora de acolhimento', 'am' => 'Data/ hora do atendimento médico'];

        if ($_REQUEST) {
            $where = [];
            $whereIn = [];
            $tabela_data = $_REQUEST['filtro'].'.created_at';
            if (!empty($_REQUEST['nm_pessoa'])) {
                $where[] = ['p.nm_pessoa', 'like', '%'.strtoupper($_REQUEST['nm_pessoa']).'%'];
            }
            if (!empty($_REQUEST['dt_nasc'])) {
                $where[] = ['p.dt_nasc', formata_data_mysql($_REQUEST['dt_nasc'])];
            }
            if (!empty($_REQUEST['nm_medico'])) {
                $where[] = ['m.nm_pessoa', 'like', '%'.strtoupper($_REQUEST['nm_medico']).'%'];
            }
            if (!empty($_REQUEST['classificacao']) && $_REQUEST['classificacao'] !== 'T') {
                $where[] = ['ac.classificacao', $_REQUEST['classificacao']];
            }
            if (!empty($_REQUEST['motivo_alta']) && $_REQUEST['motivo_alta'] !== 'T') {
                $where[] = ['am.motivo_alta', $_REQUEST['motivo_alta']];
            }
            if (!empty($_REQUEST['status'])) {
                if(trim($_REQUEST['status']) == "A") {
                    $whereIn = ['A','E'] ;
                } elseif (trim($_REQUEST['status']) == "C") {
                    $whereIn = ['C'] ;
                } else{
                    $whereIn = ['A','C','E'] ;
                }
            }
            if (!empty($_REQUEST['dt_ini'])) {
                $dt = formata_data_mysql($_REQUEST['dt_ini']);
                if (isset($dt)) {
                    $hr = $_REQUEST['hr_ini'] . ':00';
                    if (strlen($hr) != 8) {
                        $hr = '00:00:00';
                    }
                    $dt = $dt . ' ' . $hr;
                    $where[] = [$tabela_data, '>=', $dt];
                }
                $inicio = $dt;
            }

            if (!empty($_REQUEST['dt_fim'])) {
                $dt = formata_data_mysql($_REQUEST['dt_fim']);
                if (isset($dt)) {
                    $hr = $_REQUEST['hr_fim'] . ':59';
                    if (strlen($hr) != 8) {
                        $hr = '23:59:59';
                    }
                    $dt = $dt . ' ' . $hr;
                    $where[] = [$tabela_data, '<=', $dt];
                }
                $final = $dt;
            }

            $where[] = ['cd_estabelecimento','=',session()->get('estabelecimento')];
            if(isset($_REQUEST['cid']) && $_REQUEST['cid'] != '')
                $where[] = ['c.nm_cid', 'like', '%'.strtoupper($_REQUEST['cid']).'%'];

            if($_REQUEST['tp_relatorio'] == 'P'){
                $data['filtros'] = $_REQUEST;
                $data['filtros']['inicio'] = formata_data_hora($inicio);
                $data['filtros']['final'] = formata_data_hora($final);

                set_time_limit('10000');
                ini_set('memory_limit', '-1');
                $data['lista'] = DB::table('prontuario as pr')
                    ->leftJoin('atendimento_medico as am','am.cd_prontuario','pr.cd_prontuario')
                    ->leftJoin('acolhimento as ac','ac.cd_prontuario','pr.cd_prontuario')
                    ->leftJoin('atendimento_avaliacao_cid as aac','aac.cd_prontuario','pr.cd_prontuario')
                    ->leftJoin('cid as c','aac.id_cid','c.id_cid')
                    ->leftJoin('beneficiario as b', 'b.id_beneficiario','=','pr.id_beneficiario')
                    ->leftJoin('pessoa as p', 'p.cd_pessoa','=','b.cd_pessoa')
                    ->leftJoin('users as u', 'u.id','=','pr.id_user_fechou')
                    ->leftJoin('pessoa as m', 'm.cd_pessoa','=','u.cd_pessoa')
                    ->where($where)
                    ->whereIn('pr.status',$whereIn)
                    ->select($tabela_data, 'pr.finished_at', 'am.motivo_alta', 'pr.status', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'pr.cd_prontuario','m.nm_pessoa as nm_medico', DB::raw("group_concat(c.nm_cid SEPARATOR ', ') as nm_cid"))
                    ->groupBy('pr.cd_prontuario')
                    ->orderBy($tabela_data)
                    ->get();
                return view('prontuarios/relatorio', $data);
               /* $pdf = PDF::loadView('prontuarios/relatorio', $data);

                return $pdf->stream();*/
            }

            $data['lista'] = DB::table('prontuario as pr')
                ->leftJoin('atendimento_medico as am','am.cd_prontuario','pr.cd_prontuario')
                ->leftJoin('acolhimento as ac','ac.cd_prontuario','pr.cd_prontuario')
                ->leftJoin('atendimento_avaliacao_cid as aac','aac.cd_prontuario','pr.cd_prontuario')
                ->leftJoin('cid as c','aac.id_cid','c.id_cid')
                ->leftJoin('beneficiario as b', 'b.id_beneficiario','=','pr.id_beneficiario')
                ->leftJoin('pessoa as p', 'p.cd_pessoa','=','b.cd_pessoa')
                ->leftJoin('users as u', 'u.id','=','pr.id_user_fechou')
                ->leftJoin('pessoa as m', 'm.cd_pessoa','=','u.cd_pessoa')
                ->where($where)
                ->whereIn('pr.status',$whereIn)
                ->select($tabela_data, 'pr.finished_at', 'am.motivo_alta', 'pr.status', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'pr.cd_prontuario','m.nm_pessoa as nm_medico', DB::raw("group_concat(c.nm_cid SEPARATOR ', ') as nm_cid"))
                ->groupBy('pr.cd_prontuario')
                ->orderBy($tabela_data)
                ->paginate(30)
                ->appends($_REQUEST);
            }
            $data['motivo_alta'] = arrayPadrao('motivo_alta','T');
            unset($data['motivo_alta'][0]);

        return view('prontuarios/lista', $data);
    }

    public function remover(Request $request){
        try {
            $data['lista'] = DB::table('sala')->where('cd_sala', '=', $_POST['cd_sala'])->where('cd_estabelecimento', '=', session()->get('estabelecimento'))->delete();
            return redirect($_REQUEST['REFERER']);
        } catch(\Illuminate\Database\QueryException $ex){
            return redirect($_REQUEST['REFERER']);
        }

    }

}