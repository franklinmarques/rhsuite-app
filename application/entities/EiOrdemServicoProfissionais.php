<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiOrdemServicoProfissionais extends Entity
{
    protected $id;
    protected $id_ordem_servico_escola;
    protected $id_usuario;
    protected $id_supervisor;
    protected $id_usuario_sub1;
    protected $data_substituicao1;
    protected $id_usuario_sub2;
    protected $data_substituicao2;
    protected $id_departamento;
    protected $id_area;
    protected $id_setor;
    protected $id_cargo;
    protected $id_funcao;
    protected $municipio;
    protected $valor_hora;
    protected $qtde_dias;
    protected $qtde_semanas;
    protected $horas_diarias;
    protected $horas_semanais;
    protected $horas_mensais;
    protected $horas_semestre;
    protected $desconto_mensal_1;
    protected $desconto_mensal_2;
    protected $desconto_mensal_3;
    protected $desconto_mensal_4;
    protected $desconto_mensal_5;
    protected $valor_hora_operacional;
    protected $valor_mensal_1;
    protected $valor_mensal_2;
    protected $valor_mensal_3;
    protected $valor_mensal_4;
    protected $valor_mensal_5;
    protected $valor_mensal_6;
    protected $desconto_mensal_6;
    protected $valor_hora_mensal;
    protected $desconto_mensal_sub1_1;
    protected $desconto_mensal_sub1_2;
    protected $desconto_mensal_sub1_3;
    protected $desconto_mensal_sub1_4;
    protected $desconto_mensal_sub1_5;
    protected $desconto_mensal_sub1_6;
    protected $desconto_mensal_sub2_1;
    protected $desconto_mensal_sub2_2;
    protected $desconto_mensal_sub2_3;
    protected $desconto_mensal_sub2_4;
    protected $desconto_mensal_sub2_5;
    protected $desconto_mensal_sub2_6;
    protected $horas_mensais_custo;
    protected $data_inicio_contrato;
    protected $data_termino_contrato;

    protected $casts = [
        'id' => 'int',
        'id_ordem_servico_escola' => 'int',
        'id_usuario' => 'int',
        'id_supervisor' => '?int',
        'id_usuario_sub1' => '?int',
        'data_substituicao1' => '?datetime',
        'id_usuario_sub2' => '?int',
        'data_substituicao2' => '?datetime',
        'id_departamento' => '?int',
        'id_area' => '?int',
        'id_setor' => '?int',
        'id_cargo' => '?int',
        'id_funcao' => '?int',
        'municipio' => '?string',
        'valor_hora' => '?float',
        'qtde_dias' => '?float',
        'qtde_semanas' => '?int',
        'horas_diarias' => '?float',
        'horas_semanais' => '?float',
        'horas_mensais' => '?float',
        'horas_semestre' => '?float',
        'desconto_mensal_1' => 'float',
        'desconto_mensal_2' => 'float',
        'desconto_mensal_3' => 'float',
        'desconto_mensal_4' => 'float',
        'desconto_mensal_5' => 'float',
        'valor_hora_operacional' => '?float',
        'valor_mensal_1' => '?float',
        'valor_mensal_2' => '?float',
        'valor_mensal_3' => '?float',
        'valor_mensal_4' => '?float',
        'valor_mensal_5' => '?float',
        'valor_mensal_6' => '?float',
        'desconto_mensal_6' => 'float',
        'valor_hora_mensal' => '?float',
        'desconto_mensal_sub1_1' => 'float',
        'desconto_mensal_sub1_2' => 'float',
        'desconto_mensal_sub1_3' => 'float',
        'desconto_mensal_sub1_4' => 'float',
        'desconto_mensal_sub1_5' => 'float',
        'desconto_mensal_sub1_6' => 'float',
        'desconto_mensal_sub2_1' => 'float',
        'desconto_mensal_sub2_2' => 'float',
        'desconto_mensal_sub2_3' => 'float',
        'desconto_mensal_sub2_4' => 'float',
        'desconto_mensal_sub2_5' => 'float',
        'desconto_mensal_sub2_6' => 'float',
        'horas_mensais_custo' => '?string',
        'data_inicio_contrato' => '?datetime',
        'data_termino_contrato' => '?datetime'
    ];

}
