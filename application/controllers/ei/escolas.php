<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escolas extends MY_Controller
{

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar($id_diretoria = null)
    {
        $has_contrato = $this->db->get_where('ei_contratos', array('id' => $id_diretoria))->num_rows();
        if ($id_diretoria and !$has_contrato) {
            redirect(site_url('home'));
        }

        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');

        $data = array();

        $this->db->select('DISTINCT(a.depto) AS nome', false);
        $this->db->join('ei_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('ei_supervisores c', 'c.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        $data['depto'] = array();
        if (in_array($this->session->userdata('nivel'), array(11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
        } else {
            $data['depto'] = array('' => 'Todos');
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.depto', 'asc');
        $deptos = $this->db->get('ei_diretorias a')->result();
        foreach ($deptos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('ei_supervisores c', 'c.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        if ($id_diretoria) {
            $this->db->where('a.id', $id_diretoria);
        }
        if (in_array($this->session->userdata('nivel'), array(11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $data['diretoria'] = array();
            $data['id_diretoria'] = array();
        } else {
            $data['diretoria'] = array('' => 'Todas');
            $data['id_diretoria'] = array('' => 'selecione...');
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $diretorias = $this->db->get('ei_diretorias a')->result();
        foreach ($diretorias as $diretoria) {
            $data['diretoria'][$diretoria->id] = $diretoria->nome;
            $data['id_diretoria'][$diretoria->id] = $diretoria->nome;
        }

        $this->db->select('a.id, a.contrato AS nome');
        $this->db->join('ei_diretorias b', 'b.id = a.id_cliente');
        if ($id_diretoria) {
            $this->db->where('a.id', $id_diretoria);
        }
        if (in_array($this->session->userdata('nivel'), array(11))) {
            $data['contrato'] = array();
            $data['id_contrato'] = array();
        } else {
            $data['contrato'] = array('' => 'Todos');
            $data['id_contrato'] = array('' => 'selecione...');
        }
        $contratos = $this->db->get('ei_contratos a')->result();
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->id] = $contrato->nome;
            $data['id_contrato'][$contrato->id] = $contrato->nome;
        }

        $this->db->select('c.id_supervisor AS id, d.nome', false);
        $this->db->join('ei_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('ei_supervisores c', 'c.id_escola = b.id');
        $this->db->join('usuarios d', 'd.id = c.id_supervisor');
        $this->db->where('a.id_empresa', $empresa);
        if ($id_diretoria) {
            $this->db->where('a.id', $id_diretoria);
        }
        if (in_array($this->session->userdata('nivel'), array(11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $data['supervisor'] = array();
        } else {
            $data['supervisor'] = array('' => 'Todos');
        }
        $this->db->group_by('c.id_supervisor');
        $this->db->order_by('d.nome', 'asc');
        $supervisores = $this->db->get('ei_diretorias a')->result();

        foreach ($supervisores as $supervisor) {
            $data['supervisor'][$supervisor->id] = $supervisor->nome;
        }

        $this->db->select('a.municipio AS nome');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where('a.municipio IS NOT NULL');
        $this->db->group_by('a.municipio');
        $this->db->order_by('a.municipio', 'asc');
        $municipios = $this->db->get('ei_escolas a')->result();
        $data['municipio'] = ['' => 'Todos'] + array_column($municipios, 'municipio', 'municipio');

        $this->load->view('ei/escolas', $data);
    }

    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');
        $busca = $this->input->post('busca');
        $filtro = array();

        $this->db->select('a.municipio');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where('a.municipio IS NOT NULL');
        if ($busca['depto']) {
            $this->db->where('b.depto', $busca['depto']);
        }
        if ($busca['diretoria']) {
            $this->db->where('b.id', $busca['diretoria']);
        }
        $this->db->group_by('a.municipio');
        $this->db->order_by('a.municipio', 'asc');
        $municipios = $this->db->get('ei_escolas a')->result();
        $filtro['municipio'] = ['' => 'Todos'] + array_column($municipios, 'municipio', 'municipio');

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('ei_supervisores c', 'c.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        if (in_array($this->session->userdata('nivel'), array(11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $filtro['diretoria'] = array();
        } else {
            $filtro['diretoria'] = array('' => 'Todas');
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $diretorias = $this->db->get('ei_diretorias a')->result();
        foreach ($diretorias as $diretoria) {
            $filtro['diretoria'][$diretoria->id] = $diretoria->nome;
        }

        $this->db->select('c.id_supervisor AS id, d.nome', false);
        $this->db->join('ei_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('ei_supervisores c', 'c.id_escola = b.id');
        $this->db->join('usuarios d', 'd.id = c.id_supervisor');
        $this->db->where('a.id_empresa', $empresa);
        if ($busca['diretoria']) {
            $this->db->where('a.id', $busca['diretoria']);
        }
        if (in_array($this->session->userdata('nivel'), array(11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $filtro['supervisor'] = array();
        } else {
            $filtro['supervisor'] = array('' => 'Todos');
        }
        $this->db->group_by('c.id_supervisor');
        $this->db->order_by('d.nome', 'asc');
        $supervisores = $this->db->get('ei_diretorias a')->result();

        foreach ($supervisores as $supervisor) {
            $filtro['supervisor'][$supervisor->id] = $supervisor->nome;
        }

        $data['municipio'] = form_dropdown('municipio', $filtro['municipio'], $busca['municipio'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['diretoria'] = form_dropdown('diretoria', $filtro['diretoria'], $busca['diretoria'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['supervisor'] = form_dropdown('supervisor', $filtro['supervisor'], $busca['supervisor'], 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }

    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();

        $id_diretoria = $this->input->post('id_diretoria');

        $sql = "SELECT s.diretoria,
                       s.municipio,
                       s.codigo,
                       s.nome,
                       s.id
                FROM (SELECT a.id, 
                             b.nome AS diretoria,
                             a.municipio,
                             a.codigo,
                             a.nome,
                             d.nome AS supervisor
                      FROM ei_escolas a
                      INNER JOIN ei_diretorias b ON
                                b.id = a.id_diretoria
                      LEFT JOIN ei_supervisores c ON 
                                c.id_escola = a.id
                      LEFT JOIN usuarios d ON
                                 d.id = c.id_supervisor
                      WHERE b.id_empresa = {$this->session->userdata('empresa')}";
        if ($id_diretoria) {
            $sql .= " AND b.id = {$id_diretoria}";
        } elseif (!empty($busca['diretoria'])) {
            $sql .= " AND b.id = '{$busca['diretoria']}'";
        }
        if (!empty($busca['depto'])) {
            $sql .= " AND b.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['municipio'])) {
            $sql .= " AND a.municipio = '" . addslashes($busca['municipio']) . "'";
        }
        if (!empty($busca['supervisor'])) {
            $sql .= " AND c.id_supervisor = '{$busca['supervisor']}'";
        }
        if (!empty($busca['contrato'])) {
            $sql .= " AND b.contrato = '{$busca['contrato']}'";
        }
        $sql .= ' GROUP BY a.id 
                  ORDER BY a.municipio ASC) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.diretoria', 's.nome', 's.supervisor');
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
        foreach ($list as $ei) {
            $row = array();
            $row[] = $ei->diretoria;
            $row[] = $ei->municipio;
            $row[] = $ei->codigo;
            $row[] = $ei->nome;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_escola(' . $ei->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_escola(' . $ei->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                      <a class="btn btn-sm btn-primary" href="' . site_url('ei/alunos/gerenciar/' . $ei->id) . '" title="Gerenciar alunos">Alunos</a>
                     ';

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

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('ei_escolas', array('id' => $id))->row();
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        if (strlen($data['codigo']) == 0) {
            $data['codigo'] = null;
        }
        if (empty($data['numero'])) {
            $data['numero'] = null;
        }
        if (empty($data['municipio'])) {
            $data['municipio'] = null;
        }
        if (empty($data['id_diretoria'])) {
            $data['id_diretoria'] = null;
        }
        $status = $this->db->insert('ei_escolas', $data);
        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);
        if (strlen($data['codigo']) == 0) {
            $data['codigo'] = null;
        }
        if (empty($data['numero'])) {
            $data['numero'] = null;
        }
        if (empty($data['municipio'])) {
            $data['municipio'] = null;
        }
        if (empty($data['id_diretoria'])) {
            $data['id_diretoria'] = null;
        }
        if (empty($data['periodo_manha'])) {
            $data['periodo_manha'] = null;
        }
        if (empty($data['pessoas_contato'])) {
            $data['pessoas_contato'] = null;
        }
        if (empty($data['periodo_tarde'])) {
            $data['periodo_tarde'] = null;
        }
        if (empty($data['periodo_noite'])) {
            $data['periodo_noite'] = null;
        }
        $status = $this->db->update('ei_escolas', $data, array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_escolas', array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }


    public function pdf()
    {
        $empresa = $this->session->userdata('empresa');
        $this->load->library('m_pdf');

        $stylesheet = 'table { font-size: 12px; } ';
        $stylesheet .= 'table tr th, table tr td { border: 1px solid #fff; } ';
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);

        $this->db->select('foto, foto_descricao');
        $usuario = $this->db->get_where('usuarios', array('id' => $empresa))->row();

        $sql = "SELECT b.nome AS diretoria,
                       NULL,
                       a.municipio,
                       a.nome AS escola
                FROM ei_escolas a
                INNER JOIN ei_diretorias b ON
                           b.id = a.id_diretoria
                LEFT JOIN ei_supervisores c ON 
                          c.id_escola = a.id
                LEFT JOIN usuarios d ON
                          d.id = c.id_supervisor
                WHERE b.id_empresa = {$empresa}
                GROUP BY a.id
                ORDER BY a.municipio ASC";
        $data = $this->db->query($sql)->result_array();

        $cabecalho = '<table width="100%">
            <thead>
            <tr>
                <td>
                    <img src="' . base_url('imagens/usuarios/' . $usuario->foto) . '" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="vertical-align: top;" width="100%">
                    <p>
                        <img src="' . base_url('imagens/usuarios/' . $usuario->foto_descricao) . '" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2" style="padding-bottom: 12px;  text-align: center; border-top: 5px solid #ddd; border-bottom: 2px solid #ddd; padding:5px;">
                    <h1 style="font-weight: bold;">RELAÇÃO CLIENTE x MUNICÍPIO x ESCOLAS</h1>
                </td>
            </tr>
            </tbody>
        </table>
        <br><br>';

        $table = [['Cliente', '', 'Município', 'Escola']];
        foreach ($data as $row) {
            $table[] = $row;
        }
        $this->load->library('table');

        $html = $cabecalho . $this->table->generate($table);

        $this->m_pdf->pdf->writeHTML($html);

        $this->m_pdf->pdf->Output("EI_escolas.pdf", 'D');
    }

}
