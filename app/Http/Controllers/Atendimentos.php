<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Materiais\Requisicoes;
use App\Mail\mailTeste;
use App\Models\Atendimento_medico;
use App\Models\Atendimento_avaliacao_descricao;
use App\Models\Atendimento_subjetivo_descricao;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Atendimentos extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function index(){
        $data['headerText'] = 'Atendimentos';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Atendimento','href' => '#'];
        return view('atendimentos/fila',$data);
    }

    public function fila(){
        verficaPermissao('recurso.atendimentos');
        $data['headerText'] = 'Atendimentos';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Fila','href' => route('atendimentos/fila')];
        $data['pesquisa_beneficiario'] = true;

//------Aguardando acolhimento------------------------------------------------------------------------------------------
        $origem = DB::table('origem')->where('cd_estabelecimento',0)->orWhere('cd_estabelecimento',session('estabelecimento'))->select('cd_origem','nm_origem')->get();
        if(isset($origem)){
            foreach ($origem as $o)
                $data['origem'][$o->cd_origem] = $o->nm_origem;
        }
        $data['novo_prontuario'] = true;
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
                ->select('pr.created_at', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'pr.cd_prontuario','sa.nm_sala', 'e.nm_pessoa as recepcionista')
                ->orderBy('pr.created_at')
                ->get();
//------Aguardando atendimento médico-----------------------------------------------------------------------------------
        $data['acolhimento'] =
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
                ->select('ac.created_at', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'ac.cd_prontuario', 'ac.classificacao','sa.nm_sala','pa.horario','e.nm_pessoa as enfermeiro')
                ->orderByDesc('ac.classificacao')
                ->orderBy('ac.created_at')
                ->get();
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
                ->select('ac.created_at', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'ac.cd_prontuario','m.nm_pessoa as medico')
                ->orderByDesc('ac.created_at')
                ->get();
//------Pacientes atendidos - Aguardando procedimentos(Exceto radiológicos)---------------------------------------------
        $data['procedimentos'] =
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
                ->select('ap.cd_prontuario', 'ap.dt_hr_solicitacao', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo','pc.nm_procedimento','ap.id_atendimento_procedimento','m.nm_pessoa as nm_medico')
                ->distinct('ap.cd_prontuario')
                ->orderByDesc('ap.dt_hr_solicitacao')
                ->get();
//------Pacientes atendidos - Aguardando procedimentos radiológicos-----------------------------------------------------
        $data['procedimentos_radiologicos'] =
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
                ->select('ap.cd_prontuario', 'ap.dt_hr_solicitacao', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo','pc.nm_procedimento','ap.id_atendimento_procedimento','m.nm_pessoa as nm_medico')
                ->distinct('ap.cd_prontuario')
                ->orderByDesc('ap.dt_hr_solicitacao')
                ->get();
//------Pacientes atendidos - Atendimento concluído---------------------------------------------------------------------
        $data['atendimento_concluido'] =
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
                ->select('pr.finished_at', 'p.nm_pessoa', 'p.dt_nasc', 'p.id_sexo', 'pr.cd_prontuario','pr.created_at','am.motivo_alta','m.nm_pessoa as medico')
                ->orderBy('p.nm_pessoa')
                ->get();
        return view('atendimentos/fila',$data);
    }

    public function acolhimento($cdProntuario = null, Request $request){
        verificaEstabelecimentoProntuario($cdProntuario);
        verficaPermissao('recurso.atendimentos');
        $data['headerText'] = 'Consulta de Enfermagem/ Acolhimento';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Filas','href' => route('filas/filas')];
        $data['breadcrumbs'][] = ['titulo' => 'Fila de acolhimentos','href' => route('filas/acolhimento')];
        $data['breadcrumbs'][] = ['titulo' => 'Acolhimento','href' => route('atendimentos/acolhimento')];
        $data['verPessoaSemPesquisa'] = true;

        $acolhimento = DB::table('acolhimento')->where('cd_prontuario',$cdProntuario)->get();
        if (count($acolhimento) > 0) {
            return redirect("filas/acolhimento");
        }
        $data['lista'] = (array)
            DB::table('prontuario as pr')
                ->join('beneficiario as b', 'b.id_beneficiario', '=', 'pr.id_beneficiario')
                ->join('pessoa as p', 'p.cd_pessoa', '=', 'b.cd_pessoa')
                ->where('pr.cd_prontuario', '=', $cdProntuario)
                ->first();

        $data['ultimos_atendimentos'] = DB::table('prontuario')->where('id_beneficiario',$data['lista']['id_beneficiario'])->where('cd_prontuario','<>',$cdProntuario)->orderByDesc('created_at')->select('created_at','cd_prontuario')->get();
        $data_atual = Carbon::now();
        foreach($data['ultimos_atendimentos'] as $u){
            if($data_atual->diffInDays($u->created_at)<=2)
                $u->cor = "red";
            elseif($data_atual->diffInDays($u->created_at) == 3)
                $u->cor = "orange";
            else
                $u->cor = "black";
        }
        $data['salas'] =
            DB::table('sala')
                ->where('cd_estabelecimento','=',session()->get('estabelecimento'))
                ->select('cd_sala','nm_sala')
                ->get();
        $data['extracrumbinfo'] = $data['lista']['nm_pessoa']
            .(($data['lista']['id_sexo'] == 'M') ? ' - Sexo Masculino ' : ' - Sexo Feminino ')
            .(isset($data['lista']['dt_nasc']) ? calcula_idade($data['lista']['dt_nasc']) : "");

      /*  $data_nasc = str_replace('/','-',$data['lista']['dt_nasc']);
        $date = new \DateTime($data_nasc);
        $interval = $date->diff(new \DateTime('now'));*/

        $data['lista']['idade'] = (isset($data['lista']['dt_nasc']) ? Carbon::parse($data['lista']['dt_nasc'])->diff(\Carbon\Carbon::now())->format('%y') : 1);

        if($request->isMethod('post')) {
            $dados = $request->except(['_token','cd_pessoa','id_sexo','dt_nasc','checkbox-1','checkbox-2','alergia']);
            $dados['id_user'] = session()->get('id_user');
            $dados['cd_sala'] = session()->get('cd_sala');

            $rules = [
                'indice_cintura_quadril' => 'nullable|max:999|numeric',
                'massa_corporal' => 'nullable|max:999|numeric',
            ];
            $validator = Validator::make($dados, $rules);
            if ($validator->fails()) {
                $data['lista'] = $_POST;
                return view('atendimentos/acolhimento', $data)->withErrors($validator);
            } else {
                if ($dados['classificacao'] == 6) {//testa se é evasão
                    DB::table('prontuario')->
                    where('cd_prontuario', '=', $_POST['cd_prontuario'])->
                    update(["status" => 'C', "finished_at" => Carbon::now(), 'id_user_fechou' => session()
                        ->get('id_user')]);
                }
                if ($dados['classificacao'] == 8) {//testa se o paciente foi liberado
                    DB::table('prontuario')->
                    where('cd_prontuario', '=', $_POST['cd_prontuario'])->
                    update(["status" => 'C', "finished_at" => Carbon::now(), 'id_user_fechou' => session()
                        ->get('id_user')]);
                }
                $procedimentos = DB::table('atendimento_procedimento')->where('cd_prontuario','=',$cdProntuario)->count();
                if ($dados['classificacao'] == 7) {//testa se deve direcionar para os procedimentos
                    if($procedimentos == 0){
                        $request->session()->flash('confirmation', 'Você deve informar ao menos um procedimento');
                        $data['lista'] = array_merge($data['lista'], $_POST);
                        return view("atendimentos/acolhimento", $data);
                    }
                }
                else{
                    if($procedimentos > 0){
                        DB::table('atendimento_procedimento')->where('cd_prontuario','=',$cdProntuario)->delete();
                    }
               /*     if($_POST['alergia'] == true) {
                        $data['alergias'] = DB::table('pessoa_alergia')->where('cd_pessoa', $_POST['cd_pessoa'])->first();
                        if(!isset($data['alergias']))
                        {
                            $request->session()->flash('confirmation', "Você informou que o paciente possui alergias. Preencha corretamente o campo!");
                            $data['lista'] = array_merge($data['lista'], $_POST);
                            return view("atendimentos/acolhimento", $data);
                        }
                    }*/
                }
                DB::table('acolhimento')->insert($dados);
                $request->session()->flash('status', 'Salvo com Sucesso!');
                return redirect("filas/acolhimento");
            }
        }

        return view("atendimentos/acolhimento", $data);
    }

    public function atendimento_medico($cdProntuario = null, Request $request){
        verificaEstabelecimentoProntuario($cdProntuario);
        verficaPermissao('recurso.atendimentos');
        $data['headerText'] = 'Atendimento médico';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Filas','href' => route('filas/filas')];
        $data['breadcrumbs'][] = ['titulo' => 'Fila de atendimentos','href' => route('filas/atendimento-medico')];
        $data['breadcrumbs'][] = ['titulo' => 'Atendimento médico','href' => route('atendimentos/atendimento-medico')];
        $data['verPessoaSemPesquisa'] = true;

        if($request->isMethod('post')) {
            $verifica_procedimentos_invalidos = DB::table('atendimento_procedimento')
                ->where('cd_prontuario', $cdProntuario)
                ->where('cd_procedimento','<>',301100012)
                ->where('cd_procedimento','<>',401010023)
                ->where('cd_procedimento','<>',401010015)
                ->where('id_status','A')
                ->first();

            if(isset($verifica_procedimentos_invalidos) && $_POST['motivo_alta'] == 4) {
                $request->session()->flash('confirmation', "<h4>Ao optar por <b>ALTA APÓS MEDICAÇÃO</b>, os únicos procedimentos NÃO CONCLUÍDOS permitidos são:</h4>".
                                                            "<ul>".
                                                                "<li>Administração de medicamentos na atenção especializada</li>".
                                                                "<li>Curativo grau I c/ ou s/ debridamento</li>".
                                                                "<li>Curativo grau II c/ ou s/ debridamento</li>".
                                                            "</ul>");
            } else {
                $dados = $request->except(['_token']);
                $update_atendimento = new Atendimento_medico();
                $update_atendimento->fill($dados);
                $dados['id_user'] = session()->get('id_user');
                $dados['cd_sala'] = session()->get('cd_sala');

                $cid_principal = DB::table('atendimento_avaliacao_cid')->where('cd_prontuario', $_POST['cd_prontuario'])->where('cid_principal', 'S')->first();
                if (isset($cid_principal) || $_POST['motivo_alta'] == 6) {
                    $tabAtendimento = new Atendimento_medico();
                    $atendimento = $tabAtendimento->where('cd_prontuario', $_POST['cd_prontuario'])->get();
                    $tabAtendimento->fill($dados);

                    if (isset($atendimento[0])) {
                        $update_atendimento->where('cd_prontuario', $_POST['cd_prontuario'])->update($update_atendimento->toArray());
                    }
                    else
                        $tabAtendimento->save();

                    if (!empty($dados['descricao_avaliacao'])) {
                        $tabAvaliacaoDescricao = new Atendimento_avaliacao_descricao();
                        $tabAvaliacaoDescricao->fill($dados);
                        $tabAvaliacaoDescricao->save();
                        $_POST['descricao_avaliacao'] = '';
                    }
                    if (!empty($dados['descricao_subjetivo'])) {
                        $tabAvaliacaoDescricao = new Atendimento_subjetivo_descricao();
                        $tabAvaliacaoDescricao->fill($dados);
                        $tabAvaliacaoDescricao->save();
                        $_POST['descricao_subjetivo'] = '';
                    }

                    $procedimentos_nao_realizados = DB::table('atendimento_procedimento as ap')
                        ->leftJoin('procedimento as p', 'p.cd_procedimento', '=', 'ap.cd_procedimento')
                        ->where('ap.cd_prontuario', $cdProntuario)
                        ->where('ap.id_status', 'A')
                        ->get();
                    $erro = $this->verifica_lancamentos_plano($cdProntuario, $dados['motivo_alta']);

                   /* $verifica_procedimento = DB::table('atendimento_procedimento')
                        ->where('cd_prontuario', $cdProntuario)
                        ->where('cd_procedimento', 301100012)
                        ->orWhere('cd_procedimento', 401010023)
                        ->orWhere('cd_procedimento', 401010015)
                        ->first();*/

                   $verifica_procedimento = DB::select("select * from atendimento_procedimento
                        where cd_prontuario = $cdProntuario and
                        (cd_procedimento = 301100012 or
                        cd_procedimento = 401010023 or
                        cd_procedimento = 401010015) limit 1");


                    if ($dados['status'] == 'C') {
                        if ($dados['motivo_alta'] > 0) {
                            if ($dados['motivo_alta'] == 4) {
                                if ($erro == false) {
                                    if (!isset($verifica_procedimento[0])) {
                                        DB::table('atendimento_procedimento')->insert(['cd_prontuario' => $cdProntuario, 'id_user_solicitante' => session()->get('id_user'), 'cd_procedimento' => 301100012]);
                                    }
                                    DB::table('prontuario')->where('cd_prontuario', $cdProntuario)->update(['status' => 'E', 'finished_at' => Carbon::now(), 'id_user_fechou' => session()->get('id_user')]);
                                    $request->session()->flash('status', 'Prontuário salvo com sucesso!');
                                    return redirect("filas/atendimento-medico");
                                }
                            } else {
                                if (isset($procedimentos_nao_realizados[0])) {
                                    $request->session()->flash('confirmation', 'O prontuário não pode ser finalizado pois existem procedimentos que ainda não foram encerrados.');
                                } else {
                                    if ($erro == false || $dados['motivo_alta'] == 6) {
                                        DB::table('prontuario')->where('cd_prontuario', $cdProntuario)->update(['status' => $dados['status'], 'finished_at' => Carbon::now(), 'id_user_fechou' => session()->get('id_user')]);
                                        $request->session()->flash('status', 'Prontuário finalizado com sucesso!');
                                        return redirect("filas/atendimento-medico");
                                    }
                                }

                            }
                            if ($erro != false && $dados['motivo_alta'] != 6) {
                                $request->session()->flash('confirmation', $erro);
                            }
                        } else {
                            $request->session()->flash('confirmation', 'Você deve informar o motivo da alta!');
                        }
                    }
                    if ($dados['status'] == 'E') {
                        if (isset($procedimentos_nao_realizados[0])) {
                            $request->session()->flash('confirmation', 'O prontuário não pode ser finalizado pois existem procedimentos que ainda não foram encerrados');
                        } else {
                            if ($erro == false) {
                                if (!isset($verifica_procedimento[0])) {
                                    $request->session()->flash('confirmation', "<h4>Você optou por <b>ALTA APÓS MEDICAÇÃO.</b> Um dos procedimentos abaixo deve constar neste atendimento:</h4>".
                                        "<ul>".
                                        "<li>Administração de medicamentos na atenção especializada</li>".
                                        "<li>Curativo grau I c/ ou s/ debridamento</li>".
                                        "<li>Curativo grau II c/ ou s/ debridamento</li>".
                                        "</ul>");
                                }
                                else {
                                    DB::table('prontuario')->where('cd_prontuario', $cdProntuario)->update(['status' => 'C', 'finished_at' => Carbon::now()]);
                                    $request->session()->flash('status', 'Prontuário finalizado com sucesso!');
                                    return redirect("filas/atendimento-medico");
                                }
                            } else {
                                $request->session()->flash('confirmation', $erro);
                            }
                        }
                    }
                } else {
                    $request->session()->flash('confirmation', "Você deve preencher o Diagnóstico Principal para poder salvar o prontuário");
                }
            }
        }

        $data['prontuario'] = DB::table('prontuario')
            ->where('cd_prontuario', $cdProntuario)
            ->first();
        $data['lista'][0] = $data['prontuario'];
        if(!isset($data['prontuario']))
            return redirect('filas/atendimento-medico');
        $data['procedimentos_realizados'] = DB::table('atendimento_procedimento as ap')
            ->leftJoin('procedimento as p','p.cd_procedimento','=','ap.cd_procedimento')
            ->where('ap.cd_prontuario', $cdProntuario)
            ->where('ap.id_status', 'C')
            ->get();
        $data['acolhimento'] = DB::table('acolhimento as a')
                ->leftJoin('prontuario as pr', 'pr.cd_prontuario','=','a.cd_prontuario')
                ->leftJoin('beneficiario as b', 'b.id_beneficiario', '=', 'pr.id_beneficiario')
                ->leftJoin('pessoa as p', 'p.cd_pessoa', '=', 'b.cd_pessoa')
                ->where('pr.cd_prontuario', '=', $cdProntuario)
                ->first();
        if(!isset($data['acolhimento']))
            return redirect('filas/atendimento-medico');
        $data['salas'] = DB::table('sala')->where('cd_estabelecimento','=',session()->get('estabelecimento'))->select('cd_sala','nm_sala')->get();
        $data['atendimento'] = DB::table('atendimento_medico as am')
            ->leftJoin('prontuario as p','p.cd_prontuario','=','am.cd_prontuario')
            ->leftJoin('procedimento as pro','pro.cd_procedimento','=','am.cd_procedimento')
            ->where('am.cd_prontuario', '=', $cdProntuario)
            ->first();

        $data['avaliacao_descricao'] = DB::table('atendimento_avaliacao_descricao as av')
            ->leftJoin('users as u','u.id','av.id_user')
            ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->leftJoin('profissional as pr','pr.cd_pessoa','p.cd_pessoa')
            ->leftJoin('ocupacao as o','o.cd_ocupacao','pr.cd_ocupacao')
            ->where('cd_prontuario', '=', $cdProntuario)
            ->select('p.nm_pessoa','av.created_at','av.descricao_avaliacao','av.id_user', 'o.nm_ocupacao')
            ->orderBy('av.created_at')
            ->get();
        $data['avaliacao_subjetivo'] = DB::table('atendimento_subjetivo_descricao as as')
            ->leftJoin('users as u','u.id','as.id_user')
            ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->leftJoin('profissional as pr','pr.cd_pessoa','p.cd_pessoa')
            ->leftJoin('ocupacao as o','o.cd_ocupacao','pr.cd_ocupacao')
            ->where('cd_prontuario', '=', $cdProntuario)
            ->select('p.nm_pessoa','as.created_at','as.descricao_subjetivo','as.id_user', 'o.nm_ocupacao')
            ->orderBy('as.created_at')
            ->get();
        $unidades_medida = DB::table('unidade_medida')->where('situacao','A')->get();
        foreach ($unidades_medida as $um)
            $data['unidades_medida'][$um->cd_unidade_medida] = $um->abreviacao;

        $data['ultimos_atendimentos'] = DB::table('prontuario')->where('id_beneficiario',$data['acolhimento']->id_beneficiario)->where('cd_prontuario','<>',$cdProntuario)->orderByDesc('created_at')->select('created_at','cd_prontuario')->get();
        $data_atual = Carbon::now();
        foreach($data['ultimos_atendimentos'] as $u){
            if($data_atual->diffInDays($u->created_at)<=2)
                $u->cor = "red";
            elseif($data_atual->diffInDays($u->created_at) == 3)
                $u->cor = "orange";
            else
                $u->cor = "black";
        }
        $data_nasc = str_replace('/','-',$data['acolhimento']->dt_nasc);
        $date = new \DateTime($data_nasc);
        $interval = $date->diff(new \DateTime('now'));
        $data['acolhimento']->idade = $interval->format('%Y');

        $data['extracrumbinfo'] = $data['acolhimento']->nm_pessoa
                                  .(($data['acolhimento']->id_sexo == 'M') ? ' - Sexo Masculino ' : ' - Sexo Feminino ')
                                  .(isset($data['acolhimento']->dt_nasc) ? calcula_idade($data['acolhimento']->dt_nasc) : "");
        return view("atendimentos/atendimento-medico", $data);

    }

    public function procedimentos($cdProntuario = null, Request $request){
        verificaEstabelecimentoProntuario($cdProntuario);
        verficaPermissao('recurso.atendimentos');
        $data['headerText'] = 'Realização de procedimentos/ Evolução Clínica';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Fila','href' => route('filas/filas')];
        $data['breadcrumbs'][] = ['titulo' => 'Procedimentos/ Evolução Clínica','href' => route('atendimentos/procedimentos')];
        $data['verPessoaSemPesquisa'] = true;

        if($request->isMethod('post')) {
            if($request->id_status === 'C') {
                DB::table('atendimento_procedimento')
                    ->where('id_atendimento_procedimento', '=', $request->id_atendimento_procedimento)
                    ->update(["descricao_execucao" => $request->descricao_execucao, "dt_hr_execucao" => Carbon::now(),
                        'id_user_executante' => session()->get('id_user'), 'id_status' => $_POST['id_status'],
                        'cd_sala_execucao' => session()->get('cd_sala')]);
            } else {
                DB::table('atendimento_procedimento')
                    ->where('id_atendimento_procedimento', '=', $request->id_atendimento_procedimento)
                    ->update(["descricao_execucao" => $request->descricao_execucao]);
            }
            return redirect("atendimentos/procedimentos/".$cdProntuario);
        }

        $data['salas'] = DB::table('sala')->where('cd_estabelecimento','=',session()->get('estabelecimento'))->select('cd_sala','nm_sala')->get();

        $data['lista'] =
            DB::table('prontuario as pr')
                ->join('beneficiario as b', 'b.id_beneficiario', '=', 'pr.id_beneficiario')
                ->join('pessoa as p', 'p.cd_pessoa', '=', 'b.cd_pessoa')
                ->where('pr.cd_prontuario', '=', $cdProntuario)
                ->get();
        $data['acolhimento'] = DB::table('acolhimento')->where('cd_prontuario', '=', $cdProntuario)->select('classificacao')->first();
        $data['salas'] =
            DB::table('sala')
                ->where('cd_estabelecimento','=',session()->get('estabelecimento'))
                ->select('cd_sala','nm_sala')
                ->get();

        $data['extracrumbinfo'] = $data['lista'][0]->nm_pessoa
            .(($data['lista'][0]->id_sexo == 'M') ? ' - Sexo Masculino ' : ' - Sexo Feminino ')
            .(isset($data['lista'][0]->dt_nasc) ? calcula_idade($data['lista'][0]->dt_nasc) : "");

        return view("atendimentos/procedimentos", $data);
    }

    public function lista_pessoas(){
        verficaPermissao('recurso.atendimentos');

        $data['headerText'] = 'Pesquisa pessoa';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Fila','href' => route('atendimentos/fila')];
        $data['breadcrumbs'][] = ['titulo' => 'Pessoas','href' => route('atendimentos/pessoas/lista')];

        if($_REQUEST){
            $where[] = ['id_pessoa','=','F'];
            if(!empty($_REQUEST['nome'])){
                $where[] = ['nm_pessoa','like','%'.$_REQUEST['nome'].'%'];
            }
            if(!empty($_REQUEST['cnpj_cpf'])){
                $where[] = ['cnpj_cpf','=',$_REQUEST['cnpj_cpf']];
            }
            $data['lista'] =
                DB::table('pessoa')
                    ->where($where)
                    ->select('cnpj_cpf','nm_pessoa','id_situacao','id_pessoa','cd_pessoa','nm_mae','dt_nasc')
                    ->orderBy('nm_pessoa')
                    ->paginate(30)
                    ->appends($_REQUEST);

        }

        return view('atendimentos/pessoas/lista',$data);
    }

    public function buscar_planos(){
        $tp_plano = DB::table('estabelecimentos')->where('cd_estabelecimento','=',session()->get('estabelecimento'))->select('tp_plano')->get();
        $planos = str_split($tp_plano[0]->tp_plano);
        $where = [];
        foreach ($planos as $p){
            $where[] = ['p.tp_plano','=', $p];
        }
        $data['lista'] =
            DB::table('plano as p')
                ->leftJoin('contrato as c', function($join)
                {
                    $join->on('c.cd_plano','=','p.cd_plano')
                        ->where('c.cd_pessoa','=',$_POST['cd_pessoa']);
                })
                ->leftJoin('beneficiario as b','b.cd_contrato','=','c.cd_contrato')
                ->whereIn('p.tp_plano',$planos)
                ->select('p.cd_plano','p.tp_plano','p.ds_plano','b.cd_beneficiario','c.cd_contrato', 'b.id_beneficiario','b.cd_pessoa')
                ->get();
        return json_encode(['success' => true, 'dados' => $data['lista']]);

    }

    public function novo_atendimento(Request $request){
        $prontuario = DB::table('prontuario')->where('id_beneficiario','=',$request->id_beneficiario)->where('status','<>','C')->get();
        if(isset($prontuario[0])) {
            $mensagem = "O paciente já possui um prontuário em aberto: Nº ".$prontuario[0]->cd_prontuario;
            return json_encode(['success' => false, 'mensagem' => $mensagem]);
        }
        DB::table('prontuario')
            ->insert(['cd_estabelecimento'=>session()->get('estabelecimento'),'id_beneficiario'=>$request->id_beneficiario, 'cd_origem' => $request->cd_origem,
                'status'=>'A', 'id_user_abriu'=>session()->get('id_user')]);
        return json_encode(['success' => true]);
    }

    public function remover_atendimento(){
        DB::table('prontuario')
            ->where('cd_prontuario','=',$_POST['cd_atendimento'])
            ->update(["status" => 'D']);
        if (!isset($data->erro)) {
            return json_encode(['success' => true]);
        } else {
            return json_encode(['success' => false]);
        }
    }

    public function add_atendimento_avaliacao_cid(Request $request){
        $dados = $request->except('_token');

        $cid = DB::table('atendimento_avaliacao_cid')->where('cd_prontuario', $_POST['cd_prontuario'])->where('id_cid', $_POST['id_cid'])->first();
        if(isset($cid)) {
            return json_encode(['success' => false, 'mensagem' => 'Esta Cid já foi cadastrada!']);
        }

        $dados['id_user'] = session()->get('id_user');
        $dados['cd_prontuario'] =$_POST['cd_prontuario'];
        $dados['dt_primeiros_sintomas'] = formata_data_mysql($dados['dt_primeiros_sintomas']);
        if($dados['cid_principal'] == 'N') {
            DB::table('atendimento_avaliacao_cid')->insert($dados);
        } else {
            $cid_principal = DB::table('atendimento_avaliacao_cid')->where('cd_prontuario', $_POST['cd_prontuario'])->where('cid_principal', $_POST['cid_principal'])->first();
            if(isset($cid_principal)){
                DB::table('atendimento_avaliacao_cid')->where('cd_prontuario', $_POST['cd_prontuario'])->where('cid_principal', $_POST['cid_principal'])->update($dados);
            } else {
                DB::table('atendimento_avaliacao_cid')->insert($dados);
            }
        }

        if (!isset($data->erro)) {
            return json_encode(['success' => true, 'mensagem' => 'Cid cadastrada com sucesso.']);
        } else {
            return json_encode(['success' => false, 'mensagem' => 'Ocorreu um erro.']);
        }
    }

    public function lista_atendimento_avaliacao_cid(){
        $retorno =
            DB::table('atendimento_avaliacao_cid as av')
                ->leftJoin('cid as c', 'c.id_cid', '=', 'av.id_cid')
                ->leftJoin('prontuario as p', 'p.cd_prontuario', '=', 'av.cd_prontuario')
                ->where('av.cd_prontuario', $_POST['cd_prontuario'])
                ->get();

        return json_encode(['success' => true, 'retorno' => $retorno, 'id_user_atual' => Session::get('id_user')]);
    }

    public function exclui_atendimento_avaliacao_cid(){
        DB::table('atendimento_avaliacao_cid')
            ->where('cd_prontuario', $_POST['cd_prontuario'])
            ->where('id_cid',$_POST['id_cid'])
            ->delete();

        return json_encode(['success' => true]);
    }

    public function add_atendimento_procedimento(Request $request){
        $dados = $request->except('_token');
        $dados['id_user_solicitante'] = session()->get('id_user');
        unset($dados['_token']);
        $procedimento = DB::table('atendimento_procedimento')
            ->where('cd_prontuario', $_POST['cd_prontuario'])
            ->where('cd_procedimento', $_POST['cd_procedimento'])
            ->get();
        if(isset($procedimento[0]))
            return json_encode(['success' => false, 'mensagem' => 'Você já cadastrou esse procedimento.']);

        DB::table('atendimento_procedimento')->insert($dados);
        return json_encode(['success' => true, 'mensagem' => 'Salvo com sucesso.']);
    }

    public function reclassificar(){
        $dados = $_POST;
        $dados['id_user'] = session()->get('id_user');
        unset($dados['_token']);
        DB::table('acolhimento_reclassificacao')->insert($dados);
        DB::table('acolhimento')->where('cd_prontuario',$dados['cd_prontuario'])->update(["classificacao"=>$dados['classificacao_nova']]);
        if ($dados['classificacao_nova'] == 6) {//testa se é evasão
            DB::table('prontuario')->
            where('cd_prontuario', '=', $_POST['cd_prontuario'])->
            update(["status" => 'C', "finished_at" => Carbon::now(), 'id_user_fechou' => session()->get('id_user')]);
        }
        return json_encode(['success' => true, 'mensagem' => 'Salvo com sucesso.']);
    }

    public function lista_atendimento_procedimento(){
        $retorno = DB::select(
            "select ap.descricao_solicitacao,p.cd_procedimento,p.nm_procedimento,ap.id_atendimento_procedimento,ap.id_status,pro.status,ap.id_user_solicitante,rpo.cd_procedimento as permitido
            from atendimento_procedimento as ap
            left join procedimento as p on p.cd_procedimento = ap.cd_procedimento
            left join prontuario as pro on pro.cd_prontuario = ap.cd_prontuario
            left join users as u on u.id = ".Session::get('id_user')."
            left join profissional as prof on prof.cd_pessoa = u.cd_pessoa
            left join pessoa as pes on pes.cd_pessoa = prof.cd_pessoa
            left join rl_procedimento_ocupacao as rpo on rpo.cd_procedimento = ap.cd_procedimento and rpo.co_ocupacao = prof.cd_ocupacao
            where ap.cd_prontuario = ".$_POST['cd_prontuario']);

        return json_encode(['success' => true, 'retorno' => $retorno, 'id_user_atual' => Session::get('id_user')]);

    }

    public function exclui_atendimento_procedimento(){
        DB::table('atendimento_procedimento')
            ->where('cd_prontuario', $_POST['cd_prontuario'])
            ->where('cd_procedimento',$_POST['cd_procedimento'])
            ->delete();

        return json_encode(['success' => true]);

    }

    public function salva_procedimento_atendimento(Request $request){
        if($_POST['id_status'] === 'C') {
            DB::table('atendimento_procedimento')
                ->where('id_atendimento_procedimento', '=', $request->id_atendimento_procedimento)
                ->update(["descricao_execucao" => $request->descricao_execucao, "dt_hr_execucao" => Carbon::now(),
                    'id_user_executante' => session()->get('id_user'), 'id_status' => $request->id_status,
                    'cd_sala_execucao' => session()->get('cd_sala')]);
        } else {
            DB::table('atendimento_procedimento')
                ->where('id_atendimento_procedimento', '=', $request->id_atendimento_procedimento)
                ->update(["descricao_execucao" => $request->descricao_execucao]);
        }

        return json_encode(['success' => true, 'mensagem' => 'Salvo com sucesso.']);
    }

    public function carrega_modal_procedimento(){
        $retorno = DB::table('atendimento_procedimento as ap')
            ->leftJoin('procedimento as pr','pr.cd_procedimento','=','ap.cd_procedimento')
            ->leftJoin('users as u','ap.id_user_solicitante','u.id')->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->leftJoin('users as ue','ap.id_user_executante','ue.id')->leftJoin('pessoa as pe','pe.cd_pessoa','ue.cd_pessoa')
            ->leftJoin('users as ul','ap.id_user_laudo','ul.id')->leftJoin('pessoa as pl','pl.cd_pessoa','ul.cd_pessoa')
            ->leftJoin('profissional as po','po.cd_pessoa','p.cd_pessoa')->leftJoin('ocupacao as o','o.cd_ocupacao','po.cd_ocupacao')
            ->leftJoin('profissional as poe','poe.cd_pessoa','pe.cd_pessoa')->leftJoin('ocupacao as oe','oe.cd_ocupacao','poe.cd_ocupacao')
            ->leftJoin('profissional as pol','pol.cd_pessoa','pl.cd_pessoa')->leftJoin('ocupacao as ol','ol.cd_ocupacao','pol.cd_ocupacao')
            ->where('ap.id_atendimento_procedimento', '=', $_POST['id_atendimento_procedimento'])
            ->select('pr.cd_procedimento','pr.nm_procedimento','o.nm_ocupacao as profissional_solicitante','oe.nm_ocupacao as profissional_executante',
                     'ol.nm_ocupacao as profissional_laudo','p.nm_pessoa as pessoa_solicitante','pe.nm_pessoa as pessoa_executante','pl.nm_pessoa as pessoa_laudo',
                     'descricao_solicitacao','descricao_execucao', 'descricao_laudo','dt_hr_solicitacao', 'dt_hr_execucao', 'dt_hr_laudo', 'qtde_medida','ap.id_atendimento_procedimento','ap.id_status')
            ->get();

        if (!isset($data->erro))
            return json_encode(['success' => true,'retorno' => $retorno]);

    }

    public function pesquisa_cid(){
        $retorno = DB::table('cid')
            ->where('nm_cid','like','%'.$_POST['pesquisa'].'%')
            ->orWhere('cd_cid','like','%'.$_POST['pesquisa'].'%')
            ->select('id_cid','cd_cid','nm_cid')
            ->get();
        return json_encode(['success' => true,'dados' => $retorno]);
    }

    public function pesquisa_procedimento(){
        $where = "rpe.cd_estabelecimento = ".session('estabelecimento');
        if(!is_numeric($_POST['pesquisa']))
            $where .= " and upper(p.nm_procedimento) like '%".strtoupper($_POST['pesquisa'])."%'";
        else
            $where .= " and p.cd_procedimento = ".$_POST['pesquisa'];
        $retorno = DB::select("
            select p.cd_procedimento, p.nm_procedimento 
            from procedimento as p 
            inner join rl_procedimento_estabelecimento as rpe on p.cd_procedimento = rpe.cd_procedimento
            where $where
            order by p.nm_procedimento asc
            ");

        return json_encode(['success' => true,'retorno' => $retorno]);
    }

    public function pesquisa_procedimento_ocupacao(){
        $retorno = DB::table('procedimento as p')
            ->where('p.nm_procedimento','like','%'.strtoupper($_POST['pesquisa']).'%')
            ->select('p.cd_procedimento','p.nm_procedimento')
            ->orderBy('p.nm_procedimento')
            ->get();

        return json_encode(['success' => true,'retorno' => $retorno]);
    }

    public function ver_historico(){
        $retorno = DB::table('prontuario as p')
            ->leftJoin('acolhimento as a','a.cd_prontuario','p.cd_prontuario')
            ->leftJoin('users as u','u.id','a.id_user')
            ->leftJoin('pessoa as ps','ps.cd_pessoa','u.cd_pessoa')
            ->leftJoin('profissional as pro','u.cd_pessoa','pro.cd_pessoa')
            ->leftJoin('ocupacao as oc','oc.cd_ocupacao','pro.cd_ocupacao')
            ->leftJoin('procedimento as pr','a.cd_procedimento','pr.cd_procedimento')
            ->leftJoin('beneficiario as b','b.id_beneficiario','p.id_beneficiario')
            ->leftJoin('pessoa as pe','pe.cd_pessoa','b.cd_pessoa')
            ->where('b.cd_pessoa',$_POST['cd_pessoa'])
            ->select('pe.nm_pessoa','p.cd_prontuario','pr.nm_procedimento','a.cd_procedimento','a.created_at as criacao','ps.nm_pessoa as responsavel','oc.nm_ocupacao as ocupacao')
            ->orderByDesc('criacao')
            ->get();

        $retorno1 = DB::table('prontuario as p')
            ->leftJoin('atendimento_medico as a','a.cd_prontuario','p.cd_prontuario')
            ->leftJoin('users as u','u.id','a.id_user')
            ->leftJoin('pessoa as ps','ps.cd_pessoa','u.cd_pessoa')
            ->leftJoin('profissional as pro','u.cd_pessoa','pro.cd_pessoa')
            ->leftJoin('ocupacao as oc','oc.cd_ocupacao','pro.cd_ocupacao')
            ->leftJoin('procedimento as pr','a.cd_procedimento','pr.cd_procedimento')
            ->leftJoin('beneficiario as b','b.id_beneficiario','p.id_beneficiario')
            ->leftJoin('pessoa as pe','pe.cd_pessoa','b.cd_pessoa')
            ->where('b.cd_pessoa',$_POST['cd_pessoa'])
            ->select('pe.nm_pessoa','p.cd_prontuario','pr.nm_procedimento','a.cd_procedimento','a.created_at as criacao','ps.nm_pessoa as responsavel','oc.nm_ocupacao as ocupacao')
            ->orderByDesc('criacao')
            ->get();
        foreach ($retorno as $r)
            $retorno2[] = $r;
        foreach ($retorno1 as $r)
            $retorno2[] = $r;
        if(isset($retorno2[0])) {
            usort($retorno2, function ($a, $b) {
                return $b->criacao <=> $a->criacao;
            });
            return json_encode(['success' => true, 'retorno' => $retorno2]);
        }
        else
            return json_encode(['success' => false]);
    }

    public function verifica_lancamentos_plano($cdProntuario, $motivo_alta){
        if($motivo_alta != 4) {
            $procedimentos = DB::table('atendimento_procedimento as ap')
                ->leftJoin('procedimento as p', 'p.cd_procedimento', '=', 'ap.cd_procedimento')
                ->where('ap.cd_prontuario', $cdProntuario)
                ->get();
            $atendimento = DB::table('atendimento_medico as am')
                ->leftJoin('prontuario as p', 'p.cd_prontuario', '=', 'am.cd_prontuario')
                ->leftJoin('procedimento as pro', 'pro.cd_procedimento', '=', 'am.cd_procedimento')
                ->where('am.cd_prontuario', '=', $cdProntuario)
                ->get();
            if (isset($procedimentos[0]) || isset($atendimento[0]->descricao_plano) != null || $motivo_alta == 7) {
                $erro = false;
            } else {
                $erro = "Você deve lançar um procedimento ou preencher o campo Intervenção/Procedimentos do plano para poder finalizar o prontuário";
            }
        }else{
            $erro = false;
        }

        return $erro;
    }

    public function add_atendimento_evolucao(Request $request){
        DB::table('atendimento_evolucao')
            ->insert(['cd_prontuario'=>$request->cd_prontuario,'cd_sala'=>$request->cd_sala,'cd_leito'=>$request->cd_leito,'id_user'=>session()->get('id_user'),'descricao_evolucao'=>$request->descricao_evolucao]);
        return json_encode(['success' => true]);
    }

    public function lista_atendimento_evolucao(){
        $retorno = DB::table('atendimento_evolucao as ae')
            ->leftJoin('users as u','u.id','ae.id_user')
            ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->leftJoin('sala as s','s.cd_sala','ae.cd_sala')
            ->where('cd_prontuario',$_POST['cd_prontuario'])
            ->select('s.nm_sala','ae.cd_leito','ae.descricao_evolucao','ae.created_at','p.nm_pessoa')
            ->orderBy('ae.created_at')
            ->get();

        return json_encode(['success' => true, 'retorno' => $retorno]);
    }

    public function finalizar_atendimento_sem_medico(){
        $procedimentos_nao_realizados = DB::table('atendimento_procedimento as ap')
            ->leftJoin('procedimento as p', 'p.cd_procedimento', '=', 'ap.cd_procedimento')
            ->where('ap.cd_prontuario', $_POST['cd_prontuario'])
            ->where('ap.id_status', 'A')
            ->get();

        if(!isset($procedimentos_nao_realizados[0])) {
            DB::table('prontuario')->where('cd_prontuario', $_POST['cd_prontuario'])->update(['status' => 'C', 'finished_at' => Carbon::now(), 'id_user_fechou' => session()->get('id_user')]);
            return json_encode(['success' => true, 'mensagem' => 'Atendimento finalizado com sucesso!']);
        }
        else{
            return json_encode(['success' => false, 'mensagem' => 'Você não pode finalizar o atendimento se houver algum procedimento em aberto!']);
        }
    }

    public function pesquisa_alergia(){
        $retorno = DB::table('alergia')
            ->where('nm_alergia','like','%'.$_POST['pesquisa'].'%')
            ->orWhere('descricao','like','%'.$_POST['pesquisa'].'%')
            ->get();
        return json_encode(['success' => true,'dados' => $retorno]);
    }

    public function add_alergia_pessoa(Request $request){
        $dados = $request->except('_token');
        $alergia = DB::table('pessoa_alergia')->where('cd_pessoa', $_POST['cd_pessoa'])->where('cd_alergia', $_POST['cd_alergia'])->first();
        if(isset($alergia)) {
            return json_encode(['success' => false, 'mensagem' => 'Esta alergia já foi cadastrada!']);
        }
        DB::table('pessoa_alergia')->insert($dados);
        return json_encode(['success' => true, 'mensagem' => 'Alergia cadastrada com sucesso.']);
    }

    public function lista_alergia_pessoa(){
        $retorno =
            DB::table('pessoa_alergia as pa')
                ->leftJoin('alergia as a', 'a.cd_alergia', '=', 'pa.cd_alergia')
                ->where('pa.cd_pessoa', $_POST['cd_pessoa'])
                ->select('pa.cd_pessoa', 'pa.id_pessoa_alergia', 'a.nm_alergia', 'a.descricao')
                ->get();
        return json_encode(['success' => true, 'retorno' => $retorno]);
    }

    public function exclui_alergia_pessoa(){
        DB::table('pessoa_alergia')
            ->where('id_pessoa_alergia', $_POST['id_pessoa_alergia'])
            ->delete();
        return json_encode(['success' => true]);
    }

    public function add_cid_historia_medica_pregressa(Request $request){
        $dados = $request->except('_token');
        $dados['id_user'] = session()->get('id_user');
        $cid = DB::table('pessoa_historia_medica')->where('cd_pessoa', $_POST['cd_pessoa'])->where('id_cid', $_POST['id_cid'])->first();
        if(isset($cid)) {
            return json_encode(['success' => false, 'mensagem' => 'Esta Cid já foi cadastrada!']);
        }
        DB::table('pessoa_historia_medica')->insert($dados);
        return json_encode(['success' => true, 'mensagem' => 'Cid cadastrada com sucesso.']);
    }

    public function lista_cid_historia_medica_pregressa(){
        $retorno =
            DB::table('pessoa_historia_medica as phm')
                ->leftJoin('cid as c', 'c.id_cid', '=', 'phm.id_cid')
                ->where('phm.cd_pessoa', $_POST['cd_pessoa'])
                ->select('phm.id_pessoa_historia_medica', 'c.cd_cid', 'c.nm_cid')
                ->get();
        return json_encode(['success' => true, 'retorno' => $retorno]);
    }

    public function exclui_cid_historia_medica_pregressa(){
        DB::table('pessoa_historia_medica')
            ->where('id_pessoa_historia_medica', $_POST['id_pessoa_historia_medica'])
            ->delete();
        return json_encode(['success' => true]);
    }

    public function add_atendimento_prescricao(Request $request){
        $tipos = [0=>'dieta',1=>'csv',2=>'outros_cuidados',3=>'medicacao',4=>'oxigenoterapia'];
        $dados = $request->except('_token');
        $dados['id_user'] = session()->get('id_user');

        if($dados['tp_prescricao'] === 'PRESCRICAO_AMBULATORIAL') {
            $dados['expira_em'] = Carbon::now()->addHours(24);
            $retorno = (array) DB::table('atendimento_prescricao')->where('cd_prontuario', $dados['cd_prontuario'])->where('tp_prescricao', $dados['tp_prescricao'])->orderByDesc('id_atendimento_prescricao')->first();
            $dados['cd_prescricao'] = (isset($retorno['cd_prescricao']) ? $retorno['cd_prescricao'] : 0) + 1;

            DB::table('atendimento_prescricao')->insert($dados);

            if($dados['cd_prescricao'] > 1) {
                $id_atendimento_prescricao_anterior = $retorno['id_atendimento_prescricao'];
                $id_atendimento_prescricao_atual = DB::table('atendimento_prescricao')->max('id_atendimento_prescricao');
                foreach ($tipos as $nome) {
                    $ultimos_lcto = DB::table('atendimento_prescricao_' . $nome)->where('id_atendimento_prescricao', $id_atendimento_prescricao_anterior)->get();

                    if (isset($ultimos_lcto[0])) {
                        foreach ($ultimos_lcto as $u) {
                            $u->id_atendimento_prescricao = $id_atendimento_prescricao_atual;
                            $id = "id_atendimento_prescricao_".$nome;
                            unset($u->$id);
                            unset($u->created_at);
                            unset($u->id_user_executante);
                            DB::table('atendimento_prescricao_' . $nome)->insert((array) $u);
                        }
                    }

                }
            }
        }
        else{
            $dados['cd_prescricao'] = 1;
            DB::table('atendimento_prescricao')->insert($dados);
        }

        $prescricao = DB::table('atendimento_prescricao')->where('cd_prontuario',$dados['cd_prontuario'])->orderByDesc('id_atendimento_prescricao')->first();
        return json_encode(['success' => true, 'dados' => $prescricao]);
    }

    public function concluir_atendimento_prescricao(Request $request){
        DB::table('atendimento_prescricao')->where('id_atendimento_prescricao',$request['id_atendimento_prescricao'])->update(['status'=>'C']);
        $dados = (array) DB::table('atendimento_prescricao')->where('id_atendimento_prescricao',$request['id_atendimento_prescricao'])->select('id_user as id_user_solicitante','id_atendimento_prescricao')->first();
        $dados['cd_estabelecimento'] = session()->get('estabelecimento');
        $dados['cd_sala_destino'] = get_config(5,session()->get('estabelecimento')); //farmácia
        $dados['cd_sala_origem'] = get_config(4,session()->get('estabelecimento')); //pronto-atendimento
        $req = new Requisicoes();
        $id = $req->lanca_requisicao($dados,'insert');
        $itens = DB::table('atendimento_prescricao_medicacao as apm')
            ->leftJoin('produto as p','p.cd_produto','apm.cd_medicamento')
            ->where('apm.id_atendimento_prescricao',$request['id_atendimento_prescricao'])
            ->where('apm.status','A')
            ->select('apm.cd_medicamento as cd_produto', 'apm.dose as tamanho_dose','apm.intervalo','apm.tp_intervalo','apm.prazo','apm.tp_prazo','p.fracionamento','p.qtde_embalagem')
            ->get();

        $insert = [];
        foreach($itens as $key=>$i) {
            $insert[$key]['cd_requisicao'] = $id;
            $total_horas = $i->prazo;
            if($i->intervalo == 3)//3 = dias
                $total_horas = $total_horas * 24;
            $qtde_doses = $total_horas / $i->intervalo;
            $quantidade = $qtde_doses * $i->tamanho_dose;
            if($i->fracionamento == 0){
                $quantidade = ceil($quantidade/ $i->qtde_embalagem); // ceil = arredonda para cima
            }
            $insert[$key]['quantidade'] = $quantidade;
            $insert[$key]['cd_produto'] = $i->cd_produto;
        }
        $req->lanca_itens_requisicao($insert,'insert');
        return json_encode(['success' => true]);
    }

    public function add_item_atendimento_prescricao(Request $request){
        $dados = $request->except('_token','cd_prontuario','tp_conduta','nome');
        if($request['tp_conduta'] == 'PRESCRICAO_AMBULATORIAL') {
            if (!$this->verifica_procedimento_lancado('0301060029', $request['cd_prontuario']))
                DB::table('atendimento_procedimento')->insert(['cd_prontuario' => $request['cd_prontuario'], 'cd_procedimento' => '0301060029', 'id_user_solicitante' => session()->get('id_user')]);
            if (!$this->verifica_procedimento_lancado('0301100012', $request['cd_prontuario']))
                DB::table('atendimento_procedimento')->insert(['cd_prontuario' => $request['cd_prontuario'], 'cd_procedimento' => '0301100012', 'id_user_solicitante' => session()->get('id_user')]);
        }
        else{
            if($request['id_atendimento_prescricao'] == 0){
                $info['id_user'] = session()->get('id_user');
                $info['expira_em'] = Carbon::now()->addHours(24);
                $info['cd_prescricao'] = 1;
                $info['cd_prontuario'] = $request['cd_prontuario'];
                $info['tp_prescricao'] = $request['tp_conduta'];
                DB::table('atendimento_prescricao')->insert($info);
                $prescricao = DB::table('atendimento_prescricao')->where('cd_prontuario',$info['cd_prontuario'])->orderByDesc('id_atendimento_prescricao')->first();
                $dados['id_atendimento_prescricao'] = $prescricao->id_atendimento_prescricao;
            }
        }
        DB::table('atendimento_prescricao_'.strtolower($request['nome']))->insert($dados);

        return json_encode(['success' => true, 'id_atendimento_prescricao' => $dados['id_atendimento_prescricao']]);
    }

    public function busca_ultimas_prescricoes(Request $request){
        $receituario = DB::table('atendimento_prescricao')->where('cd_prontuario',$request['cd_prontuario'])->where('tp_prescricao','RECEITUARIO')->select('id_atendimento_prescricao','cd_prescricao','status')->orderByDesc('id_atendimento_prescricao')->first();
        $prescricao_ambulatorial = DB::table('atendimento_prescricao')->where('cd_prontuario',$request['cd_prontuario'])->where('tp_prescricao','PRESCRICAO_AMBULATORIAL')->orderByDesc('id_atendimento_prescricao')->first();
        $prescricao_hospitalar = DB::table('atendimento_prescricao')->where('cd_prontuario',$request['cd_prontuario'])->where('tp_prescricao','PRESCRICAO_HOSPITALAR')->select('id_atendimento_prescricao','cd_prescricao','status')->orderByDesc('id_atendimento_prescricao')->first();

        if(isset($receituario->id_atendimento_prescricao)) {
            $data['receituario']  = $this->busca_itens_prescricao($receituario->id_atendimento_prescricao);
            $prescricao['receituario']['id_atendimento_prescricao'] = $receituario->id_atendimento_prescricao;
            $prescricao['receituario']['cd_prescricao'] = $receituario->cd_prescricao;
            $prescricao['receituario']['status'] = $receituario->status;
        }
        if(isset($prescricao_ambulatorial->id_atendimento_prescricao)) {
            $data['prescricao_ambulatorial'] = $this->busca_itens_prescricao($prescricao_ambulatorial->id_atendimento_prescricao);
            $prescricao['prescricao_ambulatorial']['id_atendimento_prescricao'] = $prescricao_ambulatorial->id_atendimento_prescricao;
            $prescricao['prescricao_ambulatorial']['cd_prescricao'] = $prescricao_ambulatorial->cd_prescricao;
            $prescricao['prescricao_ambulatorial']['created_at'] = $prescricao_ambulatorial->created_at;
            $prescricao['prescricao_ambulatorial']['expira_em'] = $prescricao_ambulatorial->expira_em;
            $prescricao['prescricao_ambulatorial']['status'] = $prescricao_ambulatorial->status;
        }
        if(isset($prescricao_hospitalar->id_atendimento_prescricao)) {
            $data['prescricao_hospitalar']  = $this->busca_itens_prescricao($prescricao_hospitalar->id_atendimento_prescricao);
            $prescricao['prescricao_hospitalar']['id_atendimento_prescricao'] = $prescricao_hospitalar->id_atendimento_prescricao;
            $prescricao['prescricao_hospitalar']['cd_prescricao'] = $prescricao_hospitalar->cd_prescricao;
            $prescricao['prescricao_hospitalar']['status'] = $prescricao_hospitalar->status;
        }

        if(isset($data))
            return json_encode(['success' => true, 'itens'=> $data, 'prescricao'=>$prescricao]);
        else
            return json_encode(['success' => true]);
    }

    public function busca_prescricao_ambulatorial(Request $request){
        $prescricao_ambulatorial = DB::select("
          select * 
          from atendimento_prescricao
          where cd_prontuario = " . $request['cd_prontuario'] . " and 
                tp_prescricao = 'PRESCRICAO_AMBULATORIAL' and 
                id_atendimento_prescricao " . ($request['comando'] === 'PROXIMA' ? '>' : '<') . $request['prescricao_atual'] . " 
          order by id_atendimento_prescricao " . ($request['comando'] === 'PROXIMA' ? 'asc' : 'desc') . " limit 1");

        if(isset($prescricao_ambulatorial[0]->id_atendimento_prescricao)) {
            $data['prescricao_ambulatorial'] = $this->busca_itens_prescricao($prescricao_ambulatorial[0]->id_atendimento_prescricao);
            $prescricao['prescricao_ambulatorial']['id_atendimento_prescricao'] = $prescricao_ambulatorial[0]->id_atendimento_prescricao;
            $prescricao['prescricao_ambulatorial']['cd_prescricao'] = $prescricao_ambulatorial[0]->cd_prescricao;
            $prescricao['prescricao_ambulatorial']['created_at'] = $prescricao_ambulatorial[0]->created_at;
            $prescricao['prescricao_ambulatorial']['expira_em'] = $prescricao_ambulatorial[0]->expira_em;
            $prescricao['prescricao_ambulatorial']['status'] = $prescricao_ambulatorial[0]->status;
        }

        if(isset($data))
            return json_encode(['success' => true, 'itens'=> $data, 'prescricao'=>$prescricao]);
        else
            return json_encode(['success' => true]);

    }

    function busca_itens_prescricao($id_prescricao){
        $data['medicacao'] = DB::table('atendimento_prescricao_medicacao as apm')
            ->leftJoin('atendimento_prescricao as ap','ap.id_atendimento_prescricao','apm.id_atendimento_prescricao')
            ->leftJoin('unidade_medida as um','um.cd_unidade_medida','apm.tp_dose')
            ->leftJoin('via_aplicacao_medicamentos as vam','vam.cd_via_aplicacao_medicamentos','apm.tp_via')
            ->join('produto as p', 'apm.cd_medicamento', 'p.cd_produto')
            ->where('apm.id_atendimento_prescricao',$id_prescricao)
            ->select('apm.*', 'p.*', 'um.*', 'vam.sigla', 'ap.status as status_prescricao')
            ->get();
        foreach($data['medicacao'] as $i){
            $i->tp_intervalo = (isset($i->tp_intervalo) ? arrayPadrao('periodo')[$i->tp_intervalo] : "");
            $i->tp_prazo = (isset($i->tp_prazo) ? arrayPadrao('periodo')[$i->tp_prazo] : "");
        }
        $data['dieta'] = DB::table('atendimento_prescricao_dieta as apd')->leftJoin('atendimento_prescricao as ap','ap.id_atendimento_prescricao','apd.id_atendimento_prescricao')->where('apd.id_atendimento_prescricao',$id_prescricao)->select('apd.*','ap.status as status_prescricao')->get();
        if(isset($data['dieta'][0])){
            foreach ($data['dieta'] as $key => $pd) {
                $data['dieta'][$key]->dieta = arrayPadrao('dieta')[$pd->dieta];
                $data['dieta'][$key]->via_dieta = arrayPadrao('via_dieta')[$pd->via_dieta];
            }
        }
        $data['csv'] = DB::table('atendimento_prescricao_csv as apc')->leftJoin('atendimento_prescricao as ap','ap.id_atendimento_prescricao','apc.id_atendimento_prescricao')->where('apc.id_atendimento_prescricao',$id_prescricao)->select('apc.*','ap.status as status_prescricao')->get();
        if(isset($data['csv'][0])){
            foreach ($data['csv'] as $key => $pc) {
                $data['csv'][$key]->tp_prazo_csv = arrayPadrao('periodo')[$pc->tp_prazo_csv];
                $data['csv'][$key]->tp_intervalo_csv = arrayPadrao('periodo')[$pc->tp_intervalo_csv];
            }
        }
        $data['outros_cuidados'] = DB::table('atendimento_prescricao_outros_cuidados as apoc')->leftJoin('atendimento_prescricao as ap','ap.id_atendimento_prescricao','apoc.id_atendimento_prescricao')->where('apoc.id_atendimento_prescricao',$id_prescricao)->select('apoc.*','ap.status as status_prescricao')->get();
        if(isset($data['outros_cuidados'][0])){
            foreach ($data['outros_cuidados'] as $key => $poc) {
                $data['outros_cuidados'][$key]->posicao = arrayPadrao('posicoes_enfermagem')[$poc->posicao];
            }
        }
        $data['oxigenoterapia'] = DB::table('atendimento_prescricao_oxigenoterapia as apo')->leftJoin('atendimento_prescricao as ap','ap.id_atendimento_prescricao','apo.id_atendimento_prescricao')->where('apo.id_atendimento_prescricao',$id_prescricao)->select('apo.*','ap.status as status_prescricao')->get();

        if(isset($data['oxigenoterapia'][0])){
            foreach ($data['oxigenoterapia'] as $key => $po) {
                $data['oxigenoterapia'][$key]->administracao_oxigenio = arrayPadrao('administracao_oxigenio')[$po->administracao_oxigenio];
            }
        }
        $data['exame_laboratorial'] = DB::table('atendimento_prescricao_exame_laboratorial as apel')->leftJoin('atendimento_prescricao as ap','ap.id_atendimento_prescricao','apel.id_atendimento_prescricao')->where('apel.id_atendimento_prescricao',$id_prescricao)->select('apel.*','ap.status as status_prescricao')->get();
        foreach ($data['exame_laboratorial']as $i){
            $i->exame_laboratorial = arrayPadrao('exames_laboratoriais')[$i->cd_exame_laboratorial];
        }
        return $data;
    }

    public function add_cirurgia_previa(Request $request){
        $request['dt_cirurgia'] = formata_data_mysql($request['dt_cirurgia']);
        $dados = $request->except('_token');
        DB::table('pessoa_cirurgia_previa')->insert($dados);
        return json_encode(['success' => true, 'mensagem' => 'Cirurgia cadastrada com sucesso.']);
    }

    public function lista_cirurgia_previa(Request $request){
        $cirurgias = DB::table('pessoa_cirurgia_previa')->where('cd_pessoa',$request['cd_pessoa'])->get();
        return json_encode(['success' => true, 'retorno' => $cirurgias]);
    }

    public function exclui_cirurgia_previa(Request $request){
        DB::table('pessoa_cirurgia_previa')->where('id_pessoa_cirurgia_previa', $request['id_pessoa_cirurgia_previa'])->delete();
        return json_encode(['success' => true, 'mensagem' => 'Registro excluído com sucesso']);
    }

    public function editar_prescricao(){
        DB::table('atendimento_prescricao_'.$_POST['par1'])
            ->where('id_atendimento_prescricao_'.$_POST['par1'],$_POST['par2'])
            ->update(['status' => $_POST['par3'], 'id_executante' => session()->get('id_user')]);
        return json_encode(['success' => true, 'mensagem' => 'Registro '.($_POST['par3'] == 'E' ? 'excluído' : 'alterado').' com sucesso']);
    }

    function verifica_procedimento_lancado($cd_procedimento,$cd_prontuario){
        $existe = DB::table('atendimento_procedimento')->where('cd_prontuario',$cd_prontuario)->where('cd_procedimento',$cd_procedimento)->first();
        if(isset($existe))
            return true;
        else
            return false;
    }

    public function pesquisa_medicamento(Request $request){
        $where[] = ['p.situacao','not like','I'];
        if($request['controle']==='TRUE')
            $where[] = ['p.prescricao_interna',1];

        $retorno = DB::table('produto as p')
            ->leftJoin('unidade_medida as um','um.cd_unidade_medida','p.cd_fracao_minima')
            ->leftJoin('movimentacao_itens as mi', 'mi.cd_produto', 'p.cd_produto')
            ->leftJoin('movimentacao as m', 'mi.cd_movimentacao', 'm.cd_movimentacao')
            ->where($where)
                ->where(function($q) {
                $q->where('p.nm_produto','like','%'.$_POST['pesquisa'].'%')
                    //->orWhere('p.nm_laboratorio','like','%'.$_POST['pesquisa'].'%')
                    ->orWhere('p.ds_produto','like','%'.$_POST['pesquisa'].'%');
            })
            ->select('p.cd_produto','p.prescricao_interna','p.cd_fracao_minima as cd_unidade_medida','um.abreviacao as nm_unidade_medida','p.nm_produto as nm_med','p.ds_produto as ds_apr','p.nm_laboratorio as nm_lab'/*,'md.ds_sub'*/,DB::raw("sum(if(m.tp_saldo = 'A',mi.quantidade,if(m.tp_saldo = 'S', - mi.quantidade,0))) as quantidade"))
            ->groupBy('p.cd_produto','p.prescricao_interna','p.cd_fracao_minima','um.abreviacao','p.nm_produto','p.ds_produto','p.nm_laboratorio')
            ->get();

        return json_encode(['success' => true,'dados' => $retorno]);
    }

    public function busca_vias_aplicacao_medicamento(){
        $vias = DB::table('produto_via_aplicacao as pva')
            ->leftJoin('via_aplicacao_medicamentos as vam','vam.cd_via_aplicacao_medicamentos','pva.cd_via_aplicacao')
            ->where('pva.cd_produto', $_POST['cd_produto'])
            ->select('pva.cd_via_aplicacao','vam.nome as nm_via_aplicacao')
            ->get();
        return json_encode(['success' => true, 'vias' => $vias]);
    }

    public function sugere_posologia_medicacao(){
        $retorno = DB::table('atendimento_prescricao_medicacao as apm')
            ->leftJoin('atendimento_prescricao as ap','ap.id_atendimento_prescricao','apm.id_atendimento_prescricao')
            ->where('apm.cd_medicamento',$_POST['cd_medicamento'])
            ->where('ap.id_user',session()->get('id_user'))
            ->orderByDesc('apm.id_atendimento_prescricao_medicacao')
            ->first();
        if(isset($retorno)) {
            $tipo = DB::table('unidade_medida')->where('situacao','A')->where('cd_unidade_medida',$retorno->tp_dose)->first();
            $retorno->desc_tp_dose = $tipo->nm_unidade_medida." (".$tipo->abreviacao.")";
            //$retorno->desc_tp_embalagem = arrayPadrao('embalagem_medicamento')[$retorno->tp_embalagem];
            $retorno->desc_tp_via = arrayPadrao('via')[$retorno->tp_via];
            $retorno->desc_tp_intervalo = arrayPadrao('periodo')[$retorno->tp_intervalo];
            $retorno->desc_tp_prazo = arrayPadrao('periodo')[$retorno->tp_prazo];
            return json_encode(['success' => true,'dados' => $retorno]);
        }
        else{
            $retorno = DB::table('produto')
                ->where('cd_produto',$_POST['cd_medicamento'])
                ->select('cd_fracao_minima as tp_dose','cd_forma_aplicacao as tp_via')
                ->first();
            $tipo = DB::table('unidade_medida')->where('situacao','A')->where('cd_unidade_medida',$retorno->tp_dose)->first();
            $retorno->desc_tp_dose = $tipo->nm_unidade_medida." (".$tipo->abreviacao.")";
            $retorno->desc_tp_via = arrayPadrao('via')[$retorno->tp_via];
            return json_encode(['success' => false,'dados'=>$retorno]);
        }
    }

    public function add_medicamento_em_uso(Request $request){
        $dados = $request->except('_token');
        $dados['id_user'] = session()->get('id_user');
        DB::table('pessoa_medicamentos_em_uso')->insert($dados);
        return json_encode(['success' => true, 'mensagem' => 'Medicamento cadastrado com sucesso.']);
    }

    public function lista_medicamentos_em_uso(Request $request){
        $medicamentos = DB::table('pessoa_medicamentos_em_uso')->where('cd_pessoa',$request['cd_pessoa'])->get();
        return json_encode(['success' => true, 'retorno' => $medicamentos]);
    }

    public function exclui_medicamento_em_uso(Request $request){
        DB::table('pessoa_medicamentos_em_uso')->where('id_pessoa_medicamentos_em_uso', $request['id_pessoa_medicamentos_em_uso'])->delete();
        return json_encode(['success' => true, 'mensagem' => 'Registro excluído com sucesso']);
    }

}