<?php

namespace App\Http\Controllers\Materiais;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class Movimentacao extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function cadastrar($id = null, Request $request){
        verficaPermissao('recurso.materiais/movimentacao');

        $data['headerText'] = 'Cadastro de Movimentação';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Movimentação', 'href' => route('materiais/movimentacao/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('materiais/movimentacao/cadastro')];

        $tipo = 'E';
        $tp_movimento = ['C','V'];
        if (!empty($id) && empty($_POST['cd_movimentacao'])) {
            $movimentacao = DB::table('movimentacao as m')
                ->leftJoin('movimentacao_nfe as mnf', 'mnf.cd_movimentacao','m.cd_movimentacao')
                ->leftJoin('movimentacao_nfe_duplicata as mnd','mnd.cd_movimentacao','mnf.cd_movimentacao')
                ->leftJoin('fornecedores as f','f.cd_fornecedor','mnf.cd_emitente_destinatario')
                ->leftJoin('pessoa as p','f.cd_pessoa','p.cd_pessoa')
                ->where('m.cd_movimentacao', $id)
                ->select('m.*','mnf.serie','mnf.indPag','mnf.dhEmi','mnf.finNFe','p.nm_pessoa','mnf.cd_emitente_destinatario','mnf.vDesc','mnf.vOutro','mnf.vFrete','mnf.vSeg','mnf.vNF','mnd.nDup','mnd.dVenc','mnd.vDup')
                ->get();
            if (isset($movimentacao[0])) {
                if($movimentacao[0]->tp_movimento == 'E' || $movimentacao[0]->tp_movimento == 'S')
                    $tp_movimento = ['E','S'];
                $array = (array)$movimentacao[0];
                $data['movimentacao'] = $array;
                $data['movimentacao']['dhEmi'] = formata_data_hora($data['movimentacao']['dhEmi']);
            }
        } else {
            $data['movimentacao'] = $_POST;
        }

        $data['movimentacao_itens'] = DB::table('movimentacao_itens as mi')
            ->leftJoin('movimentacao_itens_nfe as min','min.cd_movimentacao_itens','mi.cd_movimentacao_itens')
            ->leftJoin('produto as p','p.cd_produto', 'mi.cd_produto')
            ->leftJoin('sala as s','s.cd_sala', 'mi.cd_sala')
            ->where('cd_movimentacao', $id)
            ->select('min.uCom', 'min.qCom', 'mi.cd_movimentacao_itens', 'p.nm_produto','p.ds_produto', 's.nm_sala', 'mi.lote','mi.dt_validade','mi.dt_fabricacao','mi.quantidade')
            ->get();

        $unidades_comerciais = DB::table('unidades_comerciais')->get();
        foreach ($unidades_comerciais as $u)
            $data['unidades_comerciais'][$u->unidade] = $u->descricao;

        $unidades_medida = DB::table('unidade_medida')->where('situacao','A')->get();
        foreach ($unidades_medida as $u)
            $data['unidades_medida'][$u->cd_unidade_medida] = $u->nm_unidade_medida;


        $movimento = DB::table('movimento')->where('situacao','A')->whereIn('tp_movimento',$tp_movimento)->orderBy('nm_movimento')->get();
        $data['movimento'][0] = '';
        foreach($movimento as $m){
            $data['movimento'][$m->cd_movimento] = $m->nm_movimento." (".arrayPadrao('tipo_movimento')[$m->tp_movimento].")";
        }
        $sala = DB::table('sala')->where('cd_estabelecimento',session('estabelecimento'))->where('situacao','A')->where('tipo',$tipo)->orderBy('nm_sala')->get();
        $data['sala'][0] = '';
        foreach($sala as $m){
            $data['sala'][$m->cd_sala] = $m->nm_sala;
        }

        if ($request->isMethod('post')) {
            $dados = $request->except(['_token', 'cd_movimentacao']);
            $dados = array_map('strtoupper', $dados);
            $dados['nNF'] = $dados['nr_documento'];
            $update = (!empty($_POST['cd_movimentacao']) ? true : false);

            $rules = [
                'cd_movimento' => 'required|integer|min:0|not_in:0',
                'cd_beneficiario' => 'nullable|' . ($update ? '' : 'unique:beneficiario') . '| cd_beneficiario',
                'nr_documento' => 'required_if:tp_movimento,in,V,C|integer|'.($update ? '' : 'unique:movimentacao'),
                'cd_emitente_destinatario' => 'required_if:tp_movimento,in,V,C',
                'serie' => 'required_if:tp_movimento,in,V,C|numeric',
                'dhEmi'=>'required_if:tp_movimento,in,V,C',
                'indPag'=>'required_if:tp_movimento,in,V,C',
                'finNFe'=>'required_if:tp_movimento,in,V,C',
                'vDesc'=>'required_if:tp_movimento,in,V,C|numeric',
                'vOutro'=>'required_if:tp_movimento,in,V,C|numeric',
                'vFrete'=>'required_if:tp_movimento,in,V,C|numeric',
                'vSeg'=>'required_if:tp_movimento,in,V,C|numeric',
                'vNF'=>'required_if:tp_movimento,in,V,C|numeric',
                'cd_sala' => 'required|numeric|min:0|not_in:0'
            ];
            $nomes = [
                'cd_movimento' => 'Movimento',
                'nr_documento' => 'Número do documento',
                'cd_emitente_destinatario' => 'Fornecedor/ Destinatário',
                'serie' => 'Série',
                'dhEmi' => 'Data/Hora de emissão',
                'indPag'=>'Ind. Pagamento',
                'finNFe'=>'Finalidade Nfe',
                'vDesc'=>'Desconto',
                'vOutro'=>'Despesas',
                'vFrete'=>'Frete',
                'vSeg'=>'Seguro',
                'vNF'=>'Total',
                'cd_sala' => 'Localização'
            ];

            $validator = Validator::make($dados, $rules)->setAttributeNames($nomes);

            if ($validator->fails()) {
                return view('materiais/movimentacao/cadastro', $data)->withErrors($validator);
            } else {
                $cd_movimentacao = $this->lanca_movimentacao($id, $dados);

                $tabMovNfe = new \App\Models\Movimentacao_nfe();
                $dados['cd_movimentacao'] = $cd_movimentacao;
                $dhEmi = \DateTime::createFromFormat('d/m/Y H:i:s', $dados['dhEmi'] . ":00");
                $dados['dhEmi'] = $dhEmi->format('Y-m-d H:i:s');

                $tabMovNfe->fill($dados);
                if ($cd_movimentacao == $id)
                    $tabMovNfe->where('cd_movimentacao', $cd_movimentacao)->update($tabMovNfe->toArray());
                else
                    $tabMovNfe->save();
                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('materiais/movimentacao/cadastro/' . $cd_movimentacao);
            }
        }

        return view('materiais/movimentacao/cadastro', $data);
    }

    public function listar(){
        verficaPermissao('recurso.materiais/movimentacao');

        $data['headerText'] = 'Movimentação de produtos';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Movimentação', 'href' => route('materiais/movimentacao/lista')];

        if ($_REQUEST) {
            $where = [];
            if (!empty($_REQUEST['dt_ini'])) {
                $where[] = ['ma.created_at', '>=', $_REQUEST['dt_ini']." 00:00:00"];
            }
            if (!empty($_REQUEST['dt_fim'])) {
                $where[] = ['ma.created_at', '<=', $_REQUEST['dt_fim']." 23:59:59"];
            }
            if (!empty($_REQUEST['nm_movimento'])) {
                $where[] = ['mo.nm_movimento', 'like', '%'.strtoupper($_REQUEST['nm_movimento']).'%'];
            }
            if (!empty($_REQUEST['cd_movimentacao'])) {
                $where[] = ['ma.cd_movimentacao', $_REQUEST['cd_movimentacao']];
            }

            $data['lista'] = DB::table('movimentacao as ma')
                ->leftJoin('movimento as mo','mo.cd_movimento','ma.cd_movimento')
                ->where($where)
                ->select('ma.cd_movimentacao','ma.created_at','mo.nm_movimento')
                ->orderBy('ma.cd_movimentacao','ma.created_at','mo.nm_movimento')
                ->paginate(30)
                ->appends($_REQUEST);
        }

        return view('materiais/movimentacao/lista', $data);
    }

    public function preenche_parametros_movimento(Request $request){
        $movimento = DB::table('movimento as m')
            ->leftJoin('cfop as c', 'c.cd_cfop', 'm.cd_cfop')
            ->where('m.cd_estabelecimento', '=', session()->get('estabelecimento'))
            ->where('m.cd_movimento',$request['cd_movimento'])
            ->select('m.tp_movimento','m.tp_saldo','m.tp_conta','m.tp_nf','m.cd_cfop', 'c.ds_cfop')
            ->first();
        $movimento->ds_saldo = arrayPadrao('tipo_saldo')[$movimento->tp_saldo];
        $movimento->ds_movimento = arrayPadrao('tipo_movimento')[$movimento->tp_movimento];
        $movimento->ds_conta = arrayPadrao('tipo_conta')[$movimento->tp_conta];
        $movimento->ds_nf = arrayPadrao('tipo_nota')[$movimento->tp_nf];
        return json_encode(['success' => true, 'retorno' => $movimento]);
    }

    public function pesquisa_produto(Request $request){
        $produtos = DB::table('produto as p')
            ->leftJoin('unidade_medida as um','p.cd_fracao_minima','um.cd_unidade_medida')
            ->leftJoin('unidades_comerciais as uc','p.cd_unidade_comercial','uc.cd_unidade_comercial')
            ->where('nm_produto', 'like', '%'.strtoupper($request['pesquisa']).'%')
            ->select('cd_produto','nm_produto','ds_produto','p.cd_unidade_comercial','ncm','p.qtde_embalagem','cd_fracao_minima','um.abreviacao as nm_unidade_medida','p.fracionamento','uc.unidade as nm_unidade_comercial')
            ->limit(300)
            ->get();
        return json_encode(['success' => true, 'dados' => $produtos]);
    }

    public function adicionar_item(Request $request){
        $dados = $request->except('_token','tp_saldo','auto','cd_movimentacao_itens');
        $dados['created_at'] = Carbon::now();
        $dados['id_user'] = session('id_user');

        if($request['tp_saldo'] != 'S') {
            if($dados['dt_validade'] == 'NULL')
                unset($dados['dt_validade']);
            if($dados['dt_fabricacao'] == 'NULL')
                unset($dados['dt_fabricacao']);
            $tabMovItens = new \App\Models\Movimentacao_itens();
            $tabMovItens->fill($dados);

            if($request['cd_movimentacao_itens'] == 0) {
                $tabMovItens->save();
                $dados['cd_movimentacao_itens'] = DB::table('movimentacao_itens')->max('cd_movimentacao_itens');
            }
            else {
                $tabMovItens->where('cd_movimentacao_itens', $request['cd_movimentacao_itens'])->update($tabMovItens->toArray());
                $dados['cd_movimentacao_itens'] = $request['cd_movimentacao_itens'];
            }

            if($request['auto'] == 'FALSE') {
                $cd_produto_vinculado = DB::table('produto_vinculo as pv')->where([['pv.cd_fornecedor',$request['cd_fornecedor']],['pv.cd_produto_fornecedor',$request['cd_produto_fornecedor']]])->select('pv.cd_produto')->first();
                $un = DB::table('unidades_comerciais')->where('unidade',$request['uCom'])->select('cd_unidade_comercial')->first();
                if(!isset($cd_produto_vinculado->cd_produto)){
                    $insert['un_comercial'] = $un->cd_unidade_comercial;
                    $insert['cd_produto'] = $request['cd_produto'];
                    $insert['qtde_produtos'] = 1;
                    $insert['id_user'] = session('id_user');
                    $insertean = $insert;
                    $cod = DB::table('produto')->where('cd_produto',$request['cd_produto'])->select('cd_ean')->first();
                    $insertean['cd_ean'] = $cod->cd_ean;
                    DB::table('produto_vinculo')->insert($insertean);
                    $insert['cd_fornecedor'] = $request['cd_fornecedor'];
                    $insert['cd_produto_fornecedor'] = $request['cd_produto_fornecedor'];
                    DB::table('produto_vinculo')->insert($insert);
                }

                $tabMovItensNfe = new \App\Models\Movimentacao_itens_nfe();

                $tabMovItensNfe->fill($dados);
                if($request['cd_movimentacao_itens'] == 0)
                    $tabMovItensNfe->save();
                else
                    $tabMovItensNfe->where('cd_movimentacao_itens',$request['cd_movimentacao_itens'])->update($tabMovItensNfe->toArray());
            }
            return json_encode(['success' => true, 'cd_item_movimentacao' => $dados['cd_movimentacao_itens']]);
        }else{
            $estoque = $this->verifica_estoque($request['cd_sala'], $request['cd_produto'],$request['lote']);
            if(isset($estoque->quantidade)) {
                if ($estoque->quantidade >= $dados['quantidade']) {
                    DB::table('movimentacao_itens')->insert($dados);
                    return json_encode(['success' => true]);
                } else {
                    return json_encode(['success' => false, 'retorno' => $estoque]);
                }
            }
            else{
                return json_encode(['success' => false, 'retorno' => false]);
            }
        }
    }

    public function movimento_sala(){
        //verficaPermissao('recurso.materiais/estoque');
        $data['headerText'] = 'Movimento de estoque por localização';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Materiais', 'href' => route('materiais/produtos')];
        $data['breadcrumbs'][] = ['titulo' => 'Localização', 'href' => route('materiais/estoque/lista')];

        $sala = DB::table('sala')->where('cd_estabelecimento',session('estabelecimento'))->where('situacao','A')->where('tipo','E')->orderBy('nm_sala')->get();
        foreach($sala as $m){
            $data['sala'][$m->cd_sala] = $m->nm_sala;
        }
        return view('materiais/movimentacao/movimento-sala', $data);
    }

    public function mostra_estoque(Request $request){
        session()->put('cd_sala', $request['cd_sala']);
        $where[] = ['mi.cd_sala', $request['cd_sala']];
        if (!empty($request['nome'])) {
            if(!is_numeric($request['nome']))
                $where[] = ['p.nm_produto', 'like', '%'.$request['nome'].'%'];
            else
                $where[] = ['p.cd_ean', $request['nome']];
        }

        $estoque = DB::table('movimentacao as m')
            ->leftJoin('movimentacao_itens as mi', 'mi.cd_movimentacao', 'm.cd_movimentacao')
            ->leftJoin('produto as p', 'p.cd_produto', 'mi.cd_produto')
            ->leftJoin('sala as l', 'l.cd_sala', 'mi.cd_sala')
            ->where($where)
            ->select('l.nm_sala', 'p.nm_produto', 'p.ds_produto', 'mi.lote', 'mi.cd_produto', 'mi.cd_fornecedor', 'mi.dt_validade', 'mi.dt_fabricacao', DB::raw("sum(if(m.tp_saldo = 'A',mi.quantidade,if(m.tp_saldo = 'S', - mi.quantidade,0))) as quantidade"))
            ->groupBy('l.nm_sala', 'p.nm_produto', 'p.ds_produto', 'mi.lote','mi.cd_produto','mi.cd_fornecedor', 'mi.dt_validade', 'mi.dt_fabricacao')
            ->get();
        return json_encode(['success' => true, 'retorno' => $estoque]);
    }

    public function finalizar_transferencia_produtos(Request $request){
        $dadosSaida = (array) DB::table('movimento')->where('cd_movimento',get_config(6,session('estabelecimento')))->select('cd_movimento', 'tp_movimento', 'tp_saldo', 'tp_conta', 'tp_nf', 'cd_cfop')->first();
        $dadosEntrada = (array) DB::table('movimento')->where('cd_movimento',get_config(7,session('estabelecimento')))->select('cd_movimento', 'tp_movimento', 'tp_saldo', 'tp_conta', 'tp_nf', 'cd_cfop')->first();
        $variaveis = ['nr_documento'=>$request['nr_doc'], 'created_at'=>Carbon::now(), 'cd_estabelecimento'=>session('estabelecimento'),'id_user'=>session('id_user')];
        $dadosSaida = array_merge($dadosSaida,$variaveis);
        $variaveis['id_user'] = $request['id_user'];
        $dadosEntrada = array_merge($dadosEntrada, $variaveis);
        $dadosSaida['cd_sala'] = $request['origem'];
        $dadosEntrada['cd_sala'] = $request['destino'];
        $idSaida = DB::table('movimentacao')->insertGetId($dadosSaida);
        $idEntrada = DB::table('movimentacao')->insertGetId($dadosEntrada);

        foreach($request['produtos'] as $produto){
            $estoque = $this->verifica_estoque($request['origem'], $produto['cd_produto'],$produto['lote']);
            if($estoque->quantidade >= $produto['quantidade']){
                $dados['dt_fabricacao'] = $produto['dt_fabricacao'];
                $dados['dt_validade'] = $produto['dt_validade'];
                $dados['cd_fornecedor'] = $produto['cd_fornecedor'];
                $dados['id_user'] = $request['id_user'];
                $dados['cd_movimentacao'] = $idEntrada;
                $dados['cd_sala'] = $request['destino'];
                $dados['created_at'] = Carbon::now();
                $dados['cd_produto'] = $produto['cd_produto'];
                $dados['lote'] = $produto['lote'];
                $dados['quantidade'] = $produto['quantidade'];
                DB::table('movimentacao_itens')->insert($dados);
                $dados['id_user'] = session('id_user');
                $dados['cd_movimentacao'] = $idSaida;
                $dados['cd_sala'] = $request['origem'];
                DB::table('movimentacao_itens')->insert($dados);
            }
            else{
                return json_encode(['success' => false, 'mensagem'=>'Ocorreu um erro ao tentar gravar. Recarregue a página e tente novamente. Caso o problema persista, contate o suporte.']);
            }
        }
        return json_encode(['success' => true]);
    }

    public function mostra_prescricao(Request $request){
        $where[] = ['am.cd_prontuario', $request['cd_prontuario']];
        $where[] = ['am.tp_medicacao', 'PRESCRICAO_MEDICA'];
        $where[] = ['pr.cd_estabelecimento', session('estabelecimento')];
        $where[] = ['pr.status','<>', 'C'];
        $where[] = ['am.status', 'A'];

        $prescricao =
            DB::table('atendimento_medicacao as am')
            ->leftJoin('produto as prod','prod.cd_produto','am.cd_medicamento')
            ->leftJoin('prontuario as pr','pr.cd_prontuario','am.cd_prontuario')
            ->leftJoin('beneficiario as b','b.id_beneficiario','pr.id_beneficiario')
            ->leftJoin('pessoa as p','p.cd_pessoa','b.cd_pessoa')
            ->where($where)
            ->select('am.cd_prontuario', 'p.nm_pessoa', 'am.cd_medicamento', 'prod.nm_produto','prod.cd_fracao_minima','prod.ds_produto','am.quantidade')
            ->get();

        if(isset($prescricao)){
            foreach ($prescricao as $p){
                if(isset($p->fracao_minima))
                    $p->fracao_minima = strtoupper(arrayPadrao('aplicacao')[$p->fracao_minima]);
                $p->estoque = DB::table('movimentacao as m')
                    ->leftJoin('movimentacao_itens as mi', 'mi.cd_movimentacao', 'm.cd_movimentacao')
                    ->leftJoin('produto as p', 'p.cd_produto', 'mi.cd_produto')
                    ->leftJoin('sala as l', 'l.cd_sala', 'mi.cd_sala')
                    ->where('p.cd_produto',$p->cd_medicamento)
                    ->where('mi.cd_sala', $request['cd_sala'])
                    ->select('l.nm_sala', 'p.nm_produto', 'p.ds_produto', 'mi.lote', 'mi.cd_produto', 'mi.cd_fornecedor', 'mi.dt_validade', 'mi.dt_fabricacao', DB::raw("sum(if(m.tp_saldo = 'A',mi.quantidade,if(m.tp_saldo = 'S', - mi.quantidade,0))) as quantidade"))
                    ->groupBy('l.nm_sala', 'p.nm_produto', 'p.ds_produto', 'mi.lote', 'mi.cd_produto','mi.cd_fornecedor', 'mi.dt_validade', 'mi.dt_fabricacao')
                    ->get();
            }
        }
        return json_encode(['success' => true, 'retorno' => $prescricao]);
    }

    public function pesquisa_prontuario(Request $request){
        if (!empty($request['nome'])) {
            if(is_numeric($request['nome']))
                $where[] = ['am.cd_prontuario', $request['nome']];
            else
                $where[] = ['p.nm_pessoa', 'like', "'%".$request['nome']."%'"];
        }
        $where[] = ['am.tp_medicacao', 'PRESCRICAO_MEDICA'];
        $where[] = ['pr.cd_estabelecimento', session('estabelecimento')];
        $where[] = ['pr.status','<>', 'C'];

        $prontuario =
            DB::table('prontuario as pr')
                ->leftJoin('atendimento_medicacao as am','pr.cd_prontuario','am.cd_prontuario')
                ->leftJoin('beneficiario as b','b.id_beneficiario','pr.id_beneficiario')
                ->leftJoin('pessoa as p','p.cd_pessoa','b.cd_pessoa')
                ->where($where)
                ->distinct('pr.cd_prontuario')
                ->select('pr.cd_prontuario', 'p.nm_pessoa')
                ->get();

        return json_encode(['success' => true, 'retorno' => $prontuario]);
    }

    public function pesquisa_fornecedor(Request $request){
        $fornecedores = DB::table('fornecedores as f')
            ->leftJoin('pessoa as p','p.cd_pessoa','f.cd_pessoa')
            ->where('p.nm_pessoa', 'like', '%'.strtoupper($request['pesquisa']).'%')
            ->orWhere('p.cnpj_cpf', 'like', '%'.strtoupper($request['pesquisa']).'%')
            ->select('p.nm_pessoa','f.cd_fornecedor')
            ->get();
        return json_encode(['success' => true, 'dados' => $fornecedores]);
    }

    function verifica_estoque($cd_sala, $cd_produto, $lote){
        $estoque = DB::table('movimentacao as m')
            ->leftJoin('movimentacao_itens as mi', 'mi.cd_movimentacao', 'm.cd_movimentacao')
            ->leftJoin('produto as p', 'p.cd_produto', 'mi.cd_produto')
            ->leftJoin('sala as l', 'l.cd_sala', 'mi.cd_sala')
            ->where('mi.cd_sala',$cd_sala)
            ->where('mi.cd_produto',$cd_produto)
            ->where('mi.lote',$lote)
            ->select('l.nm_sala', 'mi.cd_produto', 'p.nm_produto', 'mi.lote',  DB::raw("sum(if(m.tp_saldo = 'A',mi.quantidade,if(m.tp_saldo = 'S', - mi.quantidade,0))) as quantidade"))
            ->groupBy('l.nm_sala', 'mi.cd_produto', 'p.nm_produto', 'mi.lote')
            ->first();

        return $estoque;
    }

