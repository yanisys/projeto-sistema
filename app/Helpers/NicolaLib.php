<?php

use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Auth;
use App\Mail\mailTeste;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/*-------------------------------------------------------------------------
|
|                     Helper do Projeto Nicola
|
|--------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------
| FUNÇÃO: Ativar/ Desativar QueryLog
| DESCRIÇÃO: Retornar 'true' para ativar e 'false' para desativar
 |--------------------------------------------------------------------------*/
function query_log_ativo(){
    return true;
}
/*-------------------------------------------------------------------------
| FUNÇÃO: js_versionado
| DESCRIÇÃO: Retorna a URI + o nome do arquivo js com a versão
| -PARAMETRO $script_name: nome do arquivo js
|--------------------------------------------------------------------------*/
function js_versionado($script_name){
    if ($script_name == 'app.js') {
        return asset('public/js/app_v1.32.js');
    }
    if ($script_name == 'prontuario.js') {
        return asset('public/js/prontuario_v1.13.js');
    }
    if ($script_name == 'atendimento_medico.js') {
        return asset('public/js/atendimento_medico_v1.10.js');
    }
    if ($script_name == 'fila.js') {
        return asset('public/js/fila_v1.0.js');
    }
    if ($script_name == 'modal_pessoa.js') {
        return asset('public/js/modal_pessoa_v1.11.js');
    }
    if ($script_name == 'modal_procedimentos.js') {
        return asset('public/js/modal_procedimentos_v1.0.js');
    }
    if ($script_name == 'modal_medicamentos.js') {
        return asset('public/js/modal_medicamentos_v1.2.js');
    }
    if ($script_name == 'modal_cid.js') {
        return asset('public/js/modal_cid_v1.1.js');
    }
    if ($script_name == 'modal_alergia.js') {
        return asset('public/js/modal_alergia_v1.2.js');
    }
    if ($script_name == 'modal_evolucao.js') {
        return asset('public/js/modal_evolucao_v1.1.js');
    }
    if ($script_name == 'painel.js') {
        return asset('public/js/painel_v1.7.js');
    }
    if ($script_name == 'configuracoes.js') {
        return asset('public/js/configuracoes_v1.2.js');
    }
    if ($script_name == 'biometria.js') {
        return asset('public/js/biometria_v1.1.js');
    }
    if ($script_name == 'webcam.js') {
        return asset('public/js/webcam_v1.1.js');
    }
    if ($script_name == 'modal_historia_medica_pregressa.js') {
        return asset('public/js/modal_historia_medica_pregressa_v1.1.js');
    }
    if ($script_name == 'modal_medicamentos_em_uso.js') {
        return asset('public/js/modal_medicamentos_em_uso_v1.1.js');
    }
    if ($script_name == 'modal_receituario.js') {
        return asset('public/js/modal_receituario_v1.4.js');
    }
    if ($script_name == 'modal_pesquisa_produto.js') {
        return asset('public/js/modal_pesquisa_produto_v1.1.js');
    }
    if ($script_name == 'modal_pesquisa_cfop.js') {
        return asset('public/js/modal_pesquisa_cfop_v1.1.js');
    }
    if ($script_name == 'modal_pesquisa_user.js') {
        return asset('public/js/modal_pesquisa_user_v1.1.js');
    }
    if ($script_name == 'modal_pesquisa_fornecedor.js') {
        return asset('public/js/modal_pesquisa_fornecedor_v1.1.js');
    }
    if ($script_name == 'modal_cirurgias_previas.js') {
        return asset('public/js/modal_cirurgias_previas_v1.1.js');
    }
    if ($script_name == 'autocompletar.js') {
        return asset('public/js/autocompletar.js');
    }
    if ($script_name == 'produtos.js') {
        return asset('public/js/produtos.js');
    }
    if ($script_name == 'produtos_requisicao.js') {
        return asset('public/js/produtos_requisicao_v1.0.js');
    }
    if ($script_name == 'modal_pesquisa_kit.js') {
        return asset('public/js/modal_pesquisa_kit_v1.1.js');
    }
    if ($script_name == 'modal_prescricao.js') {
        return asset('public/js/modal_prescricao_v1.1.js');
    }
    else {
        return "";
    }
}

/*-------------------------------------------------------------------------
| FUNÇÃO: verficaPermissao
| DESCRIÇÃO: Verifica se o usuario tem permissao de acessar o $recurso
             Se não tiver esse recurso na sessão, o usuario nao tem permissao
             entao redireciona pro home
|--------------------------------------------------------------------------*/
    function verficaPermissao($recurso){
        if(!(session()->get($recurso))) {
            redirect()->to('home')->send();
        }
    }
