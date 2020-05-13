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

class Profissionais extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function index(){
        $data['headerText'] = 'Profissionais de saúde';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Profissionais','href' => '#'];

        return view('profissionais/index');
    }

    public function cadastrar($id = null, Request $request){
        verficaPermissao('recurso.profissionais');
        $data['headerText'] = 'Cadastro de Profissionais';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Profissionais', 'href' => route('profissionais/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('profissionais/cadastro')];

        if (!empty($id) && empty($_POST['cd_profissional'])) {
            $profissional = DB::table('profissional as pr')
                ->leftJoin('ocupacao as o', 'o.cd_ocupacao', '=', 'pr.cd_ocupacao')
                ->leftJoin('pessoa as p', 'pr.cd_pessoa', '=', 'p.cd_pessoa')
                ->where('cd_profissional', $id)
                ->get();
            if (isset($profissional[0])) {
                $array = (array)$profissional[0];
                $data['profissional'] = $array;
            }
        } else {
            $data['profissional'] = $_POST;
        }
        //LOg::error()

        if ($request->isMethod('post')) {
            $rules = [
                'cd_pessoa' => 'required|unique:profissional,cd_pessoa,'.$_POST['cd_profissional'].',cd_profissional',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return view('profissionais/cadastro', $data)->withErrors($validator);
            } else {
                if (!empty($id)) {
                    $dados = $request->except('_token','nm_pessoa','nome_ocupacao');
                    $dados = array_map('strtoupper', $dados);
                    DB::table('profissional')->where('cd_profissional','=',$id)->update($dados);
                }
                else {
                    $dados = $request->except('_token','nm_pessoa','cd_profissional','nome_ocupacao');
                    $dados = array_map('strtoupper', $dados);
                    $dados['cd_estabelecimento'] = session()->get('estabelecimento');
                    DB::table('profissional')->Insert($dados);
                    $id = DB::table('profissional')->max('cd_profissional');
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('profissionais/cadastro/' . $id);
            }
        }

        return view('profissionais/cadastro', $data);
    }

    public function listar(){
        verficaPermissao('recurso.profissionais');

        $data['headerText'] = 'Profissionais';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Profissionais','href' => route('profissionais/lista')];

        if($_REQUEST){
            $where = [];
            if(isset($_REQUEST['nome'])){
                $where[] = ['nm_pessoa','like','%'.$_REQUEST['nome'].'%'];
            }
            if(isset($_REQUEST['status']) && $_REQUEST['status'] != 'T'){
                $where[] = ['pr.status','=',$_REQUEST['status']];
            }
            $where[] = ['pr.cd_estabelecimento',session()->get('estabelecimento')];
            $where[] = ['c.cd_plano',1];

            $data['lista'] = DB::table('profissional as pr')
                ->leftjoin('pessoa as p', 'pr.cd_pessoa', '=', 'p.cd_pessoa')
                ->leftjoin('ocupacao as o','pr.cd_ocupacao','=','o.cd_ocupacao')
                ->leftjoin('contrato as c', 'pr.cd_pessoa', 'c.cd_pessoa')
                ->leftjoin('beneficiario as b', 'b.cd_contrato', 'c.cd_contrato')
                ->where($where)
                ->select('p.nm_pessoa','pr.cd_profissional', 'o.nm_ocupacao','pr.conselho', 'pr.nr_conselho', 'pr.status', 'b.cd_beneficiario')
                ->orderBy('p.nm_pessoa')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('profissionais/lista',$data);
    }

    public function remover(){
        DB::table('profissional')->where('cd_profissional','=',$_POST['id_profissional'])->delete();
        return redirect($_REQUEST['REFERER']);
    }

    public function buscar_pessoa(Request $request){
        $nome = $request->get('nome');
        $tp_pessoa = $request->get('tp_pessoa');
        $data['lista'] = DB::table('pessoa')->where([['nm_pessoa','like','%'.$nome.'%'],['id_pessoa','=',$tp_pessoa]])->orderBy('nm_pessoa')->get();
        if (!isset($data->erro)) {
            return json_encode(['success' => true, 'dados' => $data['lista']]);
        } else {
            return json_encode(['success' => false]);
        }

    }

    public function pesquisa_ocupacao(){
        $retorno = DB::table('ocupacao')
            ->where('nm_ocupacao','like','%'.$_POST['pesquisa'].'%')
            ->orWhere('cd_ocupacao','like','%'.$_POST['pesquisa'].'%')
            ->select('cd_ocupacao','nm_ocupacao')
            ->get();
        return json_encode(['success' => true,'retorno' => $retorno]);
    }
}