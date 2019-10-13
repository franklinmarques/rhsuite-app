<?php

include_once APPPATH . 'entities/Entity.php';

class EiControleMateriais extends Entity
{
	protected $id;
	protected $id_frequencia;
	protected $id_insumo;
	protected $qtde;

	protected $casts = [
		'id' => 'int',
		'id_frequencia' => 'int',
		'id_insumo' => 'int',
		'qtde' => 'int'
	];

}
