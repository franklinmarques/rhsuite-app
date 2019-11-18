<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_postos_model extends MY_Model
{
	protected static $table = 'alocacao_postos';

	protected $validationRules = [
		'id' => ['required', 'is_natural_no_zero', 'max_length[11]'],
		'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
		'data' => 'required|valid_date',
		'depto' => 'max_length[255]',
		'area' => 'max_length[255]',
		'setor' => 'max_length[255]',
		'cargo' => 'max_length[255]',
		'funcao' => 'max_length[255]',
		'contrato' => 'max_length[255]',
		'total_dias_mensais' => 'required|integer|max_length[11]',
		'total_horas_diarias' => 'required|integer|max_length[11]',
		'matricula' => 'max_length[255]',
		'login' => 'max_length[255]',
		'horario_entrada' => 'valid_time',
		'horario_saida' => 'valid_time',
		'valor_posto' => 'required|numeric|max_length[11]',
		'valor_dia' => 'required|numeric|max_length[11]',
		'valor_hora' => 'required|numeric|max_length[11]'
	];

	protected $validationMessages = [
		'posto_unico' => 'Os dados são idênticos aos do posto anterior deste(a) Colaborador(a).'
	];

	//==========================================================================
	public function __construct()
	{
		parent::__construct();

		$this->validationRules['id'][] = ['posto_unico', function () {
			return $this->validarPostoUnico();
		}];
	}

	//==========================================================================
	private function validarPostoUnico(): bool
	{
		$data = $this->form_validation->validation_data;

		$postoAnterior = $this->db
			->select('id')
			->where('id !=', $data['id'])
			->where('id_usuario', $data['id_usuario'])
			->order_by('data', 'desc')
			->limit(1)
			->get(self::$table)
			->row();

		if (empty($postoAnterior)) {
			return true;
		}

		unset($data[self::$primaryKey], $data['mes'], $data['ano']);

		return $this->db
				->where('id', $postoAnterior->id)
				->where($data)
				->order_by('data', 'desc')
				->get(self::$table)
				->num_rows() == 0;
	}

}
