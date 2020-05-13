<?php

namespace App\Http\Controllers\Configuracoes;

use App\Http\Controllers\Controller;
use App\Mail\mailTeste;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Monolog\Logger;
use PhpParser\Node\Expr\Array_;
use ZipArchive;

class Procedimentos extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function index(){
        $data['headerText'] = 'Objetiva Sistema de Saúde';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];

        return view('configuracoes/configuracoes',$data);
    }

    public function procedimentos(){
        verficaPermissao('recurso.configuracoes/procedimentos');

        $data['headerText'] = 'Procedimentos';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Configurações','href' => route('configuracoes/configuracoes')];
        $data['breadcrumbs'][] = ['titulo' => 'Procedimentos', 'href' => route('configuracoes/procedimentos/lista')];

        $data['competencia'] = DB::table('procedimento')->select('dt_competencia')->first();
        $grupo = DB::table('procedimento_grupo')->select('cd_grupo','nm_grupo')->get();
        $data['grupo'][0] = 'Nenhum item selecionado';
        foreach($grupo as $g) {
            $data['grupo'][$g->cd_grupo] = str_pad($g->cd_grupo, 2, '0', STR_PAD_LEFT)." - ".$g->nm_grupo;
        }
        return view('configuracoes/procedimentos/lista', $data);
    }

    public function preenche_select_sub_grupo(){
        $sub_grupo =
            DB::table('procedimento_sub_grupo')
                ->where('cd_grupo',$_POST['cd_grupo'])
                ->select('cd_sub_grupo','nm_sub_grupo')
                ->get();

        return json_encode(['success' => true, 'retorno' => $sub_grupo]);
    }

    public function preenche_select_forma_organizacao(){
        $forma_organizacao =
            DB::table('procedimento_forma_organizacao')
                ->where('cd_grupo',$_POST['cd_grupo'])
                ->where('cd_sub_grupo',$_POST['cd_sub_grupo'])
                ->select('cd_forma_organizacao','nm_forma_organizacao')
                ->get();

        return json_encode(['success' => true, 'retorno' => $forma_organizacao]);
    }

    public function pesquisa_procedimentos(){
        if($_POST['cd_grupo'] !== '00'){
            $intervalo_inicio = $_POST['cd_grupo'];
            $intervalo_fim = $_POST['cd_grupo'];
        }
        else{
            $intervalo_inicio = '00';
            $intervalo_fim = '99';
        }
        if($_POST['cd_sub_grupo'] !== 'null' && $_POST['cd_sub_grupo'] !== '00'){
            $intervalo_inicio .= $_POST['cd_sub_grupo'];
            $intervalo_fim .= $_POST['cd_sub_grupo'];
        }
        else{
            $intervalo_inicio .= '00';
            $intervalo_fim .= '99';
        }
        if($_POST['cd_forma_organizacao'] !== 'null' && $_POST['cd_forma_organizacao'] !== '00'){
            $intervalo_inicio .= $_POST['cd_forma_organizacao'];
            $intervalo_fim .= $_POST['cd_forma_organizacao'];
        }
        else{
            $intervalo_inicio .= '00';
            $intervalo_fim .= '99';
        }
        $inicio = str_pad($intervalo_inicio, 10, '0', STR_PAD_RIGHT);
        $fim = str_pad($intervalo_fim, 10, '9', STR_PAD_RIGHT);

        $where = "p.cd_procedimento between $inicio and $fim";
        if ($_POST['pesquisa']!= "") {
            if(!is_numeric($_POST['pesquisa']))
                $where .= " and upper(p.nm_procedimento) like '%".strtoupper($_POST['pesquisa'])."%'";
            else
                $where .= " and p.cd_procedimento = ".$_POST['pesquisa'];
        }
        $forma_organizacao =
            DB::select("select p.nm_procedimento, p.cd_procedimento, rpe.cd_procedimento as permitido
            from procedimento as p 
            left join rl_procedimento_estabelecimento as rpe on p.cd_procedimento = rpe.cd_procedimento and 
                      rpe.cd_estabelecimento = ".session()->get('estabelecimento')."
            where $where 
            order by p.cd_procedimento");

        return json_encode(['success' => true, 'retorno' => $forma_organizacao]);
    }

    public function add_procedimentos(){
        DB::table('rl_procedimento_estabelecimento')
            ->insert(['cd_estabelecimento' => session()->get('estabelecimento'),'cd_procedimento' => $_POST['cd_procedimento']]);
        return json_encode(['success' => true]);
    }

    public function remove_procedimentos(){
        DB::table('rl_procedimento_estabelecimento')
            ->where('cd_estabelecimento', session('estabelecimento'))
            ->where('cd_procedimento', '=', $_POST['cd_procedimento'])
            ->delete();

        return json_encode(['success' => true]);
    }

    public function rotina_atualizacao_tabelas_sus(Request $request){
        Log::error("Hora de início: ".Carbon::now());
    //    $arqLocal = file_get_contents($_FILES['upload_file']['tmp_name']);
    //    LOg::error("Arquivo: ".$arqLocal);
       // file_put_contents(storage_path("app/tabela_unificada_sus/$arqLocal.zip"), $arqLocal);

        //$this->descompactar_arquivo(storage_path('app/tabela_unificada-sus/tabela_sus.zip'));
     /*   $this->atualiza_tabela_sus('tb_cid.txt', [0 => 'cd_cid']);
        $this->atualiza_tabela_sus('tb_procedimento.txt', [0 => 'cd_procedimento']);
        $this->atualiza_tabela_sus('tb_ocupacao.txt', [0 => 'cd_ocupacao']);*/
       // $this->atualiza_tabela_sus('tb_modalidade.txt', [0 => 'cd_modalidade']);
       /* $this->atualiza_tabela_sus('tb_registro.txt', [0 => 'cd_registro']);
        $this->atualiza_tabela_sus('rl_procedimento_modalidade.txt', [0 => 'cd_procedimento', 1 => 'co_modalidade']);
        $this->atualiza_tabela_sus('rl_procedimento_ocupacao.txt', [0 => 'cd_procedimento', 1 => 'co_ocupacao']);
        $this->atualiza_tabela_sus('rl_procedimento_registro.txt', [0 => 'cd_procedimento', 1 => 'co_registro']);*/
        //$this->atualiza_tabela_sus('rl_procedimento_cid.txt', [0 => 'cd_procedimento', 1 => 'co_cid']);

        Log::error("Hora de término: ".Carbon::now());
        return json_encode(['success' => true]);
    }

    function atualiza_tabela_sus($nome_arquivo, $par){
        set_time_limit(100);
        $layout_arquivo = str_replace('.txt','_layout.txt',$nome_arquivo);
        $nome_tabela = str_replace('tb_','',$nome_arquivo);
        $nome_tabela = str_replace('.txt','',$nome_tabela);
        $instrucoes_leitura = $this->ler_arquivo_layout($layout_arquivo);
        $update = $this->ler_arquivo_tabela($instrucoes_leitura,$nome_arquivo);
        $campos = DB::select("describe $nome_tabela");

        if(strpos($nome_tabela, 'rl_procedimento') !== false) {
            DB::table($nome_tabela)->delete();
            Log::error("Tudo deletado.");
        }
        else {
            foreach ($campos as $c) {
                if ($c->Field === 'dt_competencia')
                    DB::table($nome_tabela)->update(['dt_competencia' => '']);
            }
        }
        foreach($update as $key => $u){
            if ( $key % 5000 == 0 ) {
                set_time_limit(100);
            }
            foreach($par as $p) {
                $where[$p] = $u[$p];
            }
            DB::table($nome_tabela)->updateOrInsert($where, $u);
        }
    }

    function ler_arquivo_tabela($instrucoes, $nome_arquivo){
        $arquivo = fopen (storage_path('app/tabela_unificada_sus/').$nome_arquivo, 'r');
        while(!feof($arquivo))
        {
            $linha = fgets($arquivo, 1024);
            if(trim($linha) !== '') {
                foreach ($instrucoes as $i) {
                    $array[$i['nome_campo']] = trim(substr($linha, $i['inicio'], $i['tamanho']));
                    $array[$i['nome_campo']] = utf8_encode($array[$i['nome_campo']]);
                }
                $updates[] = $array;
            }
        }
        fclose($arquivo);
        return $updates;
    }

    function ler_arquivo_layout($arquivo){
        $nome = $arquivo;
        $arquivo = fopen (storage_path('app/tabela_unificada_sus/').$arquivo, 'r');
        $cont = 0;
        while(!feof($arquivo))
        {
            $linha = fgets($arquivo, 1024);
            if(!feof($arquivo) && $cont > 0) {
                $parametros[] = $this->cria_parametros($linha,$nome);
            }
            $cont++;
        }
        fclose($arquivo);
        return $parametros;
    }

    function cria_parametros($linha,$arquivo){
        $nome = str_replace('tb_','',$arquivo);
        $nome = str_replace('_layout.txt','',$nome);
        $nome = strtolower(trim($nome));
        $linha = strtolower($linha);
        $array = explode('_',$linha);
        if (strpos($linha, $nome) !== false || strpos($linha, 'procedimento') !== false && strpos($linha, 'co_') !== false)
            $linha = str_replace('co_','cd_',$linha);
        $linha = str_replace('no_','nm_',$linha);
        $dados = explode(',',$linha);
        if(isset($dados)) {
            $retorno = array(
                'nome_campo' => trim($dados[0]),
                'inicio' => $dados[2]-1,
                'tamanho' => $dados[1]
            );
        }
        return $retorno;
    }

    public function descompactar_arquivo($arq){
        $arquivo = getcwd().$arq;
        $destino = getcwd().'/';
        $zip = new ZipArchive;
        $zip->open($arquivo, ZipArchive::CREATE);
        if($zip->extractTo($destino) == TRUE){
            Log::error('Arquivo descompactado com sucesso.');
        }
        else{
            Log::error(' Arquivo não pode ser descompactado.');
        }
        $zip->close();
    }
}