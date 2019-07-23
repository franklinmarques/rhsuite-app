<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiAlocadosTotalizacao extends Entity
{
    protected $id;
    protected $id_alocado;
    protected $periodo;
    protected $total_dias_mes1;
    protected $total_dias_mes2;
    protected $total_dias_mes3;
    protected $total_dias_mes4;
    protected $total_dias_mes5;
    protected $total_dias_mes6;
    protected $total_dias_mes7;
    protected $total_dias_sub1;
    protected $total_dias_sub2;
    protected $total_horas_mes1;
    protected $total_horas_mes2;
    protected $total_horas_mes3;
    protected $total_horas_mes4;
    protected $total_horas_mes5;
    protected $total_horas_mes6;
    protected $total_horas_mes7;
    protected $total_horas_sub1;
    protected $total_horas_sub2;
    protected $horas_descontadas_mes1;
    protected $horas_descontadas_mes2;
    protected $horas_descontadas_mes3;
    protected $horas_descontadas_mes4;
    protected $horas_descontadas_mes5;
    protected $horas_descontadas_mes6;
    protected $horas_descontadas_mes7;
    protected $horas_descontadas_sub1;
    protected $horas_descontadas_sub2;
    protected $data_aprovacao_mes1;
    protected $data_aprovacao_mes2;
    protected $data_aprovacao_mes3;
    protected $data_aprovacao_mes4;
    protected $data_aprovacao_mes5;
    protected $data_aprovacao_mes6;
    protected $data_aprovacao_mes7;
    protected $data_aprovacao_sub1;
    protected $data_aprovacao_sub2;
    protected $data_impressao_mes1;
    protected $data_impressao_mes2;
    protected $data_impressao_mes3;
    protected $data_impressao_mes4;
    protected $data_impressao_mes5;
    protected $data_impressao_mes6;
    protected $data_impressao_mes7;
    protected $data_impressao_sub1;
    protected $data_impressao_sub2;
    protected $total_horas_faturadas_mes1;
    protected $total_horas_faturadas_mes2;
    protected $total_horas_faturadas_mes3;
    protected $total_horas_faturadas_mes4;
    protected $total_horas_faturadas_mes5;
    protected $total_horas_faturadas_mes6;
    protected $total_horas_faturadas_mes7;
    protected $total_horas_faturadas_sub1;
    protected $total_horas_faturadas_sub2;
    protected $valor_pagamento_mes1;
    protected $valor_pagamento_mes2;
    protected $valor_pagamento_mes3;
    protected $valor_pagamento_mes4;
    protected $valor_pagamento_mes5;
    protected $valor_pagamento_mes6;
    protected $valor_pagamento_mes7;
    protected $valor_pagamento_sub1;
    protected $valor_pagamento_sub2;
    protected $valor_total_mes1;
    protected $valor_total_mes2;
    protected $valor_total_mes3;
    protected $valor_total_mes4;
    protected $valor_total_mes5;
    protected $valor_total_mes6;
    protected $valor_total_mes7;
    protected $valor_total_sub1;
    protected $valor_total_sub2;

    protected $casts = [
        'id' => 'int',
        'id_alocado' => 'int',
        'periodo' => '?int',
        'total_dias_mes1' => '?int',
        'total_dias_mes2' => '?int',
        'total_dias_mes3' => '?int',
        'total_dias_mes4' => '?int',
        'total_dias_mes5' => '?int',
        'total_dias_mes6' => '?int',
        'total_dias_mes7' => '?int',
        'total_dias_sub1' => '?int',
        'total_dias_sub2' => '?int',
        'total_horas_mes1' => '?string',
        'total_horas_mes2' => '?string',
        'total_horas_mes3' => '?string',
        'total_horas_mes4' => '?string',
        'total_horas_mes5' => '?string',
        'total_horas_mes6' => '?string',
        'total_horas_mes7' => '?string',
        'total_horas_sub1' => '?string',
        'total_horas_sub2' => '?string',
        'horas_descontadas_mes1' => '?string',
        'horas_descontadas_mes2' => '?string',
        'horas_descontadas_mes3' => '?string',
        'horas_descontadas_mes4' => '?string',
        'horas_descontadas_mes5' => '?string',
        'horas_descontadas_mes6' => '?string',
        'horas_descontadas_mes7' => '?string',
        'horas_descontadas_sub1' => '?string',
        'horas_descontadas_sub2' => '?string',
        'data_aprovacao_mes1' => '?datetime',
        'data_aprovacao_mes2' => '?datetime',
        'data_aprovacao_mes3' => '?datetime',
        'data_aprovacao_mes4' => '?datetime',
        'data_aprovacao_mes5' => '?datetime',
        'data_aprovacao_mes6' => '?datetime',
        'data_aprovacao_mes7' => '?datetime',
        'data_aprovacao_sub1' => '?datetime',
        'data_aprovacao_sub2' => '?datetime',
        'data_impressao_mes1' => '?datetime',
        'data_impressao_mes2' => '?datetime',
        'data_impressao_mes3' => '?datetime',
        'data_impressao_mes4' => '?datetime',
        'data_impressao_mes5' => '?datetime',
        'data_impressao_mes6' => '?datetime',
        'data_impressao_mes7' => '?datetime',
        'data_impressao_sub1' => '?datetime',
        'data_impressao_sub2' => '?datetime',
        'total_horas_faturadas_mes1' => '?string',
        'total_horas_faturadas_mes2' => '?string',
        'total_horas_faturadas_mes3' => '?string',
        'total_horas_faturadas_mes4' => '?string',
        'total_horas_faturadas_mes5' => '?string',
        'total_horas_faturadas_mes6' => '?string',
        'total_horas_faturadas_mes7' => '?string',
        'total_horas_faturadas_sub1' => '?string',
        'total_horas_faturadas_sub2' => '?string',
        'valor_pagamento_mes1' => '?float',
        'valor_pagamento_mes2' => '?float',
        'valor_pagamento_mes3' => '?float',
        'valor_pagamento_mes4' => '?float',
        'valor_pagamento_mes5' => '?float',
        'valor_pagamento_mes6' => '?float',
        'valor_pagamento_mes7' => '?float',
        'valor_pagamento_sub1' => '?float',
        'valor_pagamento_sub2' => '?float',
        'valor_total_mes1' => '?float',
        'valor_total_mes2' => '?float',
        'valor_total_mes3' => '?float',
        'valor_total_mes4' => '?float',
        'valor_total_mes5' => '?float',
        'valor_total_mes6' => '?float',
        'valor_total_mes7' => '?float',
        'valor_total_sub1' => '?float',
        'valor_total_sub2' => '?float'
    ];

}
