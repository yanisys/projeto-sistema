<?php

namespace App\Http\Controllers\Relatorios;

use Carbon\Carbon;
use const Grpc\CHANNEL_READY;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class Bpa extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function bpa(Request $request)
    {
         verficaPermissao('recurso.relatorios/bpa');
        $data['headerText'] = 'Relatórios';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Boletim de Produção Ambulatorial', 'href' => route('relatorios/bpa')];

        //startQueryLog();
        if ($request->isMethod('post')) {
            $model = new \App\Models\Bpa();
            $procedimentos_do_acolhimento[] = array('nm_procedimento' => 'AFERIÇÃO DE PRESSÃO ARTERIAL','cd_procedimento' => 301100039, 'nome' => 'pressao_arterial');
            $procedimentos_do_acolhimento[] = array('nm_procedimento' => 'AVALIAÇÃO ANTROPOMÉTRICA','cd_procedimento' => 101040024, 'nome' => 'peso');
            $procedimentos_do_acolhimento[] = array('nm_procedimento' => 'GLICEMIA CAPILAR','cd_procedimento' => 214010015, 'nome' => 'glicemia_capilar');
            $numero = cal_days_in_month(CAL_GREGORIAN, $request['mes_competencia'], $request['ano_competencia']);
            $dt_inicial = $request['ano_competencia']."-".str_pad($request['mes_competencia'], 2, '0', STR_PAD_LEFT)."-01";
            $dt_final = $request['ano_competencia']."-".str_pad($request['mes_competencia'], 2, '0', STR_PAD_LEFT)."-".str_pad($numero, 2, '0', STR_PAD_LEFT);
            $request['mes_competencia'] = str_pad($request['mes_competencia'], 2, '0', STR_PAD_LEFT);

            $bpa = get_config(3,session()->get('estabelecimento'));

            if($bpa == 'I') {
                set_time_limit('300');
                ini_set('memory_limit', '128M');

                $procedimentos_acolhimento = $model->busca_procedimentos_acolhimento_bpaI($dt_inicial,$dt_final);
                $procedimentos_atendimento_medico = $model->busca_procedimentos_atendimento_medico_bpaI($dt_inicial,$dt_final);
                $procedimentos = $model->busca_procedimentos_bpaI($dt_inicial,$dt_final);

                //talvez precise comentar para não estourar o número de linhas
               /* foreach($procedimentos_do_acolhimento as $p) {
                    $temp = $model->busca_procedimentos_nao_lancados_acolhimento_bpaI($dt_inicial, $dt_final, $p['nome'], $p['nm_procedimento'], $p['cd_procedimento']);
                    if($temp != null)
                        $procedimentos = array_merge($procedimentos,$temp);
                } */

                $procedimentos = array_merge($procedimentos,$procedimentos_acolhimento);
                $procedimentos = array_merge($procedimentos,$procedimentos_atendimento_medico);
                if(isset($procedimentos[0])) {
                    usort($procedimentos, function ($a, $b) {
                        return $a->cd_procedimento <=> $b->cd_procedimento;
                    });
                }
                $cont = 0;
                $folha = 1;
                $bpa = "";
                $cont_total = 1;
                $soma_controle = 0.00;
                foreach ($procedimentos as $p) {
                    if ($cont == 20) {
                        $folha++;
                        $cont = 1;
                    } else {
                        $cont++;
                    }
                    $cont_total++;
                    $dados = array(
                        "cnes" => $p->cnes,
                        "competencia" => $request['ano_competencia'] . $request['mes_competencia'],
                        //"cns_profissional" => (strlen($p->cns_profissional) > 15 ? substr($p->cns_profissional, 0, 15) : $p->cns_profissional),
                        "cns_profissional" => ($p->cns_profissional !== ''? (validaCNS($p->cns_profissional) ? $p->cns_profissional : '') : ''),
                        "cbo" => $p->cd_ocupacao,
                        "data_execucao" => $p->data_execucao,
                        "folha" => $folha,
                        "linha" => $cont,
                        "cd_procedimento" => $p->cd_procedimento,
                        //"cns_paciente" => (strlen($p->cns_paciente) > 15 ? substr($p->cns_paciente, 0, 15) : $p->cns_paciente),
                        "cns_paciente" => ($p->cns_paciente !== ''? (validaCNS($p->cns_paciente) ? $p->cns_paciente : '') : ''),
                        "sexo" => $p->id_sexo,
                        "cep" => (strlen($p->cep) < 8 ? 99500000 : $p->cep),
                        "cid" => $p->cd_cid,
                        "idade" => $p->idade,
                        "nm_pessoa" => (strlen($p->nm_pessoa) > 30 ? substr($p->nm_pessoa, 0, 30) : trim($p->nm_pessoa)),
                        "dt_nasc" => $p->dt_nasc,
                        "endereco" => (strlen($p->endereco) > 30 ? substr($p->endereco, 0, 30) : $p->endereco),
                        "endereco_compl" => (strlen($p->endereco_compl) > 10 ? substr($p->endereco_compl, 0, 10) : $p->endereco_compl),
                        "endereco_nro" => (strlen($p->endereco_nro) > 5 ? substr($p->endereco_nro, 0, 5) : $p->endereco_nro),
                        "bairro" => (strlen($p->bairro) > 30 ? substr($p->bairro, 0, 30) : $p->bairro),
                        "fone" => soNumero($p->nr_fone1),
                        "email" => (strlen($p->ds_email) > 40 ? substr($p->ds_email, 0, 40) : $p->ds_email)
                    );
                    $data['dados'][] = $dados;
                    $bpa = $bpa . $this->monta_linha_bpa_individualizado($dados);
                    $soma_controle = $soma_controle + $p->cd_procedimento + 1;
                }
                $controle = bcmod($soma_controle, 1111) + 1111;
                $bpa = $this->monta_header_bpa($cont_total, $folha, $request, $controle) . $bpa;
                $cep = DB::table('pessoa as p')->leftJoin('estabelecimentos as e', 'e.cd_pessoa', 'p.cd_pessoa')->where('e.cd_estabelecimento', session()->get('estabelecimento'))->select('p.cep')->first();
                $filename = 'PA' . $this->consulta_cd_ibge($cep->cep) . "." . substr(arrayPadrao('mes')[$request['mes_competencia']], 0, 3);
                //showQueries();
                if ($request['opcao_bpa'] == 'A') {
                    header("Content-Type: text/plain");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header("Content-Length: " . strlen($bpa));
                    echo $bpa;
                } else if ($request['opcao_bpa'] == 'R') {
                    $pdf = PDF::loadView('relatorios/relatorio_bpa', $data);
                    return $pdf->stream();
                } else {
                    $this->monta_relatorio_controle($cont_total, $folha, $request, $controle, $filename);
                }

            }
            else {
                $procedimentos_acolhimento = $model->busca_procedimentos_acolhimento_bpac($dt_inicial,$dt_final);
                $procedimentos_atendimento_medico = $model->busca_procedimentos_atendimento_medico_bpac($dt_inicial,$dt_final);
                $procedimentos = $model->busca_procedimentos_bpac($dt_inicial,$dt_final);

                $proc = array();
                foreach($procedimentos_do_acolhimento as $p) {
                    $temp = $model->busca_procedimentos_nao_lancados_acolhimento_bpac($dt_inicial, $dt_final, $p['nome'], $p['nm_procedimento'], $p['cd_procedimento']);
                    if($temp != null)
                        $proc[] = $temp;
                }

                if(isset($proc[0])) {
                    foreach ($proc as $keyp => $p) {
                        $achei = false;
                        foreach ($procedimentos as $keypr => $pr) {
                            if ($p->cd_ocupacao == $pr->cd_ocupacao && $p->cd_procedimento == $pr->cd_procedimento) {
                                $procedimentos[$keypr]->total = $p->total + $pr->total;
                                $achei = true;
                            }
                        }
                        if (!$achei) {
                            $procedimentos[] = $proc[$keyp];
                        }
                    }
                }

                //$procedimentos = array_merge($procedimentos,$proc);
                $procedimentos = array_merge($procedimentos,$procedimentos_acolhimento);
                $procedimentos = array_merge($procedimentos,$procedimentos_atendimento_medico);
                if(isset($procedimentos[0])) {
                    usort($procedimentos, function ($a, $b) {
                        return $a->cd_procedimento <=> $b->cd_procedimento;
                    });
                }

                $cont = 0;
                $folha = 1;
                $bpa = "";
                $cont_total = 1;
                $soma_controle = 0.00;
                foreach ($procedimentos as $p) {
                    if ($cont == 20) {
                        $folha++;
                        $cont = 1;
                    } else {
                        $cont++;
                    }
                    $cont_total++;
               $dados = array(
                   "cnes" => $p->cnes,
                   "competencia" => $request['ano_competencia'] . $request['mes_competencia'],
                   "cbo" => $p->cd_ocupacao,
                   "nm_ocupacao" => $p->nm_ocupacao,
                   "folha" => $folha,
                   "linha" => $cont,
                   "cd_procedimento" => $p->cd_procedimento,
                   "nm_procedimento" => $p->nm_procedimento,
                   "idade" => 0,
                   "quantidade" => $p->total
               );
                    $data['dados'][] = $dados;
                    $bpa = $bpa . $this->monta_linha_bpa_consolidado($dados);
                    $soma_controle = $soma_controle + $p->cd_procedimento  + $p->total;
                }
                $controle = bcmod($soma_controle, 1111) + 1111;
                $bpa = $this->monta_header_bpa($cont_total, $folha, $request, $controle) . $bpa;
                $cep = DB::table('pessoa as p')->leftJoin('estabelecimentos as e', 'e.cd_pessoa', 'p.cd_pessoa')->where('e.cd_estabelecimento', session()->get('estabelecimento'))->select('p.cep')->first();

                $filename = 'PA' . $this->consulta_cd_ibge($cep->cep) . "." . substr(arrayPadrao('mes')[$request['mes_competencia']], 0, 3);

                if ($request['opcao_bpa'] == 'A') {
                    header("Content-Type: text/plain");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header("Content-Length: " . strlen($bpa));
                    echo $bpa;
                } else if ($request['opcao_bpa'] == 'R') {
                    $pdf = PDF::loadView('relatorios/relatorio_bpa', $data);
                    return $pdf->stream();
                } else {
                    $this->monta_relatorio_controle($cont_total, $folha, $request, $controle, $filename);
                }
            }
        }
        return view('relatorios/bpa', $data);
    }

    function monta_linha_bpa_consolidado($dados){
        $linha = '02';
        $linha = $linha . str_pad($dados['cnes'], 7, '0', STR_PAD_LEFT);
        $linha = $linha . $dados['competencia'];
        $linha = $linha . str_pad($dados['cbo'], 6, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['folha'], 3, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['linha'], 2, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['cd_procedimento'], 10, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['idade'], 3, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['quantidade'], 6, '0', STR_PAD_LEFT);
        $linha = $linha . "BPA" . chr(13).chr(10);
        return $linha;
    }

    function monta_linha_bpa_individualizado($dados){
        $linha = '03';
        $linha = $linha . str_pad($dados['cnes'], 7, '0', STR_PAD_LEFT);
        $linha = $linha . $dados['competencia'];
        $linha = $linha . str_pad($dados['cns_profissional'], 15, ' ', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['cbo'], 6, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad(str_replace("-","",$dados['data_execucao']), 8, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['folha'], 3, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['linha'], 2, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['cd_procedimento'], 10, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['cns_paciente'], 15, ' ', STR_PAD_LEFT);
        $linha = $linha . str_pad($dados['sexo'], 1, ' ', STR_PAD_LEFT);
        $linha = $linha . str_pad(($dados['cep'] === 99500000) ? '430470' : $this->consulta_cd_ibge($dados['cep'])/*''*/ , 6, ' ', STR_PAD_LEFT);//cd ibge
        $linha = $linha . str_pad($dados['cid'], 4, ' ', STR_PAD_RIGHT);
        $linha = $linha . str_pad($dados['idade'], 3, '0', STR_PAD_LEFT);
        $linha = $linha . str_pad('1', 6, '0', STR_PAD_LEFT); //qtde procedimentos
        $linha = $linha . str_pad('02', 2, '0', STR_PAD_LEFT); // Caráter de atendimento - 02 = urgência
        $linha = $linha . str_pad('', 13, ' ', STR_PAD_LEFT); //Nº de autorização
        $linha = $linha . "BPA";
        $linha = $linha . $this->mb_str_pad($dados['nm_pessoa'], 30, ' ', STR_PAD_RIGHT);
        $linha = $linha . str_pad(str_replace("-","",$dados['dt_nasc']), 8, ' ', STR_PAD_RIGHT);
        $linha = $linha . str_pad('99', 2, ' ', STR_PAD_RIGHT); //raça/cor
        $linha = $linha . str_pad('', 4, ' ', STR_PAD_RIGHT);//etnia
        $linha = $linha . str_pad('010', 3, ' ', STR_PAD_RIGHT);//nacionalidade
        $linha = $linha . str_pad('', 3, ' ', STR_PAD_RIGHT);//cod. serviço
        $linha = $linha . str_pad('', 3, ' ', STR_PAD_RIGHT);//cod. classificação
        $linha = $linha . str_pad('', 8, ' ', STR_PAD_RIGHT);//cod. sew. da equipe
        $linha = $linha . str_pad('', 4, ' ', STR_PAD_RIGHT);//cod. de área da equipe
        $linha = $linha . str_pad('', 14, ' ', STR_PAD_RIGHT);//CNPJ empresa OPM
        $linha = $linha . str_pad($dados['cep'], 8, ' ', STR_PAD_LEFT);
        $linha = $linha . str_pad('081', 3, ' ', STR_PAD_RIGHT);//codigo logradouro paciente - 081 = rua
        $linha = $linha . $this->mb_str_pad(($dados['endereco'] != '' ? $dados['endereco'] : 'NÃO INFORMADO'), 30, ' ', STR_PAD_RIGHT);
        $linha = $linha . $this->mb_str_pad($dados['endereco_compl'], 10, ' ', STR_PAD_RIGHT);
        $linha = $linha . $this->mb_str_pad(($dados['endereco_nro'] != '' ? $dados['endereco_nro'] : '0'), 5, ' ', STR_PAD_RIGHT);
        $linha = $linha . $this->mb_str_pad(($dados['bairro'] != '' ? $dados['bairro'] : 'NÃO INFORMADO'), 30, ' ', STR_PAD_RIGHT);
        $linha = $linha . $this->mb_str_pad($dados['fone'], 11, ' ', STR_PAD_RIGHT);
        $linha = $linha . $this->mb_str_pad($dados['email'], 40, ' ', STR_PAD_RIGHT);
        $linha = $linha . str_pad('', 10, ' ', STR_PAD_RIGHT);//identificação nacional de equipes
        $linha = $linha . chr(13).chr(10);
        return $linha;
    }

    function monta_header_bpa($total,$folha,$dados,$controle){
        $header = '01';
        $header = $header . '#BPA#';
        $header = $header . $dados['ano_competencia'] . str_pad($dados['mes_competencia'], 2, '0', STR_PAD_LEFT);
        $header = $header . str_pad($total, 6, '0', STR_PAD_LEFT);
        $header = $header . str_pad($folha, 6, '0', STR_PAD_LEFT);
        $header = $header . $controle;
        $header = $header . str_pad($dados['orgao_origem'], 31, ' ', STR_PAD_RIGHT);
        $header = $header . str_pad($dados['sigla_orgao'], 6, ' ', STR_PAD_RIGHT);
        $header = $header . str_pad($dados['cgc_cpf'], 14, '0', STR_PAD_LEFT);
        $header = $header . str_pad($dados['orgao_destino'], 41, ' ', STR_PAD_RIGHT);
        $header = $header . $dados['indicador'];
        $header = $header . str_pad(get_versao_sistema(), 10, ' ', STR_PAD_RIGHT) . chr(13).chr(10);
        return $header;
    }

    function consulta_cd_ibge($cep){
        $data = DB::table('cd_ibge')->where('cep',$cep)->first();

        if(!isset($data)){
            $temp = file_get_contents("https://viacep.com.br/ws/$cep/json/");
            $data = json_decode($temp);
            if(isset($data->ibge))
                DB::table('cd_ibge')->insert(['cep' => $cep, 'ibge' => $data->ibge, 'localidade' => strtoupper($data->localidade), 'uf' => strtoupper($data->uf)]);
        }

        if(!isset($data->ibge))
            return('430470');

        return substr($data->ibge,0,6);
    }

    function monta_relatorio_controle($total, $folha, $dados, $controle,$filename)
    {
        $relatorio =
            "*******************************************************************Versão: " . get_versao_sistema() . "
MS/SAS/DATASUS/     SISTEMA DE INFORMACÕES AMBULATORIAIS           COMPETÊNCIA:" . chr(13).chr(10).
            date('d/m/Y') . "            RELATÓRIO DE CONTROLE DE REMESSA             " . arrayPadrao('mes')[$dados['mes_competencia']] . " " . $dados['ano_competencia'] . "
********************************************************************************


 ÓRGÃO RESPONSÁVEL PELA INFORMAÇÃO

 NOME   : " . $dados['orgao_origem'] . "

 SIGLA  : " . $dados['sigla_orgao'] . "

 CGC/CPF: " . $dados['cgc_cpf'] . "


 Carimbo e
 Assinatura : ___________________



 SECRETARIA DE SAÚDE DESTINO DOS B.P.A.(s)

 NOME  : " . $dados['orgao_destino'] . "

 ÓRGÃO MUNICIPAL OU ESTADUAL : " . $dados['indicador'] . "


 Setor de                                       Carimbo e
 Recebimento : ____________ Data : ___/___/___  Assinatura : ________________



 ARQUIVO DE BPA(s) GERADO

               NOME : " . $filename . "

 REGISTROS GRAVADOS : " . $total . "

             BPA(s) : " . $folha . "

  CAMPO DE CONTROLE : " . $controle .
            "




(ENCAMINHAR ESTE RELATÓRIO JUNTAMENTE COM O ARQUIVO DE BPA(s) GERADO.)";

        header("Content-Type: text/plain");
        header('Content-Disposition: attachment; filename="RELEXP.PRN"');
        header("Content-Length: " . strlen($relatorio));
        echo $relatorio;
    }

    function mb_str_pad( $input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT, $encoding = "UTF-8") {
        $input = strtoupper(limpar_acentos($input));
        $diff = strlen( $input ) - mb_strlen($input, $encoding);
        return str_pad( $input, $pad_length + $diff, $pad_string, $pad_type );
    }

    /*public function arquivo_bpa(Request $request){
        $numero = cal_days_in_month(CAL_GREGORIAN, $request['mes_competencia'], $request['ano_competencia']);
        $dt_inicial = $request['ano_competencia']."-".$request['mes_competencia']."-01";
        $dt_final = $request['ano_competencia']."-".$request['mes_competencia']."-".str_pad($numero, 2, '0', STR_PAD_LEFT);

        $procedimentos_acolhimento = DB::select("
            select pf.cd_ocupacao, o.nm_ocupacao, am.cd_procedimento, pc.nm_procedimento, count(*) as 'total', e.cnes
            from acolhimento as am
            left join procedimento as pc on pc.cd_procedimento = am.cd_procedimento
            left join prontuario as pr on am.cd_prontuario = pr.cd_prontuario
            left join users as u on u.id = am.id_user
            left join profissional as pf on pf.cd_pessoa = u.cd_pessoa
            left join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
            inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
            where pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  pr.created_at >= date('" . $dt_inicial . "') and 
                  pr.created_at <= date('" . $dt_final . "')
            group by am.cd_procedimento, pf.cd_ocupacao");

        $procedimentos_atendimento_medico = DB::select("
            select pf.cd_ocupacao, o.nm_ocupacao, am.cd_procedimento, pc.nm_procedimento, count(*) as 'total', e.cnes
            from atendimento_medico as am
            left join procedimento as pc on pc.cd_procedimento = am.cd_procedimento
            left join prontuario as pr on am.cd_prontuario = pr.cd_prontuario
            left join users as u on u.id = am.id_user
            left join profissional as pf on pf.cd_pessoa = u.cd_pessoa
            left join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
            inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
            where pr.status = 'C' and 
                  pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  pr.created_at >= date('" . $dt_inicial . "') and 
                  pr.created_at <= date('" . $dt_final . "')
            group by am.cd_procedimento, pf.cd_ocupacao");
        $procedimentos = DB::select("
            select pf.cd_ocupacao, o.nm_ocupacao, am.cd_procedimento, pc.nm_procedimento, count(*) as 'total', e.cnes
            from atendimento_procedimento as am
            inner join procedimento as pc on pc.cd_procedimento = am.cd_procedimento
            inner join prontuario as pr on am.cd_prontuario = pr.cd_prontuario
            inner join users as u on u.id = am.id_user_executante
            inner join profissional as pf on pf.cd_pessoa = u.cd_pessoa
            inner join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
            inner join rl_procedimento_registro as tpr on am.cd_procedimento = tpr.cd_procedimento
            inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
            where tpr.cd_registro = 1 and 
                  am.id_status = 'C' and
                  pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  am.dt_hr_execucao >= date('" . $dt_inicial . "') and 
                  am.dt_hr_execucao <= date('" . $dt_final . "')
            group by am.cd_procedimento, pf.cd_ocupacao");

        $procedimentos = array_merge($procedimentos,$procedimentos_atendimento_medico);
        $procedimentos = array_merge($procedimentos,$procedimentos_acolhimento);
        $cont = 0;
        $folha = 1;
        $bpa = "";
        $cont_total = 1;
        $soma_controle = 0.00;
        foreach ($procedimentos as $p){
            if($cont == 20) {
                $folha++;
                $cont = 1;
            }
            else
                $cont++;
            $cont_total ++;
            $dados = array(
                "cnes" => $p->cnes,
                "competencia" => $request['ano_competencia'] . $request['mes_competencia'],
                "cbo" => $p->cd_ocupacao,
                "nm_ocupacao" => $p->nm_ocupacao,
                "folha" => $folha,
                "linha" => $cont,
                "cd_procedimento" => $p->cd_procedimento,
                "nm_procedimento" => $p->nm_procedimento,
                "idade" => 0,
                "quantidade" => $p->total
            );
            $data['dados'][] = $dados;
            $bpa = $bpa . $this->monta_linha_bpa($dados);
            $soma_controle = $soma_controle + $p->cd_procedimento + $p->total;
        }
        $controle = bcmod($soma_controle, 1111) + 1111;
        $bpa = $this->monta_header_bpa($cont_total, $folha, $request, $controle) . $bpa;
        $filename = 'PA'.$this->consulta_cd_ibge_unidade().".".substr(arrayPadrao('mes')[$request['mes_competencia']],0,3);
        //$relatorio = $this->monta_relatorio_controle($cont_total, $folha, $request, $controle,$filename);
        header("Content-Type: text/plain");
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header("Content-Length: " . strlen($bpa));
        echo $bpa;
    }*/

}