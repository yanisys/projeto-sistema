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

class Operadores extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function index(){
        $data['headerText'] = 'Operadores';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Operadores','href' => '#'];

        return view('operadores/index');
    }

    public function cadastrar($id = null, Request $request){
        verficaPermissao('recurso.operadores');
        $data['headerText'] = 'Cadastro de Operadores';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Operadores', 'href' => route('operadores/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('operadores/cadastro')];
        //$data['verPessoaSemPesquisa'] = false;

        $grupos = DB::table('grupo_op')->select('cd_grupo_op','nm_grupo_op')->get();
        $data['estabelecimentos'] = DB::table('estabelecimentos as e')
            ->leftJoin('pessoa as p','p.cd_pessoa','=','e.cd_pessoa')
            ->select('e.cd_pessoa','p.nm_pessoa as nm_estabelecimento', 'e.cd_estabelecimento')
            ->get();
        $data['grupos'] = array();
        foreach ($grupos as $g){
            $data['grupos'][$g->cd_grupo_op] = $g->nm_grupo_op;
        }

        if (!empty($id) && empty($_POST['id'])) {
            $user = DB::table('users as u')
                ->leftJoin('pessoa as p', 'u.cd_pessoa', '=', 'p.cd_pessoa')
                ->where('id', $id)->select('u.*','p.nm_pessoa')
                ->get();
            $estabelecimentos_permitidos = DB::table('user_estabelecimento')
                ->where('id_user','=',$id)
                ->select('cd_estabelecimento')
                ->get();
            foreach ($estabelecimentos_permitidos as $ep)
                $data['estabelecimentos_permitidos'][] = $ep->cd_estabelecimento;
            if (isset($user[0])) {
                $array = (array)$user[0];
                $data['user'] = $array;
            }
        } else {
            $data['user'] = $_POST;
        }

       if ($request->isMethod('post')) {
            $rules = [
                'cd_pessoa' => 'required|unique:users,cd_pessoa,'.$_POST['id'].',id',
                'email' => 'required|string|email|max:191|unique:users,email,'.$_POST['id'].',id',
                'password' => (empty($id) ? 'required|string|min:6|confirmed' : ''),
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return view('operadores/cadastro', $data)->withErrors($validator);
            } else {
                $tabUser = new User();
                if (!empty($id)) {
                    $estabelecimentos = array_slice($_POST,7);
                    DB::table('user_estabelecimento')->where('id_user','=',$_POST['id'])->delete();
                    $dados = $request->except('password');
                    $tabUser->fill($dados);
                    $id = $_POST['id'];
                    $tabUser->where('id', $id)->update($tabUser->toArray());
                }
                else {
                    $estabelecimentos = array_slice($_POST,9);
                    $dados = $request->all();
                    $dados['password'] = Hash::make($dados['password']);
                    $tabUser->fill($dados);
                    $tabUser->save();
                    $id = DB::table('users')->max('id');
                }
                $estabelecimentos_permitidos = DB::table('user_estabelecimento')->where('id_user','=',$id)->select('cd_estabelecimento')->get();
                foreach ($estabelecimentos as $e){
                    DB::table('user_estabelecimento')->Insert(['id_user' => $id, 'cd_estabelecimento' => $e]);
                    foreach ($estabelecimentos_permitidos as $ep)
                        $data['estabelecimentos_permitidos'][] = $ep->cd_estabelecimento;
                }
                $estabelecimentos_permitidos = DB::table('user_estabelecimento')->where('id_user','=',$id)->select('cd_estabelecimento')->get();
                foreach ($estabelecimentos_permitidos as $ep)
                    $data['estabelecimentos_permitidos'][] = $ep->cd_estabelecimento;
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('operadores/cadastro/' . $id);
            }
        }

        return view('operadores/cadastro', $data);
    }

    public function meus_dados(Request $request){

        $data['headerText'] = 'Meus Dados';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Meus Dados', 'href' => route('operadores/meus-dados')];

        if ($request->isMethod('post')) {
            $rules = array();
            $dados = array();

            if ($request->trocar_email) {
                $rules['email'] = 'required|string|email|max:255|unique:users,email,' . Auth::user()->id . ',id';
                $dados['email'] = $request->email;
            }
            if ($request->trocar_senha) {
                $rules['nova_senha'] = 'required|string|min:6|confirmed';
                $rules['nova_senha_confirmation'] = 'required|string|min:6';
                $dados['password'] = Hash::make($request->nova_senha);
            };
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return view('operadores/meus-dados', $data)->withErrors($validator);
            } else {
                $tabUser = new User();
                $tabUser->where('id', Auth::user()->id)->update($dados);
                $request->session()->flash('status', 'Salvo com sucesso!');
            }
        }

        $user = DB::table('users as u')
            ->leftJoin('pessoa as p', 'u.cd_pessoa', '=', 'p.cd_pessoa')
            ->leftJoin('grupo_op as g', 'g.cd_grupo_op', '=', 'u.cd_grupo_op')
            ->where('id', Auth::user()->id)
            ->get();
        $data['user'] = (array)$user[0];

        return view('operadores/meus-dados', $data);
    }

    public function listar(){
        verficaPermissao('recurso.operadores');

        $data['headerText'] = 'Operadores';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Operadores','href' => route('operadores/lista')];

        if($_REQUEST){
            $where[] = ['ue.cd_estabelecimento','=',session('estabelecimento')];
            if(isset($_REQUEST['nome'])){
                $where[] = ['nm_pessoa','like','%'.$_REQUEST['nome'].'%'];
            }
            if(isset($_REQUEST['id_situacao']) && $_REQUEST['id_situacao'] != 'T'){
                $where[] = ['users.id_situacao','=',$_REQUEST['id_situacao']];
            }
            $data['lista'] = DB::table('users')
                ->join('pessoa', 'users.cd_pessoa', '=', 'pessoa.cd_pessoa')
                ->join('grupo_op', 'users.cd_grupo_op', '=', 'grupo_op.cd_grupo_op')
                ->join('user_estabelecimento as ue', 'users.id', '=', 'ue.id_user')
                ->where($where)
                ->select('nm_pessoa','email', 'id', 'users.id_situacao','nm_grupo_op')
                ->orderBy('nm_pessoa')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('operadores/lista',$data);
    }

    public function remover(){
        DB::table('user_estabelecimento')->where('id_user','=',$_POST['id_operador'])->delete();
        DB::table('users')->where('id','=',$_POST['id_operador'])->delete();
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
}