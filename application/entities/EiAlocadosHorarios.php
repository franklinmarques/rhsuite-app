<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiAlocadosHorarios extends Entity
{
    protected $id;
    protected $id_alocado;
    protected $id_os_horario;
    protected $cargo;
    protected $funcao;
    protected $dia_semana;
    protected $periodo;
    protected $horario_inicio_mes1;
    protected $horario_inicio_mes2;
    protected $horario_inicio_mes3;
    protected $horario_inicio_mes4;
    protected $horario_inicio_mes5;
    protected $horario_inicio_mes6;
    protected $horario_inicio_mes7;
    protected $horario_termino_mes1;
    protected $horario_termino_mes2;
    protected $horario_termino_mes3;
    protected $horario_termino_mes4;
    protected $horario_termino_mes5;
    protected $horario_termino_mes6;
    protected $horario_termino_mes7;
    protected $total_horas_mes1;
    protected $total_horas_mes2;
    protected $total_horas_mes3;
    protected $total_horas_mes4;
    protected $total_horas_mes5;
    protected $total_horas_mes6;
    protected $total_horas_mes7;
    protected $total_semanas_mes1;
    protected $total_semanas_mes2;
    protected $total_semanas_mes3;
    protected $total_semanas_mes4;
    protected $total_semanas_mes5;
    protected $total_semanas_mes6;
    protected $total_semanas_mes7;
    protected $desconto_mes1;
    protected $desconto_mes2;
    protected $desconto_mes3;
    protected $desconto_mes4;
    protected $desconto_mes5;
    protected $desconto_mes6;
    protected $desconto_mes7;
    protected $total_mes1;
    protected $total_mes2;
    protected $total_mes3;
    protected $total_mes4;
    protected $total_mes5;
    protected $total_mes6;
    protected $total_mes7;
    protected $id_cuidador_sub1;
    protected $cargo_sub1;
    protected $funcao_sub1;
    protected $data_substituicao1;
    protected $total_semanas_sub1;
    protected $desconto_sub1;
    protected $total_sub1;
    protected $id_cuidador_sub2;
    protected $cargo_sub2;
    protected $funcao_sub2;
    protected $data_substituicao2;
    protected $total_semanas_sub2;
    protected $desconto_sub2;
    protected $total_sub2;
    protected $data_inicio_contrato;
    protected $data_termino_contrato;
    protected $valor_hora_operacional;
    protected $horas_mensais_custo;
    protected $valor_hora_funcao;
    protected $valor_hora_pagamento;
    protected $data_inicio_real;
    protected $data_termino_real;

    protected $casts = [
        'id' => 'int',
        'id_alocado' => 'int',
        'id_os_horario' => '?int',
        'cargo' => '?string',
        'funcao' => '?string',
        'dia_semana' => 'int',
        'periodo' => '?int',
        'horario_inicio_mes1' => '?string',
        'horario_inicio_mes2' => '?string',
        'horario_inicio_mes3' => '?string',
        'horario_inicio_mes4' => '?string',
        'horario_inicio_mes5' => '?string',
        'horario_inicio_mes6' => '?string',
        'horario_inicio_mes7' => '?string',
        'horario_termino_mes1' => '?string',
        'horario_termino_mes2' => '?string',
        'horario_termino_mes3' => '?string',
        'horario_termino_mes4' => '?string',
        'horario_termino_mes5' => '?string',
        'horario_termino_mes6' => '?string',
        'horario_termino_mes7' => '?string',
        'total_horas_mes1' => '?string',
        'total_horas_mes2' => '?string',
        'total_horas_mes3' => '?string',
        'total_horas_mes4' => '?string',
        'total_horas_mes5' => '?string',
        'total_horas_mes6' => '?string',
        'total_horas_mes7' => '?string',
        'total_semanas_mes1' => 'int',
        'total_semanas_mes2' => 'int',
        'total_semanas_mes3' => 'int',
        'total_semanas_mes4' => 'int',
        'total_semanas_mes5' => 'int',
        'total_semanas_mes6' => 'int',
        'total_semanas_mes7' => 'int',
        'desconto_mes1' => '?float',
        'desconto_mes2' => '?float',
        'desconto_mes3' => '?float',
        'desconto_mes4' => '?float',
        'desconto_mes5' => '?float',
        'desconto_mes6' => '?float',
        'desconto_mes7' => '?float',
        'total_mes1' => '?string',
        'total_mes2' => '?string',
        'total_mes3' => '?string',
        'total_mes4' => '?string',
        'total_mes5' => '?string',
        'total_mes6' => '?string',
        'total_mes7' => '?string',
        'id_cuidador_sub1' => '?int',
        'cargo_sub1' => '?string',
        'funcao_sub1' => '?string',
        'data_substituicao1' => '?datetime',
        'total_semanas_sub1' => '?int',
        'desconto_sub1' => '?float',
        'total_sub1' => '?string',
        'id_cuidador_sub2' => '?int',
        'cargo_sub2' => '?string',
        'funcao_sub2' => '?string',
        'data_substituicao2' => '?datetime',
        'total_semanas_sub2' => '?int',
        'desconto_sub2' => '?float',
        'total_sub2' => '?string',
        'data_inicio_contrato' => '?datetime',
        'data_termino_contrato' => '?datetime',
        'valor_hora_operacional' => '?float',
        'horas_mensais_custo' => '?string',
        'valor_hora_funcao' => '?float',
        'valor_hora_pagamento' => '?float',
        'data_inicio_real' => '?datetime',
        'data_termino_real' => '?datetime'
    ];

}
