<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento_contratos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'contrato');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '')";
            $rows = $this->db->query($sql)->result_array();
            $data[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
        }
        $data['deptos'] = $data['depto'];
        $data['depto'][''] = 'selecione...';
        $data['area_cliente'] = $data['area'];
        $data['area'][''] = 'selecione...';
        $data['setor_unidade'] = $data['setor'];
        $data['setor'][''] = 'selecione...';
        $data['contratos'] = $data['contrato'];
        $data['contrato'][''] = 'selecione...';

        $this->db->select('id, nome');
        $this->db->where('empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $usuarios = $this->db->get('usuarios')->result();
        $data['usuarios'] = array('' => 'selecione...');
        foreach ($usuarios as $usuario) {
            $data['usuarios'][$usuario->id] = $usuario->nome;
        }

        $this->load->view('apontamento_contratos', $data);
    }

    public function atualizar_filtro()
    {
        $busca = $this->input->post('busca');

        $filtro = $this->get_filtros_usuarios($busca['depto'], $busca['area'], $busca['setor']);
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
            if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
                unset($filtro['area'][''], $filtro['setor']['']);
            }
            unset($filtro['depto']['']);
        }

        $data['area'] = form_dropdown('area', $filtro['area'], $busca['area'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['setor'] = form_dropdown('setor', $filtro['setor'], $busca['setor'], 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }

    public function novo()
    {
        $empresa = $this->session->userdata('empresa');
        $post = $this->input->post();
        $mes = empty($post['mes']) ? date('m') : $post['mes'];
        $ano = empty($post['ano']) ? date('Y') : $post['ano'];

        $this->db->where('id_empresa', $empresa);
        $this->db->where('data', date('Y-m-t', mktime(0, 0, 0, $mes, 1, $ano)));
        $this->db->where('depto', $post['depto']);
        $this->db->where('area', $post['area']);
        $this->db->where('setor', $post['setor']);
        $num_rows = $this->db->get('alocacao')->num_rows();
        if ($num_rows) {
            exit;
        }

        $data = array(
            'id_empresa' => $empresa,
            'data' => date('Y-m-t', mktime(0, 0, 0, $mes, 1, $ano)),
            'depto' => $post['depto'],
            'area' => $post['area'],
            'setor' => $post['setor']
        );
        $this->db->trans_start();

        $this->db->insert('alocacao', $data);
        $id_alocacao = $this->db->insert_id();

        $this->db->select("'{$id_alocacao}' AS id_alocacao, a.id AS id_usuario", false);
        $this->db->select("'I' AS tipo_horario, 'P' AS nivel", false);
//        $this->db->join('(SELECT @rownum:=0) b', 'a.id = a.id');
        $this->db->where('a.depto', $post['depto']);
        $this->db->where('a.area', $post['area']);
        $this->db->where('a.setor', $post['setor']);
        $data2 = $this->db->get('usuarios a, (SELECT @rownum:=0) b')->result_array();
        $this->db->insert_batch('alocacao_usuarios', $data2);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.estrutura,
                       s.contrato
                FROM (SELECT a.id, 
                             a.nome,
                             CONCAT_WS('/', a.depto, a.area) AS estrutura,
                             a.contrato
                      FROM alocacao_contratos a
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_empresa 
                      LEFT JOIN alocacao_unidades c ON
                                c.id_contrato = a.id
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}";
        if (!empty($busca['depto'])) {
            $sql .= " AND a.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['area'])) {
            $sql .= " AND a.area = '{$busca['area']}'";
        }
        if (!empty($busca['setor'])) {
            $sql .= " AND c.setor = '{$busca['setor']}'";
        }
        if (!empty($busca['contrato'])) {
            $sql .= " AND a.contrato = '{$busca['contrato']}'";
        }
        $sql .= ' GROUP BY a.id) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.estrutura', 's.contrato');
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
            $row[] = $apontamento->estrutura;
            $row[] = $apontamento->contrato;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_contrato(' . $apontamento->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_unidades(' . $apontamento->id . ')" title="Gerenciar unidades"><i class="glyphicon glyphicon-plus"></i> Unidades</button>
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_servicos(' . $apontamento->id . ')" title="Gerenciar serviços"><i class="glyphicon glyphicon-plus"></i> Serviços</button>
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_reajuste(' . $apontamento->id . ')" title="Gerenciar reajuste"><i class="glyphicon glyphicon-plus"></i> Reajustes</button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_contrato(' . $apontamento->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
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

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $this->db->select('id, id_usuario, nome, depto, area, contrato', false);
        $this->db->select("DATE_FORMAT(data_assinatura, '%d/%m/%Y') AS data_assinatura", false);
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('id', $id);
        $data = $this->db->get('alocacao_contratos')->row();

        echo json_encode($data);
    }

    public function ajax_estrutura()
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');

        $this->db->select('DISTINCT(area) AS nome', false);
        $this->db->where('empresa', $this->session->userdata('empresa'));
        if ($depto) {
            $this->db->where('depto', $depto);
        }
        $this->db->where('CHAR_LENGTH(area) >', 0);
        $areas = $this->db->get('usuarios')->result();

        $options_area = array('' => 'selecione...');
        foreach ($areas as $row_area) {
            $options_area[$row_area->nome] = $row_area->nome;
        }

        $this->db->select('DISTINCT(setor) AS nome', false);
        $this->db->where('empresa', $this->session->userdata('empresa'));
        if ($depto) {
            $this->db->where('depto', $depto);
        }
        if ($area) {
            $this->db->where('area', $area);
        }
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        $setores = $this->db->get('usuarios')->result();

        $options_setor = array('' => 'selecione...');
        foreach ($setores as $row_setor) {
            $options_setor[$row_setor->nome] = $row_setor->nome;
        }

        $data['area'] = form_dropdown('area', $options_area, $area, 'id="area" class="form-control"');
        $data['setor'] = form_dropdown('setor', $options_setor, $setor, 'id="setor" class="form-control"');

        echo json_encode($data);
    }

    public function ajax_unidades()
    {
        $id = $this->input->post('id');

        $contrato = $this->db->select('id, nome, depto, area, contrato')->get_where('alocacao_contratos', array('id' => $id))->row();
        if (empty($contrato)) {
            exit('Nenhum contrato encontrado.');
        }
        $data['cliente'] = $contrato;
        $data['id_contrato'] = $contrato->id;

        $this->db->select('DISTINCT(a.setor) AS disponivel, c.setor AS selecionado', false);
        $this->db->join('alocacao_contratos b', 'b.depto = a.depto AND b.area = a.area');
        $this->db->join('alocacao_unidades c', 'c.id_contrato = b.id', 'left');
        $this->db->where('b.id', $contrato->id);
        $this->db->order_by('a.setor', 'asc');
        $setores = $this->db->get('usuarios a')->result();
        $options = array();
        $selected = array();
        foreach ($setores as $setor) {
            $options[$setor->disponivel] = $setor->disponivel;
            if ($setor->selecionado) {
                $selected[$setor->selecionado] = $setor->selecionado;
            }
        }
        $data['setores'] = form_multiselect('setor[]', $options, $selected, 'size="10" id="unidades" class="demo2"');

        echo json_encode($data);
    }

    public function ajax_servicos()
    {
        $id = $this->input->post('id');
        $dataReajuste = $this->input->post('data_reajuste');

        $contrato = $this->db->select('id, nome, depto, area, contrato')->get_where('alocacao_contratos', array('id' => $id))->row();
        if (empty($contrato)) {
            exit('Nenhum contrato encontrado.');
        }
        $data['contrato'] = $contrato;

        $this->db->select("a.data_reajuste, DATE_FORMAT(a.data_reajuste, '%m/%Y') AS data", false);
        $this->db->join('alocacao_contratos b', 'b.id = a.id_contrato');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('a.id_contrato', $contrato->id);
        $this->db->where('a.tipo', 1);
        $this->db->group_by('a.data_reajuste', 1);
        $this->db->order_by('a.data_reajuste', 'asc');
        $reajustes = $this->db->get('alocacao_servicos a')->result();
        $arrReajustes = array('' => 'selecione...') + array_column($reajustes, 'data', 'data_reajuste');
        if (empty($dataReajuste)) {
            $dataReajuste = max(array_keys($arrReajustes));
        }
        $data['reajuste'] = form_dropdown('id_reajuste', $arrReajustes, $dataReajuste, 'id="id_reajuste" class="form-control"');

        $this->db->select("a.id, a.descricao, FORMAT(a.valor, 2, 'de_DE') AS valor", false);
        $this->db->join('alocacao_contratos b', 'b.id = a.id_contrato');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('a.id_contrato', $contrato->id);
        if ($dataReajuste) {
            $this->db->where('a.data_reajuste', $dataReajuste);
        }
        $this->db->where('a.tipo', 1);
        $this->db->limit(4);
        $data['compartilhados'] = $this->db->get('alocacao_servicos a')->result();

        $this->db->select("a.id, a.descricao, FORMAT(a.valor, 2, 'de_DE') AS valor", false);
        $this->db->join('alocacao_contratos b', 'b.id = a.id_contrato');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('a.id_contrato', $contrato->id);
        if ($dataReajuste) {
            $this->db->where('a.data_reajuste', $dataReajuste);
        }
        $this->db->where('a.tipo', 0);
        $this->db->limit(4);
        $data['nao_compartilhados'] = $this->db->get('alocacao_servicos a')->result();

        echo json_encode($data);
    }

    public function ajax_reajuste()
    {
        $id = $this->input->post('id');

        $contrato = $this->db->select('id, nome, depto, area, contrato')->get_where('alocacao_contratos', array('id' => $id))->row();
        if (empty($contrato)) {
            exit('Nenhum contrato encontrado.');
        }
        $data['cliente'] = $contrato;

        $this->db->select('a.id, a.valor_indice');
        $this->db->select("DATE_FORMAT(a.data_reajuste,'%d/%m/%Y') AS data_reajuste", false);
        $this->db->join('alocacao_contratos b', 'b.id = a.id_cliente');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('a.id_cliente', $contrato->id);
        $this->db->limit(5);
        $data['values'] = $this->db->get('alocacao_reajuste a')->result();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        $data['id_empresa'] = $this->session->userdata('empresa');
        if ($data['data_assinatura']) {
            $data['data_assinatura'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_assinatura'])));
        }
        unset($data['id']);

        $status = $this->db->insert('alocacao_contratos', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function save_unidades()
    {
        $id_contrato = $this->input->post('id_contrato');
        $setores = $this->input->post('setor');

        $this->db->trans_start();

        $this->db->where('id_contrato', $id_contrato);
        if ($setores) {
            $this->db->where_not_in('setor', $setores);
        }
        $this->db->delete('alocacao_unidades');

        if ($setores) {
            $this->db->select('setor');
            $this->db->where('id_contrato', $id_contrato);
            $rows = $this->db->get('alocacao_unidades')->result();
            $setoresExistentes = array();
            foreach ($rows as $row) {
                $setoresExistentes[] = $row->setor;
            }

            $novosSetores = array_diff($setores, $setoresExistentes);

            $data['id_contrato'] = $id_contrato;
            foreach ($novosSetores as $setor) {
                $data['setor'] = $setor;
                $this->db->insert('alocacao_unidades', $data);
            }
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function save_servicos()
    {
        $post = $this->input->post();

        $rows = array();
        foreach ($post['id'] as $k => $id) {
            $rows[$k]['id'] = $id;
            $rows[$k]['id_contrato'] = $post['id_contrato'];
            if ($post['data_reajuste']) {
                $rows[$k]['data_reajuste'] = date('Y-m-d', strtotime(str_replace('/', '-', '01/' . $post['data_reajuste'])));
            } else {
                $rows[$k]['data_reajuste'] = null;
            }
        }
        foreach ($post['descricao'] as $k2 => $descricao) {
            $rows[$k2]['tipo'] = $k2 < 4 ? 1 : 0;
            $rows[$k2]['descricao'] = $descricao;
        }
        foreach ($post['valor'] as $k3 => $valor) {
            $rows[$k3]['valor'] = floatval(str_replace(array('.', ','), array('', '.'), $valor));
        }

        $status = true;
        foreach ($rows as $row) {
            if ($row['id'] and $row['descricao'] and $row['valor'] and empty($row['data_reajuste'])) {
                unset($row['data_reajuste']);
                $status = $this->db->update('alocacao_servicos', $row, array('id' => $row['id']));
            } elseif ($row['data_reajuste'] and ($row['descricao'] or $row['valor'])) {
                unset($row['id']);
                $status = $this->db->insert('alocacao_servicos', $row);
            } elseif ($row['id'] and (empty($row['descricao']) or empty($row['valor']))) {
                $status = $this->db->delete('alocacao_servicos', array('id' => $row['id']));
            }
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function save_reajuste()
    {
        $post = $this->input->post();

        $rows = array();
        foreach ($post['id'] as $k => $id) {
            $rows[$k]['id'] = $id;
            $rows[$k]['id_cliente'] = $post['id_cliente'];
        }
        foreach ($post['data_reajuste'] as $k2 => $data) {
            $rows[$k2]['data_reajuste'] = $data ? date('Y-m-d', strtotime(str_replace('/', '-', $data))) : '';
        }
        foreach ($post['valor_indice'] as $k3 => $valor) {
            $rows[$k3]['valor_indice'] = $valor;
        }

        $status = true;
        foreach ($rows as $row) {
            if ($row['id'] and $row['data_reajuste'] and $row['valor_indice']) {
                $status = $this->db->update('alocacao_reajuste', $row, array('id' => $row['id']));
            } elseif (empty($row['id']) and ($row['data_reajuste'] or $row['valor_indice'])) {
                $status = $this->db->insert('alocacao_reajuste', $row);
            } elseif ($row['id'] and (empty($row['data_reajuste']) or empty($row['valor_indice']))) {
                $status = $this->db->delete('alocacao_reajuste', array('id' => $row['id']));
            }
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        $data['id_empresa'] = $this->session->userdata('empresa');
        if ($data['data_assinatura']) {
            $data['data_assinatura'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_assinatura'])));
        } else {
            $data['data_assinatura'] = null;
        }
        $id = $data['id'];
        unset($data['id']);

        $status = $this->db->update('alocacao_contratos', $data, array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('alocacao_contratos', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
