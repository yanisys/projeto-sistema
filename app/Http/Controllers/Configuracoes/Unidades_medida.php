<?php

namespace App\Http\Controllers\Configuracoes;

use App\Http\Controllers\Controller;
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

class Unidades_medida extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function cadastrar($id = null, Request $request){
       // verficaPermissao('recurso.configuracoes/unidades-comerciais');

        $data['headerText'] = 'Cadastro de Unidades de Medida';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Unidades Comerciais', 'href' => route('configuracoes/unidades-medida/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('configuracoes/unidades-medida/cadastro')];

        if (!empty($id) && empty($_POST['cd_unidade_medida'])) {
            $unidade_medida = DB::table('unidade_medida')->where('cd_unidade_medida', $id)->get();
            if (isset($unidade_medida[0])) {
                $array = (array)$unidade_medida[0];
                $data['unidade_medida'] = $array;
            }
        } else {
            $data['unidade_medida'] = $_POST;
        }

        if ($request->isMethod('post')) {
            $dados = $request->except(['_token', 'cd_unidade_medida']);
            $dados = array_map('strtoupper', $dados);
            $rules = [
                'nm_unidade_medida' => 'required|max:30|unique:unidade_medida,nm_unidade_medida,'.$_POST['cd_unidade_medida'].',cd_unidade_medida',
                'abreviacao' => 'required|max:30|unique:unidade_medida,abreviacao,'.$_POST['cd_unidade_medida'].',cd_unidade_medida',
            ];
            $validator = Validator::make($dados, $rules);
            if ($validator->fails()) {
                return view('configuracoes/unidades-medida/cadastro', $data)->withErrors($validator);
            } else {
                if (!empty($id)) {
                    DB::table('unidade_medida')->where('cd_unidade_medida', $id)->update($dados);
                } else {
                    DB::table('unidade_medida')->Insert($dados, 'cd_unidade_medida');
                    $id = DB::table('unidade_medida')->max('cd_unidade_medida');
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('configuracoes/unidades-medida/cadastro/'.$id);
            }
        }

        return view('configuracoes/unidades-medida/cadastro', $data);
    }

    public function listar(){
        //verficaPermissao('recurso.configuracoes/unidades-medida');

        $data['headerText'] = 'Unidades de Medida';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Unidades Comerciais', 'href' => route('configuracoes/unidades-medida/lista')];

        if ($_REQUEST) {
            $where = [];
            if (!empty($_REQUEST['nm_unidade_medida'])) {
                $where[] = ['nm_unidade_medida', 'like', '%'.strtoupper($_REQUEST['nm_unidade_medida']).'%'];
            }
            $data['lista'] = DB::table('unidade_medida')
                ->where($where)
                ->orderBy('cd_unidade_medida')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('configuracoes/unidades-medida/lista', $data);
    }


}