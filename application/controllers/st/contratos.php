<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contratos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('st_contratos_model', 'contratos');
        $this->load->model('st_unidades_model', 'unidades');
        $this->load->model('st_servicos_model', 'servicos');
        $this->load->model('st_reajustes_model', 'reajustes');
    }

    //==========================================================================
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
        $data['empresa'] = $empresa;
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

        $this->load->view('st/contratos', $data);
    }

    //==========================================================================
    public function atualizarFiltro()
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

    //==========================================================================
    public function atualizarEstrutura()
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

    //==========================================================================
    public function atualizarServicos()
    {
        $data = $this->getServicos($this->input->post('id_contrato'), $this->input->post('data_reajuste'));

        echo json_encode(['servicos' => $data]);
    }

    //==========================================================================
    public function listar()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db
            ->select("a.nome, CONCAT_WS('/', a.depto, a.area) AS estrutura, a.contrato, a.id", false)
            ->join('usuarios b', 'b.id = a.id_empresa')
            ->join('st_unidades c', 'c.id_contrato = a.id', 'left')
            ->where('a.id_empresa', $this->session->userdata('empresa'));
        if (!empty($busca['depto'])) {
            $this->db->where('a.depto', $busca['depto']);
        }
        if (!empty($busca['area'])) {
            $this->db->where('a.area', $busca['area']);
        }
        if (!empty($busca['setor'])) {
            $this->db->where('c.setor', $busca['setor']);
        }
        if (!empty($busca['contrato'])) {
            $this->db->where('a.contrato', $busca['contrato']);
        }
        $query = $this->db
            ->group_by('a.id')
            ->get('st_contratos a');

        $this->load->library('dataTables', ['search' => ['nome', 'estrutura', 'contrato']]);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = [
                $row->nome,
                $row->estrutura,
                $row->contrato,
                '<button type="button" class="btn btn-sm btn-info" onclick="edit_contrato(' . $row->id . ')" title="Editar contrato"><i class="glyphicon glyphicon-pencil"></i> </button>
                 <button type="button" class="btn btn-sm btn-info" onclick="edit_unidades(' . $row->id . ')" title="Gerenciar unidades"><i class="glyphicon glyphicon-plus"></i> Unidades</button>
                 <button type="button" class="btn btn-sm btn-info" onclick="edit_servicos(' . $row->id . ')" title="Gerenciar serviços"><i class="glyphicon glyphicon-plus"></i> Serviços</button>
                 <button type="button" class="btn btn-sm btn-info" onclick="edit_reajuste(' . $row->id . ')" title="Gerenciar reajuste"><i class="glyphicon glyphicon-plus"></i> Reajustes</button>
                 <button type="button" class="btn btn-sm btn-danger" onclick="delete_contrato(' . $row->id . ')" title="Excluir contrato"><i class="glyphicon glyphicon-trash"></i> </button>'
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $data = $this->contratos->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->contratos->errors()]));
        };

        if ($data->data_assinatura) {
            $data->data_assinatura = date('d/m/Y', strtotime($data->data_assinatura));
        }

        echo json_encode($data);
    }

    //==========================================================================
    private function getContrato($id)
    {
        $data = $this->contratos
            ->select('id, nome, depto, area, contrato')
            ->find($id);

        if (empty($data)) {
            exit(json_encode(['erro' => $this->contratos->errors()]));
        };

        return $data;
    }

    //==========================================================================
    public function gerenciarUnidades()
    {
        $contrato = $this->getContrato($this->input->post('id'));

        $unidades = $this->db
            ->select('DISTINCT(a.setor) AS disponivel, c.setor AS selecionado', false)
            ->join('st_contratos b', 'b.depto = a.depto AND b.area = a.area')
            ->join('st_unidades c', 'c.id_contrato = b.id', 'left')
            ->where('b.id', $contrato->id)
            ->order_by('a.setor', 'asc')
            ->get('usuarios a')
            ->result();

        $unidadesDisponiveis = array_column($unidades, 'disponivel', 'disponivel');
        $unidadesSelecionadas = array_column($unidades, 'selecionado', 'selecionado');

        $data = [
            'contrato' => $contrato,
            'id_contrato' => $contrato->id,
            'unidades' => form_multiselect('', $unidadesDisponiveis, $unidadesSelecionadas)
        ];

        echo json_encode($data);
    }

    //==========================================================================
    public function gerenciarServicos()
    {
        $contrato = $this->getContrato($this->input->post('id'));

        $reajustes = $this->servicos
            ->select("data_reajuste, DATE_FORMAT(data_reajuste, '%m/%Y') AS mes_ano", false)
            ->where('id_contrato', $contrato->id)
            ->group_by('data_reajuste')
            ->order_by('data_reajuste', 'asc')
            ->findAll();

        if (($msg = $this->servicos->errors()) !== null) {
            exit(json_encode(['erro' => $msg]));
        }

        $dataReajustes = ['' => 'selecione...'] + array_column($reajustes, 'mes_ano', 'data_reajuste');

        $dataReajusteSelecionada = max(array_keys($dataReajustes));

        $data = [
            'contrato' => $contrato,
            'reajustes' => form_dropdown('', $dataReajustes, $dataReajusteSelecionada),
            'servicos' => $this->getServicos($contrato->id, $dataReajusteSelecionada)
        ];

        echo json_encode($data);
    }

    //==========================================================================
    private function getServicos($idContrato, $dataReajuste = '')
    {
        $data = $this->servicos
            ->select(["id, tipo, descricao, FORMAT(valor, 2, 'de_DE') AS valor"], false)
            ->where('id_contrato', $idContrato)
            ->where('data_reajuste', $dataReajuste)
            ->findAll(8);

        if (($msg = $this->servicos->errors()) !== null) {
            exit(json_encode(['erro' => $msg]));
        };

        return $data;
    }

    //==========================================================================
    public function gerenciarReajustes()
    {
        $contrato = $this->getContrato($this->input->post('id'));

        $reajustes = $this->reajustes
            ->select('id, valor_indice')
            ->select("DATE_FORMAT(data_reajuste,'%d/%m/%Y') AS data_reajuste", false)
            ->where('id_cliente', $contrato->id)
            ->findAll(5);

        if (($msg = $this->reajustes->errors()) !== null) {
            exit(json_encode(['erro' => $msg]));
        }

        foreach ($reajustes as &$reajuste) {
            $reajuste->valor_indice = str_replace('.', ',', $reajuste->valor_indice);
        }

        echo json_encode(['dados_contrato' => $contrato, 'dados_reajustes' => $reajustes]);
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('stContratos', $this->input->post());

        $this->contratos->setValidationLabel('nome', 'Cliente');
        $this->contratos->setValidationLabel('depto', 'Departamento');
        $this->contratos->setValidationLabel('area', 'Área');
        $this->contratos->setValidationLabel('contrato', 'Contrato');
        $this->contratos->setValidationLabel('id_usuario', 'Gestor(a)');
        $this->contratos->setValidationLabel('data_assinatura', 'Data Assinatura');

        $this->contratos->save($data) or exit(json_encode(['erro' => $this->contratos->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function salvarUnidades()
    {
        $id_contrato = $this->input->post('id_contrato');
        $setores = $this->input->post('setor');

        $this->db->trans_start();

        $this->db->where('id_contrato', $id_contrato);
        if ($setores) {
            $this->db->where_not_in('setor', $setores);
        }
        $this->db->delete('st_unidades');

        if ($setores) {
            $this->db->select('setor');
            $this->db->where('id_contrato', $id_contrato);
            $rows = $this->db->get('st_unidades')->result();
            $setoresExistentes = array();
            foreach ($rows as $row) {
                $setoresExistentes[] = $row->setor;
            }

            $novosSetores = array_diff($setores, $setoresExistentes);

            $data['id_contrato'] = $id_contrato;
            foreach ($novosSetores as $setor) {
                $data['setor'] = $setor;
                $this->db->insert('st_unidades', $data);
            }
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function salvarServicos()
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
                $status = $this->db->update('st_servicos', $row, array('id' => $row['id']));
            } elseif ($row['data_reajuste'] and ($row['descricao'] or $row['valor'])) {
                unset($row['id']);
                $status = $this->db->insert('st_servicos', $row);
            } elseif ($row['id'] and (empty($row['descricao']) or empty($row['valor']))) {
                $status = $this->db->delete('st_servicos', array('id' => $row['id']));
            }
        }

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function salvarReajustes()
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
                $status = $this->db->update('st_reajustes', $row, array('id' => $row['id']));
            } elseif (empty($row['id']) and ($row['data_reajuste'] or $row['valor_indice'])) {
                $status = $this->db->insert('st_reajustes', $row);
            } elseif ($row['id'] and (empty($row['data_reajuste']) or empty($row['valor_indice']))) {
                $status = $this->db->delete('st_reajustes', array('id' => $row['id']));
            }
        }

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function excluir()
    {
        $this->contratos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->contratos->errors()]));

        echo json_encode(['status' => true]);
    }

}
