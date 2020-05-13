<?php

namespace App\Http\Controllers\Materiais;

use App\Mail\mailTeste;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class Movimento extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function cadastrar($id = null, Request $request){
        verficaPermissao('recurso.materiais/movimento');

        $data['headerText'] = 'Cadastro de Parâmetros do Movimento';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Parâmetros do Movimento', 'href' => route('materiais/movimento/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('materiais/movimento/cadastro')];

        if (!empty($id) && empty($_POST['cd_movimento'])) {
            $movimento = DB::table('movimento as m')
                ->leftJoin('cfop as c','c.cd_cfop','m.cd_cfop')
                ->where('m.cd_movimento', $id)
                ->get();
            if (isset($movimento[0])) {
                $array = (array)$movimento[0];
                $data['movimento'] = $array;
            }
        } else {
            $data['movimento'] = $_POST;
        }

        if ($request->isMethod('post')) {
            $dados = $request->except(['_token', 'cd_movimento']);
            $dados = array_map('strtoupper', $dados);
            $dados['id_user'] = session('id_user');
            $dados['cd_estabelecimento'] = session('estabelecimento');
            if($dados['cd_cfop'] == 0)
                $dados['cd_cfop'] = null;
            $rules = [
                'nm_movimento' => 'required|max:100,'.$_POST['cd_movimento'].',cd_movimento',
            ];
            $validator = Validator::make($dados, $rules);
            if ($validator->fails()) {
                return view('materiais/movimento/cadastro', $data)->withErrors($validator);
            } else {
                if (!empty($id)) {
                    DB::table('movimento')->where('cd_movimento', $id)->update($dados);
                } else {
                    $dados['created_at'] = Carbon::now();
                    DB::table('movimento')->Insert($dados, 'cd_movimento');
                    $id = DB::table('movimento')->max('cd_movimento');
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('materiais/movimento/cadastro/'.$id);
            }
        }

        return view('materiais/movimento/cadastro', $data);
    }

    public function listar(){
        verficaPermissao('recurso.materiais/movimento');

        $data['headerText'] = 'Parâmetros do Movimento';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Parâmetros do Movimento', 'href' => route('materiais/movimento/lista')];

        if ($_REQUEST) {
            $where = [];
            if (!empty($_REQUEST['nm_movimento'])) {
                $where[] = ['nm_movimento', 'like', '%'.strtoupper($_REQUEST['nm_movimento']).'%'];
            }
            $data['lista'] = DB::table('movimento')
                ->where($where)
                ->orderBy('cd_movimento')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('materiais/movimento/lista', $data);
    }

    public function remover(Request $request){
        try {
            $data['lista'] = DB::table('movimento')->where('cd_movimento', '=', $_POST['cd_movimento'])->where('cd_estabelecimento', '=', session()->get('estabelecimento'))->delete();
            return redirect($_REQUEST['REFERER']);
        } catch(\Illuminate\Database\QueryException $ex){
            return redirect($_REQUEST['REFERER']);
        }

    }

    public function pesquisa_cfop(){
        if(!is_numeric($_POST['pesquisa'])) {
            $retorno = DB::table('cfop')
                ->where('ds_cfop', 'like', '%' . $_POST['pesquisa'] . '%')
                ->get();
        }
        else {
            $retorno = DB::table('cfop')
                ->where('cd_cfop', $_POST['pesquisa'])
                ->get();
        }
        return json_encode(['success' => true,'dados' => $retorno]);
    }

}