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

class Origem extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function cadastrar($id = null, Request $request){
        verficaPermissao('recurso.configuracoes/origem');

        $data['headerText'] = 'Cadastro de Origem do paciente';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Origem', 'href' => route('configuracoes/origem/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('configuracoes/origem/cadastro')];

        if (!empty($id) && empty($_POST['cd_origem'])) {
            $origem = DB::table('origem')->where('cd_origem', $id)->get();
            if (isset($origem[0])) {
                $array = (array)$origem[0];
                $data['origem'] = $array;
            }
        } else {
            $data['origem'] = $_POST;
        }

        if ($request->isMethod('post')) {
            $dados = $request->except(['_token', 'cd_origem']);
            $dados = array_map('strtoupper', $dados);
            $rules = [
                'nm_origem' => 'required|max:100',
            ];
            $validator = Validator::make($dados, $rules);
            if ($validator->fails()) {
                return view('configuracoes/origem/cadastro', $data)->withErrors($validator);
            } else {
                if (!empty($id)) {
                    DB::table('origem')->where('cd_origem', $id)->update($dados);
                } else {
                    $dados['cd_estabelecimento'] = session('estabelecimento');
                    DB::table('origem')->insert($dados, 'cd_origem');
                    $id = DB::table('origem')->max('cd_origem');
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('configuracoes/origem/cadastro/'.$id);
            }
        }

        return view('configuracoes/origem/cadastro', $data);
    }

    public function listar(){
        verficaPermissao('recurso.configuracoes/origem');

        $data['headerText'] = 'Origem do paciente';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Origem', 'href' => route('configuracoes/origem/lista')];

        if ($_REQUEST) {
            $where[] = ['cd_estabelecimento',session('estabelecimento')];
            if (!empty($_REQUEST['nm_origem'])) {
                $where[] = ['nm_origem', 'like', '%'.strtoupper($_REQUEST['nm_origem']).'%'];
            }
            $data['lista'] = DB::table('origem')
                ->where($where)
                ->orderBy('cd_origem')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('configuracoes/origem/lista', $data);
    }


}