/*-------------------------------------------------------------------------
| FUNÇÃO: verficaPermissaoBotao
| DESCRIÇÃO: Retorna hidden se o usuario nao tem permissao para acessar o botao
|--------------------------------------------------------------------------*/
    function verficaPermissaoBotao($recurso){
        if(!(session()->get($recurso))) {
            return 'hidden';
        } else {
            return '';
        }
    }
/*-------------------------------------------------------------------------
| FUNÇÃO: sdd (Super Dump Die)
| DESCRIÇÃO: Faz o dump das variaveis passadas por parametro e cerca elas
|            com <pre> depois interrompe o codigo com die()
|--------------------------------------------------------------------------*/
    function sdd(...$args){
        foreach ($args as $x) {
            sd($x);
        }
        die(1);
    }
/*-------------------------------------------------------------------------
| FUNÇÃO: sd (Super Dump)
| DESCRIÇÃO: Faz o dump das variaveis passadas por parametro e cerca elas
|            com <pre>
|--------------------------------------------------------------------------*/
    function sd(...$args){
        echo "<pre>";
        foreach ($args as $x) {
            var_dump($x);
        }
        echo "</pre>";
    }

/*-------------------------------------------------------------------------
|  Funcoes para testes
|--------------------------------------------------------------------------*/

    function startQueryLog(){
        DB::enableQueryLog();
    }

    function showQueries(){
        dd(DB::getQueryLog());
    }

/*-------------------------------------------------------------------------
| FUNÇÃO: calcula_idade
| DESCRIÇÃO: Transforma data de nascimento para o formato de anos/meses de vida
| -PARAMETRO $data:       Data de Nascimento
|--------------------------------------------------------------------------*/
    function calcula_idade($data){
        $data = str_replace('/','-',$data);
        $date = new DateTime( $data );
        $interval = $date->diff( new DateTime( 'now' ) );
        $anos = '';
        $meses = '';
        $dias = '';

        if($interval->format('%Y') == 1)
            $anos = '%Y ano';
        elseif($interval->format('%Y') > 1)
            $anos = '%Y anos';
        if($interval->format('%m') == 1)
            $meses = ' %m mês';
        elseif($interval->format('%m') > 1)
            $meses = ' %m meses';
        if($interval->format('%d') == 1)
            $dias = ' %d dia';
        elseif($interval->format('%d') > 1)
            $dias = ' %d dias';

        if ($anos!='' && $meses!='' && $dias!='')
            return $interval->format($anos.' e '.$meses);
        elseif ($anos!='' && $meses!='' && $dias=='')
            return $interval->format($anos.' e '.$meses);
        elseif ($anos!='' && $meses=='' && $dias!='')
            return $interval->format($anos.' e '.$dias);
        elseif ($anos!='' && $meses=='' && $dias=='')
            return $interval->format($anos);
        elseif ($anos=='' && $meses!='' && $dias!='')
            return $interval->format($meses.' e '.$dias);
        elseif ($anos=='' && $meses!='' && $dias=='')
            return $interval->format($meses);
        elseif ($anos=='' && $meses=='' && $dias!='')
            return $interval->format($dias);

    }

/*-------------------------------------------------------------------------
| FUNÇÃO: formata_data_hora
| DESCRIÇÃO: Transforma timestamp em formato padrão dd/mm/aaaa hh:mm:ss
| -PARAMETRO $data_hora:       Data a ser convertida
|--------------------------------------------------------------------------*/
    function formata_data_hora($data_hora){
        if ($data_hora != null) {
            $data = new DateTime($data_hora);
            return $data->format('d/m/Y H:i');
        } else {
            return "";
        }
    }

/*-------------------------------------------------------------------------
| FUNÇÃO: formata_data_hora
| DESCRIÇÃO: Transforma timestamp em formato padrão dd/mm/aaaa hh:mm:ss
| -PARAMETRO $data_hora:       Data a ser convertida
|--------------------------------------------------------------------------*/
function formata_data_hora_timestamp($data_hora){
    if ($data_hora != null) {
        $data = new DateTime($data_hora);
        return $data->format('d/m/Y H:i:s');
    } else {
        return "";
    }
}
/*-------------------------------------------------------------------------
| FUNÇÃO: formata_hora
| DESCRIÇÃO: Transforma hora em formato padrão hh:mm:ss
| -PARAMETRO $hora:       Hora a ser convertida
|--------------------------------------------------------------------------*/
    function formata_hora($hora){
        if ($hora != null) {
            $data = new DateTime($hora);
            return $data->format('H:i:s');
        } else {
            return "";
        }
    }
/*-------------------------------------------------------------------------
| FUNÇÃO: formata_data
| DESCRIÇÃO: Transforma data em formato padrão dd/mm/aaaa
| -PARAMETRO $data:       Data a ser convertida
|--------------------------------------------------------------------------*/
    function formata_data($data){
    if ($data != null) {
        $data = new DateTime($data);
        return $data->format('d/m/Y');
    } else {
        return "";
    }
}

