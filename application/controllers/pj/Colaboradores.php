<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Colaboradores extends MY_Controller
{

	//==========================================================================
	public function index()
	{
		if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {

			$this->db->select('depto, area, setor');
			$this->db->where('id', $this->session->userdata('id'));
			$filtro = $this->db->get('usuarios')->row();

			if (in_array($this->session->userdata('nivel'), array(9, 10))) {
				$data = $this->get_filtros_usuarios($filtro->depto);
				$data['depto'] = array($filtro->depto => $filtro->depto);
			} else {
				$data = $this->get_filtros_usuarios($filtro->depto, $filtro->area, $filtro->setor);
				$data['depto'] = array($filtro->depto => $filtro->depto);
				$data['area'] = array($filtro->area => $filtro->area);
				$data['setor'] = array($filtro->setor => $filtro->setor);
			}
		} else {
			$this->db->select('depto');
			$this->db->like('depto', 'Educação Inclusiva');
			$filtro = $this->db->get('usuarios')->row();
			$data = $this->get_filtros_usuarios($filtro->depto);
		}

		if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
			if (in_array($this->session->userdata('nivel'), array(9, 10))) {
				$this->db->select("depto, '' AS area, '' AS setor", false);
			} else {
				$this->db->select('depto, area, setor');
			}
			$this->db->where('id', $this->session->userdata('id'));
		} else {
			$this->db->select("depto, '' AS area, '' AS setor", false);
			$this->db->like('depto', 'Educação Inclusiva');
		}
		$status = $this->db->get('usuarios')->row();
		$data['depto_atual'] = $status->depto;
		$data['area_atual'] = $status->area;
		$data['setor_atual'] = $status->setor;

		$this->db->select('DISTINCT(contrato) AS nome', false);
		$this->db->where('empresa', $this->session->userdata('empresa'));
		$this->db->where('CHAR_LENGTH(contrato) >', 0);
		$contratos = $this->db->get('usuarios')->result();
		$data['contrato'] = array('' => 'selecione...');
		foreach ($contratos as $contrato) {
			$data['contrato'][$contrato->nome] = $contrato->nome;
		}

		$this->load->view('pj/colaboradores', $data);
	}

	//==========================================================================
	public function filtrar()
	{
		$depto = $this->input->post('depto');
		$area = $this->input->post('area');
		$setor = $this->input->post('setor');
		$cargo = $this->input->post('cargo');
		$funcao = $this->input->post('funcao');

		$filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);


		$data['area'] = form_dropdown('area', $filtro['area'], $area, 'class="form-control input-sm"');
		$data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'class="form-control input-sm"');
		$data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'class="form-control input-sm"');
		$data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'class="form-control input-sm"');

		echo json_encode($data);
	}

	public function listar()
	{
		parse_str($this->input->post('busca'), $busca);

		$this->db
			->select('a.nome, b.nome AS funcao')
			->select(["GROUP_CONCAT(DISTINCT c.nome ORDER BY c.nome ASC SEPARATOR ', ') AS documento"], false)
			->select('MIN(c.data_inicio) AS data_inicio, MAX(c.data_termino) AS data_termino, a.id', false)
			->select(["DATE_FORMAT(MIN(c.data_inicio), '%d/%m/%Y') AS data_inicio_de"], false)
			->select(["DATE_FORMAT(MAX(c.data_termino), '%d/%m/%Y') AS data_termino_de"], false)
			->join('empresa_funcoes b', 'b.id = a.id_funcao')
			->join('usuarios_documentos c', 'c.id_usuario = a.id AND c.status_ativo = 1', 'left')
			->where('a.empresa', $this->session->userdata('empresa'));
		if (!empty($busca['status'])) {
			$this->db->where('a.status', $busca['status']);
		}
		if (!empty($busca['depto'])) {
			$this->db->where('a.depto', $busca['depto']);
		}
		if (!empty($busca['area'])) {
			$this->db->where('a.area', $busca['area']);
		}
		if (!empty($busca['setor'])) {
			$this->db->where('a.setor', $busca['setor']);
		}
		if (!empty($busca['cargo'])) {
			$this->db->where('a.cargo', $busca['cargo']);
		}
		if (!empty($busca['funcao'])) {
			$this->db->where('a.funcao', $busca['funcao']);
		}
		if (!empty($busca['contrato'])) {
			$this->db->where('a.contrato', $busca['contrato']);
		}
		$query = $this->db
			->group_by('a.id')
			->get('usuarios a');

		$this->load->library('dataTables');

		$output = $this->datatables->generate($query);

		$data = [];

		foreach ($output->data as $row) {
			$data[] = array(
				$row->nome,
				$row->funcao,
				$row->documento,
				$row->data_inicio_de,
				$row->data_termino_de,
				'<a class="btn btn-sm btn-primary" href="' . site_url('pj/colaboradores/editarPerfil/' . $row->id) . '" title="Edição rápida"><i class="fa fa-edit"></i> Edição rápida</a>
                 <button class="btn btn-sm btn-info" onclick="gerenciar_contratos(' . $row->id . ')" title="Gerenciar contratos"><i class="glyphicon glyphicon-plus"></i> Contratos</button>'
			);
		}

		$output->data = $data;

		echo json_encode($output);
	}

	//==========================================================================
	public function editarPerfil()
	{
		$this->db->where('id', $this->uri->rsegment(3, 0));
		$funcionario = $this->db->get('usuarios')->row();

		if (count($funcionario) == 0) {
			redirect(site_url('pj/colaboradores'));
		}

		if (!$funcionario->hash_acesso) {
			$funcionario->hash_acesso = 'null';
		}

		$dataFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->data_admissao)));
		$funcionario->data_admissao = $dataFormatada;
		$data['row'] = $funcionario;
		$data['status'] = array(
			'1' => 'Ativo',
			'2' => 'Inativo',
			'3' => 'Em experiência',
			'4' => 'Em desligamento',
			'5' => 'Desligado',
			'6' => 'Afastado (maternidade)',
			'7' => 'Afastado (aposentadoria)',
			'8' => 'Afastado (doença)',
			'9' => 'Afastado (acidente)'
		);

		$this->db->select('DISTINCT(area) AS nome');
		$this->db->where('empresa', $this->session->userdata('empresa'));
		$this->db->where('depto', $funcionario->depto);
		$this->db->where('CHAR_LENGTH(area) >', 0);
		$areas = $this->db->get('usuarios')->result();
		$data['area'] = array('' => 'digite ou selecione...');
		foreach ($areas as $area) {
			$data['area'][$area->nome] = $area->nome;
		}

		$this->db->select('DISTINCT(setor) AS nome');
		$this->db->where('empresa', $this->session->userdata('empresa'));
		$this->db->where('depto', $funcionario->depto);
		$this->db->where('area', $funcionario->area);
		$this->db->where('CHAR_LENGTH(setor) >', 0);
		$setores = $this->db->get('usuarios')->result();
		$data['setor'] = array('' => 'digite ou selecione...');
		foreach ($setores as $setor) {
			$data['setor'][$setor->nome] = $setor->nome;
		}

		$this->db->select('DISTINCT(contrato) AS nome');
		$this->db->where('empresa', $this->session->userdata('empresa'));
		$this->db->where('CHAR_LENGTH(contrato) >', 0);
		$contratos = $this->db->get('usuarios')->result();
		$data['contrato'] = array('' => 'digite ou selecione...');
		foreach ($contratos as $contrato) {
			$data['contrato'][$contrato->nome] = $contrato->nome;
		}

		$this->load->view('pj/colaborador', $data);
	}

	//==========================================================================
	public function salvarPerfil()
	{
		header('Content-type: text/json');
		$this->load->helper(array('date'));

		$this->db->where('id', $this->uri->rsegment(3, 0));
		$this->db->where_in('tipo', array('funcionario', 'selecionador'));
		$funcionario = $this->db->get('usuarios')->row();

		if ($funcionario->empresa != $this->session->userdata('empresa')) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
		}

		$data['area'] = $this->input->post('area');
		$data['setor'] = $this->input->post('setor');
		$data['cnpj'] = $this->input->post('cnpj');
		$data['cpf'] = $this->input->post('cpf');
		$data['rg'] = $this->input->post('rg');
		$data['telefone'] = $this->input->post('telefone');
		$data['email'] = $this->input->post('email');
		$data['contrato'] = $this->input->post('contrato');
		$data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");
		$data['status'] = $this->input->post('status');
		$data['nome_cartao'] = $this->input->post('nome_cartao');
		$data['valor_vt'] = $this->input->post('valor_vt');
		$data['nome_banco'] = $this->input->post('nome_banco');
		$data['agencia_bancaria'] = $this->input->post('agencia_bancaria');
		$data['conta_bancaria'] = $this->input->post('conta_bancaria');

		if ($this->db->where('id', $funcionario->id)->update('usuarios', $data)) {
			echo json_encode(array('retorno' => 1, 'aviso' => 'Funcionário editado com sucesso', 'redireciona' => 1, 'pagina' => site_url('pj/colaboradores')));
		} else {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar funcionário, tente novamente, se o erro persistir entre em contato com o administrador')));
		}
	}

	//==========================================================================
	public function pdf()
	{
		$this->db->select('foto, foto_descricao');
		$this->db->where('id', $this->session->userdata('empresa'));
		$data['empresa'] = $this->db->get('usuarios')->row();


		$get = $this->input->get();

		$this->db->select("a.contrato, a.nome, DATE_FORMAT(a.data_admissao, '%d/%m/%Y') AS data_admissao", false);
		$this->db->select("CONCAT_WS('/', a.depto, a.area, a.setor) AS estrutura", false);
		$this->db->select("CONCAT_WS('/', a.cargo, a.funcao) AS cargo_funcao", false);
		$this->db->where('a.empresa', $this->session->userdata('empresa'));
		if (isset($get['busca'])) {
			$this->db->like('a.nome', $get['busca']);
			$this->db->or_like('a.email', $get['busca']);
		}
		if (isset($get['status'])) {
			$this->db->where('a.status', $get['status']);
		}
		if (isset($get['depto'])) {
			$this->db->where('a.depto', $get['depto']);
		}
		if (isset($get['area'])) {
			$this->db->where('a.area', $get['area']);
		}
		if (isset($get['setor'])) {
			$this->db->where('a.setor', $get['setor']);
		}
		if (isset($get['cargo'])) {
			$this->db->where('a.cargo', $get['cargo']);
		}
		if (isset($get['funcao'])) {
			$this->db->where('a.funcao', $get['funcao']);
		}
		if (isset($get['contrato'])) {
			$this->db->where('a.contrato', $get['contrato']);
		}
		$data['colaboradores'] = $this->db->get('usuarios a')->result();

		$this->load->library('m_pdf');

		$stylesheet = '#table thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5;} ';
		$stylesheet .= '#table tbody td { font-size: 12px; padding: 4px; vertical-align: top; } ';

		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->load->view('funcionariosPdf', $data, true));

		$this->m_pdf->pdf->Output('Colaboradores.pdf', 'D');
	}

	//==========================================================================
	public function gerenciarContratos()
	{
		$idUsuario = $this->input->post('id_usuario');

		$rowsCurriculos = $this->db
			->select('id, nome, arquivo')
			->where('id_usuario', $idUsuario)
			->where('tipo', 1)
			->order_by('nome', 'asc')
			->get('usuarios_documentos')
			->result();

		$idCurriculos = ['' => 'Novo CV...'] + array_filter(array_column($rowsCurriculos, 'nome', 'id'));

		$curriculos = ['' => 'selecione...'];
		foreach ($rowsCurriculos as $rowCurriculo) {
			$curriculos[convert_accented_characters($rowCurriculo->arquivo)] = $rowCurriculo->nome;
		}

		$rowsContratos = $this->db
			->select('id, nome, arquivo')
			->where('id_usuario', $idUsuario)
			->where('tipo', 2)
			->order_by('nome', 'asc')
			->get('usuarios_documentos')
			->result();

		$idContratos = ['' => 'Novo contrato...'] + array_filter(array_column($rowsContratos, 'nome', 'id'));

		$contratos = ['' => 'selecione...'];
		foreach ($rowsContratos as $rowContrato) {
			$contratos[convert_accented_characters($rowContrato->arquivo)] = $rowContrato->nome;
		}

		$data = [
			'curriculos' => form_dropdown('', $curriculos, ''),
			'contratos' => form_dropdown('', $contratos, ''),
			'id_curriculos' => form_dropdown('', $idCurriculos, ''),
			'id_contratos' => form_dropdown('', $idContratos, ''),
		];

		echo json_encode($data);
	}


	//==========================================================================
	public function editarContrato()
	{
		$data = $this->db
			->select('id, nome, localidade, status_ativo')
			->select(["DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio"], false)
			->select(["DATE_FORMAT(data_termino, '%d/%m/%Y') AS data_termino"], false)
			->select(["TIME_FORMAT(qtde_horas_mensais, '%H:%i') AS qtde_horas_mensais"], false)
			->select(["FORMAT(valor_hora_periodo, 2, 'de_DE') AS valor_hora_periodo"], false)
			->select(["FORMAT(valor_mensal, 2, 'de_DE') AS valor_mensal"], false)
			->where('id', $this->input->post('id'))
			->get('usuarios_documentos')
			->row();

		if (empty($data)) {
			exit(json_encode(['erro' => 'Nenhum contrato encontrado.']));
		}

		echo json_encode($data);
	}


	//==========================================================================
	public function salvarContrato()
	{
		if ($this->session->userdata('tipo') != 'empresa') {
			redirect(site_url('pj/colaboradores'));
		}

		$data = $this->input->post();
		$id = $data['id'];
		unset($data['id']);

		if ($data['id_usuario'] < 1) {
			exit(json_encode(['erro' => 'Erro ao salvar arquivo, id do colaborador não identificado.']));
		}

		$contratoExistente = $this->db
			->where('id !=', $id)
			->where('nome', $data['nome'])
			->get('usuarios_documentos')
			->num_rows();
		if ($contratoExistente) {
			exit(json_encode(['erro' => 'O campo Nome já está sendo utilizado em outro C.V. ou Contrato.']));
		}

		if (strlen($data['data_inicio']) > 0) {
			$data['data_inicio'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_inicio'])));
		}
		if (strlen($data['data_termino']) > 0) {
			$data['data_termino'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_termino'])));
		}
		if (strlen($data['valor_hora_periodo']) > 0) {
			$data['valor_hora_periodo'] = str_replace(['.', ','], ['', '.'], $data['valor_hora_periodo']);
		}
		if (strlen($data['valor_mensal']) > 0) {
			$data['valor_mensal'] = str_replace(['.', ','], ['', '.'], $data['valor_mensal']);
		}
		if (!isset($data['status_ativo'])) {
			$data['status_ativo'] = null;
		}

		if (isset($_FILES['arquivo']['tmp_name']) and !empty($_FILES['arquivo']['tmp_name'])) {
			$config['upload_path'] = './arquivos/documentos/colaborador/';
			$config['allowed_types'] = 'pdf';
			$config['max_size'] = '102400';

			$this->load->library('upload', $config);
			$_FILES['arquivo']['name'] = utf8_encode($_FILES['arquivo']['name']);

			if ($this->upload->do_upload('arquivo')) {
				$foto = $this->upload->data();
				$data['arquivo'] = $foto['file_name'];

				if ($foto['file_ext'] === '.doc' || $foto['file_ext'] === '.docx') {
					shell_exec("unoconv -f pdf " . $config['upload_path'] . $foto['file_name']);
					$data['arquivo'] = $foto['raw_name'] . ".pdf";
					unlink($config['upload_path'] . $foto['file_name']);
				}

			} else {
				exit(json_encode(['erro' => 'Erro no upload do arquivo: ' . $this->upload->display_errors()]));
			}
		}

		$this->db->trans_start();
		if ($id) {
			$this->db->update('usuarios_documentos', $data, ['id' => $id]);
		} else {
			$this->db->insert('usuarios_documentos', $data);
		}
		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['erro' => 'Erro ao salvar o arquivo.']));
		}

		echo json_encode(['status' => true]);
	}


	//==========================================================================
	public function excluirContrato()
	{
		$data = $this->db
			->select('id, arquivo')
			->where('id', $this->input->post('id'))
			->get('usuarios_documentos')
			->row();

		$this->db->trans_start();
		$this->db->delete('usuarios_documentos', ['id' => $data->id]);

		$urlArquivo = './arquivos/documentos/colaborador/' . $data->arquivo;

		if (is_file($urlArquivo)) {
			if (!unlink($urlArquivo)) {
				exit(json_encode(['erro' => 'Não foi possível excluir o arquivo.']));
			}
		}

		$this->db->trans_complete();

		echo json_encode(['status' => true]);
	}

}
