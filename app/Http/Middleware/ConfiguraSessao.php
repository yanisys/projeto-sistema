<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ConfiguraSessao
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if (empty(session('nome'))) {

            $pessoa = DB::table('pessoa')
                ->where('cd_pessoa','=',Auth::user()->cd_pessoa)
                ->get(['nm_pessoa']);

            $profissional = DB::table('profissional as p')
                ->leftJoin('ocupacao as o','o.cd_ocupacao','=','p.cd_ocupacao')
                ->where('p.cd_pessoa','=',Auth::user()->cd_pessoa)
                ->where('p.status','=','A')
                ->get(['o.nm_ocupacao']);

            if (isset($profissional[0])) {
                session(['profissional' => $profissional[0]->nm_ocupacao]);
            }

            session(['nome' => title_case($pessoa[0]->nm_pessoa)]);
            session(['id_user' => Auth::user()->id]);
        }
        
        if  (empty(session('grupo'))){
            $grupo = DB::table('users')
                ->where('cd_pessoa','=',Auth::user()->cd_pessoa)
                ->get(['cd_grupo_op']);
            
            session(['grupo' => $grupo[0]->cd_grupo_op]);
        }

        if (empty(session('recurso'))){
            $data['recursos'] = DB::table('recurso')
                ->join('permissao', 'permissao.cd_recurso', '=', 'recurso.cd_recurso')
                ->join('users', 'users.cd_grupo_op', '=', 'permissao.cd_grupo_op')
                ->where('cd_pessoa','=',Auth::user()->cd_pessoa)
                ->select('recurso.cd_recurso','recurso.obj_recurso')
                ->get();

            $recurso= array();
            foreach ($data['recursos'] as $r){
                $recurso[$r->obj_recurso] = $r->cd_recurso;
            }
            session()->put('recurso', $recurso);
        }
        $status = ['A'];
        if(!empty(session('recurso.estabelecimentos-inativos')))
            $status = [0 => 'A', 1 => 'I'];

        if (empty(session('estabelecimentos')) || empty(session('estabelecimento'))){
            $data['user_estabelecimentos'] = DB::table('user_estabelecimento as ue')
                ->leftJoin('estabelecimentos as e','e.cd_estabelecimento','=','ue.cd_estabelecimento')
                ->leftJoin('pessoa as p','p.cd_pessoa','=','e.cd_pessoa')
                ->where('ue.id_user','=',session('id_user'))
                ->whereIn('e.status',$status)
                ->select('ue.cd_estabelecimento','p.nm_pessoa','p.localidade','e.cnes')
                ->get();
            if(count($data['user_estabelecimentos']) === 1){
                session()->put('estabelecimento', $data['user_estabelecimentos'][0]->cd_estabelecimento);
                session()->put('nm_estabelecimento', $data['user_estabelecimentos'][0]->nm_pessoa);
                session()->put('cidade_estabelecimento', $data['user_estabelecimentos'][0]->localidade);
                session()->put('cnes_estabelecimento', $data['user_estabelecimentos'][0]->cnes);

            }
            if(count($data['user_estabelecimentos']) === 0) {
                Session::flush();
                Auth::logout();
                return redirect('/home');
            }

            session()->put('estabelecimentos', $data['user_estabelecimentos']);
        }
        if (empty(session('estabelecimento')) && $request->getPathInfo() != '/selecionar_estabelecimento'){
            return redirect('/selecionar_estabelecimento');
        }

        return $next($request);
    }
}