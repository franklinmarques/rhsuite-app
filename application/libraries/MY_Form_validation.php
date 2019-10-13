<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Form_validation Class
 *
 * Extends Form_Validation library
 *
 */
class MY_Form_validation extends CI_Form_validation
{

	function __construct()
	{
		parent::__construct();
	}

	public function error_array()
	{
		return $this->_error_array;
	}

	// --------------------------------------------------------------------

	/**
	 * Unique
	 *
	 * Verifica se o valor já está cadastrado no banco
	 * unique[users.login] retorna FALSE se o valor postado já estiver no campo login da tabela users
	 * unique[users.login.10] retorna FALSE se o valor postado já estiver no campo login da tabela users,
	 * desde que o id seja diferente de 10. Isso é útil quando for atualizar os dados
	 * unique[users.city.10:id_cidade] retorna FALSE se o valor postado já estiver no campo city da tabela
	 * users, desde que o id_cidade seja diferente de 10. Se não for passado o valor após o : será usado o id.
	 * @access    public
	 * @param string - dados que será buscado
	 * @param string - campo, tabela e id
	 *
	 * @return    bool
	 */
	function unique($str = '', $field = '')
	{
		$CI = &get_instance();

		$res = explode('.', $field, 3);

		$table = $res[0];
		$column = $res[1];

		$CI->form_validation->set_message('unique', 'O {field} informado não está disponível.');

		$CI->db->select('COUNT(*) as total');
		$CI->db->where($column, $str);

		if (isset($res[2])) {
			$res2 = explode(':', $res[2], 2);
			$ignore_value = $res2[0];

			if (isset($res2[1]))
				$ignore_field = $res2[1];
			else
				$ignore_field = 'id';

			$CI->db->where($ignore_field . ' !=', $ignore_value);
		}

		$total = $CI->db->get($table)->row()->total;
		return ($total > 0) ? FALSE : TRUE;
	}

