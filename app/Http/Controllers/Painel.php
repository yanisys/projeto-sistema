<?php

namespace App\Http\Controllers;

use App\Mail\mailTeste;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Painel extends Controller{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function painel(){
        $data['headerText'] = 'Painel';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Painel', 'href' => route('atendimentos/painel')];

        if ($_REQUEST) {
            $where[] = ['pr.cd_estabelecimento', '=', session()->get('estabelecimento')];
            if (!empty($_REQUEST['cd_painel'])) {
                $where[] = ['pa.cd_painel', '=', $_REQUEST['cd_painel']];
            }
            $data['lista'] = DB::table('painel as pa')
                ->leftJoin('prontuario as pr','pr.cd_prontuario','=','pa.cd_prontuario')
                ->leftJoin('beneficiario as b','b.id_beneficiario','=','pr.id_beneficiario')
                ->leftJoin('pessoa as ps','ps.cd_pessoa','=','b.cd_pessoa')
                ->leftJoin('sala as s','s.cd_sala','=','pa.cd_sala')
                ->where($where)
                ->orderByDesc('pa.horario')
                ->take(5)
                ->select('ps.nm_pessoa','s.nm_sala','pa.horario','pa.cd_painel','pa.cd_chamado')
                ->get();
        }
        return view('atendimentos/painel', $data);
    }

    public function chamar_painel(){
        session()->put('cd_sala', $_POST['cd_sala']);
        session()->put('cd_painel', $_POST['cd_painel']);
        $painel = DB::table('painel')->where('cd_prontuario', $_POST['cd_prontuario'])->first();
        if(isset($painel)) {
            DB::table('painel')
                ->where('cd_prontuario', $_POST['cd_prontuario'])
                ->update(['cd_sala' => $_POST['cd_sala'], 'cd_painel' => $_POST['cd_painel'], 'horario' => Carbon::now(), 'chamado_voz' => 0]);
        } else {
            DB::table('painel')
                ->insert(['cd_sala' => $_POST['cd_sala'], 'cd_prontuario' => $_POST['cd_prontuario'], 'cd_painel' => $_POST['cd_painel']]);
        }
        return json_encode(['success' => true]);
    }

    public function atualizar_painel(){
        $where[] = ['pr.cd_estabelecimento', '=', session()->get('estabelecimento')];
        if (!empty($_POST['cd_painel'])) {
            $where[] = ['pa.cd_painel', '=', $_POST['cd_painel']];
        }
        $data['lista'] = DB::table('painel as pa')
            ->leftJoin('prontuario as pr','pr.cd_prontuario','=','pa.cd_prontuario')
            ->leftJoin('beneficiario as b','b.id_beneficiario','=','pr.id_beneficiario')
            ->leftJoin('pessoa as ps','ps.cd_pessoa','=','b.cd_pessoa')
            ->leftJoin('sala as s','s.cd_sala','=','pa.cd_sala')
            ->where($where)
            ->orderByDesc('pa.horario')
            ->take(5)
            ->select('ps.nm_pessoa','s.nm_sala','pa.horario'/*,'pa.chamado_voz'*/,'pa.cd_chamado')
            ->get();

        foreach ($data['lista'] as $l) {
            $l->horario = formata_hora($l->horario);
            $agora = formata_hora(Carbon::now());
            $inicio = formata_hora(Carbon::parse($agora)->subSeconds(10)->format('H:i:s'));
            if ($l->horario >= $inicio && $l->horario <= $agora ){
                $l->chamado_voz = 0;
            }
            else{
                $l->chamado_voz = 1;
            }
        }
        $config = get_config(1,session()->get('estabelecimento'));
        return json_encode(['success' => true, 'retorno'=>$data['lista'], 'chamado_voz' => $config]);
    }

    public function atualizar_chamado_voz(){
        DB::table('painel')->where('cd_chamado','=',$_POST['cd_chamado'])->update(['chamado_voz'=>true]);
        if (!isset($data->erro)) {
            return json_encode(['success' => true]);
        } else {
            return json_encode(['success' => false]);
        }
    }

}