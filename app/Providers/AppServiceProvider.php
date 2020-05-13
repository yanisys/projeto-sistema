<?php

namespace App\Providers;

use App\Models\QueryLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(query_log_ativo()) {
            Schema::defaultStringLength(200);
            DB::listen(function ($query) {
                $tipo = '';
                $lowerSQL = strtolower($query->sql);
                if (!str_contains($lowerSQL, 'query_log')) {
                    if (str_contains($lowerSQL, 'insert')) {
                        $tipo = 'I';
                        $tabela = $this->nome_tabela("insert into", $lowerSQL);
                    }
                    if (str_contains($lowerSQL, 'update')) {
                        $tipo = 'U';
                        $tabela = $this->nome_tabela("update", $lowerSQL);
                    }
                    if (str_contains($lowerSQL, 'delete')) {
                        $tipo = 'D';
                        $tabela = $this->nome_tabela("delete from", $lowerSQL);
                    }
                    //dd(Auth::user());
                    if (!empty($tipo)) {

                        $sql = vsprintf(str_replace('?', '%s', $query->sql), collect($query->bindings)->map(function ($binding) {
                            return is_numeric($binding) ? $binding : "'{$binding}'";
                        })->toArray());

                        $log = new QueryLog();
                        $log->fill([
                            'id' => null,
                            'id_user' => (!empty(session()->get('id_user')) ? session()->get('id_user') : 0),
                            'tipo' => $tipo,
                            'tabela' => $tabela,
                            //'query' => str_replace('`','',$query->sql) ,
                            'query' => $sql,
                            'params' => print_r($query->bindings, true),
                            //'params' => 'x',
                            'time' => $query->time
                        ]);
                        $log->save();
                    }
                }
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    function nome_tabela($remover,$sql){
        $lowerSQL = str_replace($remover,"",$sql);
        $array = explode(' ', $lowerSQL);
        return (isset($array[1]) ? $array[1] : '');
    }

    public function register()
    {
        //
    }
}
