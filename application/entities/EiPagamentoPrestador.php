<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiPagamentoPrestador extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $id_cuidador;
    protected $cuidador;
    protected $cargo;
    protected $funcao;
    protected $nota_fiscal_mes1;
    protected $nota_fiscal_mes2;
    protected $nota_fiscal_mes3;
    protected $nota_fiscal_mes4;
    protected $nota_fiscal_mes5;
    protected $nota_fiscal_mes6;
    protected $nota_fiscal_mes7;
    protected $valor_extra1_mes1;
    protected $valor_extra1_mes2;
    protected $valor_extra1_mes3;
    protected $valor_extra1_mes4;
    protected $valor_extra1_mes5;
    protected $valor_extra1_mes6;
    protected $valor_extra1_mes7;
    protected $valor_extra2_mes1;
    protected $valor_extra2_mes2;
    protected $valor_extra2_mes3;
    protected $valor_extra2_mes4;
    protected $valor_extra2_mes5;
    protected $valor_extra2_mes6;
    protected $valor_extra2_mes7;
    protected $justificativa1_mes1;
    protected $justificativa1_mes2;
    protected $justificativa1_mes3;
    protected $justificativa1_mes4;
    protected $justificativa1_mes5;
    protected $justificativa1_mes6;
    protected $justificativa1_mes7;
    protected $justificativa2_mes1;
    protected $justificativa2_mes2;
    protected $justificativa2_mes3;
    protected $justificativa2_mes4;
    protected $justificativa2_mes5;
    protected $justificativa2_mes6;
    protected $justificativa2_mes7;
    protected $data_liberacao_pagto_mes1;
    protected $data_liberacao_pagto_mes2;
    protected $data_liberacao_pagto_mes3;
    protected $data_liberacao_pagto_mes4;
    protected $data_liberacao_pagto_mes5;
    protected $data_liberacao_pagto_mes6;
    protected $data_liberacao_pagto_mes7;
    protected $data_inicio_contrato_mes1;
    protected $data_inicio_contrato_mes2;
    protected $data_inicio_contrato_mes3;
    protected $data_inicio_contrato_mes4;
    protected $data_inicio_contrato_mes5;
    protected $data_inicio_contrato_mes6;
    protected $data_inicio_contrato_mes7;
    protected $data_termino_contrato_mes1;
    protected $data_termino_contrato_mes2;
    protected $data_termino_contrato_mes3;
    protected $data_termino_contrato_mes4;
    protected $data_termino_contrato_mes5;
    protected $data_termino_contrato_mes6;
    protected $data_termino_contrato_mes7;
    protected $pagamento_proporcional_inicio;
    protected $pagamento_proporcional_termino;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'id_cuidador' => '?int',
        'cuidador' => '?string',
        'cargo' => '?string',
        'funcao' => '?string',
        'nota_fiscal_mes1' => '?string',
        'nota_fiscal_mes2' => '?string',
        'nota_fiscal_mes3' => '?string',
        'nota_fiscal_mes4' => '?string',
        'nota_fiscal_mes5' => '?string',
        'nota_fiscal_mes6' => '?string',
        'nota_fiscal_mes7' => '?string',
        'valor_extra1_mes1' => '?float',
        'valor_extra1_mes2' => '?float',
        'valor_extra1_mes3' => '?float',
        'valor_extra1_mes4' => '?float',
        'valor_extra1_mes5' => '?float',
        'valor_extra1_mes6' => '?float',
        'valor_extra1_mes7' => '?float',
        'valor_extra2_mes1' => '?float',
        'valor_extra2_mes2' => '?float',
        'valor_extra2_mes3' => '?float',
        'valor_extra2_mes4' => '?float',
        'valor_extra2_mes5' => '?float',
        'valor_extra2_mes6' => '?float',
        'valor_extra2_mes7' => '?float',
        'justificativa1_mes1' => '?string',
        'justificativa1_mes2' => '?string',
        'justificativa1_mes3' => '?string',
        'justificativa1_mes4' => '?string',
        'justificativa1_mes5' => '?string',
        'justificativa1_mes6' => '?string',
        'justificativa1_mes7' => '?string',
        'justificativa2_mes1' => '?string',
        'justificativa2_mes2' => '?string',
        'justificativa2_mes3' => '?string',
        'justificativa2_mes4' => '?string',
        'justificativa2_mes5' => '?string',
        'justificativa2_mes6' => '?string',
        'justificativa2_mes7' => '?string',
        'data_liberacao_pagto_mes1' => '?datetime',
        'data_liberacao_pagto_mes2' => '?datetime',
        'data_liberacao_pagto_mes3' => '?datetime',
        'data_liberacao_pagto_mes4' => '?datetime',
        'data_liberacao_pagto_mes5' => '?datetime',
        'data_liberacao_pagto_mes6' => '?datetime',
        'data_liberacao_pagto_mes7' => '?datetime',
        'data_inicio_contrato_mes1' => '?datetime',
        'data_inicio_contrato_mes2' => '?datetime',
        'data_inicio_contrato_mes3' => '?datetime',
        'data_inicio_contrato_mes4' => '?datetime',
        'data_inicio_contrato_mes5' => '?datetime',
        'data_inicio_contrato_mes6' => '?datetime',
        'data_inicio_contrato_mes7' => '?datetime',
        'data_termino_contrato_mes1' => '?datetime',
        'data_termino_contrato_mes2' => '?datetime',
        'data_termino_contrato_mes3' => '?datetime',
        'data_termino_contrato_mes4' => '?datetime',
        'data_termino_contrato_mes5' => '?datetime',
        'data_termino_contrato_mes6' => '?datetime',
        'data_termino_contrato_mes7' => '?datetime',
        'pagamento_proporcional_inicio' => '?int',
        'pagamento_proporcional_termino' => '?int'
    ];

}
