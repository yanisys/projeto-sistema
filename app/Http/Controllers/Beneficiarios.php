<?php

namespace App\Http\Controllers;

use App\Mail\mailTeste;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr\Array_;

class Beneficiarios extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function index(){
        $data['headerText'] = 'Beneficiários';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Beneficiários','href' => '#'];

        return view('beneficiarios/index');
    }

    public function cadastrar($id = null, Request $request){
        verficaPermissao('recurso.beneficiarios');
        $data['headerText'] = 'Cadastro de Beneficiários';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Beneficiários', 'href' => route('beneficiarios/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('beneficiarios/cadastro')];
        $data['pesquisa_beneficiario'] = true;

        $planos = DB::table('plano')->where('cd_plano','>',1)->get();
        $data['planos'] = array();
        foreach($planos as $p){
            $data['planos'][$p->cd_plano] = $p->ds_plano;
        }
        if (!empty($id) && empty($_POST['id_beneficiario'])) {
            $beneficiario = DB::table('beneficiario as b')
                ->leftJoin('pessoa as p', 'b.cd_pessoa', '=', 'p.cd_pessoa')
                ->leftJoin('contrato as c', 'c.cd_contrato', '=', 'b.cd_contrato')
                ->leftJoin('plano as pl', 'pl.cd_plano', '=', 'c.cd_plano')
                ->where('id_beneficiario', $id)
                ->select('b.id_situacao','p.nm_pessoa','p.cd_pessoa','b.id_beneficiario','b.cd_beneficiario','c.cd_plano','c.cd_contrato','b.parentesco')
                ->get();
            if (isset($beneficiario[0])) {
                $array = (array)$beneficiario[0];
                $data['beneficiario'] = $array;
                $data['dependente'] = DB::table('beneficiario as b')
                    ->leftJoin('pessoa as p','p.cd_pessoa','=','b.cd_pessoa')
                    ->where('cd_contrato','=',$data['beneficiario']['cd_contrato'])
                    ->select('cd_beneficiario','p.nm_pessoa','b.id_situacao','b.parentesco','b.id_beneficiario')
                    ->get();
            }
        } else {
            $data['beneficiario'] = $_POST;
        }

        if ($request->isMethod('post')) {
            $rules = [
                'cd_pessoa' => 'required',
                'cd_beneficiario' => 'required|max:20|unique:beneficiario,cd_beneficiario,'.$_POST['id_beneficiario'].',id_beneficiario',
            ];


            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return view('beneficiarios/cadastro', $data)->withErrors($validator);
            } else {
                if (!empty($id)) {
                    $dados = $request->except('_token','nm_pessoa','cd_plano');
                    DB::table('beneficiario')->where('id_beneficiario','=',$id)->update($dados);
                }
                else {
                    DB::table('contrato')->insert(['cd_plano'=>$request['cd_plano'],'cd_pessoa'=>$request['cd_pessoa']]);
                    $dados = $request->except('_token','nm_pessoa','id_beneficiario','cd_plano');
                    $dados['cd_contrato'] = DB::table('contrato')->max('cd_contrato');
                    DB::table('beneficiario')->insert($dados);
                    $id = DB::table('beneficiario')->max('id_beneficiario');
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('beneficiarios/cadastro/' . $id);
            }
        }

        return view('beneficiarios/cadastro', $data);
    }

    public function listar(){
        verficaPermissao('recurso.beneficiarios');

        $data['headerText'] = 'Beneficiários';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Beneficiários','href' => route('beneficiarios/lista')];

        $planos = DB::table('plano')->where('cd_plano','>',1)->get();
        $data['planos'] = array();
        $data['planos']['T'] = "Todos";
        foreach($planos as $p){
            $data['planos'][$p->cd_plano] = $p->ds_plano;
        }

        if($_REQUEST){
            $where = [];
            if(isset($_REQUEST['nome'])){
                $where[] = ['p.nm_pessoa','like','%'.$_REQUEST['nome'].'%'];
            }
            if(isset($_REQUEST['id_situacao']) && $_REQUEST['id_situacao'] != 'T'){
                $where[] = ['b.id_situacao','=',$_REQUEST['id_situacao']];
            }
            if(isset($_REQUEST['cd_plano']) && $_REQUEST['cd_plano'] != 'T'){
                $where[] = ['pl.cd_plano','=',$_REQUEST['cd_plano']];
            }
            if(isset($_REQUEST['parentesco']) && $_REQUEST['parentesco'] != 'T'){
                $where[] = ['b.parentesco','=',$_REQUEST['parentesco']];
            }
            if(isset($_REQUEST['cd_beneficiario'])){
                $where[] = ['b.cd_beneficiario','like','%'.$_REQUEST['cd_beneficiario'].'%'];
            }
            $where[] = ['pl.cd_plano','>',1];
            $where[] = ['t.parentesco','=',1];
            $data['lista'] = DB::table('beneficiario as b')
                ->leftJoin('pessoa as p', 'b.cd_pessoa', '=', 'p.cd_pessoa')
                ->leftJoin('contrato as c', 'c.cd_contrato', '=', 'b.cd_contrato')
                ->join('plano as pl', 'pl.cd_plano','=','c.cd_plano')
                ->leftJoin('beneficiario as t','b.cd_contrato','=','t.cd_contrato')
                ->where($where)
                ->select('p.nm_pessoa','b.id_beneficiario', 'b.cd_beneficiario','pl.ds_plano','b.id_situacao','b.parentesco')
                ->orderBy('p.nm_pessoa')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('beneficiarios/lista',$data);
    }

    public function remover(){
        DB::table('beneficiario')->where('id_beneficiario','=',$_POST['id_beneficiario'])->delete();
        return redirect($_REQUEST['REFERER']);
    }

    public function salvar_dependente(Request $request){
        if($_POST['parentesco']==1)
        {
            return json_encode(['success' => false, 'mensagem'=>'Já existe um titular cadastrado']);
        }
        $rules = [
            'cd_pessoa' => 'required',
            'cd_beneficiario' => 'required|max:20|unique:beneficiario,cd_beneficiario',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return json_encode(['success' => false, 'mensagem' => $validator->errors()->all()]);
        }
        else{

            DB::table('beneficiario')->insert(['cd_contrato'=>$_POST['cd_contrato'], 'cd_pessoa'=>$_POST['cd_pessoa'],'parentesco'=>$_POST['parentesco'],'cd_beneficiario'=>$_POST['cd_beneficiario']]);
            $idBeneficiario = DB::table('beneficiario')->max('id_beneficiario');
            return json_encode(['success' => true,'id_beneficiario'=>$idBeneficiario, 'mensagem' => "Salvo com sucesso!"]);
        }
    }

}