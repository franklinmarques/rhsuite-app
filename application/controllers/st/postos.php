<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Postos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('st_postos_model', 'postos');
    }

    //==========================================================================
    public function index()
    {
        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao', 'contrato');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '') 
                    ORDER BY {$field} ASC";
            $rows = $this->db->query($sql)->result_array();
            $data[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
        }
        $data['cargo'][''] = 'selecione...';
        $data['funcao'][''] = 'selecione...';

        $this->db->select('id, nome');
        $this->db->where('empresa', $empresa);
        $this->db->where('status', '1');
        $this->db->order_by('nome', 'asc');
        $usuarios = $this->db->get('usuarios')->result();
        $data['usuarios'] = array('' => 'selecione...');
        foreach ($usuarios as $usuario) {
            $data['usuarios'][$usuario->id] = $usuario->nome;
        }

        $this->load->view('st/postos', $data);
    }

    //==========================================================================
    public function atualizarFiltro()
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $cargo = $this->input->post('cargo');
        $funcao = $this->input->post('funcao');

        $filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
            if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
                unset($filtro['area'][''], $filtro['setor']['']);
            }
            unset($filtro['depto']['']);
        }

        $data['area'] = form_dropdown('area', $filtro['area'], $area, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'onchange="atualizarFiltro()" class="form-control input-sm"');

        $this->db->select('id, nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        if ($depto) {
            $this->db->where('depto', $depto);
        }
        if ($area) {
            $this->db->where('area', $area);
        }
        if ($setor) {
            $this->db->where('setor', $setor);
        }
        if ($cargo) {
            $this->db->where('cargo', $cargo);
        }
        if ($funcao) {
            $this->db->where('funcao', $funcao);
        }
        $this->db->order_by('nome', 'asc');
        $usuarios = $this->db->get('usuarios')->result();
        $options = array('' => 'selecione...');
        foreach ($usuarios as $usuario) {
            $options[$usuario->id] = $usuario->nome;
        }
        $data['id_usuario'] = form_dropdown('id_usuario', $options, '', 'class="form-control"');

        echo json_encode($data);
    }

    //==========================================================================
    public function listar()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db
            ->select('b.nome, a.data, a.valor_posto')
            ->select('a.total_dias_mensais, a.total_horas_diarias, a.valor_dia, a.valor_hora, a.id')
            ->select(["DATE_FORMAT(a.data, '%m') AS mes, DATE_FORMAT(a.data, '/%Y') AS ano"], false)
            ->join('usuarios b', 'b.id = a.id_usuario')
            ->where('b.empresa', $this->session->userdata('empresa'));
        if (!empty($busca['depto'])) {
            $this->db->where('a.depto', $busca['depto']);
        }
        if (!empty($busca['area'])) {
            $this->db->where('a.area', $busca['area']);
        }
        if (!empty($busca['setor'])) {
            $this->db->where('b.setor', $busca['setor']);
        }
        if (!empty($busca['cargo'])) {
            $this->db->where('b.cargo', $busca['cargo']);
        }
        if (!empty($busca['funcao'])) {
            $this->db->where('b.funcao', $busca['funcao']);
        }
        if (!empty($busca['contrato'])) {
            $this->db->where('a.contrato', $busca['contrato']);
        }
        if (!empty($busca['busca_mes'])) {
            $this->db->where('MONTH(a.data)', $busca['busca_mes']);
        }
        if (!empty($busca['busca_ano'])) {
            $this->db->where('YEAR(a.data)', $busca['busca_ano']);
        }
        $query = $this->db->get('st_postos a');

        $this->load->library('dataTables', ['search' => ['nome']]);

        $output = $this->datatables->generate($query);

        $data = [];

        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';

        foreach ($output->data as $row) {
            $data[] = [
                $row->nome,
                $this->calendar->get_month_name($row->mes) . $row->ano,
                number_format($row->valor_posto, 2, ',', '.'),
                $row->total_dias_mensais,
                $row->total_horas_diarias,
                number_format($row->valor_dia, 2, ',', '.'),
                number_format($row->valor_hora, 2, ',', '.'),
                '<button type="button" class="btn btn-sm btn-info" onclick="edit_posto(' . $row->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                 <button type="button" class="btn btn-sm btn-danger" onclick="delete_posto(' . $row->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>'
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $data = $this->postos->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->postos->errors()]));
        };

        $data->mes = date('m', strtotime($data->data));
        $data->ano = date('Y', strtotime($data->data));
        $data->valor_posto = number_format($data->valor_posto, 2, ',', '.');
        $data->valor_dia = number_format($data->valor_dia, 2, ',', '.');
        $data->valor_hora = number_format($data->valor_hora, 2, ',', '.');
        if ($data->horario_entrada) {
            $data->horario_entrada = date('H:i', strtotime($data->horario_entrada));
        }
        if ($data->horario_saida) {
            $data->horario_saida = date('H:i', strtotime($data->horario_saida));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function clonarAnterior()
    {
        $data = $this->postos
            ->select('matricula, login, horario_entrada, horario_saida')
            ->select('total_dias_mensais, total_horas_diarias, valor_posto, valor_dia, valor_hora')
            ->where('id_usuario', $this->input->post('id_usuario'))
            ->order_by('data', 'desc')
            ->limit(1)
            ->find();

        if (empty($data)) {
            exit(json_encode(['erro' => $this->postos->errors()]));
        };

        $data->valor_posto = number_format($data->valor_posto, 2, ',', '.');
        $data->valor_dia = number_format($data->valor_dia, 2, ',', '.');
        $data->valor_hora = number_format($data->valor_hora, 2, ',', '.');
        if ($data->horario_entrada) {
            $data->horario_entrada = date('H:i', strtotime($data->horario_entrada));
        }
        if ($data->horario_saida) {
            $data->horario_saida = date('H:i', strtotime($data->horario_saida));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('stPostos', $this->input->post());

        $this->postos->setValidationRule('mes', 'required|is_natural_no_zero|less_than_equal_to[12]');
        $this->postos->setValidationRule('ano', 'required|is_natural_no_zero|max_length[4]');
        $this->postos->setValidationRule('data', '');

        $this->postos->setValidationLabel('id_usuario', 'Colaborador(a)');
        $this->postos->setValidationLabel('mes', 'Mês');
        $this->postos->setValidationLabel('ano', 'Ano');
        $this->postos->setValidationLabel('matricula', 'Matrícula');
        $this->postos->setValidationLabel('login', 'Login');
        $this->postos->setValidationLabel('valor_posto', 'Valor Posto');
        $this->postos->setValidationLabel('total_dias_mensais', 'Qtde. Dias');
        $this->postos->setValidationLabel('valor_dia', 'Valor Diário');
        $this->postos->setValidationLabel('total_horas_diarias', 'Qtde. Horas');
        $this->postos->setValidationLabel('valor_hora', 'Valor por Hora');
        $this->postos->setValidationLabel('horario_entrada', 'Horário Entrada');
        $this->postos->setValidationLabel('horario_saida', 'Horário Saída');

        $this->postos->validate($data) or exit(json_encode(['erro' => $this->postos->errors()]));

        $this->db->select('depto, area, setor, cargo, funcao, contrato');
        $this->db->where('id', $data->id_usuario);
        $usuario = $this->db->get('usuarios')->row();

        if (empty($usuario)) {
            exit(json_encode(['erro' => 'Colaborador não encontrado']));
        }

        $data->depto = $usuario->depto;
        $data->area = $usuario->area;
        $data->setor = $usuario->setor;
        $data->cargo = $usuario->cargo;
        $data->funcao = $usuario->funcao;
        $data->data = date('Y-m-d', mktime(0, 0, 0, $data->mes, 1, $data->ano));
        unset($data->mes, $data->ano);

        $sql = "SELECT s.* 
                FROM (SELECT a.* 
                      FROM st_postos a 
                      WHERE a.id != '{$data->id}' 
                            AND a.id_usuario = '{$data->id_usuario}' 
                      ORDER BY a.data DESC 
                      LIMIT 1) s 
                WHERE s.data = '{$data->data}' OR 
                      (s.depto = '{$data->depto}' AND 
                       s.area = '{$data->area}' AND 
                       s.setor = '{$data->setor}' AND 
                       s.cargo = '{$data->cargo}' AND 
                       s.funcao = '{$data->funcao}' AND 
                       s.contrato = '{$data->contrato}' AND 
                       s.matricula = '{$data->matricula}' AND 
                       s.login = '{$data->login}' AND 
                       s.horario_entrada = '{$data->horario_entrada}' AND 
                       s.horario_saida = '{$data->horario_saida}' AND 
                       s.total_dias_mensais = '{$data->total_dias_mensais}' AND 
                       s.total_horas_diarias = '{$data->total_horas_diarias}' AND 
                       s.valor_posto = '{$data->valor_posto}' AND 
                       s.valor_dia = '{$data->valor_dia}' AND 
                       s.valor_hora = '{$data->valor_hora}')";
        $count = $this->db->query($sql)->num_rows();

        if ($count == 1) {
            exit(json_encode(['erro' => 'Os dados salvos são idênticos aos do posto anterior.']));
        }

        $this->postos->skipValidation();

        $this->postos->save($data) or exit(json_encode(['erro' => $this->postos->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluir()
    {
        $this->postos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->postos->errors()]));

        echo json_encode(['status' => true]);
    }

}
