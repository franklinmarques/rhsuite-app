<?php

include_once APPPATH . 'entities/Entity.php';

class PesquisaAvaliadores extends Entity
{
	protected $id;
	protected $id_pesquisa;
	protected $id_avaliador;
	protected $id_avaliado;
	protected $data_acesso;
	protected $data_finalizacao;
	protected $estilo_personalidade_majoritario;
	protected $estilo_personalidade_secundario;
	protected $laudo_comportamental_padrao;

	protected $casts = [
		'id' => 'int',
		'id_pesquisa' => 'int',
		'id_avaliador' => 'int',
		'id_avaliado' => '?int',
		'data_acesso' => '?datetime',
		'data_finalizacao' => '?datetime',
		'estilo_personalidade_majoritario' => '?string',
		'estilo_personalidade_secundario' => '?string',
		'laudo_comportamental_padrao' => '?string'
	];

}
