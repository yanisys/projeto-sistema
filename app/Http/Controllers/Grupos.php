<?php

namespace App\Http\Controllers;

use App\Mail\mailTeste;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Monolog\Logger;

class Grupos extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function index(){
        $data['headerText'] = 'Grupos de Operadores';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Grupos','href' => '#'];

        return view('grupos/cadastro',$data);
    }

    public function cadastrar($cdGrupo = null, Request $request){
        verficaPermissao('recurso.grupos');

        $data['headerText'] = 'Grupos de Operadores';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Grupos', 'href' => route('grupos/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('grupos/cadastro')];
        $data['grupos'] = array(
            'pessoas', 'operadores', 'grupos', 'planos', 'estabelecimentos', 'beneficiarios', 'contratos', 'salas', 'profissionais', 'prontuarios','atendimentos', 'painel','relatorios', 'configuracoes','materiais',
        );

        $dados = $request->except(['_token']);
        $dados = array_map('strtoupper', $dados);

        if (!empty($cdGrupo) && empty($_POST['cd_grupo_op'])){
            $grupo = DB::table('grupo_op')->where('grupo_op.cd_grupo_op','=',$cdGrupo)->get();

            if (isset($grupo[0])){
                $array = (array) $grupo[0];
                $data['grupo'] = $array;
            }
        } else {
            $data['grupo'] = $_POST;
        }
        $_POST['cd_grupo_op'] = (empty($_POST['cd_grupo_op']))?$cdGrupo:$_POST['cd_grupo_op'];
        $data['permissoes'] = DB::table('recurso AS R')
            ->leftJoin('permissao AS P', function($join)
            {
                $join->on('R.cd_recurso','=','P.cd_recurso')
                    ->where('P.cd_grupo_op','=',$_POST['cd_grupo_op']);
            })
            ->select('P.cd_grupo_op','R.cd_recurso','R.ds_recurso','R.obj_recurso','P.cd_recurso AS permitido')
            ->get();

        if($request->isMethod('post')) {
            $recursos = array_slice($_POST, 3);
            $rules = [
                'nm_grupo_op' => 'required|max:40|unique:grupo_op,nm_grupo_op,'.$_POST['cd_grupo_op'].',cd_grupo_op',
            ];
            $validator = Validator::make($dados, $rules);
            if ($validator->fails()) {
                return view('grupos/cadastro',$data)->withErrors($validator);
            } else {
                if (!empty($_POST['cd_grupo_op'])) {
                    DB::table('permissao')->where('cd_grupo_op','=',$_POST['cd_grupo_op'])->delete();
                    if($recursos) {
                        foreach ($recursos as $r) {
                            DB::table('permissao')->Insert(['cd_grupo_op' => $_POST['cd_grupo_op'], 'cd_recurso' => $r]);
                        }
                    }
                    DB::table('grupo_op')->where('cd_grupo_op',$cdGrupo)->update(['nm_grupo_op' => $dados['nm_grupo_op']]);
                } else {
                    $dados['cd_grupo_op'] = null;
                    $cdGrupo = DB::table('grupo_op')->insertGetId(['nm_grupo_op' => $dados['nm_grupo_op']]);
                    if($recursos){
                        foreach ($recursos as $r){
                            DB::table('permissao')->Insert(['cd_grupo_op'=>$cdGrupo, 'cd_recurso'=>$r]);
                        }
                    }
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('grupos/cadastro/'.$cdGrupo);
                //return json_encode(['success'=>true, 'mensagem'=>'Registro salvo com sucesso']);
            }
        }

        return view('grupos/cadastro',$data);
    }

    public function listar(){
        verficaPermissao('recurso.grupos');

        $data['headerText'] = 'Grupos de Operadores';
        $data['breadcrumbs'][] = ['titulo' => 'Início','href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Grupos','href' => route('grupos/lista')];

        $where = [];
        if(isset($_REQUEST['nome'])){
            $where[] = ['nm_grupo_op','LIKE','%'.strtoupper($_REQUEST['nome']).'%'];
        }

        $data['lista'] = DB::table('grupo_op')->where($where)->orderBy('nm_grupo_op')->paginate(30)->appends($_REQUEST);

        return view('grupos/lista',$data);
    }

    public function remover(){
        DB::table('permissao')->where('cd_grupo_op','=',$_POST['cd_grupo_op'])->delete();
        DB::table('grupo_op')->where('cd_grupo_op','=',$_POST['cd_grupo_op'])->delete();
        return redirect($_REQUEST['REFERER']);
    }

}