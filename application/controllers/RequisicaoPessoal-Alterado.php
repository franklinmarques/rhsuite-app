<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequisicaoPessoal extends MY_Controller
{
    public function index()
    {
        $this->initialize();
    }

    public function admFin()
    {
        $this->initialize('ADM-FIN');
    }

    public function cd()
    {
        $this->initialize('CD');
    }

    public function cdh()
    {
        $this->initialize('CDH');
    }

    public function ei()
    {
        $this->initialize('EI');
    }

    public function gexec()
    {
        $this->initialize('GExec');
    }

    public function icom()
    {
        $this->initialize('ICOM');
    }

    public function papd()
    {
        $this->initialize('PAPD');
    }

    public function st()
    {
        $this->initialize('ST');
    }

    public function initialize($modulo = null)
    {
        $id = $this->session->userdata('id');
        $empresa = $this->session->userdata('empresa');
        $tipo = $this->session->userdata('tipo');

        $data = array(
            'tipo' => $tipo,
            'nivel' => $this->session->userdata('nivel'),
            'usuario' => $this->db->select('depto')->where('id', $id)->get('usuarios')->row_array(),
            'deptos' => array(),
            'areas' => array(),
            'setores' => array(),
            'cargos' => array('' => 'selecione...'),
            'funcoes' => array('' => 'selecione...'),
            'modulo' => ''
        );


        $this->db->select('depto, area, setor');
        $this->db->where('id', $id);
        $usuario = $this->db->get('usuarios')->row();

        if ($tipo != 'funcionario') {
            $data['deptos'] = array('' => 'Todos');
            $data['areas'] = array('' => 'Todas');
            $data['setores'] = array('' => 'Todos');
        }

        switch ($modulo) {
            case 'ADM-FIN':
                $data['modulo'] = '';
                break;
            case 'CD':
                $data['modulo'] = 'Cuidadores';
                break;
            case 'CDH':
                $data['modulo'] = '';
                break;
            case 'EI':
                $data['modulo'] = 'Educação Inclusiva';
                break;
            case 'GExec':
                $data['modulo'] = '';
                break;
            case 'ICOM':
                $data['modulo'] = '';
                break;
            case 'PAPD':
                $data['modulo'] = 'Pacientes';
                break;
            case 'ST':
                $data['modulo'] = 'Serviços Terceirizados';
        }

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        if ($tipo == 'funcionario') {
            $this->db->where('nome', $usuario->depto);
        }
        $this->db->order_by('nome', 'asc');
        $deptos = $this->db->get('empresa_departamentos')->result();
        foreach ($deptos as $depto) {
            $data['deptos'][$depto->id] = $depto->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        $this->db->where('b.id_empresa', $empresa);
        if ($tipo == 'funcionario') {
            $this->db->where('b.nome', $usuario->depto);
            $this->db->where('a.nome', $usuario->area);
        }
        $this->db->order_by('a.nome', 'asc');
        $areas = $this->db->get('empresa_areas a')->result();
        foreach ($areas as $area) {
            $data['areas'][$area->id] = $area->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        $this->db->where('c.id_empresa', $empresa);
        if ($tipo == 'funcionario') {
            $this->db->where('c.nome', $usuario->depto);
            $this->db->where('b.nome', $usuario->area);
            $this->db->where('a.nome', $usuario->setor);
        }
        $this->db->order_by('a.nome', 'asc');
        $setores = $this->db->get('empresa_setores a')->result();
        foreach ($setores as $setor) {
            $data['setores'][$setor->id] = $setor->nome;
        }

        $this->db->select('id, nome');
        $this->db->where('empresa', $empresa);
        $this->db->where_in('tipo', array('funcionario', 'selecionador'));
        if ($tipo == 'funcionario') {
            $this->db->where('id', $id);
        } else {
            $data['requisitantes'] = array('' => 'selecione...');
        }
        $this->db->order_by('nome', 'asc');
        $requisitantes = $this->db->get('usuarios')->result();
        foreach ($requisitantes as $requisitante) {
            $data['requisitantes'][$requisitante->id] = $requisitante->nome;
        }

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $cargos = $this->db->get('empresa_cargos')->result();
        foreach ($cargos as $cargo) {
            $data['cargos'][$cargo->id] = $cargo->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->order_by('a.nome', 'asc');
        $funcoes = $this->db->get('empresa_funcoes a')->result();
        foreach ($funcoes as $funcao) {
            $data['funcoes'][$funcao->id] = $funcao->nome;
        }

        $this->db->select('a.id_usuario AS id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $this->session->userdata('empresa'));
        $this->db->order_by('b.nome', 'asc');
        $sqlAprovadores = $this->db->get('requisicoes_pessoal_aprovadores a')->result();
        $aprovadores = ['' => 'selecione...'] + array_column($sqlAprovadores, 'nome', 'id');
        $data['aprovado_por'] = form_dropdown('aprovado_por', $aprovadores, '');

        $this->db->where('requisitante_interno', $id);
        $this->db->where('aprovado_por IS NOT NULL');
        $aprovadosPor = $this->db->get('requisicoes_pessoal')->num_rows();

//        $data['aprovadores'] = (in_array($id, $aprovadores) or $tipo == 'empresa' or $usuario->depto == 'Gestão de pessoas' or $aprovadosPor);
        $data['idUsuario'] = $tipo == 'funcionario' ? $id : '';
        $data['aprovadores'] = true;

        $this->load->view('requisicaoPessoal', $data);
        /*if ($this->session->userdata('tipo') == 'selecionador') {
            $this->load->view('requisicaoPessoal_selecionador', $data);
        } else {
            $this->load->view('requisicaoPessoal', $data);
        }*/
    }

    public function atualizarEstrutura($retorno = false)
    {
        $id = $this->session->userdata('id');
        $empresa = $this->session->userdata('empresa');
        $tipo = $this->session->userdata('tipo');

        $idDepto = $this->input->post('id_depto');
        $idArea = $this->input->post('id_area');
        $idSetor = $this->input->post('id_setor');
        $requisitanteInterno = $this->input->post('requisitante_interno');

        $isPost = is_array($this->input->post());
        if ($this->session->userdata('tipo') == 'funcionario' and $isPost == false) {
            $this->db->select('b.id AS id_depto, c.id AS id_area, d.id AS id_setor, a.id AS id_requisitante');
            $this->db->join('empresa_departamentos b', 'b.nome = a.depto');
            $this->db->join('empresa_areas c', 'c.nome = a.area');
            $this->db->join('empresa_setores d', 'd.nome = a.setor');
            $this->db->where('a.id', $this->session->userdata('id'));
            $usuario = $this->db->get('usuarios a')->row();

            $idDepto = $usuario->id_depto;
            $idArea = $usuario->id_area;
            $idSetor = $usuario->id_setor;
            $requisitanteInterno = $usuario->id_requisitante;
        }


        $filtro = array(
            'deptos' => array('' => 'Todos'),
            'areas' => array('' => 'Todas'),
            'setores' => array('' => 'Todos'),
            'requisitantes' => array('' => 'selecione...')
        );

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $deptos = $this->db->get('empresa_departamentos')->result();
        foreach ($deptos as $depto) {
            $filtro['deptos'][$depto->id] = $depto->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where('b.id', $idDepto);
        $this->db->order_by('a.nome', 'asc');
        $areas = $this->db->get('empresa_areas a')->result();
        foreach ($areas as $area) {
            $filtro['areas'][$area->id] = $area->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        $this->db->where('c.id_empresa', $empresa);
        $this->db->where('b.id', $idArea);
        $this->db->where('c.id', $idDepto);
        $this->db->order_by('a.nome', 'asc');
        $setores = $this->db->get('empresa_setores a')->result();
        foreach ($setores as $setor) {
            $filtro['setores'][$setor->id] = $setor->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.nome = a.depto', 'left');
        $this->db->join('empresa_areas c', 'c.nome = a.area', 'left');
        $this->db->join('empresa_setores d', 'd.nome = a.setor', 'left');
        $this->db->where('a.empresa', $empresa);
        $this->db->where_in('a.tipo', array('funcionario', 'selecionador'));
        $this->db->where_in('a.nivel_acesso', array(7, 8, 9, 10)); #Presidente, Gerente, Coordenador e supervisor
        if ($tipo == 'funcionari') {
            $this->db->where('a.id', $id);
        } else {
            if ($idDepto) {
                $this->db->where('b.id', $idDepto);
            }
            if ($idArea) {
//                $this->db->where('c.id', $idArea);
            }
            if ($idSetor) {
//                $this->db->where('d.id', $idSetor);
            }
            $filtro['requisitantes'] = array('' => 'selecione...');
        }
        $this->db->order_by('a.nome', 'asc');
        $requisitantes = $this->db->get('usuarios a')->result();
        foreach ($requisitantes as $requisitante) {
            $filtro['requisitantes'][$requisitante->id] = $requisitante->nome;
        }

        $data['depto'] = form_dropdown('id_depto', $filtro['deptos'], $idDepto, 'id="depto" class="form-control estrutura"');
        $data['area'] = form_dropdown('id_area', $filtro['areas'], $idArea, 'id="area" class="form-control estrutura"');
        $data['setor'] = form_dropdown('id_setor', $filtro['setores'], $idSetor, 'id="setor" class="form-control estrutura"');
        $data['requisitante'] = form_dropdown('requisitante_interno', $filtro['requisitantes'], $requisitanteInterno, 'class="form-control"');

        if ($retorno) {
            return $data;
        }

        echo json_encode($data);
    }

    public function atualizarFuncao($retorno = false)
    {
        $empresa = $this->session->userdata('empresa');

        $idCargo = $this->input->post('id_cargo');
        $idFuncao = $this->input->post('id_funcao');

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where('b.id', $idCargo);
        $this->db->order_by('a.nome', 'asc');
        $funcoes = $this->db->get('empresa_funcoes a')->result();
        $options = array('' => 'selecione...');
        foreach ($funcoes as $funcao) {
            $options[$funcao->id] = $funcao->nome;
        }

        $data['funcao'] = form_dropdown('id_funcao', $options, $idFuncao, 'class="form-control"');

        if ($retorno) {
            return $data;
        }

        echo json_encode($data);
    }

    public function ajax_list()
    {
        $post = $this->input->post();
        $tipoUsuario = $this->session->userdata('tipo');
        $nivelUsuario = $this->session->userdata('nivel');
        $representante = $this->session->userdata('id');

        $this->db->select('nome, depto');
        $this->db->where('id', $representante);
        $usuario = $this->db->get('usuarios')->row_array();


        $sql = "SELECT s.id, 
                       s.numero,
                       s.data_abertura,
                       s.status,
                       s.estagio,
                       s.selecionador,
                       s.cargo_funcao,
                       s.area_setor,
                       s.numero_vagas,
                       s.previsao_inicio, 
                       s.tipo_vaga, 
                       s.numero_vagas_fechadas, 
                       s.numero_vagas_abertas, 
                       s.data_abertura_de, 
                       s.previsao_inicio_de,
                       s.estrutura,
                       s.requisitante,
                       s.requisitante_interno,
                       s.justificativa_contratacao,
                       s.aprovado_por,
                       s.data_aprovacao
                FROM (SELECT a.id, 
                             a.numero,
                             a.data_abertura,
                             a.selecionador,
                             CASE a.status
                                  WHEN 'A' THEN 'Ativa'
                                  WHEN 'S' THEN 'Suspensa'
                                  WHEN 'C' THEN 'Cancelada'
                                  WHEN 'G' THEN 'Aguardando aprovação'
                                  WHEN 'F' THEN 'Fechada'
                                  WHEN 'P' THEN 'Fechada parcialmente'
                                  END AS status,
                             CASE a.estagio 
                                  WHEN 1 THEN '01/10 - Alinhando perfil' 
                                  WHEN 2 THEN '02/10 - Divulgando vagas' 
                                  WHEN 3 THEN '03/10 - Tirando currículos' 
                                  WHEN 4 THEN '04/10 - Convocando candidatos' 
                                  WHEN 5 THEN '05/10 - Entrevistando candidatos' 
                                  WHEN 6 THEN '06/10 - Elaborando pareceres' 
                                  WHEN 7 THEN '07/10 - Aguardando gestor' 
                                  WHEN 8 THEN '08/10 - Entrevista solicitante' 
                                  WHEN 9 THEN '09/10 - Exame adissional' 
                                  WHEN 10 THEN '10/10 - Entrega documentos' 
                                  WHEN 11 THEN 'Faturamento' 
                                  WHEN 12 THEN 'Processo finalizado' 
                                  END AS estagio,
                             CONCAT(b.nome, '/', c.nome) AS cargo_funcao,
                             CONCAT(e.nome, '/', f.nome) AS area_setor,
                             CONCAT(d.nome, '/', e.nome, ' - ', f.nome) AS estrutura,
                             IF(a.tipo_vaga = 'I', g.nome, a.requisitante_externo) AS requisitante,
                             a.numero_vagas,
                             CASE a.tipo_vaga
                                  WHEN 'I' THEN 'Interna'
                                  WHEN 'E' THEN 'Externa'
                                  ELSE 'Indefinido' END AS tipo_vaga,
                             a.numero_vagas AS numero_vagas_fechadas,
                             NULL AS numero_vagas_abertas,
                             a.previsao_inicio,
                             DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura_de,
                             DATE_FORMAT(a.previsao_inicio, '%d/%m/%Y') AS previsao_inicio_de,
                             a.requisitante_interno,
                             a.justificativa_contratacao,
                             a.aprovado_por,
                             a.data_aprovacao
                      FROM requisicoes_pessoal a
                      LEFT JOIN empresa_cargos b ON
                                b.id = a.id_cargo
                      LEFT JOIN empresa_funcoes c ON
                                 c.id = a.id_funcao
                      LEFT JOIN empresa_departamentos d ON 
                                 d.id = a.id_depto
                      LEFT JOIN empresa_areas e ON 
                                 e.id = a.id_area
                      LEFT JOIN empresa_setores f ON 
                                 f.id = a.id_setor
                      LEFT JOIN usuarios g ON 
                                g.id = a.requisitante_interno
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}
                            AND (CASE WHEN '{$nivelUsuario}' = 9 THEN (a.requisitante_interno = '{$representante}' OR a.requisitante_externo = '{$usuario['nome']}') WHEN '{$nivelUsuario}' IN (0, 3, 6, 7, 8) THEN  1 END)
                            AND (a.status = '{$post['status']}' OR CHAR_LENGTH('{$post['status']}') = 0)
                            AND (a.estagio = '{$post['estagio']}' OR CHAR_LENGTH('{$post['estagio']}') = 0)";
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), [9])) {
            $sql .= " AND (g.id = '{$representante}' OR a.aprovado_por = '{$representante}')";
        }
        $sql .= ") s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array(
            's.numero',
            's.id',
            's.data_abertura',
            's.status',
            's.estagio',
            's.selecionador',
            's.cargo_funcao',
            's.area_setor',
            's.numero_vagas',
            's.previsao_inicio'
        );
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 1) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 1) {
                    $sql .= " 
                        WHERE {$column} LIKE '%{$post['search']['value']}%'";
                }
            }
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $requisicao) {
            $row = array();
            $row[] = $requisicao->id;
            $row[] = $requisicao->data_abertura_de;
            $row[] = $requisicao->status;
            $row[] = $requisicao->estagio;
            $row[] = $requisicao->selecionador;

            $row[] = $requisicao->cargo_funcao;
            if ($requisicao->tipo_vaga == 'Externa') {
                $row[] = $requisicao->requisitante;
            } elseif ($this->session->userdata('tipo') == 'selecionadorx') {
//                $row[] = $requisicao->estrutura . ' - ' . $requisicao->requisitante;
            } else {
                $row[] = $requisicao->area_setor . ' - ' . $requisicao->requisitante;
            }
            $row[] = $requisicao->numero_vagas;
            $row[] = $requisicao->previsao_inicio_de;

            $row[] = $requisicao->tipo_vaga;
            $row[] = $requisicao->numero_vagas_fechadas;
            $row[] = $requisicao->numero_vagas_abertas;

            if ($requisicao->justificativa_contratacao == 'A' and $requisicao->status != 'Ativa') {
                if ($requisicao->aprovado_por == $representante or $requisicao->requisitante_interno == $representante or $tipoUsuario == 'empresa') {
                    $row[] = '
                              <button type="button" class="btn btn-sm btn-info" onclick="edit_requisicao(' . $requisicao->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                              <button type="button" class="btn btn-sm btn-danger" onclick="delete_requisicao(' . $requisicao->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                              <button type="button" class="btn btn-sm btn-info disabled" title="Mostrar aprovados">Aprovados</button>
                              <button type="button" class="btn btn-sm btn-primary disabled" title="Imprimir requisição de pessoal"><i class="glyphicon glyphicon-print"></i></button>
                             ';
                } else {
                    $row[] = '
                              <button type="button" class="btn btn-sm btn-info disabled" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                              <button type="button" class="btn btn-sm btn-danger disabled" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                              <button type="button" class="btn btn-sm btn-primary disabled" title="Processo Seletivo">Processo</button>
                              <button type="button" class="btn btn-sm btn-info disabled" title="Mostrar aprovados">Aprovados</button>
                              <button type="button" class="btn btn-sm btn-primary disabled" title="Imprimir requisição de pessoal"><i class="glyphicon glyphicon-print"></i></button>
                             ';
                }
            } else {
                if ($usuario['depto'] == 'Gestão de Pessoas' or $tipoUsuario == 'empresa') {
                    $row[] = '
                              <button type="button" class="btn btn-sm btn-info" onclick="edit_requisicao(' . $requisicao->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                              <button type="button" class="btn btn-sm btn-danger" onclick="delete_requisicao(' . $requisicao->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                              <a class="btn btn-sm btn-primary" href="' . site_url('/recrutamentoPresencial_cargos/gerenciar/' . $requisicao->id) . '" title="Processo Seletivo">Processo</a>
                              <button type="button" class="btn btn-sm btn-info" onclick="mostrar_aprovados(' . $requisicao->id . ')" title="Mostrar aprovados">Aprovados</button>
                              <a class="btn btn-sm btn-primary" href="' . site_url('requisicaoPessoal/relatorio/' . $requisicao->id) . '" title="Imprimir requisição de pessoal"><i class="glyphicon glyphicon-print"></i></a>
                             ';
                } else {
                    $row[] = '
                              <button type="button" class="btn btn-sm btn-info" onclick="edit_requisicao(' . $requisicao->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                              <button type="button" class="btn btn-sm btn-danger" onclick="delete_requisicao(' . $requisicao->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                              <button type="button" class="btn btn-sm btn-info" onclick="mostrar_aprovados(' . $requisicao->id . ')" title="Mostrar aprovados">Aprovados</button>
                              <a class="btn btn-sm btn-primary" href="' . site_url('requisicaoPessoal/relatorio/' . $requisicao->id) . '" title="Imprimir requisição de pessoal"><i class="glyphicon glyphicon-print"></i></a>
                             ';
                }
            }
            $row[] = $requisicao->justificativa_contratacao;
            $row[] = $requisicao->data_aprovacao;

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function ajax_listAprovados()
    {
        $this->db->select('c.nome, d.tipo');
        $this->db->select("DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura", false);
        $this->db->select("DATE_FORMAT(a.data_aprovacao, '%d/%m/%Y') AS data_aprovacao", false);
        $this->db->select("DATE_FORMAT(b.data_selecao, '%d/%m/%Y') AS data_selecao", false);
        $this->db->select("DATE_FORMAT(b.data_requisitante, '%d/%m/%Y') AS data_requisitante", false);
        $this->db->select("DATE_FORMAT(b.data_admissao, '%d/%m/%Y') AS data_admissao", false);
        $this->db->join('requisicoes_pessoal_candidatos b', 'b.id_requisicao = a.id', 'left');
        $this->db->join('recrutamento_usuarios c', 'c.id = b.id_usuario', 'left');
        $this->db->join('deficiencias d', 'd.id = c.deficiencia', 'left');
        $this->db->where('a.id', $this->input->post('id'));
        $this->db->where('b.aprovado', 1);
        $rows = $this->db->get('requisicoes_pessoal a')->result();

        $data = array();
        foreach ($rows as $row) {
            $data[] = array(
                $row->nome,
                $row->data_abertura,
                $row->data_aprovacao,
                $row->data_selecao,
                $row->data_requisitante,
                $row->data_admissao,
                $row->tipo
            );
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => 1,
            "recordsFiltered" => 1,
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function ajax_nextId()
    {
        $empresa = $this->session->userdata('empresa');

        $this->db->select('IFNULL(MAX(id) + 1, 1) AS id', false);
        $this->db->select("DATE_FORMAT(NOW(), '%d/%m/%Y') AS data_abertura", false);
        $data = $this->db->get_where('requisicoes_pessoal')->row();


        $this->db->select('a.id_usuario AS id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $empresa);
        $this->db->order_by('b.nome', 'asc');
        $sqlAprovadores = $this->db->get('requisicoes_pessoal_aprovadores a')->result();
        $aprovadores = ['' => 'selecione...'] + array_column($sqlAprovadores, 'nome', 'id');
        $data->aprovado_por = form_dropdown('aprovado_por', $aprovadores, '');

        echo json_encode($data);
    }

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('requisicoes_pessoal', array('id' => $id))->row();
        $data->data_abertura = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_abertura)));
        if ($data->previsao_inicio) {
            $data->previsao_inicio = date("d/m/Y", strtotime(str_replace('-', '/', $data->previsao_inicio)));
        }
        if ($data->data_aprovacao) {
            $data->data_aprovacao = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_aprovacao)));
        }
        if (strlen($data->remuneracao_mensal) > 0) {
            $data->remuneracao_mensal = number_format($data->remuneracao_mensal, 2, ',', '.');
        }
        if (strlen($data->valor_vale_transporte) > 0) {
            $data->valor_vale_transporte = number_format($data->valor_vale_transporte, 2, ',', '.');
        }
        if (strlen($data->valor_vale_alimentacao) > 0) {
            $data->valor_vale_alimentacao = number_format($data->valor_vale_alimentacao, 2, ',', '.');
        }
        if (strlen($data->valor_vale_refeicao) > 0) {
            $data->valor_vale_refeicao = number_format($data->valor_vale_refeicao, 2, ',', '.');
        }
        if (strlen($data->valor_assistencia_medica) > 0) {
            $data->valor_assistencia_medica = number_format($data->valor_assistencia_medica, 2, ',', '.');
        }
        if (strlen($data->valor_plano_odontologico) > 0) {
            $data->valor_plano_odontologico = number_format($data->valor_plano_odontologico, 2, ',', '.');
        }
        if (strlen($data->valor_cesta_basica) > 0) {
            $data->valor_cesta_basica = number_format($data->valor_cesta_basica, 2, ',', '.');
        }
        if (strlen($data->valor_participacao_resultados) > 0) {
            $data->valor_participacao_resultados = number_format($data->valor_participacao_resultados, 2, ',', '.');
        }

        $_POST['id_depto'] = $data->id_depto;
        $_POST['id_area'] = $data->id_area;
        $_POST['id_setor'] = $data->id_setor;
        $_POST['id_cargo'] = $data->id_cargo;
        $_POST['id_funcao'] = $data->id_funcao;
        $_POST['requisitante_interno'] = $data->requisitante_interno;

        $estrutura = $this->atualizarEstrutura(true);
        $funcao = $this->atualizarFuncao(true);

        $this->db->select('a.id_usuario AS id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $this->session->userdata('empresa'));
        $this->db->order_by('b.nome', 'asc');
        $sqlAprovadores = $this->db->get('requisicoes_pessoal_aprovadores a')->result();
        $aprovadores = ['' => 'selecione...'] + array_column($sqlAprovadores, 'nome', 'id');
        $aprovadoPor = ['aprovado_por' => form_dropdown('aprovado_por', $aprovadores, $data->aprovado_por)];


        $input = array_merge($estrutura, $funcao, $aprovadoPor);

        echo json_encode(array('input' => $input, 'data' => $data));
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        $data['id_empresa'] = $this->session->userdata('empresa');
        if (strlen($data['data_abertura']) == 0) {
            exit(json_encode(array('erro' => 'A data de abertura é obrigatória.')));
        }
        if (!empty($data['possui_indicacao'])) {
            if (strlen($data['colaboradores_indicados']) == 0) {
                exit(json_encode(array('erro' => 'O campo Colaboradores Indicados é obrigatório.')));
            }
            if (strlen($data['indicador_responsavel']) == 0) {
                exit(json_encode(array('erro' => 'O campo Responsável pela indicação é obrigatório.')));
            }
        } else {
            $data['possui_indicacao'] = null;
            $data['colaboradores_indicados'] = null;
            $data['indicador_responsavel'] = null;
        }

        if (empty($data['requisitante_interno'])) {
            $data['requisitante_interno'] = null;
        }
        if (strlen($data['requisitante_externo']) == 0) {
            $data['requisitante_externo'] = null;
        }
        if (empty($data['id_cargo'])) {
            $data['id_cargo'] = null;
        }
        if (strlen($data['cargo_externo']) == 0) {
            $data['cargo_externo'] = null;
        }
        if (empty($data['id_funcao'])) {
            $data['id_funcao'] = null;
        }
        if (strlen($data['funcao_externa']) == 0) {
            $data['funcao_externa'] = null;
        }
        if (isset($data['vagas_deficiente'])) {
            $data['vagas_deficiente'] = 1;
        } else {
            $data['vagas_deficiente'] = null;
        }
        if (isset($data['colaborador_substituto'])) {
            if (strlen($data['colaborador_substituto']) == 0) {
                $data['colaborador_substituto'] = null;
            }
        } else {
            $data['colaborador_substituto'] = null;
        }
        if (!empty($data['aprovado_por']) == false) {
            $data['aprovado_por'] = null;
        }
        if (isset($data['selecionador']) and strlen($data['selecionador']) == 0) {
            $data['selecionador'] = null;
        }

        $data['data_abertura'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_abertura'])));
        if ($data['previsao_inicio']) {
            $data['previsao_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['previsao_inicio'])));
        } else {
            $data['previsao_inicio'] = null;
        }
        if (!empty($data['data_aprovacao'])) {
            $data['data_aprovacao'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_aprovacao'])));
        } else {
            $data['data_aprovacao'] = null;
        }
        if ($data['remuneracao_mensal']) {
            $data['remuneracao_mensal'] = str_replace(array('.', ','), array('', '.'), $data['remuneracao_mensal']);
        } else {
            $data['remuneracao_mensal'] = null;
        }
        if (strlen($data['valor_vale_transporte']) > 0) {
            $data['valor_vale_transporte'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_transporte']);
        } else {
            $data['valor_vale_transporte'] = null;
        }
        if (strlen($data['valor_vale_alimentacao']) > 0) {
            $data['valor_vale_alimentacao'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_alimentacao']);
        } else {
            $data['valor_vale_alimentacao'] = null;
        }
        if (strlen($data['valor_vale_refeicao']) > 0) {
            $data['valor_vale_refeicao'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_refeicao']);
        } else {
            $data['valor_vale_refeicao'] = null;
        }
        if (strlen($data['valor_cesta_basica']) > 0) {
            $data['valor_cesta_basica'] = str_replace(array('.', ','), array('', '.'), $data['valor_cesta_basica']);
        } else {
            $data['valor_cesta_basica'] = null;
        }
        if (strlen($data['valor_assistencia_medica']) > 0) {
            $data['valor_assistencia_medica'] = str_replace(array('.', ','), array('', '.'), $data['valor_assistencia_medica']);
        } else {
            $data['valor_assistencia_medica'] = null;
        }
        if (strlen($data['valor_plano_odontologico']) > 0) {
            $data['valor_plano_odontologico'] = str_replace(array('.', ','), array('', '.'), $data['valor_plano_odontologico']);
        } else {
            $data['valor_plano_odontologico'] = null;
        }
        if (strlen($data['valor_participacao_resultados']) > 0) {
            $data['valor_participacao_resultados'] = str_replace(array('.', ','), array('', '.'), $data['valor_participacao_resultados']);
        } else {
            $data['valor_participacao_resultados'] = null;
        }

        if ($this->db->get_where('requisicoes_pessoal', ['id' => $data['id']])->num_rows()) {
            unset($data['id']);
        }

        $status = $this->db->insert('requisicoes_pessoal', $data);

        if ($status and $data['aprovado_por']) {
            $row = array(
                'id_requisicao' => $data['id'] ?? $this->db->insert_id(),
                'id_usuario' => $data['aprovado_por'],
                'data_aprovacao' => $data['data_aprovacao']
            );
            $this->notificarAprovador($row);
        }

        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);
        if (strlen($data['data_abertura']) == 0) {
            exit(json_encode(array('erro' => 'A data de abertura é obrigatória.')));
        }
        if (!empty($data['possui_indicacao'])) {
            if (strlen($data['colaboradores_indicados']) == 0) {
                exit(json_encode(array('erro' => 'O campo Colaboradores Indicados é obrigatório.')));
            }
            if (strlen($data['indicador_responsavel']) == 0) {
                exit(json_encode(array('erro' => 'O campo Responsável pela indicação é obrigatório.')));
            }
        } else {
            $data['possui_indicacao'] = null;
            $data['colaboradores_indicados'] = null;
            $data['indicador_responsavel'] = null;
        }

        if (empty($data['requisitante_interno'])) {
            $data['requisitante_interno'] = null;
        }
        if (strlen($data['requisitante_externo']) == 0) {
            $data['requisitante_externo'] = null;
        }
        if (empty($data['id_cargo'])) {
            $data['id_cargo'] = null;
        }
        if (strlen($data['cargo_externo']) == 0) {
            $data['cargo_externo'] = null;
        }
        if (empty($data['id_funcao'])) {
            $data['id_funcao'] = null;
        }
        if (strlen($data['funcao_externa']) == 0) {
            $data['funcao_externa'] = null;
        }
        if (isset($data['vagas_deficiente'])) {
            $data['vagas_deficiente'] = 1;
        } else {
            $data['vagas_deficiente'] = null;
        }
        if (isset($data['colaborador_substituto'])) {
            if (strlen($data['colaborador_substituto']) == 0) {
                $data['colaborador_substituto'] = null;
            }
        } else {
            $data['colaborador_substituto'] = null;
        }
        if (!empty($data['aprovado_por']) == false) {
            $data['aprovado_por'] = null;
        }
        if (isset($data['selecionador']) and strlen($data['selecionador']) == 0) {
            $data['selecionador'] = null;
        }

        $data['data_abertura'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_abertura'])));
        if ($data['previsao_inicio']) {
            $data['previsao_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['previsao_inicio'])));
        } else {
            $data['previsao_inicio'] = null;
        }
        if (!empty($data['data_aprovacao'])) {
            $data['data_aprovacao'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_aprovacao'])));
        } else {
            $data['data_aprovacao'] = null;
        }
        if ($data['remuneracao_mensal']) {
            $data['remuneracao_mensal'] = str_replace(array('.', ','), array('', '.'), $data['remuneracao_mensal']);
        } else {
            $data['remuneracao_mensal'] = null;
        }
        if (strlen($data['valor_vale_transporte']) > 0) {
            $data['valor_vale_transporte'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_transporte']);
        } else {
            $data['valor_vale_transporte'] = null;
        }
        if (strlen($data['valor_vale_alimentacao']) > 0) {
            $data['valor_vale_alimentacao'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_alimentacao']);
        } else {
            $data['valor_vale_alimentacao'] = null;
        }
        if (strlen($data['valor_vale_refeicao']) > 0) {
            $data['valor_vale_refeicao'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_refeicao']);
        } else {
            $data['valor_vale_refeicao'] = null;
        }
        if (strlen($data['valor_cesta_basica']) > 0) {
            $data['valor_cesta_basica'] = str_replace(array('.', ','), array('', '.'), $data['valor_cesta_basica']);
        } else {
            $data['valor_cesta_basica'] = null;
        }
        if (strlen($data['valor_assistencia_medica']) > 0) {
            $data['valor_assistencia_medica'] = str_replace(array('.', ','), array('', '.'), $data['valor_assistencia_medica']);
        } else {
            $data['valor_assistencia_medica'] = null;
        }
        if (strlen($data['valor_plano_odontologico']) > 0) {
            $data['valor_plano_odontologico'] = str_replace(array('.', ','), array('', '.'), $data['valor_plano_odontologico']);
        } else {
            $data['valor_plano_odontologico'] = null;
        }
        if (strlen($data['valor_participacao_resultados']) > 0) {
            $data['valor_participacao_resultados'] = str_replace(array('.', ','), array('', '.'), $data['valor_participacao_resultados']);
        } else {
            $data['valor_participacao_resultados'] = null;
        }

        $checkboxes = array(
            'vale_alimentacao',
            'vale_transporte',
            'vale_refeicao',
            'assistencia_medica',
            'plano_odontologico',
            'cesta_basica',
            'participacao_resultados',
            'exame_clinico',
            'audiometria',
            'laudo_cotas'
        );
        foreach ($checkboxes as $checkbox) {
            if (!isset($data[$checkbox])) {
                $data[$checkbox] = null;
            }
        }

        $requisicao = $this->db->select('aprovado_por')->get_where('requisicoes_pessoal', ['id' => $id])->row();

        $status = $this->db->update('requisicoes_pessoal', $data, array('id' => $id));

        if ($status and $requisicao->aprovado_por !== $data['aprovado_por']) {
            $row = array(
                'id_requisicao' => $id,
                'id_usuario' => $data['aprovado_por'],
                'data_aprovacao' => $data['data_aprovacao']
            );
            $this->notificarAprovador($row);
        }

        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('requisicoes_pessoal', array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    private function notificarAprovador($data)
    {
        $this->db->select('id, nome, email');
        $this->db->where('id', $this->session->userdata('empresa'));
        $remetente = $this->db->get('usuarios')->row();

        $this->db->select('id, nome, email');
        $this->db->where('id', $data['id_usuario']);
        $destinatario = $this->db->get('usuarios')->row();


        $this->load->library('email');

        $email['remetente'] = $remetente->id;
        $email['datacadastro'] = date('Y-m-d H:i:s');
        $email['titulo'] = 'Requisição de Pessoal - Aumento de Quadro';
        $email['mensagem'] = '<p>Caro colaborador, você tem uma requisição de pessoal solicitando "Aumento de quadro", aguardando sua aprovação.</p><p>Para maiores detalhes e aprovação da mesma, faça por gentileza o login na Plataforma de Gestão Operacional AME, selecionando a opção Gestão de Processos Seletivos / Gerenciar Requisições de Pessoal. Nesta opção você terá acesso à requisição de dados.</p><p>Requisição n&ordm; ' . $data['id_requisicao'] . '</p><p>Grato pela atenção.</p>';


        $this->email->from($remetente->email, $remetente->nome);
        $this->email->to($destinatario->email);
        $this->email->subject($email['titulo']);
        $this->email->message($email['mensagem']);

        if ($this->email->send()) {
            $email['destinatario'] = $destinatario->id;
            $this->db->query($this->db->insert_string('mensagensrecebidas', $email));
            $this->db->query($this->db->insert_string('mensagensenviadas', $email));
        }

        $this->email->clear();
    }

    public function relatorio($id)
    {
        $this->ajax_relatorio();
    }

    public function ajax_relatorio($pdf = false)
    {
        $this->db->select('foto, foto_descricao');
        $usuario = $this->db->get_where('usuarios', array('id' => $this->session->userdata('empresa')))->row();
        $data['foto'] = 'imagens/usuarios/' . $usuario->foto;
        $data['foto_descricao'] = 'imagens/usuarios/' . $usuario->foto_descricao;

        $sql = "SELECT a.id, 
                       a.numero,
                       DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura,
                       IF(a.tipo_vaga = 'I', b.nome, a.requisitante_externo) AS requisitante,
                       CASE a.tipo_vaga
                            WHEN 'I' THEN 'Interno'
                            WHEN 'E' THEN 'Externo'
                            ELSE 'Indefinida' END AS tipo_vaga,
                       CONCAT_WS('/', c.nome, d.nome, e.nome) AS estrutura,
                       a.numero_contrato,
                       a.regime_contratacao,
                       a.centro_custo,
                       f.nome AS cargo,
                       g.nome AS funcao,
                       a.numero_vagas,
                       a.vagas_deficiente,
                       CASE a.justificativa_contratacao 
                            WHEN 'A' THEN 'Aumento de quadro'
                            WHEN 'S' THEN 'Substituição'
                            WHEN 'T' THEN 'Transferência'
                            END AS justificativa_contratacao,
                       a.colaborador_substituto,
                       a.possui_indicacao,
                       a.colaboradores_indicados,
                       a.indicador_responsavel,
                       CASE WHEN a.vale_alimentacao > 0 THEN 'Vale alimentacao' END AS vale_alimentacao,
                       CASE WHEN a.vale_transporte > 0 THEN 'Vale transporte' END AS vale_transporte,
                       CASE WHEN a.vale_refeicao > 0 THEN 'Vale refeição' END AS vale_refeicao,
                       CASE WHEN a.assistencia_medica > 0 THEN 'Assistência médica' END AS assistencia_medica,
                       CASE WHEN a.plano_odontologico > 0 THEN 'Plano odontológico' END AS plano_odontologico,
                       CASE WHEN a.cesta_basica > 0 THEN 'Cesta básica' END AS cesta_basica,
                       CASE WHEN a.participacao_resultados > 0 THEN 'Participação em resultados' END AS participacao_resultados,
                       NULL AS beneficios,
                       CONCAT('R$ ', FORMAT(a.remuneracao_mensal, 2, 'de_DE')) AS remuneracao_mensal,
                       a.horario_trabalho,
                       DATE_FORMAT(a.previsao_inicio, '%d/%m/%Y') AS previsao_inicio,
                       a.local_trabalho,
                       CASE WHEN a.exame_clinico > 0 THEN 'Exame clínico' END AS exame_clinico,
                       CASE WHEN a.audiometria > 0 THEN 'Audiometria' END AS audiometria,
                       CASE WHEN a.laudo_cotas > 0 THEN 'Laudo_cotas' END AS laudo_cotas,
                       a.exame_outros,
                       NULL AS exames_necessarios,
                       a.perfil_geral,
                       a.competencias_tecnicas,
                       a.competencias_comportamentais,
                       a.atividades_associadas,
                       a.observacoes,
                       NULL AS candidatos_aprovados
                FROM requisicoes_pessoal a
                INNER JOIN empresa_cargos f ON
                           f.id = a.id_cargo
                INNER JOIN empresa_funcoes g ON
                           g.id = a.id_funcao
                LEFT JOIN empresa_departamentos c 
                           ON c.id = a.id_depto
                LEFT JOIN empresa_areas d 
                           ON d.id = a.id_area
                LEFT JOIN empresa_setores e
                           ON e.id = a.id_setor
                LEFT JOIN usuarios b
                           ON b.id = a.requisitante_interno
                WHERE a.id_empresa = {$this->session->userdata('empresa')} 
                      AND a.id = {$this->uri->rsegment(3, '')}";

        $row = $this->db->query($sql)->row();
        $beneficios = array(
            $row->vale_alimentacao,
            $row->vale_transporte,
            $row->vale_refeicao,
            $row->assistencia_medica,
            $row->plano_odontologico,
            $row->cesta_basica,
            $row->participacao_resultados
        );
        $row->beneficios = implode(';<br>', array_filter($beneficios));
        $examesNecessarios = array(
            $row->exame_clinico,
            $row->audiometria,
            $row->laudo_cotas,
            $row->exame_outros
        );
        $row->exames_necessarios = implode(';<br>', array_filter($examesNecessarios));

        $this->db->select('b.nome');
        $this->db->join('recrutamento_usuarios b', 'b.id = a.id_usuario');
        $this->db->where('a.id_requisicao', $row->id);
        $this->db->where('a.aprovado', 1);
        $candidatosAprovados = $this->db->get('requisicoes_pessoal_candidatos a')->result();
        $row->candidatos_aprovados = array_column($candidatosAprovados, 'nome');

        $data['row'] = $row;

        $data['is_pdf'] = $pdf;
        if ($pdf) {
            $data['mostrar_aprovados'] = $this->input->get('aprovados');

            return $this->load->view('requisicaoPessoal_pdf', $data, true);
        }

        $this->load->view('requisicaoPessoal_relatorio', $data);
    }

    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.requisicao thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.requisicao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.requisicao tbody tr th { font-size: 11px; padding: 2px; } ';
        $stylesheet .= 'table.requisicao tbody td { font-size: 12px; padding: 1px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.requisicao tbody td strong { font-weight: bold; } ';

        $stylesheet .= 'table.dados thead th { font-size: 12px; padding: 5px; border-bottom: 2px solid #ddd; } ';
        $stylesheet .= 'table.dados thead tr.active td { background-color: #e5e5e5; }';
        $stylesheet .= 'table.dados tbody td { font-size: 12px; padding: 5px; border-top: 1px solid #ddd; word-wrap: break-word;} ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->ajax_relatorio(true));

        $this->m_pdf->pdf->Output('Requisição de Pessoal.pdf', 'D');
    }

}
