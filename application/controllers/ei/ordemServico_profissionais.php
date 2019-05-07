<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OrdemServico_profissionais extends MY_Controller
{

    public function index()
    {
        $this->gerenciar();
    }


    public function gerenciar($idEscola = null)
    {
        if (empty($idEscola)) {
            $idEscola = $this->uri->rsegment(3, 0);
        }

        $this->db->select('a2.nome AS ordemServico, e.id AS id_depto, a2.ano, a2.semestre', false);
        $this->db->select('b.nome AS nomeEscola', false);
        $this->db->select('c.nome AS nomeCliente', false);
        $this->db->select('d.contrato AS nomeContrato', false);
        $this->db->select("CONCAT(a2.ano, '/', a2.semestre) AS anoSemestre", false);
        $this->db->join('ei_ordem_servico a2', 'a.id_ordem_servico = a2.id');
        $this->db->join('ei_escolas b', 'b.id = a.id_escola');
        $this->db->join('ei_diretorias c', 'c.id = b.id_diretoria');
        $this->db->join('ei_contratos d', 'd.id_cliente = c.id');
        $this->db->join('empresa_departamentos e', 'e.nome = c.depto', 'left');
        $this->db->where('a.id', $idEscola);
        $data = $this->db->get('ei_ordem_servico_escolas a')->row();


        if ($data->semestre == 2) {
            $data->nomeMes1 = 'Julho';
            $data->nomeMes2 = 'Agosto';
            $data->nomeMes3 = 'Setembro';
            $data->nomeMes4 = 'Outubro';
            $data->nomeMes5 = 'Novembro';
            $data->nomeMes6 = 'Dezembro';
        } else {
            $data->nomeMes1 = 'Janeiro';
            $data->nomeMes2 = 'Fevereiro';
            $data->nomeMes3 = 'Março';
            $data->nomeMes4 = 'Abril';
            $data->nomeMes5 = 'Maio';
            $data->nomeMes6 = 'Junho';
        }

        $funcoes = $this->getFuncoes();
        $funcoes[''] = 'selecione...';
        $data->funcoes = $funcoes;


        $this->db->select('b.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $this->session->userdata('empresa'));
        $this->db->where('a.ano', $data->ano);
        $this->db->where('a.semestre', $data->semestre);
        $this->db->where('a.is_supervisor', 1);
        $this->db->order_by('b.nome', 'asc');
        $supervisores = $this->db->get('ei_coordenacao a')->result();

        $data->supervisor = ['' => 'nenhum...', '-1' => '-- manter --'] + array_column($supervisores, 'nome', 'id');


        $this->load->view('ei/ordemServico_profissionais', $data);
    }


    public function ajaxList()
    {
        $idEscola = $this->input->post('id_escola');

        $sql = "SELECT a.dia_semana,
                       c.nome AS profissional,
                       GROUP_CONCAT(DISTINCT i.nome ORDER BY i.nome SEPARATOR ', ') AS alunos,
                       a2.nome AS funcao,
                       b.valor_hora,
                       b.horas_semanais,
                       CONCAT(TIME_FORMAT(a.horario_inicio,'%H:%i'), ' às ', TIME_FORMAT(a.horario_termino,'%H:%i')) AS horario,
                       a.id,
                       b.id AS id_os_profissional,
                       CASE a.dia_semana
                            WHEN 0 THEN 'Domingo'
                            WHEN 1 THEN 'Segunda-feira'
                            WHEN 2 THEN 'Terça-feira'
                            WHEN 3 THEN 'Quarta-feira'
                            WHEN 4 THEN 'Quinta-feira'
                            WHEN 5 THEN 'Sexta-feira'
                            WHEN 6 THEN 'Sábado'
                            END AS semana,
                       e.nome AS profissional_sub1,
                       f.nome AS profissional_sub2,
                       FORMAT(b.valor_hora, 2, 'de_DE') AS valor_hora_de,
                       FORMAT(b.horas_semanais, 2, 'de_DE') AS horas_semanais_de
                FROM ei_ordem_servico_profissionais b
                INNER JOIN usuarios c ON 
                           c.id = b.id_usuario
                INNER JOIN ei_ordem_servico_escolas d ON 
                           d.id = b.id_ordem_servico_escola
                LEFT JOIN ei_ordem_servico_horarios a ON 
                          a.id_os_profissional = b.id
                LEFT JOIN empresa_funcoes a2 ON 
                          a2.id = a.id_funcao
                LEFT JOIN usuarios e ON 
                          e.id = b.id_usuario_sub1
                LEFT JOIN usuarios f ON 
                          f.id = b.id_usuario_sub2
                LEFT JOIN ei_ordem_servico_turmas g ON g.id_os_horario = a.id
                LEFT JOIN ei_ordem_servico_alunos h ON h.id = g.id_os_aluno AND h.id_ordem_servico_escola = d.id
                LEFT JOIN ei_alunos i ON i.id = h.id_aluno
                WHERE c.empresa = {$this->session->userdata('empresa')} 
                      AND d.id = {$idEscola} 
                GROUP BY a.id";

        $this->load->library('dataTables');

        $output = $this->datatables->query($sql);


        $data = array();
        foreach ($output->data as $ei) {
            $row = array();
            $row[] = $ei->semana;
            $row[] = '<a>' . $ei->profissional . '</a>';
            $row[] = $ei->alunos;
            $row[] = $ei->funcao;
            $row[] = $ei->valor_hora_de;
            $row[] = $ei->horas_semanais_de;
            $row[] = $ei->horario;
            if ($ei->id) {
                $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_profissional(' . $ei->id . ')" title="Editar programação semanal"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_profissional(' . $ei->id . ')" title="Excluir programação semanal"><i class="glyphicon glyphicon-trash"></i> </button>
                     ';
            } else {
                $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="add_profissional(' . $ei->id_os_profissional . ')" title="Editar profissional"><i class="glyphicon glyphicon-plus"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="limpar_profissional(' . $ei->id_os_profissional . ')" title="Excluir profissional"><i class="glyphicon glyphicon-minus"></i> </button>
                     ';
            }
            $row[] = $ei->id_os_profissional;
            $row[] = $ei->id;

            $data[] = $row;
        }

        $output->data = $data;


        echo json_encode($output);
    }


    public function ajaxEdit()
    {
        $idEscola = $this->input->post('id_escola');

        $this->db->select('id AS id_ordem_servico_escola');
        $data = $this->db->get_where('ei_ordem_servico_escolas', ['id' => $idEscola])->row_array();

        $deptos = $this->getDepartamentos();
        $areas = $this->getAreas();
        $setores = $this->getSetores();
        $cargos = $this->getCargos();
        $funcoes = $this->getFuncoes();
        $municipios = $this->getMunicipios();
        $usuarios = $this->getUsuarios();

        $this->db->select('id_usuario');
        $this->db->where('id_ordem_servico_escola', $idEscola);
        $rows = $this->db->get('ei_ordem_servico_profissionais')->result();
        $usuariosSelecionados = array_column($rows, 'id_usuario');


        $this->db->where('id_ordem_servico_escola', $data['id_ordem_servico_escola']);
        $supervisores = $this->db->get('ei_ordem_servico_profissionais')->result_array();
        if (count($supervisores) === 1) {
            $data['supervisores'] = $supervisores[0];
        } else {
            $data['supervisores'] = $supervisores ? '-1' : '';
        }


        $data['depto'] = form_dropdown('id_departamento', $deptos, '', 'id="depto" class="form-control filtro"');
        $data['area'] = form_dropdown('id_area', $areas, '', 'id="area" class="form-control"');
        $data['setor'] = form_dropdown('id_setor', $setores, '', 'id="setor" class="form-control"');
        $data['cargo'] = form_dropdown('id_cargo', $cargos, '', 'id="cargo" class="form-control"');
        $data['funcao'] = form_dropdown('id_funcao', $funcoes, '', 'id="funcao" class="form-control"');
        $data['municipio'] = form_dropdown('municipio', $municipios, '', 'id="municipio" class="form-control filtro"');
        $data['id_usuarios'] = form_multiselect('id_usuario[]', $usuarios, $usuariosSelecionados, 'id="id_usuarios" class="demo1" size="8"');

        echo json_encode($data);
    }


    public function atualizarFiltros()
    {
        parse_str($this->input->post('busca'), $busca);
        $buscaIdUsuarios = $this->input->post('id_usuarios');

        $areas = $this->getAreas($busca);
        $setores = $this->getSetores($busca);
        $cargos = $this->getCargos($busca);
        $funcoes = $this->getFuncoes($busca);
        $municipios = $this->getMunicipios($busca);
        $idUsuarios = $this->getUsuarios($busca);

        $data['area'] = form_dropdown('id_area', $areas, $busca['id_area'], 'id="area" class="form-control"');
        $data['setor'] = form_dropdown('id_setor', $setores, $busca['id_setor'], 'id="setor" class="form-control"');
        $data['cargo'] = form_dropdown('id_cargo', $cargos, $busca['id_cargo'], 'id="cargo" class="form-control"');
        $data['funcao'] = form_dropdown('id_funcao', $funcoes, $busca['id_funcao'], 'id="funcao" class="form-control"');
        $data['municipio'] = form_dropdown('municipio', $municipios, $busca['municipio'], 'id="municipio" class="form-control filtro"');
        $data['id_usuarios'] = form_multiselect('id_usuario[]', $idUsuarios, $buscaIdUsuarios, 'id="id_usuarios" class="demo1" size="8"');

        echo json_encode($data);
    }


    public function ajaxEditHorario()
    {
        $id = $this->input->post('id');
        $idEscola = $this->input->post('id_escola');
        $idProfissional = $this->input->post('id_profissional');


        $this->db->select('c.id, c.dia_semana, b.id AS id_os_profissional, c.id_funcao');
        $this->db->select("TIME_FORMAT(c.horario_inicio, '%H:%i') AS horario_inicio", false);
        $this->db->select("TIME_FORMAT(c.horario_termino, '%H:%i') AS horario_termino", false);
        $this->db->join('ei_ordem_servico_profissionais b', 'b.id_ordem_servico_escola = a.id', 'left');
        if ($id) {
            $this->db->join('ei_ordem_servico_horarios c', 'c.id_os_profissional = b.id', 'left');
            $this->db->where('a.id', $idEscola);
            $this->db->where('c.id', $id);
        } else {
            $this->db->join('ei_ordem_servico_horarios c', "c.id_os_profissional = b.id AND c.id IS NULL", 'left');
            $this->db->where('a.id', $idEscola);
            $this->db->where('b.id', $idProfissional);
            $this->db->group_by('a.id');
        }
        $data = $this->db->get('ei_ordem_servico_escolas a')->row_array();


        $this->db->select('a.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $this->session->userdata('empresa'));
        $this->db->where('a.id_ordem_servico_escola', $idEscola);
        $this->db->order_by('b.nome', 'asc');
        $profissionais = ['' => 'selecione...'] + array_column($this->db->get('ei_ordem_servico_profissionais a')->result(), 'nome', 'id');

        $idOSProfissional = $data['id_os_profissional'] ?? '';
        $data['id_os_profissional'] = form_dropdown('id_os_profissional', $profissionais, $idOSProfissional, 'class="form-control"');

        $funcoes = $this->getFuncoes();
        $funcoes[''] = 'selecione...';
        $idFuncao = $data['id_funcao'] ?? '';
        $data['id_funcao'] = form_dropdown('id_funcao', $funcoes, $idFuncao, 'class="form-control"');

        $this->db->select('a.id, b.nome, f.id_os_aluno');
        $this->db->join('ei_alunos b', 'b.id = a.id_aluno');
        $this->db->join('ei_ordem_servico_escolas c', 'c.id = a.id_ordem_servico_escola');
        $this->db->join('ei_ordem_servico_profissionais d', 'd.id_ordem_servico_escola = c.id', 'left');
        $this->db->join('ei_ordem_servico_horarios e', "e.id_os_profissional = d.id AND e.id = '{$id}'", 'left');
        $this->db->join('ei_ordem_servico_turmas f', 'f.id_os_aluno = a.id AND f.id_os_horario = e.id', 'left');
        $this->db->where('c.id', $idEscola);
        $arrAlunos = $this->db->get('ei_ordem_servico_alunos a')->result();
        $alunos = array_column($arrAlunos, 'nome', 'id');
        $alunosSelecionados = array_column($arrAlunos, 'id_os_aluno');

        $data['alunos'] = form_multiselect('alunos[]', $alunos, $alunosSelecionados, 'id="alunos" class="demo2" size="8"');
        echo json_encode($data);
    }


    public function ajaxEditDados()
    {
        $id = $this->input->post('id');
        $idOSProfissional = $this->input->post('id_os_profissional');

        $this->db->select('a.*, e.ano, e.semestre, b.id_usuario, b.id_supervisor, b.id_ordem_servico_escola, c.nome AS nome_usuario', false);
        $this->db->select("(CASE a.dia_semana WHEN 0 THEN 'Domingo' WHEN 1 THEN 'Segunda-feira' WHEN 2 THEN 'Terça-feira' WHEN 3 THEN 'Quarta-feira' WHEN 4 THEN 'Quinta-feira' WHEN 5 THEN 'Sexta-feira' WHEN 6 THEN 'Sábado' END) AS nome_semana", false);
        $this->db->select("(CASE a.periodo WHEN 0 THEN 'Madrugada' WHEN 1 THEN 'Manhã' WHEN 2 THEN 'Tarde' WHEN 3 THEN 'Noite' END) AS nome_periodo", false);
        $this->db->join('ei_ordem_servico_profissionais b', 'b.id = a.id_os_profissional');
        $this->db->join('usuarios c', 'c.id = b.id_usuario');
        $this->db->join('ei_ordem_servico_escolas d', 'd.id = b.id_ordem_servico_escola');
        $this->db->join('ei_ordem_servico e', 'e.id = d.id_ordem_servico');
        $this->db->where('a.id', $id);
        $this->db->where('b.id', $idOSProfissional);
        $data = $this->db->get('ei_ordem_servico_horarios a')->row();


        if (empty($data)) {
            $this->db->select(["a.*, d.ano, d.semestre, b.nome AS nome_usuario, 'Integral' AS nome_periodo, 'Sem cadastro' AS nome_semana"], false);
            $this->db->join('usuarios b', 'b.id = a.id_usuario');
            $this->db->join('ei_ordem_servico_escolas c', 'c.id = a.id_ordem_servico_escola');
            $this->db->join('ei_ordem_servico d', 'd.id = c.id_ordem_servico');
            $this->db->where('a.id', $idOSProfissional);
            $data = $this->db->get('ei_ordem_servico_profissionais a')->row();
        }


        if ($data) {
            if ($data->valor_hora) {
                $data->valor_hora = number_format($data->valor_hora, 2, ',', '.');
            }
            if ($data->qtde_dias) {
                $data->qtde_dias = number_format($data->qtde_dias, 2, ',', '');
            }
            if ($data->horas_diarias) {
                $data->horas_diarias = number_format($data->horas_diarias, 2, ',', '');
            }
            if ($data->horas_semanais) {
                $data->horas_semanais = number_format($data->horas_semanais, 2, ',', '');
            }
            if ($data->horas_semestre) {
                $data->horas_semestre = number_format($data->horas_semestre, 2, ',', '');
            }
            if ($data->horas_mensais) {
                $data->horas_mensais = number_format($data->horas_mensais, 2, ',', '');
            }
            if ($data->valor_hora_mensal) {
                $data->valor_hora_mensal = number_format($data->valor_hora_mensal, 2, ',', '.');
            }
            if ($data->valor_hora_operacional) {
                $data->valor_hora_operacional = number_format($data->valor_hora_operacional, 2, ',', '.');
            }
            $data->desconto_mensal_1 = number_format($data->desconto_mensal_1, 2, ',', '');
            $data->desconto_mensal_2 = number_format($data->desconto_mensal_2, 2, ',', '');
            $data->desconto_mensal_3 = number_format($data->desconto_mensal_3, 2, ',', '');
            $data->desconto_mensal_4 = number_format($data->desconto_mensal_4, 2, ',', '');
            $data->desconto_mensal_5 = number_format($data->desconto_mensal_5, 2, ',', '');
            $data->desconto_mensal_6 = number_format($data->desconto_mensal_6, 2, ',', '');

            if ($data->valor_mensal_1) {
                $data->valor_mensal_1 = number_format($data->valor_mensal_1, 2, ',', '.');
            }
            if ($data->valor_mensal_2) {
                $data->valor_mensal_2 = number_format($data->valor_mensal_2, 2, ',', '.');
            }
            if ($data->valor_mensal_3) {
                $data->valor_mensal_3 = number_format($data->valor_mensal_3, 2, ',', '.');
            }
            if ($data->valor_mensal_4) {
                $data->valor_mensal_4 = number_format($data->valor_mensal_4, 2, ',', '.');
            }
            if ($data->valor_mensal_5) {
                $data->valor_mensal_5 = number_format($data->valor_mensal_5, 2, ',', '.');
            }
            if ($data->valor_mensal_6) {
                $data->valor_mensal_6 = number_format($data->valor_mensal_6, 2, ',', '.');
            }
            if ($data->horas_mensais_custo) {
                $data->horas_mensais_custo = preg_replace('/(\d+):(\d+):(\d+)/', '$1:$2', $data->horas_mensais_custo);
            }
            if ($data->data_inicio_contrato) {
                $data->data_inicio_contrato = date('d/m/Y', strtotime($data->data_inicio_contrato));
            }
            if ($data->data_termino_contrato) {
                $data->data_termino_contrato = date('d/m/Y', strtotime($data->data_termino_contrato));
            }

        } else {
            $fields = $this->db->list_fields('ei_ordem_servico_profissionais');
            $data = array_combine(array_flip($fields), array_pad(array(), count($fields), null));
            $data['id'] = $id;
        }


        $sql = "SELECT s.id, s.nome
                FROM(SELECT b.id, b.nome 
                     FROM ei_ordem_servico_profissionais a 
                     INNER JOIN usuarios b ON b.id = a.id_supervisor
                     WHERE a.id = '{$data->id}'
                     UNION 
                     SELECT d.id, d.nome 
                     FROM ei_coordenacao c
                     INNER JOIN usuarios d ON d.id = c.id_usuario
                     WHERE d.empresa = '{$this->session->userdata('empresa')}' AND 
                           c.ano = '{$data->ano}' AND 
                           c.semestre = '{$data->semestre}' AND 
                           c.is_supervisor = 1) s 
                ORDER BY s.nome ASC";
        $supervisores = $this->db->query($sql)->result();

        $nomeUsuario = $data->nome_usuario;
        $nomeSemana = $data->nome_semana;
        $nomePeriodo = $data->nome_periodo;
        $idSupervisor = $data->id_supervisor;

        unset($data->nome_usuario, $data->nome_semana, $data->nome_periodo, $data->id_supervisor);

        $retorno = array(
            'data' => $data,
            'input' => array(
                'nome_usuario' => $nomeUsuario,
                'nome_semana' => $nomeSemana,
                'nome_periodo' => $nomePeriodo
            )
        );

        $retorno['input']['supervisores'] = form_dropdown('', ['' => 'selecione...'] + array_column($supervisores, 'nome', 'id'), $idSupervisor);


        echo json_encode($retorno);
    }


    public function ajaxEditSubstituto1()
    {
        $id = $this->input->post('id');

        $this->db->select('id, id_usuario_sub1, id_ordem_servico_escola');
        $this->db->select(["DATE_FORMAT(data_substituicao1, '%d/%m/%Y') AS data_substituicao1"], false);
        $this->db->where('id', $id);
        $data = $this->db->get('ei_ordem_servico_profissionais')->row();

        $municipios = $this->getMunicipios();
        $usuarios = ['' => 'selecione...'] + $this->getUsuarios();
        /*
                $this->db->select('id_usuario');
                $this->db->where('id_ordem_servico_escola', $data->id_ordem_servico_escola);
                $rows = $this->db->get('ei_ordem_servico_profissionais')->result();
                $usuariosSelecionados = ['' => 'selecione...'] + array_column($rows, 'id_usuario');*/

        $data->municipio = form_dropdown('municipio', $municipios, '', 'id="municipio_sub1" class="form-control"');
        $data->id_usuario_sub1 = form_dropdown('id_usuario_sub1', $usuarios, $data->id_usuario_sub1);

        echo json_encode($data);
    }

    public function ajaxEditSubstituto2()
    {
        $id = $this->input->post('id');

        $this->db->select('id, id_usuario_sub2, id_ordem_servico_escola');
        $this->db->select(["DATE_FORMAT(data_substituicao2, '%d/%m/%Y') AS data_substituicao2"], false);
        $this->db->where('id', $id);
        $data = $this->db->get('ei_ordem_servico_profissionais')->row();

        $municipios = $this->getMunicipios();
        $usuarios = ['' => 'selecione...'] + $this->getUsuarios();
        /*
                $this->db->select('id_usuario');
                $this->db->where('id_ordem_servico_escola', $data->id_ordem_servico_escola);
                $rows = $this->db->get('ei_ordem_servico_profissionais')->result();
                $usuariosSelecionados = ['' => 'selecione...'] + array_column($rows, 'id_usuario');*/

        $data->municipio = form_dropdown('municipio', $municipios, '', 'id="municipio_sub2" class="form-control"');
        $data->id_usuario_sub2 = form_dropdown('id_usuario_sub1', $usuarios, $data->id_usuario_sub2);


        echo json_encode($data);
    }

    public function atualizarSubstituto()
    {
        $municipio = $this->input->post('municipio');
        $idUsuario = $this->input->post('id_usuario');

        $where = array('id !=', $idUsuario);
        if ($municipio) {
            $where['municipio'] = $municipio;
        }
        $usuarios = ['' => 'selecione...'] + $this->getUsuarios($where);

        $data['usuario'] = form_dropdown('usuario', $usuarios, '');

        echo json_encode($data);
    }


    public function ajaxSave()
    {
        $idOSEscola = $this->input->post('id_ordem_servico_escola');
        $idUsuarios = $this->input->post('id_usuario');
        if (empty($idUsuarios)) {
            $idUsuarios = array();
        }
        $idSupervisor = $this->input->post('id_supervisor');
        if (empty($idSupervisor)) {
            $idSupervisor = null;
        }

        $this->db->trans_start();

        $this->db->where('id_ordem_servico_escola', $idOSEscola);
        $this->db->where_not_in('id_usuario', $idUsuarios + array(0));
        $this->db->delete('ei_ordem_servico_profissionais');

        $this->db->select('id_usuario');
        $this->db->where('id_ordem_servico_escola', $idOSEscola);
        $profissionais = array_column($this->db->get('ei_ordem_servico_profissionais')->result(), 'id_usuario');

        $idNovosUsuarios = array_diff($idUsuarios, $profissionais);

        foreach ($idNovosUsuarios as $idNovoUsuario) {
            $this->db->select('a.municipio, b.id AS id_departamento, c.id AS id_area');
            $this->db->select('d.id AS id_setor, e.id AS id_cargo, f.id AS id_funcao');
            $this->db->join('empresa_departamentos b', 'b.nome = a.depto', 'left');
            $this->db->join('empresa_areas c', 'c.nome = a.area', 'left');
            $this->db->join('empresa_setores d', 'd.nome = a.setor', 'left');
            $this->db->join('empresa_cargos e', 'e.nome = a.cargo', 'left');
            $this->db->join('empresa_funcoes f', 'f.nome = a.funcao', 'left');
            $this->db->where('a.id', $idNovoUsuario);
            $data = $this->db->get('usuarios a')->row_array();

            $data['id_ordem_servico_escola'] = $idOSEscola;
            $data['id_usuario'] = $idNovoUsuario;

            $this->db->insert('ei_ordem_servico_profissionais', $data);
        }

        if ($idSupervisor != '-1') {
            $this->db->set('id_supervisor', $idSupervisor);
            $this->db->where('id_ordem_servico_escola', $idOSEscola);
            $this->db->where_in('id_usuario', $idUsuarios + array(0));
            $this->db->update('ei_ordem_servico_profissionais');
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }


    public function ajaxAddHorarios()
    {
        $isOSProfissioanl = $this->input->post('id_os_profissional');
        if (empty($isOSProfissioanl)) {
            exit(json_encode(array('erro' => 'O cuidador não pode ficar em branco')));
        }

        $diasSemana = $this->input->post('dia_semana');
        if (empty($diasSemana)) {
            $diasSemana = array();
        }
        $horarioInicio = $this->input->post('horario_inicio');
        if (empty($horarioInicio)) {
            $horarioInicio = array();
        }
        $horarioTermino = $this->input->post('horario_termino');
        if (empty($horarioTermino)) {
            $horarioTermino = array();
        }


        $this->db->trans_start();


        $arrHorarios = array();
        foreach ($diasSemana as $k => $diaSemana) {
            if (strlen($diaSemana) > 0 and $horarioInicio[$k] and $horarioTermino[$k]) {
                $totalDiasMes = $this->contarSemanasDoMes($isOSProfissioanl, $diaSemana);
                $periodo = strstr($horarioInicio[$k], ':', true);
                if (strlen($periodo) > 0) {
                    $periodo = floor(intval($periodo) / 6);
                }
                $data = array(
                    'id_os_profissional' => $isOSProfissioanl,
                    'id_funcao' => $this->input->post('id_funcao'),
                    'dia_semana' => $diaSemana,
                    'periodo' => $periodo,
                    'horario_inicio' => $horarioInicio[$k],
                    'horario_termino' => $horarioTermino[$k],
                    'total_dias_mes1' => $totalDiasMes[0],
                    'total_dias_mes2' => $totalDiasMes[1],
                    'total_dias_mes3' => $totalDiasMes[2],
                    'total_dias_mes4' => $totalDiasMes[3],
                    'total_dias_mes5' => $totalDiasMes[4],
                    'total_dias_mes6' => $totalDiasMes[5]
                );

                $this->db->insert('ei_ordem_servico_horarios', $data);
                $arrHorarios[] = $this->db->insert_id();
            }
        }


        $arrAlunos = $this->input->post('alunos');
        if (empty($arrAlunos)) {
            $arrAlunos = array();
        }

        $data2 = array();
        foreach ($arrAlunos as $idOSAluno) {
            foreach ($arrHorarios as $idOSHorario) {
                $data2[] = [
                    'id_os_aluno' => $idOSAluno,
                    'id_os_horario' => $idOSHorario
                ];
            }
        }

        if ($data2) {
            $this->db->insert_batch('ei_ordem_servico_turmas', $data2);
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }


    public function ajaxUpdateHorario()
    {
        $id = $this->input->post('id');
        $data = $this->input->post();
        $arrAlunos = $this->input->post('alunos');
        if (empty($arrAlunos)) {
            $arrAlunos = array();
        }
        unset($data['id'], $data['alunos']);
        if (empty($data['id_os_profissional'])) {
            exit(json_encode(array('erro' => 'O cuidador não pode ficar em branco')));
        }
        $data['dia_semana'] = $data['dia_semana'][0] ?? null;
        $data['horario_inicio'] = $data['horario_inicio'][0] ?? null;
        $data['horario_termino'] = $data['horario_termino'][0] ?? null;
        $periodo = strstr($data['horario_inicio'], ':', true);
        if (strlen($periodo) > 0) {
            $data['periodo'] = floor(intval($periodo) / 6);
        } else {
            $data['periodo'] = null;
        }

        $this->db->trans_start();

        $this->db->select('dia_semana, horario_inicio, horario_termino');
        $this->db->where('id !=', $id);
        $this->db->where('id_os_profissional', $data['id_os_profissional']);
        $this->db->where('dia_semana', $data['dia_semana']);
        $this->db->where('horario_inicio', $data['horario_inicio']);
        $this->db->where('horario_termino', $data['horario_termino']);
        $count = $this->db->get('ei_ordem_servico_horarios')->num_rows();
        if ($count) {
            exit(json_encode(array('erro' => 'O dia e horários já foram cadastrados para este cuidador.')));
        }

        $totalDiasMes = $this->contarSemanasDoMes($data['id_os_profissional'], $data['dia_semana']);

        if ($totalDiasMes) {
            $data['total_dias_mes1'] = $totalDiasMes[0];
            $data['total_dias_mes2'] = $totalDiasMes[1];
            $data['total_dias_mes3'] = $totalDiasMes[2];
            $data['total_dias_mes4'] = $totalDiasMes[3];
            $data['total_dias_mes5'] = $totalDiasMes[4];
            $data['total_dias_mes6'] = $totalDiasMes[5];
        } else {
            $data['total_dias_mes1'] = null;
            $data['total_dias_mes2'] = null;
            $data['total_dias_mes3'] = null;
            $data['total_dias_mes4'] = null;
            $data['total_dias_mes5'] = null;
            $data['total_dias_mes6'] = null;
        }

        $this->db->update('ei_ordem_servico_horarios', $data, array('id' => $id));

        $data2 = array();
        foreach ($arrAlunos as $idOSAluno) {
            $data2[] = [
                'id_os_aluno' => $idOSAluno,
                'id_os_horario' => $id
            ];
        }

        $this->db->delete('ei_ordem_servico_turmas', ['id_os_horario' => $id]);

        if ($data2) {
            $this->db->insert_batch('ei_ordem_servico_turmas', $data2);
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }


    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_ordem_servico_profissionais', array('id' => $id));

        echo json_encode(array('status' => $status !== false));
    }


    public function ajaxDeleteHorario()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_ordem_servico_horarios', array('id' => $id));

        echo json_encode(array('status' => $status !== false));
    }


    public function ajaxSaveDados()
    {
        $data = $this->input->post();
        $id = $this->input->post('id');
        $idOSProfissional = $this->input->post('id_os_profissional');

        if (empty($data['id_supervisor'])) {
            $data['id_supervisor'] = null;
        }


        if ($id) {
            $idSupervisor = $data['id_supervisor'];
            unset($data['id'], $data['id_usuario'], $data['id_ordem_servico_escola'], $data['id_supervisor']);
            $tipo = array_column($this->db->field_data('ei_ordem_servico_horarios'), 'type', 'name');
        } else {
            unset($data['id'], $data['id_os_profissional']);
            $tipo = array_column($this->db->field_data('ei_ordem_servico_profissionais'), 'type', 'name');
        }

        foreach ($data as $campo => $valor) {
            if ($tipo[$campo] == 'decimal') {
                $data[$campo] = str_replace(array('.', ','), array('', '.'), $valor);
            } elseif ($tipo[$campo] == 'date') {
                if (strlen($data[$campo])) {
                    $data[$campo] = date('Y-m-d', strtotime(str_replace('/', '-', $data[$campo])));
                } else {
                    $data[$campo] = null;
                }
            }
        }


        if ($id) {
            $horario = $this->db->get_where('ei_ordem_servico_horarios', ['id' => $id])->row();
            $this->db->where('id_os_profissional', $idOSProfissional);
            $this->db->where('periodo', $horario->periodo);
            $status = $this->db->update('ei_ordem_servico_horarios', $data);
            $this->db->update('ei_ordem_servico_profissionais', ['id_supervisor' => $idSupervisor], ['id' => $idOSProfissional]);
        } else {
            if ($idOSProfissional) {
                if (!$this->db->get_where('ei_ordem_servico_profissionais', ['id' => $idOSProfissional])->num_rows()) {
                    exit(json_encode(array('erro' => 'Não foi possível atualizar os dados do profissional.')));
                }
                $status = $this->db->update('ei_ordem_servico_profissionais', $data, ['id' => $idOSProfissional]);
            } else {
                $status = $this->db->insert('ei_ordem_servico_profissionais', $data);
            }
        }


        echo json_encode(array('status' => $status !== false));
    }

    public function ajaxSaveSubstituto1()
    {
        $data = $this->input->post();
        $id = $this->input->post('id');
        if (empty($data['id_usuario_sub1'])) {
            exit(json_encode(array('erro' => 'O profissional substituto é obrigatório')));
        }
        if (strlen($data['data_substituicao1']) == 0) {
            exit(json_encode(array('erro' => 'A data de início é obrigatória')));
        } elseif ($data['data_substituicao1'] != date('d/m/Y', strtotime(str_replace('/', '-', $data['data_substituicao1'])))) {
            exit(json_encode(array('erro' => 'A data de início é inválida')));
        }
        unset($data['id']);
        $data['data_substituicao1'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_substituicao1'])));

        $status = $this->db->update('ei_ordem_servico_profissionais', $data, ['id' => $id]);

        echo json_encode(array('status' => $status !== false));
    }

    public function ajaxSaveSubstituto2()
    {
        $data = $this->input->post();
        $id = $this->input->post('id');
        if (empty($data['id_usuario_sub2'])) {
            exit(json_encode(array('erro' => 'O profissional substituto é obrigatório')));
        }
        if (strlen($data['data_substituicao2']) == 0) {
            exit(json_encode(array('erro' => 'A data de início é obrigatória')));
        } elseif ($data['data_substituicao2'] != date('d/m/Y', strtotime(str_replace('/', '-', $data['data_substituicao2'])))) {
            exit(json_encode(array('erro' => 'A data de início é inválida')));
        }
        unset($data['id']);
        $data['data_substituicao2'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_substituicao2'])));

        $status = $this->db->update('ei_ordem_servico_profissionais', $data, ['id' => $id]);

        echo json_encode(array('status' => $status !== false));
    }

    /*
    |---------------------------------------------------------------------------
    | Funções privadas
    |---------------------------------------------------------------------------
    */

    private function getDepartamentos()
    {
        $this->db->select('id, nome');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('nome', 'Educação Inclusiva');
        $rows = $this->db->get('empresa_departamentos')->result();
        return array_column($rows, 'nome', 'id');
    }

    private function getAreas($where = array())
    {
        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('b.nome', 'Educação Inclusiva');
        if (!empty($where['id_depto'])) {
            $this->db->where('b.id', $where['id_depto']);
        }
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('empresa_areas a')->result();
        return ['' => 'Todas'] + array_column($rows, 'nome', 'id');
    }

    private function getSetores($where = array())
    {
        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        $this->db->where('c.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('c.nome', 'Educação Inclusiva');
        if (!empty($where['id_depto'])) {
            $this->db->where('c.id', $where['id_depto']);
        }
        if (!empty($where['id_area'])) {
            $this->db->where('b.id', $where['id_area']);
        }
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('empresa_setores a')->result();
        return ['' => 'Todos'] + array_column($rows, 'nome', 'id');
    }

    private function getCargos($where = array())
    {
        $this->db->select('a.id, a.nome');
        $this->db->join('usuarios b', 'b.cargo = a.nome');
        $this->db->join('empresa_departamentos c', 'c.nome = b.depto', 'left');
        $this->db->join('empresa_areas d', 'd.nome = b.area', 'left');
        $this->db->join('empresa_setores e', 'e.nome = b.setor', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('c.nome', 'Educação Inclusiva');
        if (!empty($where['id_area'])) {
            $this->db->where('d.id', $where['id_area']);
        }
        if (!empty($where['id_setor'])) {
            $this->db->where('e.id', $where['id_setor']);
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('empresa_cargos a')->result();
        return ['' => 'Todos'] + array_column($rows, 'nome', 'id');
    }

    private function getFuncoes($where = array())
    {
        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('usuarios c', 'c.funcao = a.nome');
        $this->db->join('empresa_departamentos d', 'd.nome = c.depto', 'left');
        $this->db->join('empresa_areas e', 'e.nome = c.area', 'left');
        $this->db->join('empresa_setores f', 'f.nome = c.setor', 'left');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('d.nome', 'Educação Inclusiva');
        if (!empty($where['id_area'])) {
            $this->db->where('e.id', $where['id_area']);
        }
        if (!empty($where['id_setor'])) {
            $this->db->where('f.id', $where['id_setor']);
        }
        if (!empty($where['id_cargo'])) {
            $this->db->where('b.id', $where['id_cargo']);
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('empresa_funcoes a')->result();
        return ['' => 'Todas'] + array_column($rows, 'nome', 'id');
    }

    private function getMunicipios($where = array())
    {
        $this->db->select('a.municipio');
        $this->db->join('empresa_areas b', 'b.nome = a.area');
        $this->db->join('empresa_setores c', 'c.nome = a.setor');
        $this->db->join('empresa_cargos d', 'd.nome = a.cargo');
        $this->db->join('empresa_funcoes e', 'e.nome = a.funcao');
        $this->db->where('a.empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(a.municipio) >', 0);
        $this->db->where('a.depto', 'Educação Inclusiva');
        if (!empty($where['id_area'])) {
            $this->db->where('b.id', $where['id_area']);
        }
        if (!empty($where['id_setor'])) {
            $this->db->where('c.id', $where['id_setor']);
        }
        if (!empty($where['id_cargo'])) {
            $this->db->where('d.id', $where['id_cargo']);
        }
        if (!empty($where['id_funcao'])) {
            $this->db->where('e.id', $where['id_funcao']);
        }
        $this->db->group_by('a.municipio');
        $this->db->order_by('a.municipio', 'asc');
        $rows = $this->db->get('usuarios a')->result();
        return ['' => 'Todos'] + array_column($rows, 'municipio', 'municipio');
    }

    private function getUsuarios($where = array())
    {
        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.nome = a.area');
        $this->db->join('empresa_setores c', 'c.nome = a.setor');
        $this->db->join('empresa_cargos d', 'd.nome = a.cargo');
        $this->db->join('empresa_funcoes e', 'e.nome = a.funcao');
        $this->db->where('a.empresa', $this->session->userdata('empresa'));
        $this->db->where('a.tipo', 'funcionario');
        $this->db->where('a.depto', 'Educação Inclusiva');
        if (!empty($where['id_area'])) {
            $this->db->where('b.id', $where['id_area']);
        }
        if (!empty($where['id_setor'])) {
            $this->db->where('c.id', $where['id_setor']);
        }
        if (!empty($where['id_cargo'])) {
            $this->db->where('d.id', $where['id_cargo']);
        }
        if (!empty($where['id_funcao'])) {
            $this->db->where('e.id', $where['id_funcao']);
        }
        if (!empty($where['municipio'])) {
            $this->db->where('a.municipio', $where['municipio']);
        }
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('usuarios a')->result();
        return array_column($rows, 'nome', 'id');
    }

    //--------------------------------------------------------------------------

    /**
     * Calcula o total de dias de uma semana para cada mês de um semestre
     */
    private function contarSemanasDoMes($idOSProfissional, $diaDaSemana)
    {
        switch ($diaDaSemana) {
            case '0':
                $semana = 'sun';
                break;
            case '1':
                $semana = 'mon';
                break;
            case '2':
                $semana = 'tue';
                break;
            case '3':
                $semana = 'wed';
                break;
            case '4':
                $semana = 'thu';
                break;
            case '5':
                $semana = 'fri';
                break;
            case '6':
                $semana = 'sat';
                break;
            default:
                return false;
        }

        $this->db->select('c.ano, c.semestre');
        $this->db->select("DATE_FORMAT(MIN(f.data_inicio), '%M %Y') AS mes_inicial", false);
        $this->db->select("DATE_FORMAT(MAX(f.data_termino), '%M %Y') AS mes_final", false);
        $this->db->select('MIN(f.data_inicio) AS data_inicio', false);
        $this->db->select('MAX(f.data_termino) AS data_termino', false);
        $this->db->join('ei_ordem_servico_escolas b', 'b.id = a.id_ordem_servico_escola');
        $this->db->join('ei_ordem_servico c', 'c.id = b.id_ordem_servico');
        $this->db->join('ei_ordem_servico_horarios d', 'd.id_os_profissional = a.id', 'left');
        $this->db->join('ei_ordem_servico_turmas e', 'e.id_os_horario = d.id', 'left');
        $this->db->join('ei_ordem_servico_alunos f', 'f.id = e.id_os_aluno', 'left');
        $this->db->where('a.id', $idOSProfissional);
        $this->db->group_by('a.id');
        $row = $this->db->get('ei_ordem_servico_profissionais a')->row();

        $mesInicial = intval($row->semestre) == 2 ? 7 : 1;
        $mesFinal = $mesInicial + 5;
        $mesAno = array();
        for ($i = $mesInicial; $i <= $mesFinal; $i++) {
            $mesAno[] = date('F Y', strtotime('01-' . str_pad($i, 2, '0', STR_PAD_LEFT) . '-' . $row->ano));
        }

        $data = array();
        foreach ($mesAno as $mes) {
            if ($mes == $row->mes_inicial and $row->data_inicio) {
                $semanaInicial = date('W', strtotime("{$semana} {$row->data_inicio}"));
            } else {
                $semanaInicial = date('W', strtotime("first {$semana} of {$mes}"));
            }
            if ($mes == $row->mes_final and $row->data_termino) {
                $semanaFinal = date('W', strtotime($semana, strtotime("{$row->data_termino} -1 week +1 day"))) + 1;
            } else {
                $semanaFinal = date('W', strtotime("last {$semana} of {$mes} -1 week")) + 1;
            }

            $data[] = $semanaFinal - ($semanaInicial - 1);
        }

        return $data;
    }

}