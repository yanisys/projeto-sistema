<?php

namespace App\Classes;

class objRelatorio{

    /* array de dados que vai sair no relatorio */
    public $dados = [];
    /* titulo do relatório (não obrigatório) */
    public $titulo = '';

    /* array de campos para totalizar no fim da tabela*/
    public $totalizar = [];
    public $totalizar_nome = 'Total'; // texto que fica na primeira celula da linha do total,
                                      // pode ficar em branco caso precise totalizar a primeira celula
    //
    /* array de campos para quebrar por grupo (não implementado)*/
    public $agrupar = '';

    /*limitar nr de linhas e somar as restantes em uma linha só chamada Outros*/
    public $limite = 0; // quantas linhas vai mostrar
    public $limite_campos = []; // array com quais campos vão ser agrupados na linha do 'Outros'
    public $limite_nome = 'Outros'; // texto que fica na primeira celula da linha após o limite,
                                    // pode ficar em branco caso precise somar na primeira celula

    /* quebrar a pagina */
    public $quebrar_pagina = false;

    public function __construct($titulo){
        $this->titulo = $titulo;
    }


}
