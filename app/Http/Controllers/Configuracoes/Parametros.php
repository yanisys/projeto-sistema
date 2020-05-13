<?php

namespace App\Http\Controllers\Configuracoes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class Parametros extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function cadastrar($id = null, Request $request){
        // verficaPermissao('recurso.configuracoes/unidades-comerciais');

        $data['headerText'] = 'Cadastro de Parâmetros';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Parâmetros', 'href' => route('configuracoes/parametros/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('configuracoes/parametros/cadastro')];

        $tab = DB::table('configuracao')->select('descricao_completa')->where('cd_configuracao',$id)->first();
        $encontrado = ( strpos( $tab->descricao_completa, 'Tabela' ) === 0 );
        if($encontrado){
            $nome = str_replace('Tabela','',$tab->descricao_completa);
            $nome = trim($nome);
            $data['valores'] = $this->monta_array_tabela($nome);
        }
        else {
            $data['valores'] = $this->monta_array($id);
        }
        $data['parametro'] = DB::table('configuracao as c')
            ->leftJoin('configuracao_valores as cv','cv.cd_configuracao','c.cd_configuracao')
            ->where(['cv.cd_configuracao'=>$id,'cv.cd_estabelecimento'=>session('estabelecimento')])
            ->first();

        if ($request->isMethod('post')) {
            DB::table('configuracao_valores')->where(['cd_configuracao'=>$id,'cd_estabelecimento'=>session('estabelecimento')])->update(['valor'=>$_POST['valor']]);
            $request->session()->flash('status', 'Salvo com sucesso!');
            return redirect('configuracoes/parametros/cadastro/'.$id);
        }
        return view('configuracoes/parametros/cadastro', $data);
    }

    public function listar(){
        //verficaPermissao('recurso.configuracoes/unidades-comerciais');

        $data['headerText'] = 'Parâmetros do estabelecimento';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Parâmetros', 'href' => route('configuracoes/parametros/lista')];

        $retorno = DB::table('configuracao')->select('cd_configuracao','descricao_completa')->get();
        foreach ($retorno as $r) {
            $encontrado = ( strpos( $r->descricao_completa, 'Tabela' ) === 0 );
            if($encontrado){
                $nome = str_replace('Tabela','',$r->descricao_completa);
                $nome = trim($nome);
                $data['valores'][$r->cd_configuracao] = $this->monta_array_tabela($nome);
            }
            else {
                $data['valores'][$r->cd_configuracao] = $this->monta_array($r->cd_configuracao);
            }
        }
        $where[] = ['cd_estabelecimento', session('estabelecimento')];
        if (!empty($_REQUEST['descricao'])) {
            $where[] = ['c.descricao', 'like', '%'.strtoupper($_REQUEST['descricao']).'%'];
        }
        $data['lista'] = DB::table('configuracao as c')
            ->leftJoin('configuracao_valores as cv','cv.cd_configuracao','c.cd_configuracao')
            ->where($where)
            ->orderBy('c.cd_configuracao')
            ->paginate(30);

        return view('configuracoes/parametros/lista', $data);
    }

    function monta_array($id){
        $retorno = (array) DB::table('configuracao')->select('descricao_completa')->where('cd_configuracao',$id)->first();
        $retorno = $retorno['descricao_completa'];
        $retorno = explode(";",$retorno);
        $valores = null;
        foreach ($retorno as $r) {
            $array = explode("->", $r);
            $valores[trim($array[0])] = trim($array[1]);
        }
        return $valores;
    }

    function monta_array_tabela($tabela){
        $retorno = DB::table($tabela)->where('cd_estabelecimento',session('estabelecimento'))->where('situacao','A')->get();
        $array[0] = "NÃO CADASTRADO";
        foreach($retorno as $r){
            $indice = 'cd_'.$tabela;
            $valor = 'nm_'.$tabela;
            $array[$r->$indice] = $r->$valor;
        }
        return $array;
    }

}