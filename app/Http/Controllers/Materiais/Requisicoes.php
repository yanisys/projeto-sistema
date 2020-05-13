<?php

namespace App\Http\Controllers\Materiais;

use App\Mail\mailTeste;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PDF;


class Requisicoes extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function cadastrar($id = null, Request $request){
        verficaPermissao('recurso.materiais/requisicoes');

        $data['headerText'] = 'Cadastro de Requisições';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Requisições', 'href' => route('materiais/requisicoes/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('materiais/requisicoes/cadastro')];

        if (!empty($id) && empty($_POST['cd_requisicao'])) {
            $requisicoes = DB::table('requisicoes')->where('cd_requisicao', $id)->get();
            if (isset($requisicoes[0])) {
                $array = (array)$requisicoes[0];
                $data['requisicoes'] = $array;
                $data['requisicao_produto'] = DB::table('requisicao_produto as rp')
                    ->leftJoin('produto as p','p.cd_produto','rp.cd_produto')
                    ->leftJoin('unidades_comerciais as uc','uc.cd_unidade_comercial','p.cd_unidade_comercial')
                    ->leftJoin('unidade_medida as um','um.cd_unidade_medida','p.cd_fracao_minima')
                    ->where('rp.cd_requisicao',$id)
                    ->select('p.nm_produto', 'p.ds_produto', 'um.abreviacao as nm_unidade_medida','rp.quantidade','rp.cd_requisicao_produto','uc.unidade as nm_unidade_comercial','p.fracionamento')
                    ->get();
            }
        } else {
            $data['requisicoes'] = $_POST;
        }
        $data['solicitante'] = DB::table('users as u')->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')->where('u.id',\session('id_user'))->select('p.nm_pessoa')->first();
        $sala = DB::table('sala')->where('cd_estabelecimento',session('estabelecimento'))->where('situacao','A')->orderBy('nm_sala')->get();
        foreach($sala as $m){
            $data['sala'][$m->cd_sala] = $m->nm_sala;
        }
        $estoque = DB::table('sala')->where('cd_estabelecimento',session('estabelecimento'))->where('situacao','A')->where('tipo','E')->orderBy('nm_sala')->get();
        foreach($estoque as $e){
            $data['estoque'][$e->cd_sala] = $e->nm_sala;
        }
        if ($request->isMethod('post')) {
            $dados = $request->except(['_token', 'cd_requisicao']);
            if($dados['cd_sala_origem'] == $dados['cd_sala_destino']){
                $request->session()->flash('status', 'A localização de destino deve ser diferente da origem!');
                return redirect('materiais/requisicoes/cadastro/' . $id);
            }
            else {
                $dados['id_user_solicitante'] = session('id_user');
                $dados['cd_estabelecimento'] = session('estabelecimento');

                $dados = array_map('strtoupper', $dados);

                if (!empty($id)) {
                    $dados['cd_requisicao'] = $id;
                    $this->lanca_requisicao($dados, 'update');
                } else {
                    $id = $this->lanca_requisicao($dados, 'insert');
                }

                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('materiais/requisicoes/cadastro/' . $id);
            }
        }

        return view('materiais/requisicoes/cadastro', $data);
    }

    public function listar(){
        verficaPermissao('recurso.materiais/requisicoes');

        $data['headerText'] = 'Requisições';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Requisições', 'href' => route('materiais/requisicoes/lista')];

        if ($_REQUEST) {
            $where = [];
            if (!empty($_REQUEST['dt_ini'])) {
                $where[] = ['r.created_at', '>=', formata_data_mysql($_REQUEST['dt_ini'])." 00:00:00"];
            }
            if (!empty($_REQUEST['dt_fim'])) {
                $where[] = ['r.created_at', '<=', formata_data_mysql($_REQUEST['dt_fim'])." 23:59:59"];
            }
            if (!empty($_REQUEST['responsavel'])) {
                $where[] = ['p.nm_pessoa', 'like', '%'.strtoupper($_REQUEST['responsavel']).'%'];
            }
            if ($_REQUEST['situacao'] != 'T') {
                $where[] = ['r.situacao', $_REQUEST['situacao']];
            }
            if ($_REQUEST['cd_sala_origem'] != 0) {
                $where[] = ['r.cd_sala_origem', $_REQUEST['cd_sala_origem']];
            }
            if ($_REQUEST['cd_sala_destino'] != 0) {
                session(['cd_sala' => $_REQUEST['cd_sala_destino']]);
                $where[] = ['r.cd_sala_destino', $_REQUEST['cd_sala_destino']];
            }


            $data['lista'] = DB::table('requisicoes as r')
                ->leftJoin('sala as lor', 'lor.cd_sala','r.cd_sala_origem')
                ->leftJoin('sala as lod', 'lod.cd_sala','r.cd_sala_destino')
                ->leftJoin('users as u','u.id','r.id_user_solicitante')
                ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
                ->where($where)
                ->orderByDesc('r.cd_requisicao')
                ->select('r.created_at','p.nm_pessoa','lod.nm_sala as nm_sala_destino','lor.nm_sala as nm_sala_origem','r.cd_requisicao','r.situacao')
                ->paginate(30)
                ->appends($_REQUEST);



        }
        $sala = DB::table('sala')->where('cd_estabelecimento',session('estabelecimento'))->where('situacao','A')->where('tipo','E')->orderBy('nm_sala')->get();
        $data['sala'][0] = 'Todos';
        foreach($sala as $m){
            $data['sala'][$m->cd_sala] = $m->nm_sala;
        }
        return view('materiais/requisicoes/lista', $data);
    }

    public function requisicao_pdf($id=null){
        $data['requisicao'] = DB::table('requisicoes as r')
            ->leftJoin('atendimento_prescricao as ap', 'ap.id_atendimento_prescricao', 'r.id_atendimento_prescricao')
            ->leftJoin('prontuario as pr','pr.cd_prontuario','ap.cd_prontuario')
            ->leftJoin('beneficiario as b','b.id_beneficiario', 'pr.id_beneficiario')
            ->leftJoin('pessoa as pac','pac.cd_pessoa','b.cd_pessoa')
            ->leftJoin('sala as lor', 'lor.cd_sala','r.cd_sala_origem')
            ->leftJoin('sala as lod', 'lod.cd_sala','r.cd_sala_destino')
            ->leftJoin('users as u','u.id','r.id_user_solicitante')
            ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->where('r.cd_requisicao',$id)
            ->select('r.created_at','p.nm_pessoa','pac.nm_pessoa as nm_paciente','pac.nm_mae','lod.nm_sala as nm_sala_destino','lor.nm_sala as nm_sala_origem','r.cd_requisicao','r.situacao')
            ->first();

        $data['requisicao_produto'] = DB::table('requisicao_produto as rp')
            ->leftJoin('produto as p','p.cd_produto','rp.cd_produto')
            ->leftJoin('unidades_comerciais as uc','uc.cd_unidade_comercial','p.cd_unidade_comercial')
            ->leftJoin('unidade_medida as um','um.cd_unidade_medida','p.cd_fracao_minima')
            ->where('rp.cd_requisicao',$id)
            ->select('rp.cd_produto', 'p.nm_produto', 'p.ds_produto', 'um.abreviacao as nm_unidade_medida','rp.lote','rp.dt_validade','rp.quantidade_atendimento','rp.quantidade','rp.cd_requisicao_produto','uc.unidade as nm_unidade_comercial','p.fracionamento')
            ->get();

        $pdf = PDF::loadView('materiais/requisicoes/requisicao-pdf', $data);

        return $pdf->stream();
        //return view('materiais/requisicoes/requisicao-pdf', $data);
    }

    public function remover(Request $request){
        try {
            $data['lista'] = DB::table('requisicoes')->where('cd_requisicao', '=', $request['cd_requisicao'])->where('cd_estabelecimento', '=', session()->get('estabelecimento'))->delete();
            return redirect($_REQUEST['REFERER']);
        } catch(\Illuminate\Database\QueryException $ex){
            return redirect($_REQUEST['REFERER']);
        }
    }

    public function add_item(Request $request){
        $dados[0] = $request->except('_token');
        $this->lanca_itens_requisicao($dados,'insert');
        return json_encode(['success' => true]);
    }

    public function atendimento($id=null, Request $request){
        verficaPermissao('recurso.materiais/requisicoes');

        $data['headerText'] = 'Atendimento de Requisições';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Requisições', 'href' => route('materiais/requisicoes/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Atendimento', 'href' => route('materiais/requisicoes/atendimento')];

        $data['requisicao'] = DB::table('requisicoes as r')
            ->leftJoin('atendimento_prescricao as ap', 'ap.id_atendimento_prescricao', 'r.id_atendimento_prescricao')
            ->leftJoin('prontuario as pr','pr.cd_prontuario','ap.cd_prontuario')
            ->leftJoin('beneficiario as b','b.id_beneficiario', 'pr.id_beneficiario')
            ->leftJoin('pessoa as pac','pac.cd_pessoa','b.cd_pessoa')
            ->leftJoin('sala as lor', 'lor.cd_sala','r.cd_sala_origem')
            ->leftJoin('sala as lod', 'lod.cd_sala','r.cd_sala_destino')
            ->leftJoin('users as u','u.id','r.id_user_solicitante')
            ->leftJoin('pessoa as p','p.cd_pessoa','u.cd_pessoa')
            ->where('r.cd_requisicao',$id)
            ->select('r.created_at', 'r.situacao', 'r.id_atendimento_prescricao','p.nm_pessoa','pac.nm_pessoa as nm_paciente','pac.nm_mae','lod.cd_sala as cd_sala_destino','lod.nm_sala as nm_sala_destino','lor.nm_sala as nm_sala_origem', 'lor.cd_sala as cd_sala_origem','r.cd_requisicao','r.situacao')
            ->first();

        if (isset($data['requisicao'])) {
            $data['requisicao_produto'] = DB::table('requisicao_produto as rp')
                ->leftJoin('produto as p','p.cd_produto','rp.cd_produto')
                ->leftJoin('unidades_comerciais as uc','uc.cd_unidade_comercial','p.cd_unidade_comercial')
                ->leftJoin('unidade_medida as um','um.cd_unidade_medida','p.cd_fracao_minima')
                ->where('rp.cd_requisicao',$id)
                ->select('p.nm_produto', 'rp.lote', 'rp.dt_validade','rp.quantidade_atendimento', 'rp.cd_produto', 'p.controle_lote_validade', 'um.abreviacao as nm_unidade_medida','rp.quantidade','rp.cd_requisicao_produto','uc.unidade as nm_unidade_comercial','p.fracionamento')
                ->get();
            foreach($data['requisicao_produto'] as $rp) {
                if($rp->controle_lote_validade == 0){
                    $lotes[0] = '---';
                    $estoque = DB::table('movimentacao as m')
                        ->leftJoin('movimentacao_itens as mi', 'mi.cd_movimentacao', 'm.cd_movimentacao')
                        ->leftJoin('produto as p', 'p.cd_produto', 'mi.cd_produto')
                        ->where('mi.cd_produto', $rp->cd_produto)
                        ->where('mi.cd_sala', $data['requisicao']->cd_sala_destino)
                        ->select('mi.cd_produto','mi.cd_fornecedor','mi.dt_fabricacao', DB::raw("sum(if(m.tp_saldo = 'A',mi.quantidade,if(m.tp_saldo = 'S', - mi.quantidade,0))) as quantidade"),'mi.cd_fornecedor')
                        ->groupBy('mi.cd_produto','mi.cd_fornecedor','mi.dt_fabricacao')
                        ->get();
                }
                else {
                    $lotes[0] = 'Selecione um lote';
                    $estoque = [];
                    $est = DB::table('movimentacao as m')
                        ->leftJoin('movimentacao_itens as mi', 'mi.cd_movimentacao', 'm.cd_movimentacao')
                        ->leftJoin('produto as p', 'p.cd_produto', 'mi.cd_produto')
                        ->where('mi.cd_produto', $rp->cd_produto)
                        ->where('mi.cd_sala', $data['requisicao']->cd_sala_destino)
                        ->select('mi.lote', 'mi.dt_validade', 'mi.cd_produto','mi.cd_fornecedor','mi.dt_fabricacao', DB::raw("sum(if(m.tp_saldo = 'A',mi.quantidade,if(m.tp_saldo = 'S', - mi.quantidade,0))) as quantidade"))
                        ->groupBy('mi.lote', 'mi.dt_validade', 'mi.cd_produto','mi.cd_fornecedor','mi.dt_fabricacao')
                        ->get();
                    if(isset($est[0])) {
                        foreach ($est as $e) {
                            if($e->quantidade >= $rp->quantidade) {
                                $lotes[$e->lote] = $e->lote;
                                $estoque[$e->lote]['dt_validade'] = formata_data($e->dt_validade);
                                $estoque[$e->lote]['dt_fabricacao'] = formata_data($e->dt_fabricacao);
                                $estoque[$e->lote]['cd_fornecedor'] = $e->cd_fornecedor;
                                $estoque[$e->lote]['quantidade'] = $e->quantidade;
                            }
                        }
                    }
                }

                $rp->estoque = $estoque;
                $rp->lotes = $lotes;
                $rp->dt_validade = formata_data($rp->dt_validade);
                unset($lotes);
                unset($est);
            }
        }

        if ($request->isMethod('post')) {
            $dados = $request->except('_token','cod_barras','cd_requisicao_produto');
            $validator = Validator::make($dados, []);
            foreach ($data['requisicao_produto'] as $key => $req){
                if($req->controle_lote_validade == 1) {
                    if (!isset($dados['lote'][$key]) || $dados['lote'][$key] == 0){
                        $validator->errors()->add("lote[$key]", "Preencha corretamente o lote do(a) ".($req->nm_produto)."!");
                    }
                    if (!isset($dados['dt_validade'][$key]) || $dados['dt_validade'][$key] == ''){
                        $validator->errors()->add("dt_validade[$key]", "Preencha corretamente a validade do(a) ".($req->nm_produto)."!");
                    }
                }
                if (!isset($dados['quantidade_atendimento'][$key]) || $dados['quantidade_atendimento'][$key] == '' || $dados['quantidade_atendimento'][$key] != $req->quantidade){
                    $validator->errors()->add("quantidade_atendimento[$key]", "Preencha corretamente a quantidade de ".($req->nm_produto)."!");
                }
                $data['requisicao_produto'][$key]->lote = $_POST['lote'][$key];
                $data['requisicao_produto'][$key]->dt_validade = formata_data_mysql($_POST['dt_validade'][$key]);
                $data['requisicao_produto'][$key]->dt_fabricacao = formata_data_mysql($_POST['dt_fabricacao'][$key]);
                $data['requisicao_produto'][$key]->cd_fornecedor = $_POST['cd_fornecedor'][$key];
                $data['requisicao_produto'][$key]->quantidade_atendimento = $_POST['quantidade_atendimento'][$key];
            }

            if (count($validator->errors()) > 0) {
                return view('materiais/requisicoes/atendimento', $data)->withErrors($validator);
            }
            else{
                DB::table('requisicoes')->where('cd_requisicao',$data['requisicao']->cd_requisicao)->update(['situacao'=>'C','id_user_executante'=>session('id_user')]);
                $sala = DB::table('sala')->where('cd_sala',$data['requisicao']->cd_sala_origem)->select('tipo')->first();
                $insert['created_at'] = Carbon::now();
                $insert['id_user'] = session('id_user');
                $insert['cd_estabelecimento'] = session('estabelecimento');
                $insert['cd_sala'] = $data['requisicao']->cd_sala_destino;
                $insert['situacao'] = 'C';
                $insert['cd_requisicao'] = $data['requisicao']->cd_requisicao;


                if ($sala->tipo == 'E') {
                    $dadosSaida = (array) DB::table('movimento')->where('cd_movimento',get_config(6,session('estabelecimento')))->select('cd_movimento', 'tp_movimento', 'tp_saldo', 'tp_conta', 'tp_nf', 'cd_cfop')->first();
                    $dadosSaida = array_merge($dadosSaida, $insert);
                    $idSaida = DB::table('movimentacao')->insertGetId($dadosSaida);

                    $insert['cd_sala'] = $data['requisicao']->cd_sala_origem;
                    $dadosEntrada = (array) DB::table('movimento')->where('cd_movimento',get_config(7,session('estabelecimento')))->select('cd_movimento', 'tp_movimento', 'tp_saldo', 'tp_conta', 'tp_nf', 'cd_cfop')->first();
                    $dadosEntrada = array_merge($dadosEntrada, $insert);
                    $idEntrada = DB::table('movimentacao')->insertGetId($dadosEntrada);
                }
                else{
                    $dadosSaida = (array) DB::table('movimento')->where('cd_movimento',get_config(8,session('estabelecimento')))->select('cd_movimento', 'tp_movimento', 'tp_saldo', 'tp_conta', 'tp_nf', 'cd_cfop')->first();
                    $dadosSaida = array_merge($dadosSaida, $insert);
                    $idSaida = DB::table('movimentacao')->insertGetId($dadosSaida);
                }
                foreach ($data['requisicao_produto'] as $key => $req) {
                    $insert['created_at'] = Carbon::now();
                    $insert['id_user'] = session('id_user');
                    $insert['cd_sala'] = $data['requisicao']->cd_sala_destino;
                    $insert['cd_movimentacao'] = $idSaida;
                    $insert = array_merge($insert, (array)$req);
                    $tabMovItens = new \App\Models\Movimentacao_itens();
                    $tabMovItens->fill($insert);
                    $tabMovItens->save();
                    if ($sala->tipo == 'E') {
                        $insert['cd_sala'] = $data['requisicao']->cd_sala_origem;
                        $insert['cd_movimentacao'] = $idEntrada;
                        $tabMovItens = new \App\Models\Movimentacao_itens();
                        $tabMovItens->fill($insert);
                        $tabMovItens->save();
                    }
                   // imprime_array($req);
                    DB::table('requisicao_produto')->where('cd_requisicao_produto',$req->cd_requisicao_produto)->update(['lote' => $req->lote, 'dt_validade' => $req->dt_validade, 'quantidade_atendimento' => $req->quantidade_atendimento]);
                }

                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('materiais/requisicoes/atendimento/' . $id);
            }

        }


        return view('materiais/requisicoes/atendimento', $data);
    }

  /*  public function pesquisa_requisicao(Request $request){
        $requisicoes = DB::table('requisicoes')->where('nm_requisicao', 'like', '%'.strtoupper($request['pesquisa']).'%')->get();
        return json_encode(['success' => true, 'dados' => $requisicoes]);
    }
*/
    function lanca_requisicao($dados, $tipo){
        if($tipo == 'insert'){
            $dados['created_at'] = Carbon::now();
            DB::table('requisicoes')->insert($dados);
            return DB::table('requisicoes')->max('cd_requisicao');
        }
        else{
            DB::table('requisicoes')->where('cd_requisicao',$dados['cd_requisicao'])->update($dados);
        }
    }

    function lanca_itens_requisicao($dados, $tipo){
        imprime_array($dados);
        $produtos = [];
        foreach($dados as $key=>$d) {
            $produtos[] = $d;
            $busca = $this->busca_kit_vinculado($d['cd_produto'], 0);
            if (isset($busca[0])) {
                foreach ($busca as $b) {
                    $b->cd_requisicao = $d['cd_requisicao'];
                    $b->quantidade = $b->quantidade * $d['quantidade'];
                    $produtos[] = (array)$b;
                }
            }
        }
        if ($tipo == 'insert') {
            DB::table('requisicao_produto')->insert($produtos);
        } else {
            DB::table('requisicao_produto')->where('cd_requisicao_produto', $d['cd_requisicao_produto'])->update($dados);
        }
    }

    function busca_kit_vinculado($cd_produto, $nivel_recursao){
        $nivel_recursao++;
        if($nivel_recursao > 100)
            return 'erro';
        $produtos = DB::table('produto as p')
            ->join('kits as k','p.cd_kit_vinculado','k.cd_kit')
            ->leftJoin('kit_produto as kp','kp.cd_kit','k.cd_kit')
            ->where('p.cd_produto',$cd_produto)
            ->select('kp.cd_produto','kp.quantidade')
            ->get();
        if(!isset($produtos)) {
            return null;
        }
        else {
            $retorno = $produtos;
            foreach ($produtos as $p) {
                $aux = $this->busca_kit_vinculado($p->cd_produto, $nivel_recursao);
                if(isset($aux[0]))
                    $retorno = array_merge($retorno,$aux);
            }
            return $retorno;
        }
    }
}