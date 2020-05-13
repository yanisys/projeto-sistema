<?php
/*-------------------------------------------------------------------------
| FUNÇÃO: arrayPadrao
| DESCRIÇÃO: Retorna um array do tipo passado por parametro
| -PARAMETRO $tipo:       Tipo de dados que será retornado
| -PARAMETRO $opcaoTodos: Inclui a opção 'T' => 'Todos' na primeira posição
|                         do array retornado
|--------------------------------------------------------------------------*/
function arrayPadrao($tipo,$opcaoTodos = false){
    $retorno = array();
    if ($tipo == 'nacionalidade') {
        $retorno = [
            'Brasileiro' => 'Brasileiro',
            'Brasileiro Naturalizado' => 'Brasileiro Naturalizado',
            'Estrangeiro' => 'Estrangeiro'
        ];
    }
    if ($tipo == 'raca_cor') {
        $retorno = [
            '99' => 'NÃO INFORMADA',
            '1' => 'BRANCA',
            '2' => 'PRETA',
            '3' => 'PARDA',
            '4' => 'AMARELA',
            '5' => 'INDÍGENA',
        ];
    }
    if ($tipo == 'situacao') {
        $retorno = [
            'A'=>'Ativo',
            'I'=>'Inativo'
        ];
    }
    if ($tipo == 'sexo') {
        $retorno = [
            'M'=>'Masculino',
            'F'=>'Feminino'
        ];
    }
    if ($tipo == 'id_pessoa') {
        $retorno = [
            'F'=>'Pessoa Física',
            'J'=>'Pessoa Jurídica'
        ];
    }

    if ($tipo == 'etnias') {
        $etnias = DB::table('etnias')->get();
        foreach ($etnias as $e)
            $retorno[$e->cd_etnia] = $e->ds_etnia;
    }

    if ($tipo == 'nacionalidades') {
        $nacionalidades= DB::table('nacionalidade')->get();
        foreach ($nacionalidades as $n)
            $retorno[$n->cd_nacionalidade] = $n->ds_nacionalidade;
    }

    if ($tipo == 'uf') {
        $retorno = [
            'AC'=>'Acre',
            'AL'=>'Alagoas',
            'AP'=>'Amapá',
            'AM'=>'Amazonas',
            'BA'=>'Bahia',
            'CE'=>'Ceará',
            'DF'=>'Distrito Federal',
            'ES'=>'Espírito Santo',
            'GO'=>'Goiás',
            'MA'=>'Maranhão',
            'MT'=>'Mato Grosso',
            'MS'=>'Mato Grosso do Sul',
            'MG'=>'Minas Gerais',
            'PA'=>'Pará',
            'PB'=>'Paraíba',
            'PR'=>'Paraná',
            'PE'=>'Pernambuco',
            'PI'=>'Piauí',
            'RJ'=>'Rio de Janeiro',
            'RN'=>'Rio Grande do Norte',
            'RS'=>'Rio Grande do Sul',
            'RO'=>'Rondônia',
            'RR'=>'Roraima',
            'SC'=>'Santa Catarina',
            'SP'=>'São Paulo',
            'SE'=>'Sergipe',
            'TO'=>'Tocantins'
        ];
    }

    if ($tipo == 'resposta_verbal') {
        $retorno = [
            '0' => 'Não avaliado',
            '1' => 'Nenhuma',
            '2' => 'Palavras incompreensíveis',
            '3' => 'Palavras inapropriadas',
            '4' => 'Confusa',
            '5' => 'Orientada'
        ];
    }
    if ($tipo == 'resposta_motora') {
        $retorno = [
            '0' => 'Não avaliado',
            '1' => 'Nenhuma',
            '2' => 'Extensão anormal',
            '3' => 'Flexão anormal',
            '4' => 'Movimento de retirada',
            '5' => 'Localiza dor',
            '6' => 'Obedece comandos'
        ];
    }
    if ($tipo == 'abertura_ocular') {
        $retorno = [
            '0' => 'Não avaliado',
            '1' => 'Nenhuma',
            '2' => 'À dor',
            '3' => 'À voz',
            '4' => 'Espontânea'
        ];
    }
    if ($tipo == 'classificar_risco') {
        $retorno = [
            '1' => 'NÃO URGENTE',
            '2' => 'POUCO URGENTE',
            '3' => 'URGENTE',
            '4' => 'MUITO URGENTE',
            '5' => 'EMERGÊNCIA',
            '6' => 'DESISTÊNCIA/ EVASÃO',
            '7' => 'CONSULTA DE ENFERMAGEM',
            '8' => 'PACIENTE LIBERADO'
        ];
    }
    if ($tipo == 'paineis') {
        $retorno = [
            '1' => 'PAINEL 1'/*,
            '2' => 'PAINEL 2',
            '3' => 'PAINEL 3'*/
        ];
    }
    if ($tipo == 'situacao_prontuario') {
        $retorno = [
            'A' => 'Abertos',
            'C' => 'Encerrados',
            'T' => 'Todos'
        ];
    }
    if ($tipo == 'tipo_plano') {
        $retorno = [
            'P' => 'PARTICULAR',
            'N' => 'NICOLA',
            'S' => 'SUS',
            'O' => 'OUTROS PLANOS'
        ];
    }
    if ($tipo == 'tipo_estabelecimento') {
        $retorno = [
            'A' => 'ADMINISTRAÇÃO',
            'U' => 'UPA',
            'H' => 'HOSPITAL',
            'P' => 'POSTO DE SAÚDE',
            'C' => 'CLÍNICA'
        ];
    }
    if ($tipo == 'profissao') {
        $retorno = [
            '1' => 'DENTISTA',
            '2' => 'ENFERMEIRO',
            '3' => 'FARMACÊUTICO',
            '4' => 'MÉDICO',
            '5' => 'TÉCNICO DE ENFERMAGEM',
            '6' => 'TÉCNICO EM RADIOLOGIA'
        ];
    }
    if ($tipo == 'parentesco') {
        $retorno = [
            '1'  => 'TITULAR',
            '2'  => 'AGREGADO',
            '3'  => 'NETO',
            '4'  => 'IRMÃO',
            '5'  => 'SOGRO',
            '6'  => 'CÔNJUGE',
            '7'  => 'ENTEADO',
            '8'  => 'PAI',
            '9'  => 'MÃE',
            '10' => 'FILHO'
        ];
    }
    if ($tipo == 'tipo_diagnostico') {
        $retorno = [
            'P'  => 'Provisório',
            'D'  => 'Definitivo'
        ];
    }
    if ($tipo == 'opcao') {
        $retorno = [
            'S'  => 'Sim',
            'N'  => 'Não',
            'I'  => 'Não informado',
        ];
    }
    if ($tipo == 'motivo_alta') {
        $retorno = [
            '0'  => 'Não informado',
            '1'  => 'A pedido',
            '2'  => 'Encaminhamento hospitalar',
            '8'  => 'Encaminhamento hospitalar - Traumatologia',
            '3'  => 'Melhora do quadro clínico',
            '4'  => 'Alta após medicaçao',
            '5'  => 'Óbito',
            '6'  => 'Evasão',
            '7'  => 'Liberado após consulta',
        ];
    }
    if ($tipo == 'status_prontuario') {
        $retorno = [
            'E'  => 'Aberto com Alta Após Medicação',
            'C'  => 'Concluído',
            'A'  => 'Aberto'
        ];
    }
    if ($tipo == 'leitos') {
        $retorno = [
            '0' => 'NÃO INFORMADO',
            '1' => 'LEITO 1',
            '2' => 'LEITO 2',
            '3' => 'LEITO 3',
            '4' => 'LEITO 4',
            '5' => 'LEITO 5',
            '6' => 'LEITO 6',
            '7' => 'LEITO 7',
            '8' => 'LEITO 8',
            '9' => 'LEITO 9',
            '10' => 'LEITO 10'
        ];
    }
    if ($tipo == 'mes') {
        $retorno = [
            '01' => 'JANEIRO',
            '02' => 'FEVEREIRO',
            '03' => 'MARÇO',
            '04' => 'ABRIL',
            '05' => 'MAIO',
            '06' => 'JUNHO',
            '07' => 'JULHO',
            '08' => 'AGOSTO',
            '09' => 'SETEMBRO',
            '10' => 'OUTUBRO',
            '11' => 'NOVEMBRO',
            '12' => 'DEZEMBRO'
        ];
    }

    if ($tipo == 'tipo_arquivo') {
        $retorno = [
            '1' => 'principal',
            '2' => 'radiografia',
        ];
    }

    if ($tipo == 'via') {
        $retorno = [
            '1' => 'Via Oral',
            '2' => 'Subcutânea',
            '3' => 'Intramuscular',
            '4' => 'Endovenoso',
            '5' => 'Intradérmica',
            '6' => 'Sublingual',
            '7' => 'Tópico',
            '8' => 'Otológico',
            '9' => 'Retal',
            '10' => 'Vaginal',
            '11' => 'Ocular',
        ];
    }

    if ($tipo == 'via_dieta') {
        $retorno = [
            '1' => 'Via Oral',
            '2' => 'Nutrição Parental',
            '3' => 'Nutrição Enteral'
        ];
    }

    if ($tipo == 'embalagem_medicamento') {
        $retorno = [
            '1' => 'Caixa',
            '2' => 'Cartela',
            '3' => 'Bisnaga',
            '4' => 'Frasco',
            '5' => 'Ampola',
        ];
    }

    if ($tipo == 'periodo') {
        $retorno = [
            '1' => 'min',
            '2' => 'hr',
            '3' => 'dia(s)',
            '4' => 'mês'
        ];
    }

    if ($tipo == 'periodo_plural') {
        $retorno = [
            '1' => 'Minutos',
            '2' => 'Horas',
            '3' => 'Dias',
            '4' => 'Meses'
        ];
    }

    if ($tipo == 'aplicacao') {
        $retorno = [
            '1' => 'Comprimido',
            '2' => 'Jato',
            '3' => 'ml',
            '4' => 'Gota'
        ];
    }

    if ($tipo == 'dieta') {
        $retorno = [
            '1' => 'Livre',
            '2' => 'Branda',
            '3' => 'Líquida',
            '4' => 'Hipossódica',
            '5' => 'Hipercalórica',
            '6' => 'Hiperproteica',
            '7' => 'Hipocalórica',
            '8' => 'Nada por via oral (NPVO)',
            '9' => 'Dm'
        ];
    }

    if ($tipo == 'posicoes_enfermagem') {
        $retorno = [
            '1' => 'Decúbito dorsal ou Supina',
            '2' => 'Decúbito ventral ou Prona',
            '3' => 'Decúbito lateral ou sims',
            '4' => 'Posição de litotômia ou Ginecológica',
            '5' => 'Posição trendelenburg',
            '6' => 'Posição trendelenburg Reverso',
            '7' => 'Posição Fowler ou sentada',
            '8' => 'Posição de canivete'
        ];
    }

    if ($tipo == 'exames_laboratoriais') {
        $retorno = [
            '1' => 'Hemograma',
            '2' => 'Ureia',
            '3' => 'Creatinina',
            '4' => 'Sodio',
            '5' => 'Potássio',
            '6' => 'TGO',
            '7' => 'TGP',
            '8' => 'Amilase',
            '9' => 'CK-MB',
            '10' => 'CK-Total',
            '11' => 'Troponina',
            '12' => 'EQU',
            '13' => 'Bilirrubina Total',
            '14' => 'Bilirrubina Direta',
            '15' => 'Bilirrubina Indireta',
        ];
    }

    if ($tipo == 'administracao_oxigenio') {
        $retorno = [
            '1' => 'Cateter nasal',
            '2' => 'Óculos nasal',
            '3' => 'Máscara de Ventury',
            '4' => 'Máscara de Hudson',
            '5' => 'Ventilação mecânica',
            '6' => 'T. Ayre',
            '7' => 'Estubação',
        ];
    }

    if ($tipo == 'tipo_movimento') {
        $retorno = [
            'C' => 'Compra',
            'V' => 'Venda',
            'E' => 'Entrada',
            'S' => 'Saída'
        ];
    }

    if ($tipo == 'tipo_nota') {
        $retorno = [
            '0' => 'Nenhum',
            '1' => 'NF-e – Nota Fiscal de Produto',
            '2' => 'NFS-e – Nota Fiscal de Serviço',
            '3' => 'CT-e – Conhecimento de Transporte Eletrônico',
            '4' => 'NFC-e – Nota Fiscal ao Consumidor Eletrônica',
            '5' => 'MDF-e – Manifesto de Documentos Fiscais Eletrônicos'
        ];
    }

    if ($tipo == 'tipo_conta') {
        $retorno = [
            'N' => 'Nenhum',
            'P' => 'Contas a pagar',
            'R' => 'Contas a receber'
        ];
    }

    if ($tipo == 'tipo_saldo') {
        $retorno = [
            'N' => '',
            'A' => 'Adicionar',
            'S' => 'Subtrair'
        ];
    }

    if ($tipo == 'ind_forma_pagamento') {
        $retorno = [
            null => '',
            '0' => 'Pagamento à vista',
            '1' => 'Pagamento a prazo',
            '2' => 'Outros'
        ];
    }

    if ($tipo == 'tipo_emissao_nfe') {
        $retorno = [
            null => '',
            '1' => 'Emissão normal (não em contingência)',
            '2' => 'Contingência FS-IA, com impressão do DANFE em formulário de segurança',
            '3' => 'Contingência SCAN (Sistema de Contingência do Ambiente Nacional)',
            '4' => 'Contingência DPEC (Declaração Prévia da Emissão em Contingência)',
            '5' => 'Contingência FS-DA, com impressão do DANFE em formulário de segurança',
            '6' => 'Contingência SVC-AN (SEFAZ Virtual de Contingência do AN)',
            '7' => 'Contingência SVC-RS (SEFAZ Virtual de Contingência do RS)',
            '9' => 'Contingência off-line da NFC-e (as demais opções de contingência são válidas também para a NFC-e). Para a NFC-e somente estão disponíveis e são válidas as opções de contingência 5 e 9'
        ];
    }

    if ($tipo == 'tipo_sala') {
        $retorno = [
            'E' => 'ESTOQUE',
            'C' => 'CENTRO DE CUSTO',
         ];
    }

    if ($tipo == 'finalidade_nfe') {
        $retorno = [
            null => '',
            '1' => 'NF-e normal',
            '2' => 'NF-e complementar',
            '3' => 'NF-e de ajuste',
            '4' => 'Devolução de mercadoria'
        ];
    }

    if ($tipo == 'processo_emissao_nfe') {
        $retorno = [
            null => '',
            '0' => 'Emissão de NF-e com aplicativo do contribuinte',
            '1' => 'Emissão de NF-e avulsa pelo Fisco',
            '2' => 'Emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco',
            '3' => 'Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco'
        ];
    }

    if ($opcaoTodos){
        $retorno = ['T' => 'Todos'] + $retorno;
    }
    return $retorno;
}

function get_versao_sistema()
{
    $versao_sistema = '1.0.1';
    return $versao_sistema;
}