/*-------------------------------------------------------------------------
| FUNÇÃO: formata_data
| DESCRIÇÃO: Transforma data em formato padrão dd/mm/aaaa
| -PARAMETRO $data:       Data a ser convertida
|--------------------------------------------------------------------------*/
function formata_data_Ymd($data){
    if ($data != null) {
        $data = new DateTime($data);
        return $data->format('Y-m-d');
    } else {
        return "";
    }
}

/*-------------------------------------------------------------------------
| FUNÇÃO: formata_data_mysql
| DESCRIÇÃO: Transforma data do dd/mm/aaaa para o formato mysql aaaa-mm-dd
| -PARAMETRO $data:       Data a ser convertida
|--------------------------------------------------------------------------*/
    function formata_data_mysql($data){
        $dt = explode('/',$data);
        if (count($dt) < 3) {
            $data = null;
        } else {
            $data = $dt[2] . '-' . $dt[1] . '-' . $dt[0];
            $data = strtotime($data);
            $data = date('Y-m-d',$data);
        }
        return $data;
    }
/*-------------------------------------------------------------------------
| FUNÇÃO: escala_de_coma_glasgow
| DESCRIÇÃO: Traduz a escala para um formato de texto
| -PARAMETRO $escala:       nivel da escala de glasgow
|--------------------------------------------------------------------------*/
    function escala_de_coma_glasgow($escala){
        if ($escala >= 3 && $escala <= 8) {
            return ($escala . " - Trauma grave");
        }
        else if ($escala >= 9 && $escala <= 12) {
            return($escala . " - Trauma moderado");
        }
        else if ($escala >= 13 && $escala <= 15) {
            return($escala . " - Trauma leve");
        }
        else
            return "";
    }
/*-------------------------------------------------------------------------
| FUNÇÃO: somente_alfanumericos
| DESCRIÇÃO: remove caracteres invalidos da string, retornando apenas digitos e numeros
| -PARAMETRO $string:  texto para limpar
|--------------------------------------------------------------------------*/
    function somente_alfanumericos($string){
        return preg_replace('/[^\p{L}\p{N}\s]/', '', $string );
    }
/*-------------------------------------------------------------------------
| FUNÇÃO: limpar_acentos
| DESCRIÇÃO: remove acentos da string
| -PARAMETRO $str:  texto para limpar
|--------------------------------------------------------------------------*/
function limpar_acentos($str) {
    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    return $str;
}

/*-------------------------------------------------------------------------
| FUNÇÃO: get_config
| DESCRIÇÃO: retorna valor da configuracao da tabela de configuracoes
| -PARAMETRO    $id: cd_configuracao
             $estab: cd_estabelecimento
|--------------------------------------------------------------------------*/
function get_config($id,$estab){
    $config = DB::table('configuracao_valores')->where([['cd_configuracao',$id],['cd_estabelecimento',$estab]])->select('valor')->first();
    if ($config) {
        return $config->valor;
    } else {
        return null;
    }
}
/*-------------------------------------------------------------------------
| FUNÇÃO: set_config
| DESCRIÇÃO: seta o valor da configuracao na tabela de configuracoes
| -PARAMETRO    $id: cd_configuracao
             $estab: cd_estabelecimento
             $valor: novo valor
|--------------------------------------------------------------------------*/
function set_config($id,$estab,$valor){
    if (get_config($id,$estab)) {
        DB::table('configuracao_valores')->where([['cd_configuracao', $id], ['cd_estabelecimento', $estab]])->update(['valor' => $valor]);
    } else {
        DB::table('configuracao_valores')->insert([
            'cd_configuracao' => $id,
            'cd_estabelecimento' => $estab,
            'valor' => $valor
        ]);
    }
}

/*-------------------------------------------------------------------------
| FUNÇÃO: verificaEstabelecimentoProntuario
| DESCRIÇÃO: Bloqueia o acesso a prontuários de outros estabelecimentos
             Caso tente acessar prontuários de outros estabelecimentos,
             entao redireciona pro home
|--------------------------------------------------------------------------*/
function verificaEstabelecimentoProntuario($cd_prontuario){
    $verifica = DB::table('prontuario')->where('cd_prontuario', $cd_prontuario)->select('cd_estabelecimento')->first();
    if($verifica->cd_estabelecimento != session('estabelecimento')) {
        redirect()->to('home')->send();
    }
}

