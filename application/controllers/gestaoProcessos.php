<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class GestaoProcessos extends MY_Controller
{

	public function index()
	{
		$this->load->view('gestao_processos');
	}


	public static function getProcesso($url = null)
	{
		$ci = &get_instance();

		$ci->db->where('url_pagina', $url);
		$data = $ci->db->get('abcbr304_processos')->row();

		return $data;
	}


	public function ajaxList()
	{
		$this->db->select('url_pagina, orientacoes_gerais, id');
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		$query = $this->db->get('abcbr304_processos');

		$config = array(
			'search' => ['url_pagina', 'orientacoes_gerais']
		);

		$this->load->library('dataTables', $config);

		$output = $this->datatables->generate($query);

		$urlPaginas = self::getUrlPaginas();

		$data = array();
		foreach ($output->data as $row) {
			$data[] = array(
				$urlPaginas[$row->url_pagina] ?? $row->url_pagina,
				$row->orientacoes_gerais,
				'<a class="btn btn-sm btn-primary" title="Editar" href="' . site_url('gestaoProcessos/editar/' . $row->id) . '"><i class="glyphicon glyphicon-pencil"></i></a>
                 <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_processo(' . $row->id . ')"><i class="glyphicon glyphicon-trash"></i></button>'
			);
		}

		$output->data = $data;

		echo json_encode($output);
	}


	public function novo()
	{
		$data['empresa'] = $this->session->userdata('empresa');

		$data['urlPaginas'] = self::getUrlPaginas();

		$this->load->view('gestao_processos_novo', $data);
	}


	public function editar()
	{
		$this->db->where('id', $this->uri->rsegment(3));
		$data = $this->db->get('abcbr304_processos')->row();

		if (empty($data)) {
			show_404('O processo requisitado é inexistente.');
		}

		$data->urlPaginas = self::getUrlPaginas();

		$this->load->view('gestao_processos_edicao', $data);
	}


	private static function getUrlPaginas()
	{
		return array(
			'' => 'selecione...',
			'home' => 'Início',
			'atividades' => 'Lista de Pendências | To Do',
			'atividades_scheduler' => 'Scheduler - Atividades',
			'Gestão Operacional GT' => array(
				'funcionario/novo' => 'Adicionar Colaborador (CLT/PJ)',
				'funcionario/editar' => 'Editar Colaborador (CLT/PJ)',
				'funcionario' => 'Gerenciar Colaboradores (CLT/PJ)',
				'funcionario/importarFuncionario' => 'Importar Colaboradores (CLT)',
				'gestaoDePessoal' => 'Relatórios Gestão GP',
				'examePeriodico' => 'Relatórios de Exames Periódicos',
				'usuarioAfastamento' => 'Relatório de Afastamentos',
				'usuarioDemissao' => 'Relatório de Demissões',
				'funcionario/aniversariantes' => 'Lista de Aniversariantes',
				'ead/funcionarios' => 'Gerenciar Alocação Treinamentos',
				'avaliacaoexp_avaliados/status/2' => 'Status Avaliações Experiência',
				'avaliacaoexp_avaliados/status/1' => 'Status Avaliações Periódicas',
				'pdi' => 'PDIs - Plane de Desenvolvimento Individual'
			),
			'Estrutura Organizacional' => array(
				'estruturas' => 'Gerenciar Estruturas',
				'cargo_funcao' => 'Gerenciar Cargos/Funções'
			),
			'Job Descriptor' => array(
				'jobDescriptor' => 'Job Descriptor'
			),
			'Gestão Processos Seletivos' => array(
				'recrutamento_modelos' => 'Modelos de Testes Online',
				'requisicaoPessoal_emails' => 'E-mails - De Apoio',
				'recrutamento_candidatos' => 'Banco de Candidatos',
				'requisicaoPessoal' => 'Gerenciar Requisições Pessoal',
				'requisicaoPessoal_fontes' => 'Gerenciar Fontes/Aprovadores'
			),
			'Programas de Capacitação' => array(
				'ead/cursos/novo' => 'Adicionar Treinamento',
				'ead/cursos/editar' => 'Editar Treinamento',
				'ead/cursos' => 'Gerenciar Treinamentos',
				'ead/clientes' => 'Gerenciar Treinamentos Clientes',
				'ead/pilulasConhecimento' => 'Gerenciar Pílulas Conhecimento'
			),
			'Gestão de Treinamentos' => array(
				'ead/treinamento' => 'Meus Treinamentos',
				'ead/cursos/disponiveis' => 'Treinamentos Disponíveis'
			),
			'Gestão de Documentos Corporativos' => array(
				'documentos/organizacao' => 'Adicionar Documento',
				'documentos/organizacao/gerenciar' => 'Gerenciar Documentos Organizacionais',
				'documentos/colaborador/gerenciar' => 'Meus Documentos'
			),
			'Ferramentas de Assessment' => array(
				'pesquisa_modelos' => 'Modelos de Pesquisa/Assessment',
				'pesquisa/eneagrama' => 'Personalidade - Eneagrama',
				'pesquisa/quati' => 'Personalidade - Jung',
				'pesquisa/lifo' => 'Personalidade - Estilos LIFO',
				'pesquisa/potencial-ninebox' => 'Potencial-NineBox'
			),
			'Gestão de Desempenho' => array(
				'competencias/cargos' => 'Mapeamento de Competências',
				'competencias/avaliacao' => 'Avaliações por Competências',
				'avaliacaoexp_modelos' => 'Modelos de Avaliações',
				'avaliacaoexp_avaliados' => 'Avaliações Período Experiência',
				'avaliacaoexp' => 'Avaliações Periódicas Desempenho'
			),
			'Gestão de Pesquisas' => array(
				'pesquisa/clima' => 'Pesquisa de Clima Organizacional',
				'pesquisa/perfil' => 'Pesquisa de Perfil Profissional',
				'pesquisa_modelos' => 'Modelos de Pesquisa/Assessment'
			),
			'Gestão de Facilities' => array(
				'facilities/empresas' => 'Itens de Vistoria/Manutenção',
				'facilities/estruturas' => 'Cadastro Estrutural',
				'facilities/modelos' => 'Modelos de Vistorias/Manutenções',
				'facilities/vistorias' => 'Gerenciar Vistorias',
				'facilities/manutencoes' => 'Gerenciar Manutenções',
				'facilities/contasMensais' => 'Contas Mensais Facilities',
				'facilities/fornecedoresPrestadores' => 'Gerenciar Fornecedores',
				'facilities/ordensServico' => 'Gerenciar Ordens de Serviço'
			),
			'Gestão de Processos' => array(
				'relatoriosGestao' => 'Relatórios de Gestão'
			),
			'Gestão Operacional ST' => array(
				'st/apontamento' => 'Gerenciar Apontamentos',
				'requisicaoPessoal/st' => 'Requisição de Pessoal'
			),
			'Gestão Operacional CD' => array(
				'cd/apontamento' => 'Gerenciar Apontamentos',
				'requisicaoPessoal/cd' => 'Requisição de Pessoal'
			),
			'Gestão Operacional EI' => array(
				'ei/apontamento' => 'Gerenciar Apontamentos',
				'requisicaoPessoal/ei' => 'Requisição de Pessoal'
			),
			'Gestão Operacional PAPD' => array(
				'papd/atendimentos' => 'Gerenciar Atendimentos',
				'papd/pacientes' => 'Gerenciar Pacientes',
				'papd/atividades_deficiencias' => 'Gerenciar Atividades/Deficiências',
				'papd/relatorios/medicao_mensal' => 'Relatório de Medição (Individual)',
				'papd/relatorios/medicao_consolidada' => 'Relatório de Medição (Equipe)',
				'papd/relatorios/medicao_anual' => 'Relatório de Medição (Consolidado)',
				'requisicaoPessoal/papd' => 'Gerenciar Requisição de Pessoal'
			),
			'Gestão Operacional CDH' => array(
				'cdh/apontamento' => 'Gerenciar Apontamentos',
				'requisicaoPessoal/cdh' => 'Requisição de Pessoal'
			),
			'Gestão Operacional ICOM' => array(
				'icom/apontamento' => 'Gerenciar Apontamentos',
				'requisicaoPessoal/icom' => 'Requisição de Pessoal'
			),
			'Gestão Operacional ADM-FIN' => array(
				'adm-fin/apontamento' => 'Gerenciar Apontamentos',
				'requisicaoPessoal/adm-fin' => 'Requisição de Pessoal'
			),
			'Gestão Operacional GExec' => array(
				'gexec/apontamento' => 'Gerenciar Apontamentos',
				'requisicaoPessoal/gexec' => 'Requisição de Pessoal'
			),
			'Gestão da Plataforma' => array(
				'gestaoProcessos' => 'Gestão de Processos',
				'backup' => 'Backup/Restore de Database',
				'log_usuarios' => 'Log de Usuários'
			)
		);
	}


	public function inserir()
	{
		$data = $this->input->post();

		if (strlen($data['url_pagina']) == 0) {
			exit(json_encode(['retorno' => 0, 'aviso' => 'A URL da página do processo é obrigatória.', 'redireciona' => 0, 'pagina' => '']));
		}

		$data['url_pagina'] = str_replace(site_url(), '', $data['url_pagina']);


		$empresa = $this->session->userdata('empresa');
		if ($empresa) {
			$data['id_empresa'] = $empresa;
		}

		if (strlen($data['nome_processo_1']) == 0) {
			$data['nome_processo_1'] = null;
		}
		if (strlen($data['nome_processo_2']) == 0) {
			$data['nome_processo_2'] = null;
		}
		if (strlen($data['nome_documentacao_1']) == 0) {
			$data['nome_documentacao_1'] = null;
		}
		if (strlen($data['nome_documentacao_2']) == 0) {
			$data['nome_documentacao_2'] = null;
		}


		$arquivo = $this->uploadArquivo();

		$data['arquivo_processo_1'] = $arquivo['arquivo_processo_1'] ?? null;
		$data['arquivo_processo_2'] = $arquivo['arquivo_processo_2'] ?? null;
		$data['arquivo_documentacao_1'] = $arquivo['arquivo_documentacao_1'] ?? null;
		$data['arquivo_documentacao_2'] = $arquivo['arquivo_documentacao_2'] ?? null;


		$this->db->trans_start();

		$this->db->insert('abcbr304_processos', $data);

		$this->db->trans_complete();

		$status = $this->db->trans_status();

		if ($status == false) {
			$this->excluirArquivo($data['arquivo_processo_1']);
			$this->excluirArquivo($data['arquivo_processo_2']);
			$this->excluirArquivo($data['arquivo_documentacao_1']);
			$this->excluirArquivo($data['arquivo_documentacao_2']);

			exit(json_encode(array('retorno' => 0, 'aviso' => 'Não foi possível salvar o processo', 'redireciona' => 0, 'pagina' => '')));
		}

		echo json_encode(array('retorno' => 1, 'aviso' => 'Processo cadastrado com sucesso', 'redireciona' => 1, 'pagina' => site_url('gestaoProcessos')));
	}


	public function alterar()
	{
		$data = $this->input->post();

		if (strlen($data['url_pagina']) == 0) {
			exit(json_encode(['retorno' => 0, 'aviso' => 'A URL da página do processo é obrigatória.', 'redireciona' => 0, 'pagina' => '']));
		}

		$data['url_pagina'] = str_replace(site_url(), '', $data['url_pagina']);

		$empresa = $this->session->userdata('empresa');
		if ($empresa) {
			$data['id_empresa'] = $empresa;
		}

		if (strlen($data['nome_processo_1']) == 0) {
			$data['nome_processo_1'] = null;
		}
		if (strlen($data['nome_processo_2']) == 0) {
			$data['nome_processo_2'] = null;
		}
		if (strlen($data['nome_documentacao_1']) == 0) {
			$data['nome_documentacao_1'] = null;
		}
		if (strlen($data['nome_documentacao_2']) == 0) {
			$data['nome_documentacao_2'] = null;
		}


		$arquivo = $this->uploadArquivo();

		if (isset($arquivo['arquivo_processo_1'])) {
			$data['arquivo_processo_1'] = $arquivo['arquivo_processo_1'];
		}
		if (isset($arquivo['arquivo_processo_2'])) {
			$data['arquivo_processo_2'] = $arquivo['arquivo_processo_2'];
		}
		if (isset($arquivo['arquivo_documentacao_1'])) {
			$data['arquivo_documentacao_1'] = $arquivo['arquivo_documentacao_1'];
		}
		if (isset($arquivo['arquivo_documentacao_2'])) {
			$data['arquivo_documentacao_2'] = $arquivo['arquivo_documentacao_2'];
		}

		$id = $data['id'];
		unset($data['id']);


		$this->db->trans_start();

		$dataOld = $this->db->get_where('abcbr304_processos', ['id' => $id])->row();

		$this->db->update('abcbr304_processos', $data, ['id' => $id]);

		$this->db->trans_complete();

		$status = $this->db->trans_status();

		if ($status == false) {
			$this->excluirArquivo($data['arquivo_processo_1'] ?? null);
			$this->excluirArquivo($data['arquivo_processo_2'] ?? null);
			$this->excluirArquivo($data['arquivo_documentacao_1'] ?? null);
			$this->excluirArquivo($data['arquivo_documentacao_2'] ?? null);

			exit(json_encode(array('retorno' => 0, 'aviso' => 'Não foi possível salvar o processo', 'redireciona' => 0, 'pagina' => '')));
		}

		if (isset($data['arquivo_processo_1'])) {
			$this->excluirArquivo($dataOld->arquivo_processo_1);
		}
		if (isset($data['arquivo_processo_2'])) {
			$this->excluirArquivo($dataOld->arquivo_processo_2);
		}
		if (isset($data['arquivo_documentacao_1'])) {
			$this->excluirArquivo($dataOld->arquivo_documentacao_1);
		}
		if (isset($data['arquivo_documentacao_2'])) {
			$this->excluirArquivo($dataOld->arquivo_documentacao_2);
		}

		echo json_encode(array('retorno' => 1, 'aviso' => 'Processo alterado com sucesso', 'redireciona' => 1, 'pagina' => site_url('gestaoProcessos')));
	}


	private function uploadArquivo()
	{
		$data = [];

		$status = true;

		$arquivos = ['arquivo_processo_1', 'arquivo_processo_2', 'arquivo_documentacao_1', 'arquivo_documentacao_2'];

		$config = array(
			'upload_path' => './arquivos/pdf/',
			'allowed_types' => 'pdf'
		);


		foreach ($arquivos as $arquivo) {
			if (!empty($_FILES[$arquivo]['tmp_name'])) {
				$config['file_name'] = utf8_decode($_FILES[$arquivo]['name']);

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload($arquivo)) {
					$status = false;
					break;
				}

				$dataArquivo = $this->upload->data();
				$data[$arquivo] = utf8_encode($dataArquivo['file_name']);

			} elseif (strlen($this->input->post($arquivo)) == 0) {
				$data[$arquivo] = null;
			}
		}

		if ($status == false) {
			foreach ($data as $nomeArquivo) {
				$this->excluirArquivo($nomeArquivo);
			}

			exit(json_encode(['erro' => $this->upload->display_errors() . ' - ' . $nomeArquivo]));
		}

		return $data;
	}


	public function excluir()
	{
		$id = $this->input->post('id');

		$this->db->trans_start();

		$documento = $this->db->get_where('abcbr304_processos', ['id' => $id])->row();

		if (empty($documento)) {
			exit(json_encode(['erro' => 'O processo não foi encontrado ou já foi excluído.']));
		}

		$this->db->delete('abcbr304_processos', ['id' => $id]);

		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['erro' => 'Não foi possível excluir o processo.']));
		}

		$this->excluirArquivo($documento->arquivo_processo_1);
		$this->excluirArquivo($documento->arquivo_processo_2);
		$this->excluirArquivo($documento->arquivo_documentacao_1);
		$this->excluirArquivo($documento->arquivo_documentacao_2);


		echo json_encode(['status' => true]);
	}


	private function excluirArquivo($documento = null)
	{
		if (strlen($documento) > 0) {
			@unlink('./arquivos/pdf/' . $documento);
		}
	}


}