//-------PASSO 1 DA IMPORTAÇÃO DA NFE-----------------------------------------------------------------------------------
    public function importa_xml_nfe(){
        $dadosXml = file_get_contents($_FILES['arquivo']['tmp_name']);
        $dir = storage_path("app/nfe/importacao/".date('d.m.Y')."/");
        $nome = $_FILES['arquivo']['name'];
        if (!file_exists($dir))
            mkdir($dir, 0777, true);
        if (file_exists($dir.$nome))
            unlink($dir.$nome);
        file_put_contents($dir.$nome, $dadosXml);
        $xml = simplexml_load_string($dadosXml);
        return json_encode(['success' => true, 'nfe'=>$xml]);
    }

//-------PASSO 2 DA IMPORTAÇÃO DA NFE-----------------------------------------------------------------------------------
    public function existe_fornecedor(Request $request){
        $fornecedor = $request['fornecedor'];
        $pessoa = DB::table('pessoa as p')->where('cnpj_cpf',$fornecedor['CNPJ'])->first();
        if(isset($pessoa)) {
            $fornecedor = DB::table('fornecedores')->where('cd_pessoa', $pessoa->cd_pessoa)->select('cd_fornecedor')->first();
            if($request['cd_fornecedor'] == $fornecedor->cd_fornecedor)
                return json_encode(['success' => true, 'cd_fornecedor' => $fornecedor->cd_fornecedor]);
            else
                return json_encode(['success' => false, 'mensagem'=>'Ocorreu um erro. O CNPJ do fornecedor informado é diferente do CNPJ encontrado na Nfe. Corrija as informações e repita a operação!']);
        }
        else{
            return json_encode(['success' => false, 'mensagem'=>'Fornecedor não cadastrado. Efetue o cadastro e repita a operação!']);
        }
    }

