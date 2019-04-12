<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Modelos extends MY_Controller
{

    public function index()
    {
        $data['idEmpresa'] = $this->session->userdata('empresa');
        $data['facilityEmpresas'] = ['' => 'selecione...'] + $this->getFacilitiesEmpresas();

        $this->load->view('facilities/modelos', $data);
    }

    // -------------------------------------------------------------------------

    public function ajaxList()
    {
        $post = $this->input->post();

        $this->db->select(["nome, versao, tipo, IF(status = 1, 'Ativo', 'Inativo') AS status, id"], false);
        if (!empty($post['status'])) {
            $this->db->where('status', $post['status']);
        }
        $recordsTotal = $this->db->get('facilities_modelos a')->num_rows();

        $sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

        $post = $this->input->post();
        if ($post['search']['value']) {
            $sql .= " WHERE s.nome LIKE '%{$post['search']['value']}%' OR 
                            s.versao LIKE '%{$post['search']['value']}%'";
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if ($post['order']) {
            $orderBy = [];
            foreach ($post['order'] as $order) {
                $orderBy[] = intval($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }

        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $rows = $this->db->query($sql)->result();

        $data = array();

        foreach ($rows as $row) {
            $data[] = array(
                $row->nome,
                $row->tipo,
                $row->versao,
                $row->status,
                '<button class="btn btn-sm btn-info" onclick="edit_modelo_vistoria(' . $row->id . ');" title="Editar modelo"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_modelo_vistoria(' . $row->id . ');" title="Excluir modelo"><i class="glyphicon glyphicon-trash"></i></button>
                 <a class="btn btn-sm btn-primary" href="' . site_url('facilities/modelos/relatorio/' . $row->id) . '" target="_blank" title="Imprimir plano"><i class="glyphicon glyphicon-list-alt"></i> Imprimir</a>
                 <button class="btn btn-sm btn-success" onclick="copiar_modelo_vistoria(' . $row->id . ');" title="Copiar plano"><i class="glyphicon glyphicon-plus"></i> Copiar</button>'
            );
        }

        $status = array(
            '' => 'Todos',
            '1' => 'Ativos',
            '0' => 'Inativos'
        );

        $output = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'status' => form_dropdown('', $status, $post['status'], 'id="status" class="form-control input-sm" onchange="reload_table();"'),
            'data' => $data
        );

        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    public function ajaxListInspecao()
    {
        $post = $this->input->post();
//        print_r($post['item']);exit;

        $sql = "SELECT t.*
                FROM (SELECT b.nome AS unidade,
                             c.andar,
                             d.sala,
                             e.nome AS item, 
                             f.nome AS subitem,
                             'V' AS id_tipo,
                             f.id AS id_subitem,
                             g.id AS id_vistoria,
                             h.id,
                             b.id AS id_unidade
                      FROM facilities_empresas a
                      INNER JOIN facilities_unidades b ON 
                                 b.id_empresa = a.id
                      INNER JOIN facilities_andares c ON 
                                 c.id_unidade = b.id
                      INNER JOIN facilities_salas d ON 
                                 d.id_andar = c.id
                      INNER JOIN facilities_itens e ON 
                                 e.id_sala = d.id
                      INNER JOIN facilities_vistorias f ON 
                                 f.id_item = e.id
                      LEFT JOIN facilities_modelos g ON 
                                g.id_facility_empresa = a.id AND g.id = '{$post['id']}'
                      LEFT JOIN facilities_modelos_vistorias h ON 
                                h.id_vistoria = f.id AND
                                h.id_modelo = g.id
                      WHERE a.id = '{$post['id_facility_empresa']}'
                      UNION
                      SELECT b.nome AS unidade,
                             c.andar,
                             d.sala,
                             e.nome AS item, 
                             f.nome AS subitem,
                             'M' AS id_tipo,
                             f.id AS id_subitem,
                             g.id AS id_vistoria,
                             h.id,
                             b.id AS id_unidade
                      FROM facilities_empresas a
                      INNER JOIN facilities_unidades b ON 
                                 b.id_empresa = a.id
                      INNER JOIN facilities_andares c ON 
                                 c.id_unidade = b.id
                      INNER JOIN facilities_salas d ON 
                                 d.id_andar = c.id
                      INNER JOIN facilities_itens e ON 
                                 e.id_sala = d.id
                      INNER JOIN facilities_manutencoes f ON 
                                 f.id_item = e.id
                      LEFT JOIN facilities_modelos g ON 
                                g.id_facility_empresa = a.id AND g.id = '{$post['id']}'
                      LEFT JOIN facilities_modelos_manutencoes h ON 
                                h.id_manutencao = f.id AND
                                h.id_modelo = g.id
                      WHERE a.id = '{$post['id_facility_empresa']}') t
                WHERE t.id_tipo = '{$post['tipo']}'";
        if (!empty($post['id_unidade'])) {
            $sql .= " AND t.id_unidade = '{$post['id_unidade']}'";
        }
        $recordsTotal = $this->db->query($sql)->num_rows();

        $sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

        $post = $this->input->post();
        if ($post['search']['value']) {
            $sql .= " WHERE s.unidade LIKE '%{$post['search']['value']}%' OR 
                            s.andar LIKE '%{$post['search']['value']}%' OR 
                            s.sala LIKE '%{$post['search']['value']}%' OR 
                            s.item LIKE '%{$post['search']['value']}%' OR 
                            s.subitem LIKE '%{$post['search']['value']}%'";
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if ($post['order']) {
            $orderBy = [];
            foreach ($post['order'] as $order) {
                $orderBy[] = intval($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }

        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $rows = $this->db->query($sql)->result();

        $data = array();

        foreach ($rows as $k => $row) {
            $data[] = array(
                $row->unidade,
                $row->andar,
                $row->sala,
                $row->item,
                $row->subitem,
                $row->id_tipo,
                form_checkbox("item[{$row->id_tipo}][{$row->id_subitem}]", $row->id, !empty($row->id), 'class="item"')
            );
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('facilities_andares b', 'b.id_unidade = a.id');
        $this->db->join('facilities_salas c', 'c.id_andar = b.id');
        $this->db->join('facilities_itens d', 'd.id_sala = c.id');
        $this->db->join('facilities_vistorias e', 'e.id_item = d.id', 'left');
        $this->db->join('facilities_manutencoes f', 'f.id_item = d.id', 'left');
        $this->db->where('a.id_empresa', $post['id_facility_empresa']);
        $this->db->where('(e.id IS NOT NULL OR f.id IS NOT NULL)', null, false);
        $rowUnidades = $this->db->get('facilities_unidades a')->result();
        $unidades = ['' => 'Todas'] + array_column($rowUnidades, 'nome', 'id');

        $output = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'unidades' => form_dropdown('', $unidades, $post['id_unidade'], 'class="form-control"'),
            'data' => $data
        );

        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    public function copiar()
    {
        $data = $this->getData($this->input->post('id'));

        $data->versao .= ' (Cópia)';
        $data->id_copia = $data->id;
        unset($data->id);

        $status = $this->db->insert('facilities_modelos', $data);

        if ($status) {
            $idModelo = $this->db->insert_id();

            $this->db->select("'{$idModelo}' as id_modelo, id_vistoria, status", false);
            $this->db->where('id_modelo', $idModelo);
            $dataVistorias = $this->db->get('facilities_modelos_vistorias')->result();

            $this->db->insert('facilities_modelos_vistorias', $dataVistorias);


            $this->db->select("'{$idModelo}' as id_modelo, id_manutencao, status", false);
            $this->db->where('id_modelo', $idModelo);
            $dataManutencao = $this->db->get('facilities_modelos_manutencoes')->result();

            $this->db->insert('facilities_modelos_manutencoes', $dataManutencao);
        }

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxEdit()
    {
        echo json_encode($this->getData($this->input->post('id')));
    }

    // -------------------------------------------------------------------------

    public function ajaxAdd()
    {
        $data = $this->setData();
        $status = $this->db->insert('facilities_modelos', $data);

        if ($status) {
            $status = $this->ajaxSaveInspecoes($this->db->insert_id());
        }

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxUpdate()
    {
        $id = $this->input->post('id');

        $data = $this->setData();
        $this->db->set($data);
        $this->db->where('id', $id);
        $status = $this->db->update('facilities_modelos');

        if ($status) {
            $status = $this->ajaxSaveInspecoes($id);
        }

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_modelos', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxSaveInspecoes($idModelo)
    {
        $status = true;

        $dataVistoria = $this->setDataInspecoes($idModelo, 'V');

        if ($dataVistoria['update']) {
            $this->db->where('id_modelo', $idModelo);
            $this->db->where_not_in('id_vistoria', array_column($dataVistoria['update'], 'id_vistoria'));
            $this->db->delete('facilities_modelos_vistorias');
        }

        if ($dataVistoria['insert']) {
            $status = $this->db->insert_batch('facilities_modelos_vistorias', $dataVistoria['insert']);
        }

        $dataManutencao = $this->setDataInspecoes($idModelo, 'M');

        if ($status) {
            if ($dataManutencao['update']) {
                $this->db->where('id_modelo', $idModelo);
                $this->db->where_not_in('id_manutencao', array_column($dataManutencao['update'], 'id_manutencao'));
                $this->db->delete('facilities_modelos_manutencoes');
            }

            if ($dataManutencao['insert']) {
                $status = $this->db->insert_batch('facilities_modelos_manutencoes', $dataManutencao['insert']);
            }
        }

        return $status;
    }

    // -------------------------------------------------------------------------

    public function relatorio($idVistoria, $pdf = false)
    {
        $data = array(
            'idVistoria' => $idVistoria,
            'is_pdf' => $pdf
        );


        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();
        $data['query_string'] = '?id=' . $idVistoria;

        $this->db->select('a.nome, b.nome AS empresa, a.tipo');
        $this->db->join('facilities_empresas b', 'b.id = a.id_facility_empresa');
        $vistoria = $this->db->get_where('facilities_modelos a', ['a.id' => $idVistoria])->row();
        $data['nomeVistoria'] = $vistoria->nome;
        $data['empresaFacilities'] = $vistoria->empresa;


        $sql = "SELECT c.id AS id_item,
                       b.id AS id_subitem,
                       d.id AS id_sala,
                       b.nome AS subitem,
                       'vistoria' AS tipo,
                       c.nome AS item,
                       d.sala,
                       e.andar,
                       f.nome AS unidade
                FROM facilities_modelos_vistorias a
                JOIN facilities_vistorias b ON b.id = a.id_vistoria
                JOIN facilities_itens c ON c.id = b.id_item
                JOIN facilities_salas d ON d.id = c.id_sala
                JOIN facilities_andares e ON e.id = d.id_andar
                JOIN facilities_unidades f ON f.id = e.id_unidade
                WHERE a.id_modelo = '{$idVistoria}' AND '{$vistoria->tipo}' = 'V'
                UNION
                SELECT c.id AS id_item,
                       b.id AS id_subitem,
                       d.id AS id_sala,
                       b.nome AS subitem,
                       'manutencao' AS tipo,
                       c.nome AS item,
                       d.sala,
                       e.andar,
                       f.nome AS unidade
                FROM facilities_modelos_manutencoes a
                JOIN facilities_manutencoes b ON b.id = a.id_manutencao
                JOIN facilities_itens c ON c.id = b.id_item
                JOIN facilities_salas d ON d.id = c.id_sala
                JOIN facilities_andares e ON e.id = d.id_andar
                JOIN facilities_unidades f ON f.id = e.id_unidade
                WHERE a.id_modelo = '{$idVistoria}' AND '{$vistoria->tipo}' = 'M'
                ORDER BY unidade, andar, sala, item, subitem;";

        $vistoriaItens = $this->db->query($sql)->result();

        $data['vistorias'] = array();
        $data['totalVistorias'] = 0;
        $data['totalManutencoes'] = 0;

        foreach ($vistoriaItens as $k => $vistoriaItem) {
            if (!isset($data['vistorias'][$vistoriaItem->id_sala])) {
                $data['vistorias'][$vistoriaItem->id_sala]['nome'] = $vistoriaItem;
            }
            $data['vistorias'][$vistoriaItem->id_sala]['itens'][] = $vistoriaItem;
            $data['vistorias'][$vistoriaItem->id_sala]['subitens'][] = $vistoriaItem;

            if ($vistoriaItem->tipo === 'V') {
                $data['totalVistorias']++;
            } elseif ($vistoriaItem->tipo === 'M') {
                $data['totalManutencoes']++;
            }
        }

        if ($pdf) {
            return $this->load->view('facilities/relatorio_modelo', $data, true);
        }

        $this->load->view('facilities/relatorio_modelo', $data);
    }

    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '.itens { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '.itens thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '.itens tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';
        $stylesheet .= '#no_itens { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#no_itens thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#no_itens tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(54);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);

        $data = $this->input->get();
        $this->m_pdf->pdf->writeHTML($this->relatorio($data['id'], true));


        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Apontamento de Insumos - ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }

    /* -------------------------------------------------------------------------
     *
     * -------------------------------------------------------------------------
     */

    protected function getData($id = '')
    {
        if ($id) {
            $this->db->where('id', $id);
        }
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        return $this->db->get_where('facilities_modelos')->row();
    }

    // -------------------------------------------------------------------------

    protected function getFacilitiesEmpresas()
    {
        $this->db->select('id, nome');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'asc');
        $rows = $this->db->get('facilities_empresas')->result();

        return array_column($rows, 'nome', 'id');
    }

    // -------------------------------------------------------------------------

    protected function setData()
    {
        $data = $this->input->post();

        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome é obrigatório']));
        }

        if (strlen($data['id_facility_empresa']) == 0) {
            exit(json_encode(['erro' => 'A empresa de facilities é obrigatória']));
        }

        if (strlen($data['versao']) == 0) {
            $data['versao'] = null;
        }

        unset($data['id'], $data['item']);

        return $data;
    }

    // -------------------------------------------------------------------------

    protected function setDataInspecoes($idModelo, $tipo)
    {
        if ($tipo === 'V') {
            $nomeColunaItem = 'id_vistoria';
        } elseif ($tipo === 'M') {
            $nomeColunaItem = 'id_manutencao';
        } else {
            return [];
        }

        $itens = $this->input->post('item')[$tipo] ?? [];

        $data = ['insert' => null, 'update' => null];

        foreach ($itens as $item => $id) {
            if ($id) {
                $data['update'][] = array(
                    'id' => $id,
                    'id_modelo' => $idModelo,
                    $nomeColunaItem => $item
                );
            } else {
                $data['insert'][] = array(
                    'id_modelo' => $idModelo,
                    $nomeColunaItem => $item
                );
            }
        }

        return $data;
    }

}
