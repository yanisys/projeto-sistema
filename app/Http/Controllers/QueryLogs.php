<?php

namespace App\Http\Controllers;

use App\Mail\mailTeste;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr\Array_;


class QueryLogs extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('configuraSessao');
    }

    public function index()
    {
        $data['headerText'] = 'Planos';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Log', 'href' => '#'];

        $results = DB::select(DB::raw('show tables'));

        return view('query_log/lista', $results);
    }

    public function listar()
    {
        //verficaPermissao('recurso.log');

        $data['headerText'] = 'Log';
        $data['breadcrumbs'][] = ['titulo' => 'Início', 'href' => route('home')];
        $data['breadcrumbs'][] = ['titulo' => 'Log', 'href' => route('log/lista')];

        //dd($_REQUEST);

        if ($_REQUEST) {
            $where = [];
            if (!empty($_REQUEST['id_user'])) {
                $where[] = ['id_user', '=', (int)$_REQUEST['id_user']];
            }

            if (!empty($_REQUEST['tabelas'])) {
                $where[] = ['tabela', 'like', '%' . strtoupper($_REQUEST['tabelas']) . '%'];
            }


            if (!empty($_REQUEST['dataInicial'])) {
                $where[] = ['data', '>=', date($_REQUEST['dataInicial'])];
            }

            if (!empty($_REQUEST['dataFinal'])) {
                $where[] = ['data', '<=', date($_REQUEST['dataFinal'])];
            }

            if (!empty($_REQUEST['tipo'])) {
                $where[] = ['tipo', '=', $_REQUEST['tipo']];

            }
            /*
            if (!empty($_REQUEST['campo0'])) {

                $where[] = ['query', 'like', '%' . $_REQUEST['campo0']] . '%';
            }*/

            $data['lista'] = DB::table('query_log')
                ->where($where)
                ->orderBy('data')
                ->paginate(30);
        }

        $data['tabelas'] = DB::select(DB::raw('show tables'));

        //dd($data);
        return view('query_log/lista', $data);
    }

    public function ajaxDesc()
    {
        if (!empty($_REQUEST['nomeTabela'])) {
            $result = DB::select(DB::raw('desc ' . $_REQUEST['nomeTabela']));
        } else {
            $result = null;
        }
        //return json_encode(['success' => true, 'dados' => $result]);
        return ($result);
    }


}