<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class API extends Controller{

    public function __construct(){
    }

    public function setStatusPessoa(Request $request){
        if (!isset($request->ativos)){
            echo "success";
        } else {
            $arrInativos = explode(',',$request->inativos);
            $arrAtivos = explode(',',$request->ativos);
            DB::table('beneficiario')->whereIn('cd_contrato', $arrInativos)->update(['id_situacao'=>'I']);
            DB::table('beneficiario')->whereIn('cd_contrato', $arrAtivos)->update(['id_situacao'=>'A']);
            echo 'Sincronização feita com sucesso! ';
            /*echo " ";
            echo 'Contratos Ativos: ' . $request->ativos;
            echo " ";
            echo 'Contratos Inativos: ' . $request->inativos;*/
        }
    }

    public function delete(Request $request){
        $error ='';
        $system_message='';
        if (empty($request->get('tabela'))){
            $error += 'parametro tabela esta vazio ';
        }
        if (empty($request->get('chave'))){
            $error += 'parametro chave esta vazio ';
        }
        if (empty($request->get('valor'))){
            $error += 'parametro valor esta vazio ';
        }
        if (empty($error)) {
            try {
                $tabela = $request->get('tabela');
                $chave  = $request->get('chave');
                $valor  = $request->get('valor');
                DB::table($tabela)->where($chave,'=',$valor)->delete();
            } catch (\Exception $e) {
                if(str_contains($e->getMessage(),'foreign key constraint')) {
                    $error = 'Não é possivel deletar! Existem registros relacionados!';
                } else {
                    $error = 'Não é possivel deletar!';
                    Log::error("Exception: API.delete(tabela='$tabela',chave='$chave',valor='$valor') => ".$e->getMessage());
                }
                $system_message = 'Exception: '.  $e->getMessage();
            }
        }
        echo json_encode([
            'success'=>(empty($error) ? true : false),
            'mensagem'=>$error,
            'system message'=>$system_message
        ]);
    }
}