	/**
	 *
	 * decimar_br
	 *
	 * Verifica se é decimal, mas com virgula no lugar de .
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	public function decimal_br($str)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('decimal_br', 'O campo {field} não contem um valor decimal válido.');

		return (bool)preg_match('/^[\-+]?[0-9]+\,[0-9]+$/', $str);
	}

	/**
	 *
	 * valid_cpf
	 *
	 * Verifica CPF é válido
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function valid_cpf($cpf)
	{
		$CI = &get_instance();

		$CI->form_validation->set_message('valid_cpf', 'O {field} informado não é válido.');

		$cpf = preg_replace('/[^0-9]/', '', $cpf);

		if (strlen($cpf) != 11 || preg_match('/^([0-9])\1+$/', $cpf)) {
			return false;
		}

		// 9 primeiros digitos do cpf
		$digit = substr($cpf, 0, 9);

		// calculo dos 2 digitos verificadores
		for ($j = 10; $j <= 11; $j++) {
			$sum = 0;
			for ($i = 0; $i < $j - 1; $i++) {
				$sum += ($j - $i) * ((int)$digit[$i]);
			}

			$summod11 = $sum % 11;
			$digit[$j - 1] = $summod11 < 2 ? 0 : 11 - $summod11;
		}

		return $digit[9] == ((int)$cpf[9]) && $digit[10] == ((int)$cpf[10]);
	}

	/**
	 *
	 * valid_cnpj
	 *
	 * Verifica CNPJ é válido
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function valid_cnpj($cnpj)
	{
		$CI = &get_instance();

		$CI->form_validation->set_message('valid_cnpj', 'O {field} informado não é válido.');

		$cnpj = preg_replace('/[^0-9]/', '', $cnpj);

		if (strlen($cnpj) != 14 || preg_match('/^([0-9])\1+$/', $cnpj)) {
			return false;
		}

		// 12 primeiros digitos do cnpj
		$digit = substr($cnpj, 0, 12);

		// calculo dos 2 digitos verificadores
		$v1 = 0;
		$v2 = 0;
		$multiplicador1 = 5;
		$multiplicador2 = 6;
		for ($i = 1; $i < 13; $i++) {
			$v1 += $multiplicador1 * $digit[$i];
			$v2 += $multiplicador2 * $digit[$i];
			if ($multiplicador1 == 2 and $i < 12) {
				$multiplicador1 = 9;
			}
			if ($multiplicador2 == 2) {
				$multiplicador2 = 9;
			}
		}
		$v1 = 11 - $v1 % 11;
		$v2 = 11 - $v2 % 11;
		for ($j = 10; $j <= 11; $j++) {
			$sum = 0;
			for ($i = 0; $i < $j - 1; $i++) {
				$sum += ($j - $i) * ((int)$digit[$i]);
			}

			$summod11 = $sum % 11;
			$digit[$j - 1] = $summod11 < 2 ? 0 : 11 - $summod11;
		}

		return $digit[9] == ((int)$cnpj[9]) && $digit[10] == ((int)$cnpj[10]);
	}

	/**
	 * valid_date
	 *
	 * valida data no padrão brasileiro
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function valid_date($str)
	{
		if (strlen($str) == 0) {
			return true;
		}

		if (strpos($str, '/') === false) {
			$str = substr($str, 0, 10);
			$date = date_create_from_format('Y-m-d', substr($str, 0, 10));
		} else {
			$date = date_create_from_format('d/m/Y', substr($str, 0, 10));
		}

		$CI = &get_instance();
		$CI->form_validation->set_message('valid_date', 'O campo {field} não contém uma data válida.');

		return (bool)$date && date_get_last_errors()['warning_count'] === 0 && date_get_last_errors()['error_count'] === 0;
	}

	/**
	 * valid_datetime
	 *
	 * valida data e hora no padrão brasileiro
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function valid_datetime($str)
	{
		if (strlen($str) == 0) {
			return true;
		}

		$CI = &get_instance();
		if (strlen($str) <= 10) {
			$CI->form_validation->set_message('valid_datetime', 'O campo {field} não contém uma data válida.');
		} else {
			$CI->form_validation->set_message('valid_datetime', 'O campo {field} não contém uma data e hora válidas.');
		}

		$str .= substr('00/00/0000 00:00:00', strlen($str), 19 - strlen($str));

		$date = date_create_from_format('d/m/Y H:i:s', $str);

		return (bool)$date && date_get_last_errors()['warning_count'] === 0 && date_get_last_errors()['error_count'] === 0;
	}

	/**
	 * valid_time
	 *
	 * valida hora
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function valid_time($str)
	{
		if (strlen($str) == 0) {
			return true;
		}

		$CI = &get_instance();
		$CI->form_validation->set_message('valid_time', 'O campo {field} não contém um horário válido.');

		return (bool)preg_match('/^-?(\d{1,}):[0-5][0-9](:[0-5][0-9])?$/', $str);
	}

	/**
	 * before_date
	 *
	 * verifica se a data é anterior à de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function before_date($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('before_date', 'O campo {field} deve conter uma data menor à do campo {param}.');

		return $this->_compare_timestamp($str, $field, false, false);
	}

	/**
	 * before_datetime
	 *
	 * verifica se a data e hora são anteriores às de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function before_datetime($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('before_datetime', 'O campo {field} deve conter uma data e hora menores às do campo {param}.');

		return $this->_compare_timestamp($str, $field, false, false);
	}

	/**
	 * before_time
	 *
	 * verifica se a horário é anterior ao de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function before_time($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('before_time', 'O campo {field} deve conter um horário menor ao do campo {param}.');

		return $this->_compare_timestamp($str, $field, false, false);
	}

	/**
	 * before_or_equal_date
	 *
	 * verifica se a data é anterior ou igual à de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function before_or_equal_date($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('before_or_equal_date', 'O campo {field} deve conter uma data menor ou igual à do campo {param}.');

		return $this->_compare_timestamp($str, $field, false);
	}

	/**
	 * before_or_equal_datetime
	 *
	 * verifica se a data e hora são anteriores ou iguais às de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function before_or_equal_datetime($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('before_or_equal_datetime', 'O campo {field} deve conter uma data e hora menores ou iguais às do campo {param}.');

		return $this->_compare_timestamp($str, $field, false);
	}

	/**
	 * before_or_equal_time
	 *
	 * verifica se o horário é anterior ou igual ao de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function before_or_equal_time($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('before_or_equal_time', 'O campo {field} deve conter um horário menor ou igual ao do campo {param}.');

		return $this->_compare_timestamp($str, $field, false);
	}

	/**
	 * after_date
	 *
	 * verifica se a data é posterior à de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function after_date($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('after_date', 'O campo {field} deve conter uma data maior à do campo {param}.');

		return $this->_compare_timestamp($str, $field, true, false);
	}

	/**
	 * after_datetime
	 *
	 * verifica se a data e hora são posteriores às de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function after_datetime($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('after_datetime', 'O campo {field} deve conter uma data e hora maiores às do campo {param}.');

		return $this->_compare_timestamp($str, $field, true, false);
	}

	/**
	 * after_time
	 *
	 * verifica se a horário é posterior ao de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function after_time($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('after_time', 'O campo {field} deve conter um horário maior ao do campo {param}.');

		return $this->_compare_timestamp($str, $field, true, false);
	}

	/**
	 * after_or_equal_date
	 *
	 * verifica se a data é posterior ou igual à de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function after_or_equal_date($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('after_or_equal_date', 'O campo {field} deve conter uma data maior ou igual à do campo {param}.');

		return $this->_compare_timestamp($str, $field);
	}

	/**
	 * after_or_equal_datetime
	 *
	 * verifica se a data e hora são posteriores ou iguais às de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function after_or_equal_datetime($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('after_or_equal_datetime', 'O campo {field} deve conter uma data e hora maiores ou iguais às do campo {param}.');

		return $this->_compare_timestamp($str, $field);
	}

	/**
	 * after_or_equal_time
	 *
	 * verifica se o horário é posterior ou igual ao de outro campo
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function after_or_equal_time($str, $field)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('after_or_equal_time', 'O campo {field} deve conter um horário maior ou igual ao do campo {param}.');

		return $this->_compare_timestamp($str, $field);
	}

	/**
	 * _compare_timestamp
	 *
	 * Compara o timestamp entre dois campos
	 *
	 * @access    protected
	 * @param string
	 * @return    bool
	 */
	protected function _compare_timestamp($str, $field, $greather = true, $equal = true)
	{
		if (!isset($_POST[$field])) {
			return false;
		}

		$field = str_replace('/', '-', $_POST[$field]);

		if (strlen($str) == 0 or strlen($field) == 0) {
			return true;
		}

		$str = str_replace('/', '-', $str);

		if ($greather and $equal) {
			return strtotime($str) >= strtotime($field);
		} elseif ($greather and !$equal) {
			return strtotime($str) > strtotime($field);
		} elseif (!$greather and $equal) {
			return strtotime($str) <= strtotime($field);
		} elseif (!$greather and !$equal) {
			return strtotime($str) < strtotime($field);
		}
	}

