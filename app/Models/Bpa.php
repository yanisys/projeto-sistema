<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Bpa extends Model
{
    public function busca_procedimentos_acolhimento_bpac($dt_inicial, $dt_final){
        return DB::select("
            select am.cd_procedimento,pf.cd_ocupacao, o.nm_ocupacao, pc.nm_procedimento, e.cnes, count(*) as 'total'
            from acolhimento as am
            left join procedimento as pc on pc.cd_procedimento = am.cd_procedimento
            left join prontuario as pr on am.cd_prontuario = pr.cd_prontuario
            left join users as u on u.id = am.id_user
            left join profissional as pf on pf.cd_pessoa = u.cd_pessoa
            left join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
            inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
            where pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  am.classificacao <> 6 and                
                  date(pr.created_at) >= date('" . $dt_inicial . "') and 
                  date(pr.created_at) <= date('" . $dt_final . "')
            group by am.cd_procedimento, pf.cd_ocupacao, o.nm_ocupacao, pc.nm_procedimento, e.cnes");
    }

    public function busca_procedimentos_atendimento_medico_bpac($dt_inicial, $dt_final){
        return DB::select("
            select am.cd_procedimento, pf.cd_ocupacao, o.nm_ocupacao, pc.nm_procedimento, e.cnes, count(*) as 'total'
            from atendimento_medico as am
            left join procedimento as pc on pc.cd_procedimento = am.cd_procedimento
            left join prontuario as pr on am.cd_prontuario = pr.cd_prontuario
            left join users as u on u.id = am.id_user
            left join profissional as pf on pf.cd_pessoa = u.cd_pessoa
            left join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
            inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
            where pr.status = 'C' and 
                  am.motivo_alta <> 6 and 
                  pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  date(pr.created_at) >= date('" . $dt_inicial . "') and 
                  date(pr.created_at) <= date('" . $dt_final . "')
            group by am.cd_procedimento, pf.cd_ocupacao, o.nm_ocupacao, pc.nm_procedimento, e.cnes");
    }

    public function busca_procedimentos_bpac($dt_inicial, $dt_final){
        return DB::select("
            select am.cd_procedimento, pf.cd_ocupacao, o.nm_ocupacao, pc.nm_procedimento, e.cnes, count(*) as 'total'
            from atendimento_procedimento as am
            inner join procedimento as pc on pc.cd_procedimento = am.cd_procedimento
            inner join prontuario as pr on am.cd_prontuario = pr.cd_prontuario
            inner join users as u on u.id = am.id_user_executante
            inner join profissional as pf on pf.cd_pessoa = u.cd_pessoa
            inner join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
            inner join rl_procedimento_registro as tpr on am.cd_procedimento = tpr.cd_procedimento
            inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
            where tpr.cd_registro = 1 and 
                  am.id_status = 'C' and
                  pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  date(am.dt_hr_execucao) >= date('" . $dt_inicial . "') and 
                  date(am.dt_hr_execucao) <= date('" . $dt_final . "')
            group by am.cd_procedimento, pf.cd_ocupacao, o.nm_ocupacao, pc.nm_procedimento, e.cnes");
    }

    public function busca_procedimentos_nao_lancados_acolhimento_bpac($dt_inicial,$dt_final,$procedimento,$nome,$codigo){
        $retorno =  DB::select("
            select am.cd_procedimento, pf.cd_ocupacao, o.nm_ocupacao, pc.nm_procedimento,e.cnes, 
            sum(case when am.$procedimento is not null THEN 1 ELSE 0 END) AS 'total'
            from acolhimento as am
            inner join procedimento as pc on pc.cd_procedimento = am.cd_procedimento
            inner join prontuario as pr on am.cd_prontuario = pr.cd_prontuario
            inner join users as u on u.id = am.id_user
            inner join profissional as pf on pf.cd_pessoa = u.cd_pessoa
            inner join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
            inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
            where pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  date(pr.created_at) >= date('" . $dt_inicial . "') and 
                  date(pr.created_at) <= date('" . $dt_final . "') 
            group by am.cd_procedimento, pf.cd_ocupacao, o.nm_ocupacao, pc.nm_procedimento, e.cnes");
        if(isset($retorno[0])) {
            $retorno[0]->nm_procedimento = $nome;
            $retorno[0]->cd_procedimento = $codigo;
            return $retorno[0];

        }
        else
            return null;
    }

    public function busca_procedimentos_bpaI($dt_inicial, $dt_final){ //ok
        return DB::select("
        select date(ap.dt_hr_execucao) as data_execucao, b_pac.cd_beneficiario as cns_paciente, ap.cd_procedimento, e.cnes, b_prof.cd_beneficiario as cns_profissional, pf.cd_ocupacao,  p_pac.id_sexo, p_pac.cep, c.cd_cid, /*truncate(datediff(date(ap.dt_hr_execucao), p_pac.dt_nasc)/365.25, 0)*/ YEAR(FROM_DAYS(DATEDIFF(date(ap.dt_hr_execucao),p_pac.dt_nasc))) as idade, p_pac.nm_pessoa, p_pac.dt_nasc,
        p_pac.endereco, p_pac.endereco_compl, p_pac.endereco_nro, p_pac.bairro, p_pac.nr_fone1, p_pac.ds_email    
        from atendimento_procedimento as ap
        left join procedimento as pc on pc.cd_procedimento = ap.cd_procedimento
        left join prontuario as pr on ap.cd_prontuario = pr.cd_prontuario
        left join atendimento_avaliacao_cid as avc on avc.cd_prontuario = ap.cd_prontuario and avc.cid_principal = 'S'
        left join cid as c on c.id_cid = avc.id_cid
        inner join users as u_prof on u_prof.id = ap.id_user_executante
        inner join pessoa as p_prof on p_prof.cd_pessoa = u_prof.cd_pessoa
        inner join beneficiario as b_prof on b_prof.cd_pessoa = p_prof.cd_pessoa
        inner join contrato as c_prof on c_prof.cd_contrato = b_prof.cd_contrato and c_prof.cd_plano = 1
        inner join profissional as pf on pf.cd_pessoa = u_prof.cd_pessoa
        inner join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
        inner join beneficiario as b_pac on b_pac.id_beneficiario = pr.id_beneficiario
        inner join contrato as c_pac on c_pac.cd_contrato = b_pac.cd_contrato and c_pac.cd_plano = 1
        inner join pessoa as p_pac on p_pac.cd_pessoa = b_pac.cd_pessoa
        inner join rl_procedimento_registro as tpr on ap.cd_procedimento = tpr.cd_procedimento
        inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
        where tpr.cd_registro = 2 and 
              ap.id_status = 'C' and
              pr.cd_estabelecimento = ".session()->get('estabelecimento')." and                
              date(ap.dt_hr_execucao) >= date('$dt_inicial') and 
              date(ap.dt_hr_execucao) <= date('$dt_final')");
    }

    public function busca_procedimentos_acolhimento_bpaI($dt_inicial, $dt_final){ //ok
        return DB::select("
            select date(ac.created_at) as data_execucao, b_pac.cd_beneficiario as cns_paciente, ac.cd_procedimento, e.cnes, b_prof.cd_beneficiario as cns_profissional, pf.cd_ocupacao,  p_pac.id_sexo, p_pac.cep, c.cd_cid, truncate(datediff(date(ac.created_at), p_pac.dt_nasc)/365.25, 0) as idade, p_pac.nm_pessoa, p_pac.dt_nasc,
            p_pac.endereco, p_pac.endereco_compl, p_pac.endereco_nro, p_pac.bairro, p_pac.nr_fone1, p_pac.ds_email    
            from acolhimento as ac
            left join procedimento as pc on pc.cd_procedimento = ac.cd_procedimento
            left join prontuario as pr on ac.cd_prontuario = pr.cd_prontuario
            left join atendimento_avaliacao_cid as avc on avc.cd_prontuario = ac.cd_prontuario and avc.cid_principal = 'S'
            left join cid as c on c.id_cid = avc.id_cid
            left join users as u_prof on u_prof.id = ac.id_user
            inner join pessoa as p_prof on p_prof.cd_pessoa = u_prof.cd_pessoa
            inner join beneficiario as b_prof on b_prof.cd_pessoa = p_prof.cd_pessoa
            inner join contrato as c_prof on c_prof.cd_contrato = b_prof.cd_contrato and c_prof.cd_plano = 1
            inner join profissional as pf on pf.cd_pessoa = u_prof.cd_pessoa
            inner join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
            inner join beneficiario as b_pac on b_pac.id_beneficiario = pr.id_beneficiario
            inner join contrato as c_pac on c_pac.cd_contrato = b_pac.cd_contrato and c_pac.cd_plano = 1
            inner join pessoa as p_pac on p_pac.cd_pessoa = b_pac.cd_pessoa
            inner join rl_procedimento_registro as tpr on ac.cd_procedimento = tpr.cd_procedimento
            inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
            where tpr.cd_registro = 2 and 
                  pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  ac.classificacao <> 6 and   
                  date(ac.created_at) >= date('" . $dt_inicial . "') and 
                  date(ac.created_at) <= date('" . $dt_final . "')");
    }

    public function busca_procedimentos_atendimento_medico_bpaI($dt_inicial, $dt_final){ //ok
        return DB::select("
            select date(am.created_at) as data_execucao, b_pac.cd_beneficiario as cns_paciente, am.cd_procedimento, e.cnes, b_prof.cd_beneficiario as cns_profissional, pf.cd_ocupacao,  p_pac.id_sexo, p_pac.cep, c.cd_cid, truncate(datediff(date(am.created_at), p_pac.dt_nasc)/365.25, 0) as idade, p_pac.nm_pessoa, p_pac.dt_nasc,
            p_pac.endereco, p_pac.endereco_compl, p_pac.endereco_nro, p_pac.bairro, p_pac.nr_fone1, p_pac.ds_email
            from atendimento_medico as am
            inner join procedimento as pc on pc.cd_procedimento = am.cd_procedimento
            inner join prontuario as pr on am.cd_prontuario = pr.cd_prontuario
            left join atendimento_avaliacao_cid as avc on avc.cd_prontuario = am.cd_prontuario and avc.cid_principal = 'S'
            left join cid as c on c.id_cid = avc.id_cid
            inner join users as u_prof on u_prof.id = am.id_user
            inner join pessoa as p_prof on p_prof.cd_pessoa = u_prof.cd_pessoa
            inner join beneficiario as b_prof on b_prof.cd_pessoa = p_prof.cd_pessoa
            inner join contrato as c_prof on c_prof.cd_contrato = b_prof.cd_contrato and c_prof.cd_plano = 1
            inner join profissional as pf on pf.cd_pessoa = u_prof.cd_pessoa
            inner join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
            inner join beneficiario as b_pac on b_pac.id_beneficiario = pr.id_beneficiario
            inner join contrato as c_pac on c_pac.cd_contrato = b_pac.cd_contrato and c_pac.cd_plano = 1
            inner join pessoa as p_pac on p_pac.cd_pessoa = b_pac.cd_pessoa
            inner join rl_procedimento_registro as tpr on am.cd_procedimento = tpr.cd_procedimento
            inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
            where tpr.cd_registro = 2 and 
                  pr.status = 'C' and 
                  am.motivo_alta <> 6 and 
                  pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  date(am.created_at) >= date('" . $dt_inicial . "') and 
                  date(am.created_at) <= date('" . $dt_final . "')");
    }

    public function busca_procedimentos_nao_lancados_acolhimento_bpaI($dt_inicial,$dt_final,$procedimento,$nome,$codigo){ //ok
        $retorno =  DB::select("
            select date(ac.created_at) as data_execucao, b_pac.cd_beneficiario as cns_paciente, ac.cd_procedimento, e.cnes, b_prof.cd_beneficiario as cns_profissional, pf.cd_ocupacao,  p_pac.id_sexo, p_pac.cep, c.cd_cid, truncate(datediff(date(ac.created_at), p_pac.dt_nasc)/365.25, 0) as idade, p_pac.nm_pessoa, p_pac.dt_nasc,
                p_pac.endereco, p_pac.endereco_compl, p_pac.endereco_nro, p_pac.bairro, p_pac.nr_fone1, p_pac.ds_email
            from acolhimento as ac
            inner join procedimento as pc on pc.cd_procedimento = ac.cd_procedimento
            inner join prontuario as pr on ac.cd_prontuario = pr.cd_prontuario
            left join atendimento_avaliacao_cid as avc on avc.cd_prontuario = ac.cd_prontuario and avc.cid_principal = 'S'
            left join cid as c on c.id_cid = avc.id_cid
            inner join users as u_prof on u_prof.id = ac.id_user
            inner join pessoa as p_prof on p_prof.cd_pessoa = u_prof.cd_pessoa
            inner join beneficiario as b_prof on b_prof.cd_pessoa = p_prof.cd_pessoa
            inner join contrato as c_prof on c_prof.cd_contrato = b_prof.cd_contrato and c_prof.cd_plano = 1
            inner join profissional as pf on pf.cd_pessoa = u_prof.cd_pessoa
            inner join ocupacao as o on pf.cd_ocupacao = o.cd_ocupacao
            inner join beneficiario as b_pac on b_pac.id_beneficiario = pr.id_beneficiario
            inner join contrato as c_pac on c_pac.cd_contrato = b_pac.cd_contrato and c_pac.cd_plano = 1
            inner join pessoa as p_pac on p_pac.cd_pessoa = b_pac.cd_pessoa
            inner join rl_procedimento_registro as tpr on ac.cd_procedimento = tpr.cd_procedimento
            inner join estabelecimentos as e on e.cd_estabelecimento = pr.cd_estabelecimento
            where tpr.cd_registro = 2 and 
                  pr.cd_estabelecimento = " . session()->get('estabelecimento') . " and                
                  date(ac.created_at) >= date('" . $dt_inicial . "') and 
                  date(ac.created_at) <= date('" . $dt_final . "') and
                  ac.$procedimento is not null");
        if(isset($retorno[0])) {
            foreach($retorno as $r)
                $r->cd_procedimento = $codigo;
            return $retorno;

        }
        else
            return null;
    }
}
