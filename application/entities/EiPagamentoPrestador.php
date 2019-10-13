<?php

include_once APPPATH . 'entities/Entity.php';

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
	protected $tipo_pagamento_mes1;
	protected $tipo_pagamento_mes2;
	protected $tipo_pagamento_mes3;
	protected $tipo_pagamento_mes4;
	protected $tipo_pagamento_mes5;
	protected $tipo_pagamento_mes6;
	protected $tipo_pagamento_mes7;
	protected $observacoes_mes1;
	protected $observacoes_mes2;
	protected $observacoes_mes3;
	protected $observacoes_mes4;
	protected $observacoes_mes5;
	protected $observacoes_mes6;
	protected $observacoes_mes7;

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
		'data_liberacao_pagto_mes1' => '?date',
		'data_liberacao_pagto_mes2' => '?date',
		'data_liberacao_pagto_mes3' => '?date',
		'data_liberacao_pagto_mes4' => '?date',
		'data_liberacao_pagto_mes5' => '?date',
		'data_liberacao_pagto_mes6' => '?date',
		'data_liberacao_pagto_mes7' => '?date',
		'data_inicio_contrato_mes1' => '?date',
		'data_inicio_contrato_mes2' => '?date',
		'data_inicio_contrato_mes3' => '?date',
		'data_inicio_contrato_mes4' => '?date',
		'data_inicio_contrato_mes5' => '?date',
		'data_inicio_contrato_mes6' => '?date',
		'data_inicio_contrato_mes7' => '?date',
		'data_termino_contrato_mes1' => '?date',
		'data_termino_contrato_mes2' => '?date',
		'data_termino_contrato_mes3' => '?date',
		'data_termino_contrato_mes4' => '?date',
		'data_termino_contrato_mes5' => '?date',
		'data_termino_contrato_mes6' => '?date',
		'data_termino_contrato_mes7' => '?date',
		'pagamento_proporcional_inicio' => '?int',
		'pagamento_proporcional_termino' => '?int',
		'tipo_pagamento_mes1' => '?int',
		'tipo_pagamento_mes2' => '?int',
		'tipo_pagamento_mes3' => '?int',
		'tipo_pagamento_mes4' => '?int',
		'tipo_pagamento_mes5' => '?int',
		'tipo_pagamento_mes6' => '?int',
		'tipo_pagamento_mes7' => '?int',
		'observacoes_mes1' => '?string',
		'observacoes_mes2' => '?string',
		'observacoes_mes3' => '?string',
		'observacoes_mes4' => '?string',
		'observacoes_mes5' => '?string',
		'observacoes_mes6' => '?string',
		'observacoes_mes7' => '?string'
	];

}
