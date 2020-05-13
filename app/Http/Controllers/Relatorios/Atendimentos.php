<?php

namespace App\Http\Controllers\Relatorios;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Classes\objRelatorio;

class Atendimentos extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function media(Request $request){
        verficaPermissao('recurso.relatorios/atendimentos');
        $data['headerText'] = 'Relatórios';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Relatório de atendimentos', 'href' => route('relatorios/atendimentos/media')];

        if ($_REQUEST) {

            if (!empty($_REQUEST['dt_inicial'])) {
                $dt_inicial = formata_data_mysql($_REQUEST['dt_inicial']);
            }
            if (!empty($_REQUEST['dt_final'])) {
                $dt_final = formata_data_mysql($_REQUEST['dt_final']);
            }
            if(!isset($dt_inicial) || ! isset($dt_final)){
                $request->session()->flash('confirmation', 'Preencha a data inicial e a data final!');
                return redirect('relatorios/atendimentos/media')->withInput();
            } else {
                $data['titulo'] = 'Relatório de Atendimentos - '.$_REQUEST['dt_inicial'].' até '.$_REQUEST['dt_final'];

                $objRelatorio = $this->media_diaria($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $objRelatorio = $this->por_dia_semana($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $objRelatorio = $this->por_turno($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $objRelatorio = $this->por_turno_e_bairro($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $objRelatorio = $this->por_faixa_etaria($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $objRelatorio = $this->por_bairro($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $objRelatorio = $this->por_genero($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $objRelatorio = $this->por_classificacao_de_risco($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $objRelatorio = $this->por_grupo_cid($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $objRelatorio = $this->por_cid($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $objRelatorio = $this->por_origem($dt_inicial, $dt_final);
                $data['relatorio'][] = $objRelatorio;

                $pdf = PDF::loadView('relatorios/relatorio_padrao', $data);
                return $pdf->stream();
                //return view('relatorios/relatorio_padrao', $data);
            }
        }

        return view('relatorios/atendimentos/media', $data);
    }

    function media_diaria($dt_inicial, $dt_final){
        $intervalo = strtotime($dt_final) - strtotime($dt_inicial);
        $dias = floor($intervalo / (60 * 60 * 24)) + 1;
        $objRelatorio = new objRelatorio('Média diária de atendimentos');
        $media = DB::table('prontuario')
            ->whereDate('created_at', '>=', $dt_inicial)
            ->whereDate('created_at', '<=', $dt_final)
            ->where('cd_estabelecimento', session()->get('estabelecimento'))
            ->count('created_at');
        if ($dias > 0) {
            $media = round(($media / $dias), 2);
        } else {
            $media = 0;
        }

        $retorno[] = 'Média';
        $retorno['Nº de atendimentos diários'] = $media;

        $objRelatorio->dados[] = $retorno;
        return $objRelatorio;
    }

    function por_dia_semana($dt_inicial, $dt_final)
    {
        $semana = array('Domingo' => 1, 'Segunda-feira' => 2, 'Terça-feira' => 3, 'Quarta-feira' => 4, 'Quinta-feira' => 5, 'Sexta-feira' => 6, 'Sábado' => 7);
        $objRelatorio = new objRelatorio('Atendimentos conforme o dia da semana');
        foreach ($semana as $s => $k) {
            $total = DB::table('prontuario')
                ->whereDate('created_at', '>=', $dt_inicial)
                ->whereDate('created_at', '<=', $dt_final)
                ->where('cd_estabelecimento', session()->get('estabelecimento'))
                ->where(DB::raw('DAYOFWEEK(created_at)'), $k)
                ->count('created_at');
            $retorno[$s] = $total;
        }

        $objRelatorio->dados[] = $retorno;
        return $objRelatorio;
    }

    function por_turno($dt_inicial, $dt_final){
        $turno = array(
            'Manhã' => array('inicio' => '07:00:00', 'fim' => '13:00:00'),
            'Tarde' => array('inicio' => '13:00:01', 'fim' => '18:59:59'),
            'Noite' => array('inicio' => '19:00:00', 'fim' => '23:59:59'),
            'Madrugada' => array('inicio' => '00:00:00', 'fim' => '06:59:59'));

        $objRelatorio = new objRelatorio('Atendimentos conforme o turno');
        $soma = 0;
        foreach ($turno as $s => $k) {
            $total = DB::table('prontuario')
                ->whereDate('created_at', '>=', $dt_inicial)
                ->whereDate('created_at', '<=', $dt_final)
                ->where('cd_estabelecimento', session()->get('estabelecimento'))
                ->whereTime('created_at', '>=', $k['inicio'])
                ->whereTime('created_at', '<=', $k['fim'])
                ->count('created_at');
            $retorno[$s] = $total;
            $soma = $soma + $total;
        }
        $retorno['Total'] = $soma;
        $retorno['Noite'] = $retorno['Noite'] + $retorno['Madrugada'];

        unset($retorno['Madrugada']);
        $objRelatorio->dados[] = $retorno;
        return $objRelatorio;
    }

    function por_turno_e_bairro($dt_inicial, $dt_final){
        $objRelatorio = new objRelatorio('Quantitativo de curativos realizados por bairro e turno');
        $objRelatorio->limite = 10;
        $objRelatorio->limite_campos = ['Manhã','Tarde','Noite','Total'];
        $objRelatorio->totalizar = ['Manhã','Tarde','Noite','Total'];

        $retorno = (array) DB::select(
            "select p.bairro, 
                sum(if(time(pr.created_at) > '07:00:00' and time(pr.created_at) <= '13:00:00', 1, 0) ) as 'Manhã',
                sum(if(time(pr.created_at) > '13:00:00' and time(pr.created_at) <= '19:00:00', 1, 0) ) as 'Tarde',
                sum(if(time(pr.created_at) > '19:00:00' and time(pr.created_at) <= '23:59:59', 1, 0) ) +
                sum(if(time(pr.created_at) > '00:00:00' and time(pr.created_at) <= '07:00:00', 1, 0) ) as 'Noite',
                count(*) as 'Total'
                from prontuario as pr
                left join beneficiario as b on b.id_beneficiario = pr.id_beneficiario
                left join pessoa as p on p.cd_pessoa = b.cd_pessoa
                left join atendimento_procedimento as ap on ap.cd_prontuario = pr.cd_prontuario
                where pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and
                      (date(pr.created_at) >= '" . $dt_inicial . "' and date(pr.created_at) <= '" . $dt_final . "') and 
                      (ap.cd_procedimento = 401010023 or
                      ap.cd_procedimento = 401010015)
                group by p.bairro
                order by count(*) desc");

        $objRelatorio->dados = $retorno;
        return $objRelatorio;
    }

    function por_faixa_etaria($dt_inicial, $dt_final){

        $objRelatorio = new objRelatorio('Atendimentos conforme a faixa etária');
        $retorno = DB::select(
            "select 
                sum(if( YEAR(FROM_DAYS(DATEDIFF(date(pr.created_at),p.dt_nasc)))>= 60,1,0)) as 'Idosos(60 anos ou mais)',
                sum(if( YEAR(FROM_DAYS(DATEDIFF(date(pr.created_at),p.dt_nasc))) >= 18 and YEAR(FROM_DAYS(DATEDIFF(date(pr.created_at),p.dt_nasc))) <= 59, 1, 0)) as 'Adultos(18-59 anos)',
                sum(if( YEAR(FROM_DAYS(DATEDIFF(date(pr.created_at),p.dt_nasc))) >= 13 and  YEAR(FROM_DAYS(DATEDIFF(date(pr.created_at),p.dt_nasc))) <= 17, 1, 0)) as 'Adolescentes(13-17 anos)',
                sum(if( YEAR(FROM_DAYS(DATEDIFF(date(pr.created_at),p.dt_nasc))) >= 0 and  YEAR(FROM_DAYS(DATEDIFF(date(pr.created_at),p.dt_nasc))) <= 12, 1, 0)) as 'Crianças(0-12 anos)',
                count(*) as 'Total'
                from prontuario as pr
                left join beneficiario as b on b.id_beneficiario = pr.id_beneficiario
                left join pessoa as p on p.cd_pessoa = b.cd_pessoa
                where pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                      (date(pr.created_at) >= '" . $dt_inicial . "' and date(pr.created_at) <= '" . $dt_final . "')");
        $objRelatorio->dados = $retorno;
        return $objRelatorio;

    }

    function por_bairro($dt_inicial, $dt_final){
        $objRelatorio = new objRelatorio('Atendimentos realizados por bairro de origem');
        $objRelatorio->quebrar_pagina = true;
        $objRelatorio->limite = 20;
        $objRelatorio->limite_campos = ['Total'];
        $objRelatorio->totalizar = ['Total'];

        $retorno = DB::select(
            "select p.bairro, count(*) as 'Total'
                from prontuario as pr
                left join beneficiario as b on b.id_beneficiario = pr.id_beneficiario
                left join pessoa as p on p.cd_pessoa = b.cd_pessoa
                where pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and
                      (date(pr.created_at) >= '" . $dt_inicial . "' and date(pr.created_at) <= '" . $dt_final . "') 
                group by p.bairro
                order by count(*) desc");

        $objRelatorio->dados = $retorno;
        return $objRelatorio;
    }

    function por_genero($dt_inicial, $dt_final){
        $objRelatorio = new objRelatorio('Atendimentos conforme o gênero');
        $objRelatorio->totalizar = ['Total'];

        $retorno = DB::select(
            "select if(p.id_sexo = 'F', 'Feminino', 'Masculino') as 'Gênero', count(*) as 'Total'
                from prontuario as pr
                left join beneficiario as b on b.id_beneficiario = pr.id_beneficiario
                left join pessoa as p on p.cd_pessoa = b.cd_pessoa
                where pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and
                      (date(pr.created_at) >= '" . $dt_inicial . "' and date(pr.created_at) <= '" . $dt_final . "') 
                group by p.id_sexo");

        $objRelatorio->dados = $retorno;
        return $objRelatorio;
    }

    function por_classificacao_de_risco($dt_inicial, $dt_final){
        $objRelatorio = new objRelatorio('Classificação de risco');
        $classificacao = DB::select(
            "select 
            'Número de atendimentos' as '',
            sum(if(ac.classificacao = 1,1,0)) as 'Azul', 
            sum(if(ac.classificacao = 2,1,0)) as 'Verde', 
            sum(if(ac.classificacao = 3,1,0)) as 'Amarelo', 
            sum(if(ac.classificacao = 4,1,0)) as 'Laranja', 
            sum(if(ac.classificacao = 5,1,0)) as 'Vermelho'
            from prontuario as pr 
            left join acolhimento as ac on pr.cd_prontuario = ac.cd_prontuario 
            left join atendimento_medico as am on am.cd_prontuario = ac.cd_prontuario 
            where pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  ac.classificacao between 1 and 5 and
                  date(pr.created_at) >= '" . $dt_inicial . "' and 
                  date(pr.created_at) <= '" . $dt_final . "'");
        if(isset($classificacao[0]))
            $retorno[] = $classificacao[0];
        $media = DB::select(
            "select 
            'Tempo médio de espera(minutos)' as '',
            round(avg(if(ac.classificacao = 1, TIMESTAMPDIFF(MINUTE, ac.created_at, am.created_at),null)),0) as 'Azul',
            round(avg(if(ac.classificacao = 2, TIMESTAMPDIFF(MINUTE, ac.created_at, am.created_at),null)),0) as 'Verde',
            round(avg(if(ac.classificacao = 3, TIMESTAMPDIFF(MINUTE, ac.created_at, am.created_at),null)),0) as 'Amarelo',
            round(avg(if(ac.classificacao = 4, TIMESTAMPDIFF(MINUTE, ac.created_at, am.created_at),null)),0) as 'Laranja',
            round(avg(if(ac.classificacao = 5, TIMESTAMPDIFF(MINUTE, ac.created_at, am.created_at),null)),0) as 'Vermelho'
            from prontuario as pr 
            left join acolhimento as ac on pr.cd_prontuario = ac.cd_prontuario 
            left join atendimento_medico as am on am.cd_prontuario = ac.cd_prontuario 
            where pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  ac.classificacao between 1 and 5 and
                  date(pr.created_at) >= '" . $dt_inicial . "' and 
                  date(pr.created_at) <= '" . $dt_final . "'");

        $total = 0;
        foreach($classificacao[0] as $c => $k) {
            if($c === '') {
                $percentual[''] = 'Percentual';
                foreach ($classificacao[0] as $b => $h) {
                    if($b !== '')
                        $total = $total + $h;
                }
            } else {
                if ($total > 0) {
                    $percentual[$c] = round((100 * $k) / $total, 2);
                } else {
                    $percentual[$c] = 0;
                }
            }
        }
        $retorno[] = $percentual;
        $retorno[] = array(''=>'Tempo estimado(minutos)', 'Azul'=>'240','Verde'=>'120','Amarelo'=>'50','Laranja'=>'10','Vermelho'=>'Imediato');
        if(isset($media[0]))
            $retorno[] = $media[0];
        $objRelatorio->dados = $retorno;
        return $objRelatorio;

    }

    function por_grupo_cid($dt_inicial, $dt_final){
        $objRelatorio = new objRelatorio('Diagnósticos conforme grupo de CID');
        $objRelatorio->quebrar_pagina = true;
        $objRelatorio->totalizar = ['Quantidade'];

        $retorno =
            DB::select("select cc.nome as 'Tipo de diagnóstico',count(*) as 'Quantidade' 
            from atendimento_avaliacao_cid as avc
            left join prontuario as pr on pr.cd_prontuario = avc.cd_prontuario 
            left join cid on cid.id_cid = avc.id_cid
            left join cid_codificacao cc on cid.cd_cid >= cc.inicio and cid.cd_cid <= cc.fim  
            where avc.cid_principal = 'S' and
                  pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  (date(pr.created_at) >= '" . $dt_inicial . "' and date(pr.created_at) <= '" . $dt_final . "')          
            group BY `cc`.`nome` 
            ORDER by count(*) desc");
        $total = 0;
        foreach ($retorno as $k => $r)
            $total = $total + $r->Quantidade;
        foreach ($retorno as $k => $r)
            $retorno[$k]->Porcentagem = round((100 * $r->Quantidade)/$total,2);

        $objRelatorio->dados = $retorno;
        return $objRelatorio;
    }

    function por_cid($dt_inicial, $dt_final){
        $objRelatorio = new objRelatorio('Diagnósticos conforme CID');
        $objRelatorio->limite = 20;
        $objRelatorio->limite_campos = ['Quantidade','Porcentagem'];
        $objRelatorio->totalizar = ['Quantidade'];

        $retorno =
            DB::select("select c.cd_cid as 'Cid', c.nm_cid as 'Disgnósticos mais atendidos', count(*) as 'Quantidade'
            from atendimento_avaliacao_cid as avc
            left join prontuario as pr on pr.cd_prontuario = avc.cd_prontuario 
            left join cid as c on c.id_cid = avc.id_cid
            where avc.cid_principal = 'S' and
                  pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  (date(pr.created_at) >= '" . $dt_inicial . "' and date(pr.created_at) <= '" . $dt_final . "')  
            group by c.cd_cid,c.nm_cid
            order by count(*) desc ");
        $total = 0;
        foreach ($retorno as $k => $r)
            $total = $total + $r->Quantidade;
        foreach ($retorno as $k => $r)
            $retorno[$k]->Porcentagem = round((100 * $r->Quantidade)/$total,2);

        $objRelatorio->dados = $retorno;
        return $objRelatorio;
    }

    function por_origem($dt_inicial, $dt_final){
        $objRelatorio = new objRelatorio('Atendimentos realizados por origem');
        $objRelatorio->totalizar = ['Total'];

        $retorno = DB::select(
            "select o.nm_origem, count(*) as 'Total'
                from prontuario as pr
                left join origem as o on o.cd_origem = pr.cd_origem and (o.cd_estabelecimento = 0 or o.cd_estabelecimento = ".session()->get('estabelecimento').")
                where pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and
                      (date(pr.created_at) >= '" . $dt_inicial . "' and date(pr.created_at) <= '" . $dt_final . "') 
                group by o.nm_origem
                order by count(*) desc");

        $objRelatorio->dados = $retorno;
        return $objRelatorio;
    }


}