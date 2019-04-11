<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//require_once APPPATH . 'controllers/Funcionarios.php';
//class PDIs extends Funcionarios
class PDI extends MY_Controller
{

    public function index()
    {
        $this->gerenciar();
//        parent::index();
    }

    public function gerenciar()
    {
        $row = $this->db->get_where('usuarios', array('id' => $this->uri->rsegment(3)))->row();
        $data['id_usuario'] = $row->id;
        $data['nome_usuario'] = $row->nome;
        $data['funcao_usuario'] = $row->funcao;
        $this->load->view('pdi', $data);
    }

    public function ajax_list()
    {
        $this->load->library('pagination');

        $query = $this->input->post('busca');

        $qryWHERE = 'WHERE usuario IN (SELECT usuario FROM usuarios WHERE usuario = ?) ';
        $dataWHERE[] = $this->uri->rsegment(3);

        if (!empty($query)) {
            $qryWHERE .= 'AND (pdi LIKE ? OR data_inicio LIKE ? OR data_termino LIKE ?)';
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = site_url('pdi/ajax_list');
        $config['total_rows'] = $this->db->query("SELECT * FROM pdi {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $dataWHERE[] = $this->uri->rsegment(4, 0);
        $dataWHERE[] = $config['per_page'];

        $data['total'] = $this->db->get('pdi')->num_rows();
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT id, 
                                                  usuario, 
                                                  nome, 
                                                  DATE_FORMAT(data_inicio,'%d/%m/%Y') AS data_inicio, 
                                                  DATE_FORMAT(data_termino,'%d/%m/%Y') AS data_termino 
                                           FROM pdi {$qryWHERE} ORDER BY id DESC LIMIT ?,?", $dataWHERE);
        $this->load->view('getpdi', $data);
//        $id_empresa = $this->uri->rsegment(4) ? $this->uri->rsegment(4) : $this->session->userdata('id');
//        $post = $this->input->post();
//
//        $sql = "SELECT a.id, 
//                       a.avaliado, 
//                       b.nome, 
//                       d.nome AS cargo 
//                FROM avaliador_avaliados a 
//                INNER JOIN usuarios b ON 
//                     	   a.avaliado = b.id
//                INNER JOIN funcionarios_cargos c ON 
//                           c.id_usuario = b.id 
//                INNER JOIN cargos d ON 
//                           d.id = c.id_cargo 
//                WHERE a.id_avaliacao = {$id_avaliacao}";
//
//        $columns = array('a.avaliado', 'b.nome', 'd.nome');
//
//        if ($post['search']['value']) {
//            foreach ($columns as $key => $column) {
//                if ($key > 1) {
//                    $sql .= " OR
//                         {$column} LIKE '%{$post['search']['value']}%'";
//                } elseif ($key == 1) {
//                    $sql .= " AND 
//                        ({$column} LIKE '%{$post['search']['value']}%'";
//                }
//            }
//            $sql .= ')';
//        }
//        if (isset($post['order'])) {
//            $orderBy = array();
//            foreach ($post['order'] as $order) {
//                $orderBy[] = ($order['column'] + 3) . ' ' . $order['dir'];
//            }
//            $sql .= ' 
//                    ORDER BY ' . implode(', ', $orderBy);
//        } else {
//            $sql .= ' 
//                    ORDER BY 2';
//        }
//        $sql .= " 
//                LIMIT {$post['start']}, {$post['length']}";
//
//        $query = $this->db->query($sql);
//        $list = $query->result();
//
//        $data = array();
//        foreach ($list as $avaliacao) {
//            $row = array();
//            $row[] = $avaliacao->nome;
//            $row[] = $avaliacao->cargo;
//            $row[] = '
//                      <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_pdi(' . "'" . $avaliacao->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
//                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pdi(' . "'" . $avaliacao->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
//                      <a class="btn btn-sm btn-success" href="' . site_url('pdi_desenvolvimento/' . $avaliacao->id . "/" . $id) . '" title="Gerenciar Avaliacao" ><i class="glyphicon glyphicon-plus"></i>Plano de desenvolvimento</a>
//                      <a class="btn btn-sm btn-info" href="' . site_url('pdi/relatorio/' . $avaliacao->id . "/" . $id) . '" title="Relatórios"><i class="glyphicon glyphicon-list-alt"></i> Imprimir</a>
//                     ';
//
//            $data[] = $row;
//        }
//
//        $output = array(
//            "draw" => $post['draw'],
//            "recordsTotal" => $this->gerenciarAvaliacao->count_all(),
//            "recordsFiltered" => $query->num_rows(),
//            "data" => $data,
//        );
//
//        //output to json format
//        echo json_encode($output);
    }

    public function ajax_list1($id_usuario)
    {
        if (empty($id_usuario)) {
            $id_usuario = $this->uri->rsegment(3, 0);
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, s.usuario, s.nome, s.data_inicio, s.data_termino 
                FROM (SELECT id, usuario, nome,
                             DATE_FORMAT(data_inicio,'%d/%m/%Y') AS data_inicio,
                             DATE_FORMAT(data_termino,'%d/%m/%Y') AS data_termino
                      FROM pdi
                      WHERE usuario = {$id_usuario}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.usuario', 's.nome', 's.data_inicio', 's.data_termino');
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
        foreach ($list as $pdi) {
            $row = array();
            $row[] = $pdi->nome;
            $row[] = $pdi->data_inicio;
            $row[] = $pdi->data_termino;

            $row[] = '
                      <button class="btn btn-sm btn-primary" onclick="edit_pdi(' . $pdi->id . ');"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="delete_pdi(' . $pdi->id . ');"><i class="glyphicon glyphicon-trash"></i></button>
                      <a class="btn btn-sm btn-success" href="' . site_url('pdi_desenvolvimento/gerenciar/' . $pdi->usuario . '/' . $pdi->id) . '"><i class="glyphicon glyphicon-plus"></i> Plano de desenvolvimento</a>
                      <a class="btn btn-sm btn-info" href="' . site_url('pdi/relatorio/' . $pdi->id) . '"><i class="glyphicon glyphicon-list-alt"></i> Relatório</a>
                      <a class="btn btn-sm btn-info" href="' . site_url('pdi/pdfRelatorio/' . $pdi->id) . '"><i class="fa fa-print"></i> Imprimir</a>
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

    public function ajax_edit($id)
    {
        $data = $this->db->get_where('pdi', array('id' => $id))->row();
        if ($data->data_inicio) {
            $dataFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_inicio)));
            $data->data_inicio = $dataFormatada;
        }
        if ($data->data_termino) {
            $dataFimFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_termino)));
            $data->data_termino = $dataFimFormatada;
        }

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        if ($data['data_termino']) {
            $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));
        }

        $status = (bool)$this->db->insert('pdi', $data);
        echo json_encode(array("status" => $status));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        if ($data['data_termino']) {
            $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));
        }

        $status = (bool)$this->db->update('pdi', $data, array('id' => $data['id']));
        echo json_encode(array("status" => $status));
    }

    public function ajax_delete($id)
    {
        $status = (bool)$this->db->delete('pdi', array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    public function relatorio($pdi = null, $pdf = false)
    {
        if (empty($pdi)) {
            $pdi = $this->uri->rsegment(3);
        }

        $vars['foto'] = 'imagens/usuarios/' . $this->session->userdata('foto');
        $vars['foto_descricao'] = 'imagens/usuarios/' . $this->session->userdata('foto_descricao');

        $sql = "SELECT a.nome,
                       b.nome AS colaborador,
                       b.nome AS cargo,
                       b.funcao,
                       CONCAT_WS('/', b.depto, b.area, b.setor) AS depto,
                       DATE_FORMAT(a.data_inicio,'%d/%m/%Y') AS data_inicio, 
                       DATE_FORMAT(a.data_termino,'%d/%m/%Y') AS data_termino, 
                       DATE_FORMAT(curdate(),'%d/%m/%Y') AS data_atual, 
                       (case when curdate() between a.data_inicio and a.data_termino
                            then 'ok' 
                            else 'expirado' end) AS data_valida
                FROM pdi a
                LEFT JOIN usuarios b ON
                            b.id = a.usuario 
                LEFT JOIN funcionarios_cargos d ON
                            d.id_usuario = b.id 
                LEFT JOIN cargos c ON
                            c.id = d.id_cargo 
                LEFT JOIN pdi_desenvolvimento e ON
                          e.pdi = a.id
                WHERE a.id = {$pdi}";


        $vars['dadosPDI'] = $this->db->query($sql)->row();
        $query = "SELECT competencia,
                         descricao,
                         expectativa,
                         resultado,
                         DATE_FORMAT(data_inicio,'%d/%m/%Y') AS data_inicio, 
                         DATE_FORMAT(data_termino,'%d/%m/%Y') AS data_termino, 
                         status
                 FROM pdi_desenvolvimento
                 WHERE pdi = {$pdi} 
                 ORDER BY data_inicio ASC, 
                          data_termino ASC";
        $vars['itensPDI'] = $this->db->query($query)->result();

        $vars['is_pdf'] = $pdf;

        $this->load->helper('url');
        if ($pdf) {
            return $this->load->view('getpdi_relatorio', $vars, true);
        } else {
            $this->load->view('pdi_relatorio', $vars);
        }
    }

    public function pdfRelatorio()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.pdi thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.pdi tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.pdi tbody tr th { font-size: 11px; padding: 2px; } ';
        $stylesheet .= 'table.pdi tbody tr:nth-child(2) td { border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.pdi tbody td { font-size: 12px; padding: 1px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.pdi tbody td strong { font-weight: bold; } ';

        $stylesheet .= 'table.desenvolvimento thead tr.success th { background-color: #dff0d8; }';
        $stylesheet .= 'table.desenvolvimento thead th { font-size: 12px; padding: 5px; border-bottom: 2px solid #ddd; } ';
        $stylesheet .= 'table.desenvolvimento thead tr.active td { background-color: #e5e5e5; }';
        $stylesheet .= 'table.desenvolvimento tbody td { font-size: 12px; padding: 5px; border-top: 1px solid #ddd; word-wrap: break-word;} ';
        $stylesheet .= 'table.desenvolvimento tbody td:nth-child(2) + td { text-align: center; }';
        $stylesheet .= 'table.desenvolvimento tbody td.success { background-color: #dff0d8; }';
        $stylesheet .= 'table.desenvolvimento tbody td.info { background-color: #d9edf7; }';
        $stylesheet .= 'table.desenvolvimento tbody td.warning { background-color: #fcf8e3; }';
        $stylesheet .= 'table.desenvolvimento tbody td.danger { background-color: #f2dede; }';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), true));
        $this->m_pdf->pdf->SetHTMLFooter('<table style="width:100%; text-align: center; page-break-inside: avoid; font-size: 12px; padding: 5px;">
                <tr>
                    <td style="width:50%;">Data:_____/_____/__________</td>
                    <td>________________________________________</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Assinatura do colaborador</td>
                </tr>
                <tr>
                    <td colspan="2"><br><br><br></td>
                </tr>
            </table>
            <div style="border-top: 5px solid #ddd;"></div>');

        $sql = "SELECT b.nome 
                FROM pdi a 
                INNER JOIN usuarios b ON 
                           b.id = a.usuario 
                WHERE a.id = {$this->uri->rsegment(3)}";
        $row = $this->db->query($sql)->row();
        $this->m_pdf->pdf->Output('PDI-' . $row->nome . '.pdf', 'D');
    }

}