	/**
	 * valid_cep
	 *
	 * Verifica se CEP é válido
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function valid_cep($cep)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('valid_cep', 'O campo {field} não contém um CEP válido.');

		$cep = str_replace('.', '', $cep);
		$cep = str_replace('-', '', $cep);

		$url = 'http://republicavirtual.com.br/web_cep.php?cep=' . urlencode($cep) . '&formato=query_string';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 0);

		$resultado = curl_exec($ch);
		curl_close($ch);

		if (!$resultado)
			$resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";

		$resultado = urldecode($resultado);
		$resultado = utf8_encode($resultado);
		parse_str($resultado, $retorno);

		if ($retorno['resultado'] == 1 || $retorno['resultado'] == 2)
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * valid_phone
	 *
	 * validação simples de telefone
	 *
	 * @access    public
	 * @param string
	 * @return    bool
	 */
	function valid_phone($fone)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('valid_fone', 'O campo {field} não contém um telefone válido.');

		$fone = preg_replace('/[^0-9]/', '', $fone);
		$fone = (string)$fone;

		if (strlen($fone) >= 10)
			return TRUE;
		else
			return FALSE;
	}

	public function multiple_of($str, $val)
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('multiple_of', 'O campo {field} não contém um valor múltiplo de {param}.');
		if (!is_numeric($val)) {
			return FALSE;
		}

		return (mb_strlen($str) % (int)$val === 0);
	}


	public function uploaded($blank, $field)
	{
		if (!isset($_FILES[$field])) {
			return true;
		}

		$CI = &get_instance();
		$CI->form_validation->set_message('uploaded', 'O campo {field} não corresponde a um arquivo válido.');

		return empty($_FILES[$field]['error']);
	}


	public function mime_in($blank, $field_mime_types)
	{
		$field_mime_types = explode('.', $field_mime_types);
		$field = $field_mime_types[0] ?? '';

		if (!isset($_FILES[$field])) {
			return true;
		}

		$mime_types = explode(',', ($field_mime_types[1] ?? ''));
		$msg_types = preg_replace('/(.*),/', '${1} ou', implode(', ', $mime_types));

		$CI = &get_instance();
		$CI->form_validation->set_message('mime_in', 'O campo {field} deve possui um arquivo no formato ' . $msg_types . '.');


		if ($CI->load->is_loaded('upload') == false) {
			$CI->load->library('upload');
		}

		foreach ($mime_types as $mime_type) {

			$new_file_type = $CI->upload->mimes_types($mime_type);

			if ($new_file_type === false) {
				continue;
			}

			if (is_string($new_file_type)) {
				$new_file_type = [$new_file_type];
			}

			if (in_array($_FILES[$field]['type'], $new_file_type)) {
				return true;
			}
		}

		return false;
	}


	public function max_size($blank, $field_max_size)
	{
		$field_max_size = explode('.', $field_max_size);
		$field = $field_max_size[0] ?? '';


		if (!isset($_FILES[$field])) {
			return true;
		}

		$CI = &get_instance();
		$CI->form_validation->set_message('max_size', 'O campo {field} deve possui um arquivo com tamanho inferior ou igual a {param}.');

		return $_FILES[$field]['size'] <= ($field_max_size[1] ?? min((ini_get('post_max_size') * 1024), (ini_get('upload_max_filesize') * 1024)));
	}

	/*
	| ---------------------------------------------------------
	| FUNÇÕES DO CODEIGNITER 4
	| ---------------------------------------------------------
	*/

	//--------------------------------------------------------------------

	/**
	 * Equals the static value provided.
	 *
	 * @param string $str
	 * @param string $val
	 *
	 * @return boolean
	 */
	public function equals(string $str = null, string $val): bool
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('equals', 'O campo {field} deve ser igual a: {param}.');

		return $str === $val;
	}

	/**
	 * Does not equal the static value provided.
	 *
	 * @param string $str
	 * @param string $val
	 *
	 * @return boolean
	 */
	public function not_equals(string $str = null, string $val): bool
	{
		$CI = &get_instance();
		$CI->form_validation->set_message('not_equals', 'O campo {field} não pode ser igual a: {param}.');

		return $str !== $val;
	}

	//--------------------------------------------------------------------

	/**
	 * The field is required when any of the other required fields are present
	 * in the data.
	 *
	 * Example (field is required when the password field is present):
	 *
	 *     required_with[password]
	 *
	 * @param $str
	 * @param string $fields List of fields that we should check if present
	 * @param array $data Complete list of fields from the form
	 *
	 * @return boolean
	 */
	public function required_with($str = null, string $fields): bool
	{
		$fields = explode(',', $fields);

		// If the field is present we can safely assume that
		// the field is here, no matter whether the corresponding
		// search field is present or not.
		$present = $this->required($str ?? '');

		if ($present) {
			return true;
		}

		$CI = &get_instance();
		$CI->form_validation->set_message('required_with', 'O campo {field} é obrigatório se {param} estiver preenchido.');

		$data = $_POST;
		// Still here? Then we fail this test if
		// any of the fields are present in $data
		// as $fields is the lis
		$requiredFields = [];

		foreach ($fields as $field) {
			if (array_key_exists($field, $data)) {
				$requiredFields[] = $field;
			}
		}

		// Remove any keys with empty values since, that means they
		// weren't truly there, as far as this is concerned.
		$requiredFields = array_filter($requiredFields, function ($item) use ($data) {
			return !empty($data[$item]);
		});

		return empty($requiredFields);
	}

	//--------------------------------------------------------------------

	/**
	 * The field is required when all of the other fields are present
	 * in the data but not required.
	 *
	 * Example (field is required when the id or email field is missing):
	 *
	 *     required_without[id,email]
	 *
	 * @param $str
	 * @param string $fields
	 * @param array $data
	 *
	 * @return boolean
	 */
	public function required_without($str = null, string $fields): bool
	{
		$fields = explode(',', $fields);

		// If the field is present we can safely assume that
		// the field is here, no matter whether the corresponding
		// search field is present or not.
		$present = $this->required($str ?? '');

		if ($present) {
			return true;
		}

		$CI = &get_instance();
		$CI->form_validation->set_message('required_without', 'O campo {field} é obrigatório se {param} não estiver preenchido.');

		$data = $_POST;
		// Still here? Then we fail this test if
		// any of the fields are not present in $data
		foreach ($fields as $field) {
			if (!array_key_exists($field, $data)) {
				return false;
			}
		}

		return true;
	}

}
