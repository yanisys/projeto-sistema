<?php

namespace App\Http\Controllers;

use App\Models\Estabelecimento;
use App\Models\Pessoa;
use App\Models\User;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Classes\objRelatorio;

class Teste extends Controller
{
    public function teste(){

        //echo (get_config(1,1));
        set_config(2,10,'S');
    }

    public function errorlog(){
        echo "<pre>";
        if (Storage::disk('logs')->exists('error.log')) {
            echo Storage::disk('logs')->get('error.log');
        } else {
            echo "error.log not found";
        }
        echo "</pre>";
    }

    public function fullerrorlog(){
        echo "<pre>";
        if (Storage::disk('logs')->exists('fullerror.log')) {
            echo Storage::disk('logs')->get('fullerror.log');
        } else {
            echo "fullerror.log not found";
        }
        echo "</pre>";
    }
}
