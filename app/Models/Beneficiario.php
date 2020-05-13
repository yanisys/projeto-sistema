<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    protected $table = 'beneficiario';
    protected $primaryKey = 'id_beneficiario';
    public $timestamps = false;

    protected $fillable = [
        'cd_contrato',
        'cd_pessoa',
        'cd_beneficiario',
        'parentesco',
        'id_situacao'
    ];
}
