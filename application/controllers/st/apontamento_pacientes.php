<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento_pacientes extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $empresa = $this->session->userdata('empresa');

        $sql = "SELECT a.cod_uf, 
                       a.uf 
                FROM estados a 
                INNER JOIN alocacao_pacientes b ON 
                           b.estado = a.cod_uf 
                WHERE b.id_instituicao = {$empresa}";
        $estados = $this->db->query($sql)->result();
        $data['estado'] = array('' => 'Todos');
        foreach ($estados as $estado) {
            $data['estado'][$estado->cod_uf] = $estado->uf;
        }

        $sql2 = "SELECT a.cod_mun, 
                        a.municipio 
                 FROM municipios a 
                 INNER JOIN alocacao_pacientes b ON 
                            b.cidade = a.cod_mun 
                 WHERE b.id_instituicao = {$empresa}";
        $cidades = $this->db->query($sql2)->result();
        $data['cidade'] = array('' => 'Todas');
        foreach ($cidades as $cidade) {
            $data['cidade'][$cidade->cod_mun] = $cidade->municipio;
        }

        $this->db->distinct('bairro');
        $bairros = $this->db->get_where('alocacao_pacientes', array('id_instituicao' => $empresa))->result();
        $data['bairro'] = array('' => 'Todos');
        foreach ($bairros as $bairro) {
            $data['bairro'][$bairro->bairro] = $bairro->bairro;
        }

        $sql3 = "SELECT status AS id,
                        CASE status
                        WHEN 'A' THEN 'Ativo'
                        WHEN 'I' THEN 'Inativo'
                        WHEN 'X' THEN 'Afastado'
                        WHEN 'E' THEN 'Em fila de espera' END AS nome
                 FROM alocacao_pacientes 
                 WHERE id_instituicao = {$empresa}";
        $grupo_status = $this->db->query($sql3)->result();
        $data['status'] = array('' => 'Todos');
        foreach ($grupo_status as $status) {
            $data['status'][$status->id] = $status->nome;
        }

        $sql4 = "SELECT id, 
                        nome 
                 FROM alocacao_deficiencias
                 WHERE id_instituicao = {$empresa}";
        $deficiencias = $this->db->query($sql4)->result();
        $data['deficiencia'] = array('' => 'Sem filtro');
        foreach ($deficiencias as $deficiencia) {
            $data['deficiencia'][$deficiencia->id] = $deficiencia->nome;
        }

        $data['empresa'] = $empresa;

        $this->load->view('apontamento_pacientes', $data);
    }

    public function atualizar_filtro()
    {
        $estado = $this->input->post('estado');
        $cidade = $this->input->post('cidade');
        $bairro = $this->input->post('bairro');

        $filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
                unset($filtro['area'][''], $filtro['setor']['']);
            }
            unset($filtro['depto']['']);
        }

        $data['area'] = form_dropdown('area', $filtro['area'], $area, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }

    public function ajax_list()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.status,
                       s.deficiencia,
                       s.data_ingresso
                FROM (SELECT a.id, 
                             a.nome,
                             CASE a.status
                                  WHEN 'A' THEN 'Ativo'
                                  WHEN 'I' THEN 'Inativo'
                                  WHEN 'X' THEN 'Afastado'
                                  WHEN 'E' THEN 'Em fila de espera' END AS status,
                             b.nome AS deficiencia,
                             DATE_FORMAT(a.data_ingresso, '%d/%m/%Y') AS data_ingresso
                  FROM alocacao_pacientes a
                  LEFT JOIN alocacao_deficiencias b ON
                            b.id = a.deficiencia
                  WHERE a.id_instituicao= {$this->session->userdata('empresa')}";
        if ($post['estado']) {
            $sql .= " AND a.estado = {$post['estado']}";
        }
        if ($post['cidade']) {
            $sql .= " AND a.cidade = {$post['cidade']}";
        }
        if ($post['bairro']) {
            $sql .= " AND a.bairro = '{$post['bairro']}'";
        }
        if ($post['deficiencia']) {
            $sql .= " AND a.deficiencia = {$post['deficiencia']}";
        }
        $sql .= ')s';

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome');
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
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $apontamento) {
            $row = array();
            $row[] = $apontamento->nome;
            $row[] = $apontamento->status;
            $row[] = $apontamento->deficiencia;
            $row[] = $apontamento->data_ingresso;
            $row[] = '
                      <a class="btn btn-sm btn-primary" href="' . site_url('apontamento_pacientes/cadastro/' . $apontamento->id) . '" title="Editar"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_paciente(' . "'" . $apontamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('apontamento_pacientes/frequencia/' . $apontamento->id) . '" title="Ver processo"><i class="glyphicon glyphicon-list"></i> Gerar controle de frequência individual</a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('apontamento_pacientes/relatorio/' . $apontamento->id) . '" title="Ver processo"><i class="glyphicon glyphicon-list-alt"></i> Relatório</a>
                     ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function cadastro($id = null)
    {
        if ($id) {
            $data = $this->db->get_where('alocacao_pacientes', array('id' => $id))->row_array();
        } else {
            $this->db->select('*, COUNT(*) as count', false);
            $this->db->where('id', null);
            $data = $this->db->get('alocacao_pacientes')->row_array();

            $data['id_instituicao'] = $this->session->userdata('empresa');
            unset($data['count']);
        }

        if ($data['data_nascimento']) {
            $data['data_nascimento'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_nascimento'])));
        }
        if ($data['data_ingresso']) {
            $data['data_ingresso'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_ingresso'])));
        }

        $this->db->order_by('uf', 'asc');
        $estados = $this->db->get('estados')->result();
        $data['estados'] = array('' => 'selecione ...');
        foreach ($estados as $estado) {
            $data['estados'][$estado->cod_uf] = $estado->uf;
        }

        $this->db->where('cod_uf', $data['estado']);
        $cidades = $this->db->get('municipios')->result();
        $data['cidades'] = array('' => 'selecione ...');
        foreach ($cidades as $cidade) {
            $data['cidades'][$cidade->cod_mun] = $cidade->municipio;
        }

        $deficiencias = $this->db->get('deficiencias')->result();
        $data['deficiencias'] = array('' => 'selecione ...');
        foreach ($deficiencias as $deficiencia) {
            $data['deficiencias'][$deficiencia->id] = $deficiencia->tipo;
        }

        $data['grupo_status'] = array('A' => 'Ativo', 'I' => 'Inativo', 'X' => 'Afastado', 'E' => 'Em fila de espera');

        $this->load->view('apontamento_perfil', $data);
    }

    public function ajax_save()
    {
        $data = $this->input->post();
        $data['data_nascimento'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_nascimento'])));
        if ($data['data_ingresso']) {
            $data['data_ingresso'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_ingresso'])));
        } else {
            $data['data_ingresso'] = date("Y-m-d");
        }

        $id = $data['id'];
        unset($data['id']);
        if ($id) {
            $this->db->update('alocacao_pacientes', $data, array('id' => $id));
        } else {
            $this->db->insert('alocacao_pacientes', $data);
        }

        echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de paciente salvo com sucesso', 'redireciona' => 1, 'pagina' => site_url('apontamento_pacientes')));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('alocacao_pacientes', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function frequencia($id, $pdf = false)
    {
        if (empty($id)) {
            redirect(site_url('apontamento_pacientes'));
        }
        $sql = "SELECT 'Associação dos Amigos Metroviários dos Excepcionais' AS instituicao_nome, 
                       '64.917.818/0001-56' AS instituicao_cnpj, 
                       a.nome, 
                       CASE a.sexo 
                            WHEN 'M' THEN 'Masculino' 
                            WHEN 'F' THEN 'Feminino' END AS sexo, 
                       DATE_FORMAT(a.data_nascimento, '%d/%m/%Y') AS data_nascimento, 
                       a.cpf, 
                       a.cadastro_municipal, 
                       a.hd, 
                       a.nome_responsavel_1, 
                       a.telefone_fixo_1, 
                       a.telefone_celular_1,
                       a.nome_responsavel_2, 
                       a.telefone_fixo_2, 
                       a.telefone_celular_2, 
                       a.logradouro, 
                       a.numero, 
                       a.complemento, 
                       a.bairro, 
                       c.municipio AS cidade, 
                       d.uf AS estado, 
                       a.cep, 
                       DATE_FORMAT(a.data_ingresso, '%m') AS mes_ingresso, 
                       DATE_FORMAT(a.data_ingresso, '%Y') AS ano_ingresso 
                FROM alocacao_pacientes a 
                INNER JOIN usuarios b ON 
                           b.id = a.id_instituicao 
                LEFT JOIN municipios c ON 
                          c.cod_mun = a.cidade
                LEFT JOIN estados d ON 
                          d.cod_uf = a.estado 
                WHERE a.id = {$id}";
        $row = $this->db->query($sql)->row();
        if (isset($row->mes_ingresso)) {
            $this->load->library('Calendar');
            $row->mes_ingresso = $this->calendar->get_month_name($row->mes_ingresso);
        }

        $data['paciente'] = $row;
        $data['is_pdf'] = $pdf;

        if ($pdf) {
            return $this->load->view('apontamento_pdfFrequencia', $data, true);
        } else {
            $this->load->view('apontamento_frequencia', $data);
        }
    }

    public function atividades()
    {
        $this->load->view('apontamento_atividades');
    }

    public function ajax_atividades()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome
                FROM (SELECT a.id, 
                             a.nome
                  FROM alocacao_atividades a
                  WHERE a.id_instituicao= {$this->session->userdata('empresa')}";
        $sql .= ')s';

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome');
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
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $atividade) {
            $row = array();
            $row[] = $atividade->id;
            $row[] = $atividade->nome;
            $row[] = '
                      <button class="btn btn-xs btn-danger" title="Excluir" onclick="delete_atividade(' . "'" . $atividade->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
                     ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_deficiencias()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome
                FROM (SELECT a.id, 
                             a.nome
                  FROM alocacao_deficiencias a
                  WHERE a.id_instituicao= {$this->session->userdata('empresa')}";
        $sql .= ')s';

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome');
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
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $deficiencia) {
            $row = array();
            $row[] = $deficiencia->id;
            $row[] = $deficiencia->nome;
            $row[] = '
                      <button class="btn btn-xs btn-danger" title="Excluir" onclick="delete_deficiencia(' . "'" . $deficiencia->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
                     ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function save_deficiencia()
    {
        $id = $this->input->post('id');
        $data = array(
            'nome' => $this->input->post('nome'),
            'id_instituicao' => $this->session->userdata('empresa')
        );
        if ($id) {
            $this->db->update('alocacao_deficiencias', $data, array('id' => $id));
        } else {
            $this->db->insert('alocacao_deficiencias', $data);
        }

        echo json_encode(array("status" => true));
    }

    public function save_atividade()
    {
        $id = $this->input->post('id');
        $data = array(
            'nome' => $this->input->post('nome'),
            'id_instituicao' => $this->session->userdata('empresa')
        );
        if ($id) {
            $this->db->update('alocacao_atividades', $data, array('id' => $id));
        } else {
            $this->db->insert('alocacao_atividades', $data);
        }

        echo json_encode(array("status" => true));
    }

    public function delete_deficiencia()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('alocacao_deficiencias', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function delete_atividade()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('alocacao_atividades', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function relatorio($id = '')
    {
        $this->load->view('manutencao');
    }

    public function pdfFrequencia()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.avaliacao thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.avaliacao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliacao tbody tr.avaliador { border-width: 1px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliacao tbody td { font-size: 12px; padding: 5px; } ';
        $stylesheet .= 'table.avaliacao tbody tr.avaliador td { width: 50%; border-width: 1px; border-color: #ddd; } ';

        $stylesheet .= 'table.avaliado thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= 'table.avaliado tbody td { font-size: 12px; padding: 5px; text-align: right; } ';
        $stylesheet .= 'table.avaliado tbody td:nth-child(1) { text-align: left; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->frequencia($this->uri->rsegment(3), true));

        $this->db->select('nome');
        $this->db->where('id', $this->uri->rsegment(3));
        $row = $this->db->get('alocacao_pacientes')->row();

        $this->m_pdf->pdf->Output('PAPD-' . $row->nome . '.pdf', 'D');
    }

}