//-------PASSO 3 DA IMPORTAÇÃO DA NFE-----------------------------------------------------------------------------------
    public function cadastra_movimentacao(Request $request){
        $dados = (array) DB::table('movimento')->where('cd_movimento',$request['cd_movimento'])->select('cd_movimento','cd_cfop','tp_movimento','tp_nf','tp_conta','tp_saldo')->first();
        $dados['nr_documento'] = $request['nr_documento'];
        $dados['cd_sala'] = $request['cd_sala'];
        $cd_movimentacao = $this->lanca_movimentacao(null, $dados);
        return json_encode(['success' => true, 'cd_movimentacao'=>$cd_movimentacao]);
    }

//-------PASSO 4 DA IMPORTAÇÃO DA NFE-----------------------------------------------------------------------------------
    public function cadastra_movimentacao_nfe(Request $request){
        $dados = $this->salva_nfe($request['nfe'],$request['cd_movimentacao']);
        $dados['cd_emitente_destinatario'] = $request['cd_emitente_destinatario'];
        if(isset($dados['duplicata'][0])) {
            $duplicata = $dados['duplicata'];
            unset($dados['duplicata']);
        }
        DB::table('movimentacao_nfe')->insert($dados);
        if(isset($duplicata[0])){
            foreach($duplicata as $d){
                $d['cd_movimentacao'] = $request['cd_movimentacao'];
                DB::table('movimentacao_nfe_duplicata')->insert($d);
            }
        }
        return json_encode(['success' => true]);
    }

