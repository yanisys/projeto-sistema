<?php

namespace App\Http\Controllers\Materiais;

use App\Mail\mailTeste;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Relatorios extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function relatorio_estoque(Request $request){
        $data['headerText'] = 'Relatório de estoque';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Relatório', 'href' => route('materiais/relatorios/estoque')];

        if ($_REQUEST) {
            $sala = $lote = $dt_validade = "";
            $where = "where (p.nm_produto like '%".strtoupper($_REQUEST['nm_produto'])."%' or p.ds_produto like '%".strtoupper($_REQUEST['nm_produto'])."%')";

            if(isset($_REQUEST['agrupar_estoque'])) {
                $sala = "l.nm_sala,";
                if ($_REQUEST['cd_sala'] != 0)
                    $where .= " and mi.cd_sala = " . $_REQUEST['cd_sala'];
            }
            if(isset($_REQUEST['agrupar_lote'])) {
                $lote = "mi.lote,";
                if (!empty($_REQUEST['lote']))
                    $where .= " and mi.lote like '%".strtoupper($_REQUEST['lote'])."%'";
            }
            if(isset($_REQUEST['agrupar_validade'])) {
                $dt_validade = "mi.dt_validade,";
                if (!empty($_REQUEST['dt_validade']))
                    $where .= " and mi.dt_validade = " . formata_data_mysql($_REQUEST['dt_validade']);
            }



            $data['estoque'] = DB::select(
                "select mi.cd_produto, p.nm_produto, ". $sala .$lote . $dt_validade." p.ds_produto, p.fracionamento, uc.unidade as nm_unidade_comercial, um.abreviacao as nm_unidade_medida, sum(if(m.tp_saldo = 'A',mi.quantidade,if(m.tp_saldo = 'S', - mi.quantidade,0))) as quantidade 
                from movimentacao as m 
                left join movimentacao_itens as mi on mi.cd_movimentacao = m.cd_movimentacao 
                left join produto as p on p.cd_produto = mi.cd_produto 
                left join unidades_comerciais as uc on uc.cd_unidade_comercial = p.cd_unidade_comercial 
                left join unidade_medida as um on um.cd_unidade_medida = p.cd_fracao_minima 
                left join sala as l on l.cd_sala = mi.cd_sala 
                $where
                group by mi.cd_produto, p.nm_produto, ".$sala .$lote . $dt_validade." p.ds_produto, p.fracionamento, uc.unidade, um.abreviacao 
                limit 30 
                offset 0");

            $pdf = PDF::loadView('materiais/relatorios/relatorio-estoque', $data);
            return $pdf->stream();

        }
        $sala = DB::table('sala')->where('cd_estabelecimento',session('estabelecimento'))->where('situacao','A')->where('tipo','E')->orderBy('nm_sala')->get();
        $data['sala'][0] = 'TODOS';
        foreach($sala as $m){
            $data['sala'][$m->cd_sala] = $m->nm_sala;
        }

        return view('materiais/relatorios/estoque', $data);
    }

}