/*function validaCNS($cns)
{
    $soma = 0;
    if ($cns == null || strlen($cns) != 15 || (substr($cns,0,1) >= 3 && substr($cns,0,1) <= 6)){
        return false;
    }
    if (substr($cns,0,1) == 1 || substr($cns,0,1) == 2)
    {
        $pis = substr($cns,0,11);
        for($x=0;$x<11;$x++){
            $soma = $soma + (15 - $x) * substr($pis,$x,$x+1);
        }
        $resto = $soma % 11;
        $dv = 11 - $resto;
        if ($dv == 11){
            $dv = 0;
        }
        if ($dv == 10){
            $soma += 2;
            $resto = $soma % 11;
            $dv = 11 - $resto;
            $resultado = $pis . "001" . $dv;
        }
        else{
            $resultado = $pis . "000" . $dv;
        }
        return ($cns === $resultado ? true : false);
    }

    if (substr($cns,0,1) == 7 || substr($cns,0,1) == 8 || substr($cns,0,1) == 9){
        for($x=0;$x<15;$x++)
            $soma = $soma + (15 - $x) * substr($cns,$x,$x+1);
        return ($soma % 11) == 0 ? true : false;
    }
}*/

function validaCNS($cns) {
    if (preg_match('/[0-9]/',$cns) && strlen(trim($cns)) === 15) {
        $lErro = false;
        $iInicioNumero = substr(trim($cns), 0, 1);
        if ($iInicioNumero == 1 || $iInicioNumero == 2) {
            $pis = substr($cns, 0, 11);
            $soma = (((substr($pis, 0, 1)) * 15) +
                ((substr($pis, 1, 1)) * 14) +
                ((substr($pis, 2, 1)) * 13) +
                ((substr($pis, 3, 1)) * 12) +
                ((substr($pis, 4, 1)) * 11) +
                ((substr($pis, 5, 1)) * 10) +
                ((substr($pis, 6, 1)) * 9) +
                ((substr($pis, 7, 1)) * 8) +
                ((substr($pis, 8, 1)) * 7) +
                ((substr($pis, 9, 1)) * 6) +
                ((substr($pis, 10, 1)) * 5));

            $resto = fmod($soma, 11);
            $dv = 11 - $resto;
            if ($dv == 11) {
                $dv = 0;
            }

            if ($dv == 10) {

                $soma = ((((substr($pis, 0, 1)) * 15) +
                        ((substr($pis, 1, 1)) * 14) +
                        ((substr($pis, 2, 1)) * 13) +
                        ((substr($pis, 3, 1)) * 12) +
                        ((substr($pis, 4, 1)) * 11) +
                        ((substr($pis, 5, 1)) * 10) +
                        ((substr($pis, 6, 1)) * 9) +
                        ((substr($pis, 7, 1)) * 8) +
                        ((substr($pis, 8, 1)) * 7) +
                        ((substr($pis, 9, 1)) * 6) +
                        ((substr($pis, 10, 1)) * 5)) + 2);
                $resto = fmod($soma, 11);
                $dv = 11 - $resto;
                $resultado = $pis . "001" . $dv;
            } else {
                $resultado = $pis . "000" . $dv;
            }

            if ($cns != $resultado) {
                $lErro = true;
            }

        } else {

            $soma = (((substr($cns, 0, 1)) * 15) +
                ((substr($cns, 1, 1)) * 14) +
                ((substr($cns, 2, 1)) * 13) +
                ((substr($cns, 3, 1)) * 12) +
                ((substr($cns, 4, 1)) * 11) +
                ((substr($cns, 5, 1)) * 10) +
                ((substr($cns, 6, 1)) * 9) +
                ((substr($cns, 7, 1)) * 8) +
                ((substr($cns, 8, 1)) * 7) +
                ((substr($cns, 9, 1)) * 6) +
                ((substr($cns, 10, 1)) * 5) +
                ((substr($cns, 11, 1)) * 4) +
                ((substr($cns, 12, 1)) * 3) +
                ((substr($cns, 13, 1)) * 2) +
                ((substr($cns, 14, 1)) * 1));
            $resto = fmod($soma, 11);
            if ($resto != 0) {
                $lErro = true;
            }
        }
    }
    else{
        $lErro = true;
    }
    return !$lErro;
}

function soNumero($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

function arredonda_data_hora($datahora, $precision, $formatado=false) {
    $datetime = new DateTime( $datahora );
    $second = (int) $datetime->format("s");
    if ($second > 30) {
        $datetime->add(new \DateInterval("PT".(60-$second)."S"));
    } elseif ($second > 0) {
        $datetime->sub(new \DateInterval("PT".$second."S"));
    }
    $minute = (int) $datetime->format("i");
    $minute = $minute % $precision;
    if ($minute > 0) {
        $diff = $precision - $minute;
        $datetime->add(new \DateInterval("PT".$diff."M"));
    }
    if($formatado)
        return $datetime->format('d/m H:i');
    else
        return $datetime->format('Y-m-d H:i:s');
}

function imprime_array($array) {
    Log::error("<pre>".print_r($array,true));
}

?>