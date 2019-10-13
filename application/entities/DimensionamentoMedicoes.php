<?php

include_once APPPATH . 'entities/Entity.php';

class DimensionamentoMedicoes extends Entity
{
	protected $id;
	protected $id_executor;
	protected $id_etapa;
	protected $tempo_inicio;
	protected $tempo_termino;
	protected $tempo_gasto;
	protected $quantidade;
	protected $tempo_unidade;
	protected $indice_mao_obra;
	protected $complexidade;
	protected $tipo_item;
	protected $medicao_calculada;
	protected $valor_min_calculado;
	protected $valor_medio_calculado;
	protected $valor_max_calculado;
	protected $mao_obra_min_calculada;
	protected $mao_obra_media_calculada;
	protected $mao_obra_max_calculada;
	protected $status;

	protected $casts = [
		'id' => 'int',
		'id_executor' => 'int',
		'id_etapa' => 'int',
		'tempo_inicio' => 'float',
		'tempo_termino' => 'float',
		'tempo_gasto' => '?float',
		'quantidade' => '?float',
		'tempo_unidade' => '?float',
		'indice_mao_obra' => '?float',
		'complexidade' => '?int',
		'tipo_item' => '?int',
		'medicao_calculada' => 'int',
		'valor_min_calculado' => '?float',
		'valor_medio_calculado' => '?float',
		'valor_max_calculado' => '?float',
		'mao_obra_min_calculada' => '?float',
		'mao_obra_media_calculada' => '?float',
		'mao_obra_max_calculada' => '?float',
		'status' => 'int'
	];

}
