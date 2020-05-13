<?php

namespace App\Http\Controllers\Materiais;

use App\Mail\mailTeste;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Kits extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function cadastrar($id = null, Request $request){
        verficaPermissao('recurso.materiais/kits');

        $data['headerText'] = 'Cadastro de Kits';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Kits', 'href' => route('materiais/kits/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('materiais/kits/cadastro')];

        if (!empty($id) && empty($_POST['cd_kit'])) {
            $kits = DB::table('kits')->where('cd_kit', $id)->get();
            if (isset($kits[0])) {
                $array = (array)$kits[0];
                $data['kits'] = $array;
                $data['kit_produto'] = DB::table('kit_produto as kp')
                    ->leftJoin('produto as p','p.cd_produto','kp.cd_produto')
                    ->leftJoin('unidade_medida as um','um.cd_unidade_medida','p.cd_fracao_minima')
                    ->leftJoin('unidades_comerciais as uc','uc.cd_unidade_comercial','p.cd_unidade_comercial')
                    ->where('kp.cd_kit',$id)
                    ->select('p.nm_produto', 'p.ds_produto', 'um.nm_unidade_medida','uc.descricao as nm_unidade_comercial','p.fracionamento','kp.quantidade','kp.cd_kit_produto')
                    ->get();
            }
        } else {
            $data['kits'] = $_POST;
        }

        if ($request->isMethod('post')) {
            $dados = $request->except(['_token', 'cd_kit']);
            $dados['id_user'] = session('id_user');
            $dados['cd_estabelecimento'] = session('estabelecimento');
            $dados = array_map('strtoupper', $dados);
            $rules = [
                'nm_kit' => 'required|max:100|unique:kits,nm_kit,'.$_POST['cd_kit'].',cd_kit',
            ];
            $validator = Validator::make($dados, $rules);
            if ($validator->fails()) {
                return view('materiais/kits/cadastro', $data)->withErrors($validator);
            } else {
                if (!empty($id)) {
                    DB::table('kits')->where('cd_kit', $id)->update($dados);
                } else {
                    DB::table('kits')->Insert($dados, 'cd_kit');
                    $id = DB::table('kits')->max('cd_kit');
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('materiais/kits/cadastro/'.$id);
            }
        }

        return view('materiais/kits/cadastro', $data);
    }


    public function listar(){
        verficaPermissao('recurso.materiais/kits');

        $data['headerText'] = 'Kits';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Kits', 'href' => route('materiais/kits/lista')];

        if ($_REQUEST) {
            $where = [];
            if (!empty($_REQUEST['nm_kit'])) {
                $where[] = ['nm_kit', 'like', '%'.strtoupper($_REQUEST['nm_kit']).'%'];
            }
            $data['lista'] = DB::table('kits')
                ->where($where)
                ->orderBy('cd_kit')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('materiais/kits/lista', $data);
    }

    public function remover(Request $request){
        try {
            $data['lista'] = DB::table('kits')->where('cd_kit', '=', $request['cd_kit'])->where('cd_estabelecimento', '=', session()->get('estabelecimento'))->delete();
            return redirect($_REQUEST['REFERER']);
        } catch(\Illuminate\Database\QueryException $ex){
            return redirect($_REQUEST['REFERER']);
        }

    }

    public function add_item(Request $request){
        $dados = $request->except('_token');
        DB::table('kit_produto')->insert($dados);
        return json_encode(['success' => true]);
    }

    public function pesquisa_kit(Request $request){
        $kits = DB::table('kits')->where('nm_kit', 'like', '%'.strtoupper($request['pesquisa']).'%')->get();
        return json_encode(['success' => true, 'dados' => $kits]);
    }

}