//-------PASSO 5 DA IMPORTAÇÃO DA NFE-----------------------------------------------------------------------------------
    public function verifica_cadastro_produto(Request $request){
        if(empty($request['pesquisa'])) {
            $produto = $request['produto']['prod'];
            $cd_produto_vinculado = DB::table('produto_vinculo as pv')
                ->where([['pv.cd_fornecedor',$request['cd_fornecedor']],['pv.cd_produto_fornecedor',$produto['cProd']]])
                ->orWhere('pv.cd_ean',$produto['cEAN'])
                ->select('pv.cd_produto','pv.qtde_produtos as multiplicador')
                ->first();

            if(isset($cd_produto_vinculado->cd_produto)){
                $cadastro = DB::table('produto as p')
                    ->leftJoin('unidades_comerciais as uc', 'uc.cd_unidade_comercial', 'p.cd_unidade_comercial')
                    ->leftJoin('unidade_medida as um', 'um.cd_unidade_medida', 'p.cd_fracao_minima')
                    ->where('p.cd_produto', $cd_produto_vinculado->cd_produto)
                    ->select('p.cd_produto', 'p.cd_ean', 'p.fracionamento', 'p.nm_produto', 'p.ds_produto', 'p.nm_laboratorio', 'uc.unidade as embalagem', 'p.qtde_embalagem', 'p.cd_fracao_minima','um.abreviacao as un_medida')
                    ->first();
                $cadastro->multiplicador = $cd_produto_vinculado->multiplicador;
                $cadastro->importado_anteriormente = true;
            }
            else {
                $cadastro = DB::table('produto as p')
                    ->leftJoin('unidades_comerciais as u', 'u.cd_unidade_comercial', 'p.cd_unidade_comercial')
                    ->leftJoin('unidade_medida as um', 'um.cd_unidade_medida', 'p.cd_fracao_minima')
                    ->where('p.cd_ean', $produto['cEAN'])
                    ->select('p.cd_produto', 'p.cd_ean', 'p.nm_produto', 'p.fracionamento', 'p.ds_produto', 'p.nm_laboratorio', 'u.unidade as embalagem', 'p.qtde_embalagem', 'p.cd_fracao_minima','um.abreviacao as un_medida')
                    ->first();
                if(isset($cadastro))
                    $cadastro->importado_anteriormente = false;
            }

        }
        else{
            if(is_numeric($request['pesquisa'])) {
                $cadastro = DB::table('produto as p')
                    ->leftJoin('unidades_comerciais as u','u.cd_unidade_comercial','p.cd_unidade_comercial')
                    ->leftJoin('unidade_medida as um', 'um.cd_unidade_medida', 'p.cd_fracao_minima')
                    ->where('p.cd_ean', $request['pesquisa'])
                    ->select('p.cd_produto','p.cd_ean','p.nm_produto', 'p.fracionamento', 'p.ds_produto', 'p.nm_laboratorio', 'u.unidade as embalagem', 'p.qtde_embalagem', 'p.cd_fracao_minima','um.abreviacao as un_medida')
                    ->get();
            }
            else {
                $cadastro = DB::table('produto as p')
                    ->leftJoin('unidades_comerciais as u','u.cd_unidade_comercial','p.cd_unidade_comercial')
                    ->leftJoin('unidade_medida as um', 'um.cd_unidade_medida', 'p.cd_fracao_minima')
                    ->where('p.nm_produto', 'like', '%'.strtoupper($request['pesquisa']).'%')
                    ->select('p.cd_produto','p.cd_ean','p.nm_produto', 'p.fracionamento', 'p.ds_produto', 'p.nm_laboratorio', 'u.unidade as embalagem', 'p.qtde_embalagem', 'p.cd_fracao_minima','um.abreviacao as un_medida')
                    ->get();
            }
        }

        if(isset($cadastro)) {
            return json_encode(['success' => true, 'existe' => true, 'cadastro' => $cadastro]);
        }else{
            return json_encode(['success' => true, 'existe' => false]);
        }

    }

