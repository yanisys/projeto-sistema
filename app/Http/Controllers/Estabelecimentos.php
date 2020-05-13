<?php

namespace App\Http\Controllers;

use App\Mail\mailTeste;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Estabelecimentos extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');

    }

    public function index(){
        $data['headerText'] = 'Estabelecimentos';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Estabelecimentos', 'href' => '#'];

        return view('estabelecimentos/index');
    }

    public function listar(){
        verficaPermissao('recurso.estabelecimentos');
        $data['headerText'] = 'Estabelecimentos';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Estabelecimentos', 'href' => route('estabelecimentos/lista')];

        if ($_REQUEST) {
            $where = [];
            if (!empty($_REQUEST['nome'])) {
                $where[] = ['p.nm_pessoa', 'like', '%' . strtoupper($_REQUEST['nome']) . '%'];
            }
            $data['lista'] = DB::table('estabelecimentos as e')
                ->leftJoin('pessoa as p','p.cd_pessoa','=','e.cd_pessoa')
                ->where($where)
                ->select('e.cd_estabelecimento','p.nm_pessoa as nm_estabelecimento','e.tp_estabelecimento','e.cnes','e.status')
                ->orderBy('e.cd_estabelecimento')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('estabelecimentos/lista', $data);
    }

    public function cadastrar($idEstabelecimento = null, Request $request){
        verficaPermissao('recurso.estabelecimentos');
        $data['headerText'] = 'Cadastro de Estabelecimentos';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Estabelecimentos', 'href' => route('estabelecimentos/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('estabelecimentos/cadastro')];

        $data['pj'] = true;

        if (!empty($idEstabelecimento) && empty($_POST['id'])) {
            $estabelecimento = DB::table('estabelecimentos as e')
                ->leftJoin('pessoa as p','p.cd_pessoa','=','e.cd_pessoa')
                ->where('cd_estabelecimento', $idEstabelecimento)
                ->select('p.nm_pessoa as nm_estabelecimento','e.cd_estabelecimento','e.cd_pessoa','e.tp_estabelecimento','e.tp_plano','e.cnes','e.status')
                ->get();
            if (isset($estabelecimento[0])) {
                $array = (array)$estabelecimento[0];
                $data['estabelecimento'] = $array;
            }
        } else {
            $data['estabelecimento'] = $_POST;
        }

        if ($request->isMethod('post')) {
            $dados = $request->except(['_token', 'id','nm_estabelecimento']);
            $dados = array_map('strtoupper', $dados);
            $rules = [
                'cd_pessoa' => 'required|unique:estabelecimentos,cd_pessoa,'.$_POST['cd_estabelecimento'].',cd_estabelecimento',
                'cnes' => 'required|unique:estabelecimentos,cnes,'.$_POST['cd_estabelecimento'].',cd_estabelecimento',
            ];
            $validator = Validator::make($dados, $rules);

            if ($validator->fails()) {
                return view('estabelecimentos/cadastro', $data)->withErrors($validator);
            } else {
                $dados['updated_at'] = Carbon::now();
                if (!empty($idEstabelecimento)) {
                    $dados['tp_plano'] = implode(array_slice($_POST,7));
                    DB::table('estabelecimentos')->where('cd_estabelecimento', $idEstabelecimento)->update(["tp_estabelecimento"=>$dados['tp_estabelecimento'],"updated_at"=>$dados['updated_at'], "cd_pessoa"=>$dados['cd_pessoa'],"tp_plano"=>$dados['tp_plano'],"cnes"=>$dados['cnes'],'status'=>$dados['status']]);
                } else {
                    $dados['tp_plano'] = implode(array_slice($_POST,7));
                    $dados['created_at'] = Carbon::now();
                    DB::table('estabelecimentos')->insert(["tp_estabelecimento"=>$dados['tp_estabelecimento'],"updated_at"=>$dados['updated_at'], "cd_pessoa"=>$dados['cd_pessoa'],"tp_plano"=>$dados['tp_plano'],"cnes"=>$dados['cnes'],'status'=>$dados['status']]);
                    $idEstabelecimento = DB::table('estabelecimentos')->max('cd_estabelecimento');
                }
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('estabelecimentos/cadastro/'.$idEstabelecimento);
            }
        }

        return view('estabelecimentos/cadastro', $data);
    }

    public function remover(){
        DB::table('estabelecimentos')->where('cd_estabelecimento', '=', $_POST['id'])->delete();

        return redirect($_REQUEST['REFERER']);
    }

    public function selecionar_estabelecimento(Request $request){
        $data['headerText'] = 'YaniSys | Sistema de Gestão em Saúde';
        $data['breadcrumbs'][] = ['titulo' => 'Olá '. Session('nome'),'href' => route('home')];

        if ($request->isMethod('post')) {
            $estabelecimento = DB::table('estabelecimentos as e')
                ->leftJoin('pessoa as p','p.cd_pessoa','=','e.cd_pessoa')
                ->where('e.cd_estabelecimento','=',$request->get('cd_estabelecimento'))
                ->select('p.nm_pessoa as nm_estabelecimento','p.localidade','e.cnes')
                ->first();
            session()->put('estabelecimento', $request->get('cd_estabelecimento'));
            session()->put('nm_estabelecimento', $estabelecimento->nm_estabelecimento);
            session()->put('cidade_estabelecimento', $estabelecimento->localidade);
            session()->put('cnes_estabelecimento', $estabelecimento->cnes);

            return redirect('/home');
        } else {
            $status = ['A'];
            if(!empty(session('recurso.estabelecimentos-inativos')))
                $status = [0 => 'A', 1 => 'I'];
            $estabelecimentos = DB::table('user_estabelecimento as ue')
                ->leftJoin('estabelecimentos as e','e.cd_estabelecimento','=','ue.cd_estabelecimento')
                ->leftJoin('pessoa as p','p.cd_pessoa','=','e.cd_pessoa')
                ->whereIn('e.status',$status)
                ->select('e.cd_estabelecimento', 'p.nm_pessoa as nm_estabelecimento', 'e.status')
                ->get();
            foreach ($estabelecimentos as $e) {
                $data['estabelecimentos'][$e->cd_estabelecimento] = $e->nm_estabelecimento . ($e->status == 'I' ? " (INATIVO)" : "");
            }
            return view('estabelecimentos/seleciona_estabelecimento', $data);
        }

    }

}