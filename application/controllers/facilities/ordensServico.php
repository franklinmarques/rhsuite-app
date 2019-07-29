<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OrdensServico extends MY_Controller
{
    //==========================================================================
    public function index()
    {
        $idUsuario = $this->session->userdata('id');

        $data = $this->getEstruturas($idUsuario);

        $data['idUsuario'] = $idUsuario;

        $data['vistoriador'] = $this->session->userdata('nivel') === '17';

        $this->load->view('facilities/ordens_servico', $data);
    }

    //==========================================================================
    public function montarEstrutura()
    {
        $post = $this->input->post();

        $areas = $this->getAreas($post['depto']);
        $setores = $this->getSetores($post['depto'], $post['area']);
        $requisitantes = $this->getRequisitantes($post['depto'], $post['area'], $post['setor']);

        $data['area'] = form_dropdown('id_area', $areas, $post['area'], 'class="form-control" onchange="montar_estrutura()"');
        $data['setor'] = form_dropdown('id_setor', $setores, $post['setor'], 'class="form-control" onchange="montar_estrutura()"');
        $data['requisitante'] = form_dropdown('id_representante', $requisitantes, $post['requisitante'], 'id="requisitante" class="form-control"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxList()
    {
        $post = $this->input->post();

        $idUsuario = $this->session->userdata('id');


        $sql = "SELECT a.numero_os,
                       a.data_abertura,
                       a.data_resolucao_problema,
                       a.data_tratamento,
                       a.data_fechamento,
                       a.prioridade,
                       (CASE a.status
                             WHEN 'A' THEN 'Aberta'
                             WHEN 'F' THEN 'Fechada'
                             WHEN 'P' THEN 'Parcialmente fechada'
                             WHEN 'E' THEN 'Em tratamento'
                             WHEN 'G' THEN 'Tratada - Aguardando aprovação requisitante' END) AS descricao_status,
                       c.nome AS vistoriador,
                       CONCAT('<strong>Solicitação/andamento:</strong>\n', IFNULL(a.descricao_problema, ''),'\n<strong>Solicitação/complemento:</strong>\n', IFNULL(a.complemento, ''), '\n<strong>Andamento:</strong>\n', IFNULL(a.observacoes, '')) AS descricao_problema,
                       DATE_FORMAT(a.data_abertura, '%d/%m/%Y')   AS data_abertura_de,
                       DATE_FORMAT(a.data_resolucao_problema, '%d/%m/%Y')   AS data_resolucao_problema_de,
                       DATE_FORMAT(a.data_tratamento, '%d/%m/%Y')   AS data_tratamento_de,
                       DATE_FORMAT(a.data_fechamento, '%d/%m/%Y') AS data_fechamento_de,
                       (CASE a.prioridade
                             WHEN 0 THEN 'Nenhuma'
                             WHEN 1 THEN 'Baixa'
                             WHEN 2 THEN 'Média'
                             WHEN 3 THEN 'Alta'
                             WHEN 4 THEN 'Urgente' END) AS descricao_prioridade,
                       a.status
                FROM facilities_ordens_servico a
                INNER JOIN usuarios c ON 
                           c.id = a.id_requisitante
                LEFT JOIN usuarios b ON 
                           b.id = a.id_usuario";
        if ($this->session->userdata('tipo') == 'empresa') {
            $sql .= " WHERE (b.id = '{$idUsuario}' OR b.empresa = '{$idUsuario}')";
        } elseif (in_array($this->session->userdata('nivel'), [7, 8, 17, 18])) {
            $sql .= " WHERE c.empresa = '{$this->session->userdata('empresa')}'";
        } elseif (in_array($this->session->userdata('nivel'), [9, 10])) {
            $sql .= " WHERE b.id = '{$idUsuario}'";
        }
        if (!empty($post['status'])) {
            $sql .= " AND a.status = '{$post['status']}'";
        }
        if (!empty($post['ano'])) {
            $sql .= " AND (YEAR(a.data_abertura) = '{$post['ano']}' OR YEAR(a.data_fechamento) = '{$post['ano']}')";
        }

        $config = array(
            'search' => ['requisitante', 'estrutura', 'descricao_problema']
        );
        $this->load->library('dataTables', $config);

        $rows = $this->datatables->query($sql);


        $data = array();

        foreach ($rows->data as $row) {
            if ($this->session->userdata('nivel') === '17') {
//                if ($row->status === 'G') {
                $acoes = '<button class="btn btn-sm btn-info" onclick="edit_os(' . $row->numero_os . ');" title="Editar ordem de serviço"><i class="glyphicon glyphicon-pencil"></i></button>
                              <button class="btn btn-sm btn-danger" onclick="delete_os(' . $row->numero_os . ');" title="Excluir ordem de serviço"><i class="glyphicon glyphicon-trash"></i></button>
                              <a class="btn btn-sm btn-primary" href="' . site_url('facilities/ordensServico/relatorio/' . $row->numero_os) . '" title="Imprimir ordem de serviço"><i class="glyphicon glyphicon-print"></i></a>
                              <button class="btn btn-sm btn-warning notificarFechamento" onclick="notificar_fechamento(' . $row->numero_os . ');" title="Notificar fechamento de O. S."><i class="glyphicon glyphicon-envelope"></i></button>';
//                } else {
//                    $acoes = '<button class="btn btn-sm btn-info" onclick="edit_os(' . $row->numero_os . ');" title="Editar ordem de serviço"><i class="glyphicon glyphicon-pencil"></i></button>
//                              <button class="btn btn-sm btn-danger" onclick="delete_os(' . $row->numero_os . ');" title="Excluir ordem de serviço"><i class="glyphicon glyphicon-trash"></i></button>
//                              <a class="btn btn-sm btn-primary" href="' . site_url('facilities/ordensServico/relatorio/' . $row->numero_os) . '" title="Imprimir ordem de serviço"><i class="glyphicon glyphicon-print"></i></a>
//                              <button class="btn btn-sm btn-warning disabled" title="Notificar fechamento de O. S."><i class="glyphicon glyphicon-envelope"></i></button>';
//                }
            } else {
                $acoes = '<button class="btn btn-sm btn-info" onclick="edit_os(' . $row->numero_os . ');" title="Editar ordem de serviço"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger" onclick="delete_os(' . $row->numero_os . ');" title="Excluir ordem de serviço"><i class="glyphicon glyphicon-trash"></i></button>
                          <a class="btn btn-sm btn-primary" href="' . site_url('facilities/ordensServico/relatorio/' . $row->numero_os) . '" title="Imprimir ordem de serviço"><i class="glyphicon glyphicon-print"></i></a>';
            }

            $data[] = array(
                $row->numero_os,
                $row->data_abertura_de,
                $row->data_resolucao_problema_de,
                $row->data_tratamento_de,
                $row->data_fechamento_de,
                $row->descricao_prioridade,
                $row->descricao_status,
                $row->vistoriador,
                nl2br($row->descricao_problema),
                $acoes,
                $row->prioridade,
                $row->status
            );
        }

        $status = array(
            '' => 'Todas',
            'A' => 'Abertas',
            'E' => 'Em tratamento',
            'G' => 'Aguardando aprovação',
            'P' => 'Parcialmente fechadas',
            'F' => 'Fechadas'
        );

        $rows->status = form_dropdown('busca_status', $status, $post['status'], 'class="form-control input-sm" aria-controls="table" onchange="reload_table();"');
        $rows->ano = form_input('busca_ano', $post['ano'], 'class="form-control text-center input-sm ano" style="width: 60px;" aria-controls="table" onblur="reload_table();"');
        $rows->data = $data;

        echo json_encode($rows);
    }

    //==========================================================================
    public function ajaxNovo()
    {
        $this->db->select('IFNULL(MAX(numero_os), 0) + 1 AS numero_os', false);
//        $this->db->where('id_usuario', $this->session->userdata('id'));
        $facility = $this->db->get('facilities_ordens_servico')->row();

        $this->db->select('id, id_depto, id_area, id_setor');
        $this->db->where('id', $this->session->userdata('id'));
        $usuario = $this->db->get('usuarios')->row();

        $estruturas = $this->getEstruturas($usuario->id);
        if ($this->session->userdata('tipo') != 'empresa') {
            $estruturas['depto'] = $usuario->id_depto;
            $estruturas['area'] = $usuario->id_area;
            $estruturas['setor'] = $usuario->id_setor;
        }

        $data = array(
            'numero_os' => $facility->numero_os,
            'deptos' => form_dropdown('', $estruturas['deptos'], $estruturas['depto'] ?? ''),
            'areas' => form_dropdown('', $estruturas['areas'], $estruturas['area'] ?? ''),
            'setores' => form_dropdown('', $estruturas['setores'], $estruturas['setor'] ?? ''),
            'requisitantes' => form_dropdown('', $estruturas['requisitantes'], ($usuario->id ?? '')),
        );

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxEdit()
    {
        $data = $this->getData($this->input->post('numero_os'));
        $output['data'] = $data;

        $estruturas = $this->getEstruturas($data->id_requisitante ?? null);

        $output['input'] = array(
            'depto' => form_dropdown('', $estruturas['deptos'], $data->id_depto),
            'area' => form_dropdown('', $estruturas['areas'], $data->id_area),
            'setor' => form_dropdown('', $estruturas['setores'], $data->id_setor),
            'requisitante' => form_dropdown('', $estruturas['requisitantes'], $data->id_requisitante),
        );

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxAdd()
    {
        $data = $this->setData();
        $status = $this->db->insert('facilities_ordens_servico', $data);

        $this->enviarEmail($this->db->insert_id());

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajaxUpdate()
    {
        $numeroOS = $this->input->post('numero_os');

        $osAntiga = $this->db
            ->select('status')
            ->where('numero_os', $numeroOS)
            ->get('facilities_ordens_servico')
            ->row();

        if (empty($osAntiga)) {
            exit(json_encode(['erro' => 'Ordem de Serviço não encontrada ou excluída recentemente.']));
        }

        $data = $this->setData();

        $this->db->set($data);
        $this->db->where('numero_os', $numeroOS);
        $status = $this->db->update('facilities_ordens_servico');

        if ($osAntiga->status !== $data['status']) {
            $this->enviarEmail($numeroOS);
        }

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    private function enviarEmail($numeroOS, $msgPadrao = '')
    {
        $os = $this->db
            ->select('a.status, b.email AS email_requisitante')
            ->join('usuarios b', 'b.id = a.id_requisitante')
            ->where('numero_os', $numeroOS)
            ->get('facilities_ordens_servico a')
            ->row();


        switch ($os->status) {
            case 'A':
                $emailDestinatario = null;
                $titulo = 'Nova Ordem de Serviço de Facilities aberta';
                $msg = "A Ordem de Serviço de Facilities n&ordm; {$numeroOS} foi aberta, favor verificar.";
                break;
            case 'E':
                $emailDestinatario = $os->email_requisitante;
                $titulo = 'Ordem de Serviço de Facilities iniciada';
                $msg = "A Ordem de Serviço de Facilities n&ordm; {$numeroOS} foi visualizada e seu tratamento foi iniciado, favor verificar.";
                break;
            case 'G':
                $emailDestinatario = $os->email_requisitante;
                $titulo = 'Ordem de Serviço de Facilities tratada';
                $msg = "A Ordem de Serviço de Facilities n&ordm; {$numeroOS} teve o seu tratamento finalizado e aguarda aprovação do requisitante, favor verificar.";
                break;
            case 'F':
                $emailDestinatario = null;
                $titulo = 'Ordem de Serviço de Facilities fechada';
                $msg = "A Ordem de Serviço de Facilities n&ordm; {$numeroOS} foi fechada pelo requisitante, favor verificar a pesquisa de satisfação.";
                break;
            case 'P':
                $emailDestinatario = null;
                $titulo = 'Ordem de Serviço de Facilities parcialmente fechada';
                $msg = "A Ordem de Serviço de Facilities n&ordm; {$numeroOS} foi parcialmente fechada, favor verificar.";
                break;
            default:
                return;
        }

        if (empty($emailDestinatario)) {
            $vistoriadores = $this->db
                ->select('email')
                ->where('empresa', $this->session->userdata('empresa'))
                ->where('tipo', 'funcionario')
                ->where('nivel_acesso', 17)
                ->get('usuarios')
                ->result();

            if (empty($vistoriadores)) {
                return;
            }

            $emailDestinatario = array_column($vistoriadores, 'email');
        }

        $this->load->library('email');

        $this->email
            ->from('contato@rhsuite.com.br', 'RhSuite')
            ->to($emailDestinatario)
            ->subject($titulo)
            ->message(strlen($msgPadrao) > 0 ? $msgPadrao : $msg)
            ->send();
    }

    //==========================================================================
    public function notificarFechamento()
    {
        if ($this->session->userdata('nivel') !== '17') {
            exit(json_encode(['erro' => 'Você não tem permissão para notificar o requisitante.']));
        }

        $msg = 'Sua solicitação de serviço foi realizada; por favor verifique se a resolução está plenamente atendida e feche a OS';

        $this->enviarEmail($this->input->post('numero_os'), $msg);

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxDelete()
    {
        $numeroOS = $this->input->post('numero_os');
        $status = $this->db->delete('facilities_ordens_servico', ['numero_os' => $numeroOS]);

        echo json_encode(array("status" => $status !== false));
    }

    /* -------------------------------------------------------------------------
     *
     * -------------------------------------------------------------------------
     */

    protected function getData($numeroOS = '')
    {
        $this->db->where('numero_os', $numeroOS);
        $os = $this->db->get_where('facilities_ordens_servico')->row();

        if (empty($os)) {
            $keys = $this->db->list_fields('facilities_ordens_servico');
            $values = array_pad([], count($keys), null);

            return array_combine($keys, $values);
        }

        if ($os->data_abertura) {
            $os->data_abertura = date('d/m/Y', strtotime($os->data_abertura));
        }

        if ($os->data_resolucao_problema) {
            $os->data_resolucao_problema = date('d/m/Y', strtotime($os->data_resolucao_problema));
        }

        if ($os->data_tratamento) {
            $os->data_tratamento = date('d/m/Y', strtotime($os->data_tratamento));
        }

        if ($os->data_fechamento) {
            $os->data_fechamento = date('d/m/Y', strtotime($os->data_fechamento));
        }

        return $os;
    }

    // -------------------------------------------------------------------------

    protected function setData()
    {
        $data = $this->input->post();

        if (isset($data['data_abertura'])) {
            if (strlen($data['data_abertura']) > 0) {

                $dataAbertura = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_abertura'])));
                if ($data['data_abertura'] != preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $dataAbertura)) {
                    exit(json_encode(['erro' => 'A data de abertura é inválida.']));
                }

                $data['data_abertura'] = $dataAbertura;
            } else {
                exit(json_encode(['erro' => 'A data de abertura é obrigatória.']));
            }
        }


        if (isset($data['data_resolucao_problema'])) {
            if (strlen($data['data_resolucao_problema']) > 0) {

                $dataResolucaoProblema = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_resolucao_problema'])));
                if ($data['data_resolucao_problema'] != preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $dataResolucaoProblema)) {
                    exit(json_encode(['erro' => 'A data estimada de solução do problema é inválida.']));
                }

                if (strtotime($dataResolucaoProblema) < strtotime($data['data_resolucao_problema'])) {
                    exit(json_encode(['erro' => 'A data estimada de solução do problema deve ser maior ou igual à data de abertura.']));
                }

                $data['data_resolucao_problema'] = $dataResolucaoProblema;
            } else {
                $data['data_resolucao_problema'] = null;
            }
        }


        if (isset($data['data_tratamento'])) {
            if (strlen($data['data_tratamento']) > 0) {

                $dataTratamento = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_tratamento'])));
                if ($data['data_tratamento'] != preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $dataTratamento)) {
                    exit(json_encode(['erro' => 'A data de tratamento é inválida.']));
                }

                if (strtotime($dataTratamento) < strtotime($data['data_tratamento'])) {
                    exit(json_encode(['erro' => 'A data de tratamento deve ser maior ou igual à data de abertura.']));
                }

                $data['data_tratamento'] = $dataTratamento;
            } else {
                $data['data_tratamento'] = null;
            }
        }


        if (isset($data['data_fechamento'])) {
            if (strlen($data['data_fechamento']) > 0) {

                $dataFechamento = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_fechamento'])));
                if ($data['data_fechamento'] != preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $dataFechamento)) {
                    exit(json_encode(['erro' => 'A data de fechamento é inválida.']));
                }

                if (strtotime($dataFechamento) < strtotime($data['data_fechamento'])) {
                    exit(json_encode(['erro' => 'A data de fechamento deve ser maior ou igual à data de abertura.']));
                }

                $data['data_fechamento'] = $dataFechamento;
            } else {
                $data['data_fechamento'] = null;
            }
        }


        if (isset($data['id_depto'])) {
            if (strlen($data['id_depto']) == 0) {
                $data['id_depto'] = null;
            }
        }
        if (isset($data['id_area'])) {
            if (strlen($data['id_area']) == 0) {
                $data['id_area'] = null;
            }
        }
        if (isset($data['id_setor'])) {
            if (strlen($data['id_setor']) == 0) {
                $data['id_setor'] = null;
            }
        }

        if (isset($data['id_requisitante'])) {
            if (strlen($data['id_requisitante']) == 0) {
                exit(json_encode(['erro' => 'O requisitante é obrigatório.']));
            }
        }

        if (isset($data['descricao_problema'])) {
            if (strlen($data['descricao_problema']) == 0) {
                $data['descricao_problema'] = null;
            }
        }

        if (isset($data['complemento'])) {
            if (strlen($data['complemento']) == 0) {
                $data['complemento'] = null;
            }
        }

        if (isset($data['observacoes'])) {
            if (strlen($data['observacoes']) == 0) {
                $data['observacoes'] = null;
            }
        }

        if (isset($data['resolucao_satisfatoria'])) {
            if (strlen($data['resolucao_satisfatoria']) == 0) {
                $data['resolucao_satisfatoria'] = null;
            }
        }

        if (isset($data['observacoes_positivas'])) {
            if (strlen($data['observacoes_positivas']) == 0) {
                $data['observacoes_positivas'] = null;
            }
        }

        if (isset($data['observacoes_negativas'])) {
            if (strlen($data['observacoes_negativas']) == 0) {
                $data['observacoes_negativas'] = null;
            }
        }

        unset($data['numero_os']);

        return $data;
    }

    // -------------------------------------------------------------------------

    public function getEstruturas($idUsuario)
    {
        $this->db->select('id AS id_requisitante, id_depto, id_area, id_setor');
        $this->db->where('id', $idUsuario);
        $usuario = $this->db->get('usuarios')->row();

        if (empty($usuario)) {
            return array();
        }

        $data = (array)$usuario;

        $data['deptos'] = $this->getDepartamentos();
        $data['areas'] = $this->getAreas($usuario->id_depto);
        $data['setores'] = $this->getSetores($usuario->id_depto, $usuario->id_area);
        $data['requisitantes'] = $this->getRequisitantes($usuario->id_depto, $usuario->id_area, $usuario->id_setor);

        return $data;
    }

    // -------------------------------------------------------------------------

    private function getDepartamentos()
    {
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'asc');
        $rows = $this->db->get('empresa_departamentos')->result();
        $data = ['' => 'selecione...'] + array_column($rows, 'nome', 'id');
        return $data;
    }

    // -------------------------------------------------------------------------

    private function getAreas($idDepto = '')
    {
        $this->db->where('id_departamento', $idDepto);
        $this->db->order_by('nome', 'asc');
        $rows = $this->db->get('empresa_areas')->result();
        $data = ['' => 'selecione...'] + array_column($rows, 'nome', 'id');
        return $data;
    }

    // -------------------------------------------------------------------------

    private function getSetores($idDepto = '', $idArea = '')
    {
        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->where('b.id_departamento', $idDepto);
        $this->db->where('a.id_area', $idArea);
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('empresa_setores a')->result();
        $data = ['' => 'selecione...'] + array_column($rows, 'nome', 'id');
        return $data;
    }

    // -------------------------------------------------------------------------

    private function getRequisitantes($idDepto = '', $idArea = '', $idSetor = '')
    {
        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_depto');
        $this->db->join('empresa_areas c', 'c.id = a.id_area');
        $this->db->join('empresa_setores d', 'd.id = a.id_setor');
        $this->db->where('a.empresa', $this->session->userdata('empresa'));
        $this->db->where('a.tipo', 'funcionario');
//        $this->db->where_in('a.nivel_acesso', [3, 7, 8, 9, 10, 17]);
        $this->db->where('b.id', $idDepto);
        $this->db->where('c.id', $idArea);
        $this->db->where('d.id', $idSetor);
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('usuarios a')->result();

        $data = ['' => 'selecione...'] + array_column($rows, 'nome', 'id');

        return $data;
    }


    public function relatorio($isPdf = false)
    {
        $numeroOS = $this->uri->rsegment(3);


        $this->db->select('a.numero_os, b.nome AS requisitante');
        $this->db->select(["(CASE a.prioridade WHEN 0 THEN 'Sem prioridade'  WHEN 1 THEN 'Baixa'  WHEN 2 THEN 'Média'  WHEN 3 THEN 'Alta' WHEN 4 THEN 'Urgente' END) AS prioridade"], false);
        $this->db->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura"], false);
        $this->db->select(["DATE_FORMAT(a.data_fechamento, '%d/%m/%Y') AS data_fechamento"], false);
        $this->db->select(["DATE_FORMAT(a.data_resolucao_problema, '%d/%m/%Y') AS data_resolucao_problema"], false);
        $this->db->select(["CONCAT_WS('/', c.nome, d.nome, e.nome) AS estrutura"], false);
        $this->db->select('a.descricao_problema, a.observacoes');
        $this->db->select(["(CASE a.resolucao_satisfatoria WHEN 'S' THEN 'Satisfatória'  WHEN 'N' THEN 'Não satisfatória'  WHEN 'P' THEN 'Parcialmente satisfatória' ELSE 'Não definida' END) AS resolucao_satisfatoria"], false);
        $this->db->select(["(CASE a.resolucao_satisfatoria WHEN 'S' THEN 'text-success'  WHEN 'N' THEN 'text-danger'  WHEN 'P' THEN 'text-warning' ELSE 'text-muted'END) AS classe_resolucao_satisfatoria"], false);
        $this->db->select('a.observacoes_positivas, a.observacoes_negativas');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('empresa_departamentos c', 'c.id = a.id_depto', 'left');
        $this->db->join('empresa_areas d', 'd.id = a.id_area', 'left');
        $this->db->join('empresa_setores e', 'e.id = a.id_setor', 'left');
        $this->db->where('a.numero_os', $numeroOS);
        $data = $this->db->get('facilities_ordens_servico a')->row_array();


        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();


        $data['is_pdf'] = $isPdf === true;


        if ($data['is_pdf']) {
            return $this->load->view('facilities/relatorio_ordem_servico_pdf', $data, true);
        }


        $this->load->view('facilities/relatorio_ordem_servico', $data);
    }


    public function pdf()
    {
        $this->load->library('m_pdf');


        $stylesheet = 'table.ordem_servico thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.ordem_servico tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.ordem_servico tbody tr th { font-size: 11px; padding: 2px; } ';
        $stylesheet .= 'table.ordem_servico tbody td { font-size: 12px; padding: 1px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.ordem_servico tbody td strong { font-weight: bold; } ';

        $stylesheet .= 'table.dados thead th { font-size: 12px; padding: 5px; border-bottom: 2px solid #ddd; } ';
        $stylesheet .= 'table.dados thead tr.active td { background-color: #e5e5e5; }';
        $stylesheet .= 'table.dados tbody td { font-size: 12px; padding: 5px; border-top: 1px solid #ddd; word-wrap: break-word;} ';


        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio(true));


        $this->db->select('numero_os');
        $this->db->where('numero_os', $this->uri->rsegment(3));
        $row = $this->db->get('facilities_ordens_servico')->row();


        $this->m_pdf->pdf->Output('Requisição de Ordem de Serviços - ' . $row->numero_os . '.pdf', 'D');
    }


}