//-------PASSO 6 DA IMPORTAÇÃO DA NFE-----------------------------------------------------------------------------------
    //Função adicionar_item(acima)

//-------PASSO 7 DA IMPORTAÇÃO DA NFE-----------------------------------------------------------------------------------
    public function cadastra_item_movimentacao_nfe(Request $request){
        $dados = $this->salva_itens_nfe($request['produto']);
        $dados['cd_movimentacao_itens'] = $request['cd_item_movimentacao'];
        DB::table('movimentacao_itens_nfe')->insert($dados);
        return json_encode(['success' => true, 'retorno'=>'Parece que funcionou!!!!']);
    }

    public function add_produto_vinculo(Request $request){
        $un_comercial = DB::table('unidades_comerciais')->where('unidade',$request['produto']['prod']['uCom'])->select('cd_unidade_comercial')->first();
        if(isset($un_comercial->cd_unidade_comercial)) {
            $dados['un_comercial'] = $un_comercial->cd_unidade_comercial;
            $dados['cd_produto'] = $request['cd_produto'];
            $dados['qtde_produtos'] = $request['quantidade'];
            $dados['id_user'] = session('id_user');
            $dadosean = $dados;
            $dadosean['cd_ean'] = $request['produto']['prod']['cEAN'];
            if(strtoupper($dadosean['cd_ean']) != 'SEM GTIN' && $dadosean['cd_ean'] != '' && $dadosean['cd_ean'] != null)
                DB::table('produto_vinculo')->insert($dadosean);
            $dados['cd_fornecedor'] = $request['cd_fornecedor'];
            $dados['cd_produto_fornecedor'] = $request['produto']['prod']['cProd'];
            DB::table('produto_vinculo')->insert($dados);
            return json_encode(['success' => true]);
        }
        else
            return json_encode(['success' => false,'mensagem'=>'Unidade comercial ('.$request['produto']['prod']['uCom'].") não cadastrada. Cadastre-a e repita a opetação!"]);
    }

    function salva_nfe($xml,$cd_movimentacao){
        $dados['cd_movimentacao'] = $cd_movimentacao;

        $chave = isset($xml['NFe']['infNFe']['@attributes']['Id']) ? $xml['NFe']['infNFe']['@attributes']['Id'] : null;
        $dados['chave'] = strtr(strtoupper($chave), array("NFE" => NULL));

//===============================================================================================================================================
    //<ide>
        $dados['cUF'] = isset($xml['NFe']['infNFe']['ide']['cUF']) ? $xml['NFe']['infNFe']['ide']['cUF'] : null;
        $dados['cNF'] = isset($xml['NFe']['infNFe']['ide']['cNF']) ? $xml['NFe']['infNFe']['ide']['cNF'] : null;
        $dados['natOp'] = isset($xml['NFe']['infNFe']['ide']['natOp']) ? $xml['NFe']['infNFe']['ide']['natOp'] : null;
        $dados['mod'] = isset($xml['NFe']['infNFe']['ide']['mod']) ? $xml['NFe']['infNFe']['ide']['mod'] : null;
        $dados['serie'] = isset($xml['NFe']['infNFe']['ide']['serie']) ? $xml['NFe']['infNFe']['ide']['serie'] : null;
        $dados['nNF'] =  isset($xml['NFe']['infNFe']['ide']['nNF']) ? $xml['NFe']['infNFe']['ide']['nNF'] : null;
        $dados['dhEmi'] = isset($xml['NFe']['infNFe']['ide']['dhEmi']) ? str_replace("T", " ", substr($xml['NFe']['infNFe']['ide']['dhEmi'],0, strlen($xml['NFe']['infNFe']['ide']['dhEmi']) - 6)) : null;
        $dados['dhSaiEnt'] = isset($xml['NFe']['infNFe']['ide']['dhSaiEnt']) ? str_replace("T"," ", substr($xml['NFe']['infNFe']['ide']['dhSaiEnt'],0, strlen($xml['NFe']['infNFe']['ide']['dhSaiEnt']) - 6)) : null;
        $dados['tpNF'] = isset($xml['NFe']['infNFe']['ide']['tpNF']) ? $xml['NFe']['infNFe']['ide']['tpNF'] : null;
        $dados['cMunFG'] = isset($xml['NFe']['infNFe']['ide']['cMunFG']) ? $xml['NFe']['infNFe']['ide']['cMunFG'] : null;
        $dados['tpImp'] = isset($xml['NFe']['infNFe']['ide']['tpImp']) ? $xml['NFe']['infNFe']['ide']['tpImp'] : null;
        $dados['tpEmis'] = isset($xml['NFe']['infNFe']['ide']['tpEmis']) ? $xml['NFe']['infNFe']['ide']['tpEmis'] : null;
        $dados['cDV'] = isset($xml['NFe']['infNFe']['ide']['cDV']) ? $xml['NFe']['infNFe']['ide']['cDV'] : null;
        $dados['tpAmb'] = isset($xml['NFe']['infNFe']['ide']['tpAmb']) ? $xml['NFe']['infNFe']['ide']['tpAmb'] : null;
        $dados['finNFe'] = isset($xml['NFe']['infNFe']['ide']['finNFe']) ? $xml['NFe']['infNFe']['ide']['finNFe'] : null;
        $dados['procEmi'] = isset($xml['NFe']['infNFe']['ide']['procEmi']) ? $xml['NFe']['infNFe']['ide']['procEmi'] : null;
        $dados['verProc'] = isset($xml['NFe']['infNFe']['ide']['verProc']) ? $xml['NFe']['infNFe']['ide']['verProc'] : null;
    //</ide>
        $dados['xMotivo'] = isset($xml['protNFe']['infProt']['xMotivo']) ? $xml['protNFe']['infProt']['xMotivo'] : null;
        $dados['nProt'] = isset($xml['protNFe']['infProt']['nProt']) ? $xml['protNFe']['infProt']['nProt'] : null;
//===============================================================================================================================================
    // <emit> Emitente
        $dados['emit_CPF'] = isset($xml['NFe']['infNFe']['emit']['CPF']) ? $xml['NFe']['infNFe']['emit']['CPF'] : null;
        $dados['emit_CNPJ'] = isset($xml['NFe']['infNFe']['emit']['CNPJ']) ? $xml['NFe']['infNFe']['emit']['CNPJ'] : null;
        $dados['emit_xNome'] = isset($xml['NFe']['infNFe']['emit']['xNome']) ? $xml['NFe']['infNFe']['emit']['xNome'] : null;
        $dados['emit_xFant'] = isset($xml['NFe']['infNFe']['emit']['xFant']) ? $xml['NFe']['infNFe']['emit']['xFant'] : null;
        //<enderEmit>
            $dados['emit_xLgr'] = isset($xml['NFe']['infNFe']['emit']['enderEmit']['xLgr']) ? $xml['NFe']['infNFe']['emit']['enderEmit']['xLgr'] : null;
            $dados['emit_nro'] = isset($xml['NFe']['infNFe']['emit']['enderEmit']['nro']) ? $xml['NFe']['infNFe']['emit']['enderEmit']['nro'] : null;
            $dados['emit_xBairro'] = isset($xml['NFe']['infNFe']['emit']['enderEmit']['xBairro']) ? $xml['NFe']['infNFe']['emit']['enderEmit']['xBairro'] : null;
            $dados['emit_cMun'] = isset($xml['NFe']['infNFe']['emit']['enderEmit']['cMun']) ? $xml['NFe']['infNFe']['emit']['enderEmit']['cMun'] : null;
            $dados['emit_xMun'] = isset($xml['NFe']['infNFe']['emit']['enderEmit']['xMun']) ? $xml['NFe']['infNFe']['emit']['enderEmit']['xMun'] : null;
            $dados['emit_UF'] = isset($xml['NFe']['infNFe']['emit']['enderEmit']['UF']) ? $xml['NFe']['infNFe']['emit']['enderEmit']['UF'] : null;
            $dados['emit_CEP'] = isset($xml['NFe']['infNFe']['emit']['enderEmit']['CEP']) ? $xml['NFe']['infNFe']['emit']['enderEmit']['CEP'] : null;
            $dados['emit_cPais'] = isset($xml['NFe']['infNFe']['emit']['enderEmit']['cPais']) ? $xml['NFe']['infNFe']['emit']['enderEmit']['cPais'] : null;
            $dados['emit_xPais'] = isset($xml['NFe']['infNFe']['emit']['enderEmit']['xPais']) ? $xml['NFe']['infNFe']['emit']['enderEmit']['xPais'] : null;
            $dados['emit_fone'] = isset($xml['NFe']['infNFe']['emit']['enderEmit']['fone']) ? $xml['NFe']['infNFe']['emit']['enderEmit']['fone'] : null;
        //</enderEmit>
        $dados['emit_IE'] = isset($xml['NFe']['infNFe']['emit']['IE']) ? $xml['NFe']['infNFe']['emit']['IE'] : null;
        $dados['emit_IM'] = isset($xml['NFe']['infNFe']['emit']['IM']) ? $xml['NFe']['infNFe']['emit']['IM'] : null;
        $dados['emit_CNAE'] = isset($xml['NFe']['infNFe']['emit']['CNAE']) ? $xml['NFe']['infNFe']['emit']['CNAE'] : null;
        $dados['emit_CRT'] = isset($xml['NFe']['infNFe']['emit']['CRT']) ? $xml['NFe']['infNFe']['emit']['CRT'] : null;
    //</emit>
//===============================================================================================================================================
    //<dest>
        $dados['dest_cnpj'] =  isset($xml['NFe']['infNFe']['dest']['CNPJ']) ? $xml['NFe']['infNFe']['dest']['CNPJ'] : null;
        $dados['dest_xNome'] = isset($xml['NFe']['infNFe']['dest']['xNome']) ? $xml['NFe']['infNFe']['dest']['xNome'] : null;
        //<enderDest>
            $dados['dest_xLgr'] = isset($xml['NFe']['infNFe']['dest']['enderDest']['xLgr']) ? $xml['NFe']['infNFe']['dest']['enderDest']['xLgr'] : null;
            $dados['dest_nro'] =  isset($xml['NFe']['infNFe']['dest']['enderDest']['nro']) ? $xml['NFe']['infNFe']['dest']['enderDest']['nro'] : null;
            $dados['dest_xBairro'] = isset($xml['NFe']['infNFe']['dest']['enderDest']['xBairro']) ? $xml['NFe']['infNFe']['dest']['enderDest']['xBairro'] : null;
            $dados['dest_cMun'] = isset($xml['NFe']['infNFe']['dest']['enderDest']['cMun']) ? $xml['NFe']['infNFe']['dest']['enderDest']['cMun'] : null;
            $dados['dest_xMun'] = isset($xml['NFe']['infNFe']['dest']['enderDest']['xMun']) ? $xml['NFe']['infNFe']['dest']['enderDest']['xMun'] : null;
            $dados['dest_UF'] = isset($xml['NFe']['infNFe']['dest']['enderDest']['UF']) ? $xml['NFe']['infNFe']['dest']['enderDest']['UF'] : null;
            $dados['dest_CEP'] = isset($xml['NFe']['infNFe']['dest']['enderDest']['CEP']) ? $xml['NFe']['infNFe']['dest']['enderDest']['CEP'] : null;
            $dados['dest_cPais'] = isset($xml['NFe']['infNFe']['dest']['enderDest']['cPais']) ? $xml['NFe']['infNFe']['dest']['enderDest']['cPais'] : null;
            $dados['dest_xPais'] = isset($xml['NFe']['infNFe']['dest']['enderDest']['xPais']) ? $xml['NFe']['infNFe']['dest']['enderDest']['xPais'] : null;
        //</enderDest>
        $dados['dest_IE'] = isset($xml['NFe']['infNFe']['dest']['IE']) ? $xml['NFe']['infNFe']['dest']['IE'] : null;
    //</dest>
//===============================================================================================================================================
        $dados['vBC'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vBC']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vBC'] : null;
        $dados['vICMS'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vICMS']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vICMS'] : null;
        $dados['vBCST'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vBCST']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vBCST'] : null;
        $dados['vST'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vST']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vST'] : null;
        $dados['vProd'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vProd']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vProd'] : null;
        $dados['vNF'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vNF']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vNF'] : null;
        $dados['vFrete'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vFrete']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vFrete'] : null;
        $dados['vSeg'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vSeg']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vSeg'] : null;
        $dados['vDesc'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vDesc']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vDesc'] : null;
        $dados['vIPI'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vIPI']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vIPI'] : null;
        $dados['vOutro'] = isset($xml['NFe']['infNFe']['total']['ICMSTot']['vOutro']) ? $xml['NFe']['infNFe']['total']['ICMSTot']['vOutro'] : null;

        $dados['indPag'] = isset($xml['NFe']['infNFe']['pag']['detPag']['indPag']) ? $xml['NFe']['infNFe']['pag']['detPag']['indPag'] : null;
        $dados['tPag'] = isset($xml['NFe']['infNFe']['pag']['detPag']['tPag']) ? $xml['NFe']['infNFe']['pag']['detPag']['tPag'] : null;
        $dados['vPag'] = isset($xml['NFe']['infNFe']['pag']['detPag']['vPag']) ? $xml['NFe']['infNFe']['pag']['detPag']['vPag'] : null;

        if (isset($xml['NFe']['infNFe']['cobr']['dup']))
        {
            $key = 0;
            if(isset($xml['NFe']['infNFe']['cobr']['dup']['nDup'])){
                $dados['duplicata'][$key]['nDup'] = isset($xml['NFe']['infNFe']['cobr']['dup']['nDup']) ? $xml['NFe']['infNFe']['cobr']['dup']['nDup'] : null;
                $dados['duplicata'][$key]['dVenc'] = isset($xml['NFe']['infNFe']['cobr']['dup']['dVenc']) ? $xml['NFe']['infNFe']['cobr']['dup']['dVenc'] : null;
                $dados['duplicata'][$key]['vDup'] = isset($xml['NFe']['infNFe']['cobr']['dup']['vDup']) ? $xml['NFe']['infNFe']['cobr']['dup']['vDup'] : null;
            }
            else {
                foreach ($xml['NFe']['infNFe']['cobr']['dup'] as $dup) {
                    $dados['duplicata'][$key]['nDup'] = isset($dup['nDup']) ? $dup['nDup'] : null;
                    $dados['duplicata'][$key]['dVenc'] = isset($dup['dVenc']) ? $dup['dVenc'] : null;
                    $dados['duplicata'][$key]['vDup'] = isset($dup['vDup']) ? $dup['vDup'] : null;
                    $key++;
                }
            }
        }
//===============================================================================================================================================
    //Autorização
        $dados['xMotivo'] = isset($xml['protNFe']['infProt']['xMotivo']) ? $xml['protNFe']['infProt']['xMotivo'] : null;
        $dados['nProt'] = isset($xml['protNFe']['infProt']['nProt']) ? $xml['protNFe']['infProt']['nProt'] : null;

        return($dados);
    }

    function salva_itens_nfe($item)
    {
        $st = array('00','10','20','30','40','41','50','51','60','70','90','Part','ST','SN101','SN102','SN201','SN202','SN500','SN900');
//-------------Informações do produto-----------------------------------------------------------------------------------
        $dados['nr_item_nfe'] = isset($item['@attributes']['nItem']) ? $item['@attributes']['nItem'] : null;
        $dados['cd_produto_fornecedor'] = isset($item['prod']['cProd']) ? $item['prod']['cProd'] : null;
        $dados['xProd'] = isset($item['prod']['xProd']) ? $item['prod']['xProd'] : null;
        $dados['NCM'] = isset($item['prod']['NCM']) ? $item['prod']['NCM'] : null;
        $dados['CFOP'] = isset($item['prod']['CFOP']) ? $item['prod']['CFOP'] : null;
        $dados['uCom'] = isset($item['prod']['uCom']) ? $item['prod']['uCom'] : null;
        $dados['qCom'] = isset($item['prod']['qCom']) ? $item['prod']['qCom'] : null;
        $dados['vUnCom'] = isset($item['prod']['vUnCom']) ? $item['prod']['vUnCom'] : null;
        $dados['vProd'] = isset($item['prod']['vProd']) ? $item['prod']['vProd'] : null;
        $dados['cEANTrib'] = isset($item['prod']['cEANTrib']) ? $item['prod']['cEANTrib'] : null;
        $dados['uTrib'] = isset($item['prod']['uTrib']) ? $item['prod']['uTrib'] : null;
        $dados['qTrib'] = isset($item['prod']['qTrib']) ? $item['prod']['qTrib'] : null;
        $dados['vUnTrib'] = isset($item['prod']['vUnTrib']) ? $item['prod']['vUnTrib'] : null;
        $dados['indTot'] = isset($item['prod']['indTot']) ? $item['prod']['indTot'] : null;
        $dados['xPed'] = isset($item['prod']['xPed']) ? $item['prod']['xPed'] : null;
//-------------Informações do rastreio do produto-----------------------------------------------------------------------
        if(isset($item['prod']['rastro'])) {
            $dados['nLote'] = isset($item['prod']['rastro']['nLote']) ? $item['prod']['rastro']['nLote'] : null;
            $dados['qLote'] = isset($item['prod']['rastro']['qLote']) ? $item['prod']['rastro']['qLote'] : null;
            $dados['dFab'] = isset($item['prod']['rastro']['dFab']) ? $item['prod']['rastro']['dFab'] : null;
            $dados['dVal'] = isset($item['prod']['rastro']['dVal']) ? $item['prod']['rastro']['dVal'] : null;
        }
        if(isset($item['prod']['med'])) {
            $dados['cProdANVISA'] = isset($item['prod']['med']['cProdANVISA']) ? $item['prod']['med']['cProdANVISA'] : null;
            $dados['vPMC'] = isset($item['prod']['med']['vPMC']) ? $item['prod']['med']['vPMC'] : null;
        }
//-------------Informações dos impostos---------------------------------------------------------------------------------
        $dados['vTotTrib'] = isset($item['imposto']['vTotTrib']) ? $item['imposto']['vTotTrib'] : null;

        foreach($st as $s){
            $indice = 'ICMS'.$s;
            if(isset($item['imposto']['ICMS'][$indice])){
                $dados['icms_grupo_trib'] = $s;
                $dados['icms_orig'] = isset($item['imposto']['ICMS'][$indice]['orig']) ? $item['imposto']['ICMS'][$indice]['orig'] : null;
                $dados['icms_CST'] = isset($item['imposto']['ICMS'][$indice]['CST']) ? $item['imposto']['ICMS'][$indice]['CST'] : null;
                $dados['icms_modBC'] = isset($item['imposto']['ICMS'][$indice]['modBC']) ? $item['imposto']['ICMS'][$indice]['modBC'] : null;
                $dados['icms_vBC'] = isset($item['imposto']['ICMS'][$indice]['vBC']) ? $item['imposto']['ICMS'][$indice]['vBC'] : null;
                $dados['icms_pICMS'] = isset($item['imposto']['ICMS'][$indice]['pICMS']) ? $item['imposto']['ICMS'][$indice]['pICMS'] : null;
                $dados['icms_vICMS'] = isset($item['imposto']['ICMS'][$indice]['vICMS']) ? $item['imposto']['ICMS'][$indice]['vICMS'] : null;
            }
        }
        if(isset($item['imposto']['IPI'])){
                $dados['ipi_cEnq'] = isset($item['imposto']['IPI']['cEnq']) ? $item['imposto']['IPI']['cEnq'] : null;
            if(isset($item['imposto']['IPI']['IPITrib'])){
                $dados['ipi_CST'] = isset($item['imposto']['IPI']['IPITrib']['CST']) ? $item['imposto']['IPI']['IPITrib']['CST'] : null;
                $dados['ipi_vBC'] = isset($item['imposto']['IPI']['IPITrib']['vBC']) ? $item['imposto']['IPI']['IPITrib']['vBC'] : null;
                $dados['ipi_pIPI'] = isset($item['imposto']['IPI']['IPITrib']['pIPI']) ? $item['imposto']['IPI']['IPITrib']['pIPI'] : null;
                $dados['ipi_vIPI'] = isset($item['imposto']['IPI']['IPITrib']['vIPI']) ? $item['imposto']['IPI']['IPITrib']['vIPI'] : null;
            }
        }
        if(isset($item['imposto']['PIS'])){
          if(isset($item['imposto']['PIS']['PISAliq'])){
              $dados['pis_CST'] = isset($item['imposto']['PIS']['PISAliq']['CST']) ? $item['imposto']['PIS']['PISAliq']['CST'] : null;
              $dados['pis_vBC'] = isset($item['imposto']['PIS']['PISAliq']['vBC']) ? $item['imposto']['PIS']['PISAliq']['vBC'] : null;
              $dados['pis_pPIS'] = isset($item['imposto']['PIS']['PISAliq']['pPIS']) ? $item['imposto']['PIS']['PISAliq']['pPIS'] : null;
              $dados['pis_vPIS'] = isset($item['imposto']['PIS']['PISAliq']['vPIS']) ? $item['imposto']['PIS']['PISAliq']['vPIS'] : null;
          }
        }
        if(isset($item['imposto']['COFINS'])){
            if(isset($item['imposto']['COFINS']['COFINSAliq'])){
                $dados['cofins_CST'] = isset($item['imposto']['COFINS']['COFINSAliq']['CST']) ? $item['imposto']['COFINS']['COFINSAliq']['CST'] : null;
                $dados['cofins_vBC'] = isset($item['imposto']['COFINS']['COFINSAliq']['vBC']) ? $item['imposto']['COFINS']['COFINSAliq']['vBC'] : null;
                $dados['cofins_pCOFINS'] = isset($item['imposto']['COFINS']['COFINSSAliq']['pCOFINS']) ? $item['imposto']['COFINS']['COFINSAliq']['pCOFINS'] : null;
                $dados['cofins_vCOFINS'] = isset($item['imposto']['COFINS']['COFINSAliq']['vCOFINS']) ? $item['imposto']['COFINS']['COFINSAliq']['vCOFINS'] : null;
            }
        }

        return $dados;
    }

    function lanca_movimentacao($id, $dados){
        $dados['id_user'] = session('id_user');
        $dados['cd_estabelecimento'] = session('estabelecimento');
        $tabMov = new \App\Models\Movimentacao();
        if (!empty($id)) {
            $tabMov->fill($dados);
            $tabMov->where('cd_movimentacao', $_POST['cd_movimentacao'])->update($tabMov->toArray());
        } else {
            $dados['created_at'] = Carbon::now();
            $tabMov->fill($dados);
            $tabMov->save();
            $id = DB::table('movimentacao')->max('cd_movimentacao');
        }
        return $id;
    }

    public function detalhes_movimentacao_item(Request $request){
        $retorno = (array) DB::table('movimentacao_itens as mi')
            ->leftJoin('movimentacao_itens_nfe as min','min.cd_movimentacao_itens','mi.cd_movimentacao_itens')
            ->leftJoin('produto as p','p.cd_produto', 'mi.cd_produto')
            ->leftJoin('produto_vinculo as pv','pv.cd_produto','p.cd_produto')
            ->leftJoin('unidade_medida as um','um.cd_unidade_medida','p.cd_fracao_minima')
            ->leftJoin('unidades_comerciais as uc','uc.cd_unidade_comercial','p.cd_unidade_comercial')
            ->leftJoin('sala as lo','lo.cd_sala', 'mi.cd_sala')
            ->where('mi.cd_movimentacao_itens', $request['cd_movimentacao_itens'])
            ->select('mi.cd_movimentacao_itens', 'mi.valor_unitario', 'pv.qtde_produtos as proporcao', 'p.fracionamento', 'uc.unidade as nm_unidade_comercial', 'p.qtde_embalagem','um.abreviacao as nm_fracao_minima', 'min.vProd', 'min.NCM', 'min.CFOP', 'min.icms_vICMS', 'min.ipi_vIPI', 'min.vUnCom', 'mi.cd_produto','min.uCom', 'min.cd_produto_fornecedor','min.qCom', 'p.nm_produto','p.ds_produto', 'mi.lote','mi.dt_validade','mi.dt_fabricacao','mi.quantidade','mi.cd_sala')
            ->first();

        return json_encode(['success' => true, 'retorno'=> $retorno]);
    }
}