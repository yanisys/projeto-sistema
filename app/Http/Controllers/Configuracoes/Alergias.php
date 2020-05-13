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

class Alergias extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function cadastrar($id = null, Request $request){
        verficaPermissao('recurso.configuracoes/alergias');

        $data['headerText'] = 'Cadastro de Alergias';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Alergias', 'href' => route('configuracoes/alergias/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('configuracoes/alergias/cadastro')];

        if (!empty($id) && empty($_POST['cd_alergia'])) {
            $alergia = DB::table('alergia')->where('cd_alergia', $id)->get();
            if (isset($alergia[0])) {
                $array = (array)$alergia[0];
                $data['alergia'] = $array;
            }
        } else {
            $data['alergia'] = $_POST;
        }

        if ($request->isMethod('post')) {
            $dados = $request->except(['_token', 'cd_alergia']);
            $dados = array_map('strtoupper', $dados);
            $rules = [
                'nm_alergia' => 'required|max:100|unique:alergia,nm_alergia,'.$_POST['cd_alergia'].',cd_alergia',
            ];
            $validator = Validator::make($dados, $rules);
            if ($validator->fails()) {
                return view('configuracoes/alergias/cadastro', $data)->withErrors($validator);
            } else {
                if (!empty($id)) {
                    DB::table('alergia')->where('cd_alergia', $id)->update($dados);
                } else {
                    DB::table('alergia')->Insert($dados, 'cd_alergia');
                    $id = DB::table('alergia')->max('cd_alergia');
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('configuracoes/alergias/cadastro/'.$id);
            }
        }

        return view('configuracoes/alergias/cadastro', $data);
    }


    public function listar(){
        verficaPermissao('recurso.configuracoes/alergias');

        $data['headerText'] = 'Alergias';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Alergias', 'href' => route('configuracoes/alergias/lista')];

        if ($_REQUEST) {
            $where = [];
            if (!empty($_REQUEST['nm_alergia'])) {
                $where[] = ['nm_alergia', 'like', '%'.strtoupper($_REQUEST['nm_alergia']).'%'];
            }
            $data['lista'] = DB::table('alergia')
                ->where($where)
                ->orderBy('cd_alergia')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('configuracoes/alergias/lista', $data);
    }

}