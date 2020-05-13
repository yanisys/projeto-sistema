<?php


namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest as TransformsRequest;

class RequestFilter extends TransformsRequest
{

    protected $except = [
        'password',
        'password_confirmation',
        'email'
    ];

    protected function transform($key, $value){
        if (in_array($key, $this->except, true)) {
            return $value;
        }

        return is_string($value) ? mb_strtoupper($value,'UTF-8') : $value;
    }
}



