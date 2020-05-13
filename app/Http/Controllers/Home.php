<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Home extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('configuraSessao');
        $this->middleware('requestFilter');
    }

    public function index(){
        $data['headerText'] = 'YaniSys | Sistema de Gestão em Saúde';
        $data['breadcrumbs'][] = ['titulo' => 'Olá '. Session('nome'),'href' => route('home')];

        return view('home', $data);
    }

}
