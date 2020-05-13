<?php

namespace App\Http\Controllers;

use App\Mail\mailTeste;
use App\Models\Pessoa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Monolog\Logger;
use Illuminate\Support\Facades\Log;

class Pessoas extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function index(){
        $data['headerText'] = 'Pessoas';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Pessoas', 'href' => '#'];

        return view('pessoas/cadastro', $data);
    }
/*
    public function cadastrar($cdPessoa = null, Request $request)
    {
        verficaPermissao('recurso.pessoas');

        $data['headerText'] = 'Pessoas';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Pessoas', 'href' => route('pessoas/lista')];
        $data['breadcrumbs'][] = ['titulo' => 'Cadastrar', 'href' => route('operadores/cadastro')];

        $cdBeneficiario = (isset($_POST['cd_beneficiario']) ? $_POST['cd_beneficiario'] : "");
        $cdPlano = (isset($_POST['cd_plano']) ? $_POST['cd_plano'] : "");
        $planos = DB::table('plano')->get();
        $data['planos'] = array();
        foreach ($planos as $p) {
            $data['planos'][$p->cd_plano] = $p->ds_plano;
        }
        $dados = $request->except(['_token', 'cd_beneficiario', 'cd_plano']);
        if (!empty($dados)) {
            $dados = array_map('strtoupper', $dados);
            $dados['ds_email'] = strtolower($dados['ds_email']);
            if ($dados['dt_nasc_contato'] == "")
                $dados['dt_nasc_contato'] = null;
            if ($dados['dt_nasc'] == "")
                $dados['dt_nasc'] = null;
        }

        if (!empty($cdPessoa) && empty($_POST['id'])) {
            $update = true;

            $pessoa =
                DB::table('pessoa')
                    ->leftJoin('beneficiario as b', 'pessoa.cd_pessoa', '=', 'b.cd_pessoa')
                    ->leftJoin('contrato as c', 'pessoa.cd_pessoa', '=', 'c.cd_pessoa')
                    ->where('pessoa.cd_pessoa', $cdPessoa)
                    ->select('pessoa.*', 'b.cd_beneficiario', 'c.cd_plano')
                    ->get();
            if (isset($pessoa[0])) {
                $array = (array)$pessoa[0];
                $data['pessoa'] = $array;
            }
        } else {
            $update = false;
            $data['pessoa'] = $_POST;
        }

        if ($request->isMethod('post')) {
            $dados['cep'] = preg_replace('/\D/', '', $_POST['cep']);
            $dados['cep_aux'] = preg_replace('/\D/', '', $_POST['cep_aux']);
            $dados['cnpj_cpf'] = preg_replace('/\D/', '', $_POST['cnpj_cpf']);
            $dados['op_alter'] = Auth::user()->id;

            $rules = [
                'nm_pessoa' => 'required',
                'cnpj_cpf' => 'required|' . ($update ? '' : 'unique:pessoa') . '|' . ($dados['id_pessoa'] == 'F' ? 'cpf' : 'cnpj'),
                'dt_nasc' => 'nullable|date|after:"01/01/1900"|before:' . date('Y/m/d'),
                'dt_nasc_contato' => 'nullable|date|after:"01/01/1900"|before:' . date('Y/m/d'),
                'localidade' => 'required',
                'endereco' => 'required',
                'endereco_nro' => 'required',
                'bairro' => 'required',
                'uf' => 'required',
                'cep' => 'required'
            ];
            $validator = Validator::make($dados, $rules);

            if ($validator->fails()) {
                return view('pessoas/cadastro', $data)->withErrors($validator);
            } else {
                $tabPessoa = new Pessoa();
                if (!empty($_POST['cd_pessoa'])) {
                    $tabPessoa->fill($dados);
                    $tabPessoa->where('cd_pessoa', $_POST['cd_pessoa'])->update($tabPessoa->toArray());
                } else {
                    $dados['cd_pessoa'] = null;
                    $tabPessoa->fill($dados);
                    $tabPessoa->save();
                }
                $cdPessoa = $tabPessoa->cd_pessoa;

                Log::info('cd_pessoa> ' . $tabPessoa->cd_pessoa);

                if (!empty($cdBeneficiario)) {
                    $beneficiario = DB::table('beneficiario')
                        ->where('cd_pessoa', $cdPessoa)
                        ->first();
                    if (isset($beneficiario)) {
                        DB::table('contrato')
                            ->where('cd_pessoa', $cdPessoa)
                            ->update(["cd_plano" => $cdPlano]);
                        DB::table('beneficiario')
                            ->where('cd_pessoa', $cdPessoa)
                            ->update(['cd_beneficiario' => $cdBeneficiario]);

                    } else {
                        $cdContrato = DB::table('contrato')
                            ->insertGetId(["cd_pessoa" => $cdPessoa, "cd_plano" => $cdPlano], 'cd_contrato');
                        DB::table('beneficiario')
                            ->insert(["cd_pessoa" => $cdPessoa, "cd_contrato" => $cdContrato, "cd_beneficiario" => $cdBeneficiario]);
                    }
                } else {
                    DB::table('contrato')->where('cd_pessoa', $cdPessoa)->delete();
                    DB::table('beneficiario')->where('cd_pessoa', $cdPessoa)->delete();
                }

                $request->session()->flash('status', 'Salvo com sucesso!');
                return redirect('pessoas/cadastro/' . $cdPessoa);
            }
        }

        return view('pessoas/cadastro', $data);
    }
*/
    public function listar(){
        verficaPermissao('recurso.pessoas');

        $data['headerText'] = 'Pessoas';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Pessoas', 'href' => route('pessoas/lista')];
        $data['verPessoaSemPesquisa'] = true;
        $data['escolhe_pf_pj'] = true;

        if ($_REQUEST) {
            $where = [];
            if (!empty($_REQUEST['nome'])) {
                $where[] = ['nm_pessoa', 'like', '%' . $_REQUEST['nome'] . '%'];
            }
            if (!empty($_REQUEST['id_situacao']) && ($_REQUEST['id_situacao'] !== 'T')) {
                $where[] = ['p.id_situacao', '=', $_REQUEST['id_situacao']];
            }
            if (!empty($_REQUEST['cnpj_cpf'])) {
                $where[] = ['p.cnpj_cpf', '=', $_REQUEST['cnpj_cpf']];
            }
            $data['lista'] =
                DB::table('pessoa as p')
                    ->leftJoin('arquivos as a', function($join)
                    {
                        $join->on('a.cd_pessoa','=','p.cd_pessoa')
                            ->where('a.tp_arquivo','=',1);
                    })
                    ->where($where)
                    ->select('p.cnpj_cpf', 'p.nm_pessoa', 'p.id_situacao', 'p.id_pessoa', 'p.cd_pessoa','a.nm_arquivo')
                    ->orderBy('nm_pessoa')
                    ->paginate(30)
                    ->appends($_REQUEST);
        }

        return view('pessoas/lista', $data);
    }

    public function remover(){
        DB::table('beneficiario')->where('cd_pessoa', '=', $_POST['cd_pessoa'])->delete();
        DB::table('contrato')->where('cd_pessoa', '=', $_POST['cd_pessoa'])->delete();
        DB::table('pessoa')->where('cd_pessoa', '=', $_POST['cd_pessoa'])->delete();
        return redirect($_REQUEST['REFERER']);
    }

    public function buscar_user(Request $request){
        $cd_pessoa = $request->get('cd_pessoa');
        $retorno = DB::table('users')->where('cd_pessoa', '=', $cd_pessoa)->get();
        $existe = false;
        if (count($retorno) > 0) {
            $existe = true;
        }
        return json_encode(['success' => true, 'existe' => $existe, 'cd_pessoa' => $cd_pessoa]);
    }

    public function cadastrar_via_modal(Request $request){

        $dados = $request->except(['_token', 'cd_plano']);
        $dados = array_map('strtoupper', $dados);
        $dados['dt_nasc'] = formata_data_mysql($dados['dt_nasc']);
        $dados['dt_nasc_contato'] = formata_data_mysql($dados['dt_nasc_contato']);

        $dados['ds_email'] = strtolower($dados['ds_email']);
        $dados['cep'] = preg_replace('/\D/', '', $_POST['cep']);
        if ($dados['cep'] == ''){
            $dados['cep'] = 0;
        }
        $dados['cnpj_cpf'] = preg_replace('/\D/', '', $_POST['cnpj_cpf']);
        $dados['op_alter'] = Auth::user()->id;

        $dados['endereco'] = substr($dados['endereco'],0,100);
        $dados['endereco_compl'] = substr($dados['endereco_compl'],0,100);
        $dados['bairro'] = substr($dados['bairro'],0,100);
        $dados['localidade'] = substr($dados['localidade'],0,100);

        if (!empty($_POST['cd_pessoa'])) {
            $update = true;
        } else {
            $update = false;
        }

        if ($dados['cep'] == '99.999-999' || $dados['nao_validar_endereco'] == '1'){
            $validar_end = 'nullable';
        } else {
            $validar_end = 'required';
        }

        $rules = [
            'nm_pessoa' => 'required',
            'cnpj_cpf' => 'nullable|' . ($update ? '' : 'unique:pessoa') . '|' . ($_POST['id_pessoa'] == 'F' ? 'cpf' : 'cnpj'),
            //'cd_beneficiario' => 'nullable|' . ($update ? '' : 'unique:beneficiario') . '| cd_beneficiario',
            'cd_beneficiario' => 'nullable|'.($update ? '' : 'unique:beneficiario'),
            'dt_nasc' => ($dados['id_pessoa'] == 'F' ? 'required|date|after:"01/01/1900"|before:' . date('Y/m/d') : 'nullable'),
            'dt_nasc_contato' => 'nullable|date|after:"01/01/1900"|before:' . date('Y/m/d'),
            'localidade' => $validar_end,
            'endereco' => $validar_end,
            'endereco_nro' => $validar_end,
            'bairro' => $validar_end,
            'uf' => $validar_end,
            'cep' => $validar_end,

            /*'localidade' => ($dados['cep'] == '99.999-999' ? 'nullable' : 'required'),
            'endereco' => ($dados['cep'] == '99.999-999' ? 'nullable' : 'required'),
            'endereco_nro' => ($dados['cep'] == '99.999-999' ? 'nullable' : 'required'),
            'bairro' => ($dados['cep'] == '99.999-999' ? 'nullable' : 'required'),
            'uf' => ($dados['cep'] == '99.999-999' ? 'nullable' : 'required'),
            'cep' => 'required'*/


        ];
        $validator = Validator::make($dados, $rules);
        $cdBeneficiario = (isset($_POST['cd_beneficiario']) ? $_POST['cd_beneficiario'] : "");

        //regra para validar o CNS
        if ($cdBeneficiario != '' && !validaCNS($_POST['cd_beneficiario'])) {
            $validator->errors()->add('cd_beneficiario', 'CNS inválido.');
            return json_encode(['success' => false, 'erros' => $validator->errors()->all()]);
        }

        if ($validator->fails()) {
            return json_encode(['success' => false, 'erros' => $validator->errors()->all()]);
        } else {
            $tabPessoa = new Pessoa();
            if (!empty($_POST['cd_pessoa'])) {
                $tabPessoa->fill($dados);
                $tabPessoa->where('cd_pessoa', $_POST['cd_pessoa'])->update($tabPessoa->toArray());
                if($dados['id_pessoa'] !== 'J') {
                    DB::table('beneficiario as b')
                        ->join('contrato as c', 'c.cd_contrato', '=', 'b.cd_contrato')
                        ->where('b.cd_pessoa', $_POST['cd_pessoa'])
                        ->where('c.cd_plano', 1)
                        ->update(['b.cd_beneficiario' => $_POST['cd_beneficiario']]);
                    $digital_pessoa = DB::table('pessoa_digital')->where('cd_pessoa',$_POST['cd_pessoa'])->first();
                    if(isset($_POST['impressao_digital']) && $_POST['impressao_digital'] !== '') {
                        if(isset($digital_pessoa)) {
                            DB::table('pessoa_digital')
                                ->where('cd_pessoa', $_POST['cd_pessoa'])
                                ->update(['impressao_digital' => $_POST['impressao_digital']]);
                        }
                        else{
                            DB::table('pessoa_digital')
                                ->insert(["cd_pessoa" => $_POST['cd_pessoa'], 'impressao_digital' => $_POST['impressao_digital']]);
                        }
                    }
                }
                $cdPessoa = $_POST['cd_pessoa'];
            } else {
                $dados['cd_pessoa'] = null;
                $tabPessoa->fill($dados);
                $tabPessoa->save();
                $cdPessoa = DB::table('pessoa')->max('cd_pessoa');
                if($dados['id_pessoa'] !== 'J') {
                    DB::table('contrato')->insert(["cd_pessoa" => $cdPessoa, "cd_plano" => 1]);
                    $cdContrato = DB::table('contrato')->where(["cd_pessoa" => $cdPessoa, "cd_plano" => 1])->first();
                    DB::table('beneficiario')->insert(["cd_pessoa" => $cdPessoa, "cd_contrato" => $cdContrato->cd_contrato, "cd_beneficiario" => $cdBeneficiario]);
                    DB::table('contrato')->insert(["cd_pessoa" => $cdPessoa, "cd_plano" => 0]);
                    $cdContrato = DB::table('contrato')->where(["cd_pessoa" => $cdPessoa, "cd_plano" => 0])->first();
                    DB::table('beneficiario')->insert(["cd_pessoa" => $cdPessoa, "cd_contrato" => $cdContrato->cd_contrato, "cd_beneficiario" => '']);
                    if(isset($_POST['impressao_digital']) && $_POST['impressao_digital'] !== '') {
                        DB::table('pessoa_digital')
                            ->insert(["cd_pessoa" => $cdPessoa, 'impressao_digital' => $_POST['impressao_digital']]);
                    }
                }
            }
            if($_POST['foto_pessoa'] !== ''){
                $this->salvar_imagem($_POST['foto_pessoa'],$cdPessoa,'1');
            }
            return json_encode(['success' => true, 'status' => 'Salvo com sucesso', 'pessoa'=>$cdPessoa]);
        }
    }

    public function preenche_modal_pessoa(Request $request){
        $tp_pessoa = DB::table('pessoa')->where('cd_pessoa', $_POST['cd_pessoa'])->select('id_pessoa')->first();
        if ($tp_pessoa->id_pessoa === 'F'){
            $pessoa = DB::table('pessoa as p')
                ->leftjoin('pessoa_digital as pd', 'p.cd_pessoa', '=', 'pd.cd_pessoa')
                ->join('beneficiario as b', 'p.cd_pessoa', '=', 'b.cd_pessoa')
                ->join('contrato as c', 'c.cd_contrato', '=', 'b.cd_contrato')
                ->where('p.cd_pessoa', '=', $_POST['cd_pessoa'])
                ->where('c.cd_plano', '=', 1)
                ->select('p.*', 'b.cd_beneficiario','pd.impressao_digital')
                ->get();
        } else {
            $pessoa = DB::table('pessoa as p')
                ->where('p.cd_pessoa', '=', $_POST['cd_pessoa'])
                ->get();
        }
        $arquivo = DB::table('arquivos')
            ->where('cd_pessoa',$_POST['cd_pessoa'])
            ->where('tp_arquivo',1)
            ->select('nm_arquivo')
            ->first();
        if (isset($pessoa[0])){
            $array = (array) $pessoa[0];
            $data['pessoa'] = $array;
            if(isset($arquivo))
                $data['pessoa']['nm_arquivo'] = $arquivo->nm_arquivo;
            else
                $data['pessoa']['nm_arquivo'] = null;
        }
        return json_encode(['success' => true, 'pessoa'=>$data['pessoa']]);
    }

    public function buscar_pessoa(Request $request){
        $nome = $request->get('nome');
        $tp_pessoa = $request->get('tp_pessoa');
        if(!is_numeric($nome))
            $data['lista'] = DB::table('pessoa as p')
                ->leftJoin('arquivos as a','a.cd_pessoa','p.cd_pessoa')
                ->where([['p.nm_pessoa','like','%'.$nome.'%'],['p.id_pessoa','=',$tp_pessoa]])
                ->select('p.*','a.nm_arquivo')
                ->orderBy('p.nm_pessoa')
                ->get();
        else
            $data['lista'] = DB::table('beneficiario as b')->join('pessoa as p','p.cd_pessoa','=','b.cd_pessoa')->where('b.cd_beneficiario','=',$nome)->get();
        if (!isset($data->erro)) {
            return json_encode(['success' => true, 'dados' => $data['lista']]);
        } else {
            return json_encode(['success' => false]);
        }
    }

    public function pesquisar_cep(Request $request){
        $uf = somente_alfanumericos(limpar_acentos($request->get('uf')));
        $city = somente_alfanumericos(limpar_acentos($request->get('cidade')));
        $address = somente_alfanumericos(limpar_acentos($request->get('endereco')));

        $zipcodeaddressinfo = zipcodeaddress($uf, $city, $address);
        if ($zipcodeaddressinfo) {
            return json_encode(['success' => true, 'dados' => $zipcodeaddressinfo->getArray()]);
        } else {
            return json_encode(['success' => false, 'mensagem' => 'Erro ao buscar cep do webservice viacep.']);
        }
    }

    public function auto_preencher_endereco(Request $request){
        $cep = $request->get('cep');
        $formated_cep = preg_replace("/[^0-9]/", "", $cep);
        if (preg_match('/^[0-9]{8}?$/', $formated_cep)) {
            $temp = file_get_contents("http://viacep.com.br/ws/$cep/json/");
            $data = json_decode($temp);
            if (!isset($data->erro)) {
                $data->localidade = substr($data->localidade, 0, 40);
                return json_encode(['success' => true, 'dados' => $data]);
            } else {
                return json_encode(['success' => false]);
            }
        } else {
            return json_encode(['success' => false]);
        }
    }

    function salvar_imagem($arquivo, $cd_pessoa, $tp_arquivo){
        $arquivo = str_replace('data:image/png;base64,', '', $arquivo);
        $arquivo = str_replace(' ', '+', $arquivo);
        $fileData = base64_decode($arquivo);

        $dir = storage_path("app/images/pessoas/$cd_pessoa/".arrayPadrao('tipo_arquivo')[$tp_arquivo]."/");
        $nm_arquivo = $cd_pessoa.".png";
        $nm_thumb = $dir.$cd_pessoa.'_thumb.png';
        $arq = $dir.$nm_arquivo;

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        if (file_exists($arq)) {
            unlink($arq);
            if(file_exists($nm_thumb))
                unlink($nm_thumb);
            DB::table('arquivos')->update(["updated_at" => Carbon::now()]);
        }
        else{
            DB::table('arquivos')->insert(["tp_arquivo" => $tp_arquivo, "cd_pessoa"=>$cd_pessoa, "nm_arquivo"=>$nm_arquivo]);
        }
        file_put_contents($arq, $fileData);
       /* if($tp_arquivo == 1){
            $image_resize = Image::make($fileData);
            $image_resize->resize(90, 45);
            $image_resize->save($nm_thumb);
        }*/
        return json_encode(['success' => true]);
    }

}