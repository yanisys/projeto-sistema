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

class Unidades_comerciais extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function cadastrar($id = null, Request $request){
       // verficaPermissao('recurso.configuracoes/unidades-comerciais');

        $data['headerText'] = 'Cadastro de Unidades Comerciais';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Unidades Comerciais', 'href' => route('configuracoes/unidades-comerciais/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('configuracoes/unidades-comerciais/cadastro')];

        if (!empty($id) && empty($_POST['cd_unidade_comercial'])) {
            $unidade_comercial = DB::table('unidades_comerciais')->where('cd_unidade_comercial', $id)->get();
            if (isset($unidade_comercial[0])) {
                $array = (array)$unidade_comercial[0];
                $data['unidade_comercial'] = $array;
            }
        } else {
            $data['unidade_comercial'] = $_POST;
        }

        if ($request->isMethod('post')) {
            $dados = $request->except(['_token', 'cd_unidade_comercial']);
            $dados = array_map('strtoupper', $dados);
            $rules = [
                'descricao' => 'required|max:30|unique:unidades_comerciais,descricao,'.$_POST['cd_unidade_comercial'].',cd_unidade_comercial',
                'unidade' => 'required|max:30|unique:unidades_comerciais,unidade,'.$_POST['cd_unidade_comercial'].',cd_unidade_comercial',
            ];
            $validator = Validator::make($dados, $rules);
            if ($validator->fails()) {
                return view('configuracoes/unidades-comerciais/cadastro', $data)->withErrors($validator);
            } else {
                if (!empty($id)) {
                    DB::table('unidades_comerciais')->where('cd_unidade_comercial', $id)->update($dados);
                } else {
                    DB::table('unidades_comerciais')->Insert($dados, 'cd_unidade_comercial');
                    $id = DB::table('unidades_comerciais')->max('cd_unidade_comercial');
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('configuracoes/unidades-comerciais/cadastro/'.$id);
            }
        }

        return view('configuracoes/unidades-comerciais/cadastro', $data);
    }

    public function listar(){
        //verficaPermissao('recurso.configuracoes/unidades-comerciais');

        $data['headerText'] = 'Unidades comerciais';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Unidades Comerciais', 'href' => route('configuracoes/unidades-comerciais/lista')];

        if ($_REQUEST) {
            $where = [];
            if (!empty($_REQUEST['descricao'])) {
                $where[] = ['descricao', 'like', '%'.strtoupper($_REQUEST['descricao']).'%'];
            }
            $data['lista'] = DB::table('unidades_comerciais')
                ->where($where)
                ->orderBy('cd_unidade_comercial')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('configuracoes/unidades-comerciais/lista', $data);
    }

}