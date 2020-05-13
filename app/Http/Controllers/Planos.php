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

class Planos extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function index(){
        $data['headerText'] = 'Planos';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Planos', 'href' => '#'];

        return view('planos/index');
    }

    public function cadastrar($id = null, Request $request){
        verficaPermissao('recurso.planos');

        $data['headerText'] = 'Cadastro de Planos';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Planos', 'href' => route('planos/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('planos/cadastro')];

        if (!empty($id) && empty($_POST['cd_plano'])) {
            $plano = DB::table('plano')->where('cd_plano', $id)->get();
            if (isset($plano[0])) {
                $array = (array)$plano[0];
                $data['plano'] = $array;
            }
        } else {
            $data['plano'] = $_POST;
        }

        if ($request->isMethod('post')) {
            $dados = $request->except(['_token', 'cd_plano']);
            $dados = array_map('strtoupper', $dados);
            $rules = [
                'ds_plano' => 'required|max:100|unique:plano,ds_plano,'.$_POST['cd_plano'].',cd_plano',
            ];
            $validator = Validator::make($dados, $rules);

            if ($validator->fails()) {
                return view('planos/cadastro', $data)->withErrors($validator);
            } else {
                if (!empty($id)) {
                    DB::table('plano')->where('cd_plano', $id)->update($dados);
                } else {
                    DB::table('plano')->insert($dados, 'cd_plano');
                    $id = DB::table('plano')->max('cd_plano');
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('planos/cadastro/'.$id);
            }
        }

        return view('planos/cadastro', $data);
    }


    public function listar(){
        verficaPermissao('recurso.planos');

        $data['headerText'] = 'Planos';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Planos', 'href' => route('planos/lista')];

        if ($_REQUEST) {
            $where[] = ['cd_plano','>','0'];
            if (!empty($_REQUEST['ds_plano'])) {
                $where[] = ['ds_plano', 'like', '%'.strtoupper($_REQUEST['ds_plano']).'%'];
            }

            $data['lista'] = DB::table('plano')
                ->where($where)
                ->orderBy('cd_plano')
                ->paginate(30);
        }

        return view('planos/lista', $data);
    }

    public function remover(){
        DB::table('plano')->where('cd_plano', '=', $_POST['cd_plano'])->delete();
        return redirect($_REQUEST['REFERER']);
    }

}