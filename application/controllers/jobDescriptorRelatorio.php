<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class JobDescriptorRelatorio extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->relatorio();
    }

    public function relatorio($pdf = false)
    {
        $id = $this->uri->rsegment(3, 0);
        if ($pdf !== true) {
            $pdf = false;
        }

        $get = $this->input->get();

        $data = array('is_pdf' => $pdf);

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $this->db->select('a.*, c.nome AS cargo, d.nome AS funcao', false);
        $this->db->select(["IFNULL(CONCAT(c.familia_CBO, '-', d.ocupacao_CBO), '--') AS cbo"], false);
        $this->db->join('empresa_cargos c', 'c.id = a.id_cargo');
        $this->db->join('empresa_funcoes d', 'd.id = a.id_funcao');
        $this->db->where('a.id', $id);
        $data['jobDescriptor'] = $this->db->get('job_descriptor a')->row();

        $this->db->select('a.*, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('a.id_descritor', $id);
        $this->db->order_by('b.nome', 'asc');
        $respondentes = $this->db->get('job_descriptor_respondentes a')->result();

        $data['usuarios'] = array('Agrupados' => array('' => 'Consolidado (sem edição)', 'consolidado' => 'Consolidado (com edição)'));

        foreach ($respondentes as $respondente) {
            $data['usuarios']['Individuais'][$respondente->id] = $respondente->nome;
        }

        $data['estruturas'] = array(
            'sumario' => 'Descrição sumária',
            'formacao_experiencia' => 'Formação e experiência',
            'condicoes_gerais_exercicio' => 'Condições gerais de exercício',
            'codigo_internacional_CIUO88' => 'Código Internacional CIUO88',
            'notas' => 'Notas',
            'recursos_trabalho' => 'Recursos de trabalho',
            'atividades' => 'Atribuições e atividades',
            'responsabilidades' => 'Responsabilidades',
            'habilidades_basicas' => 'Conhecimentos e habilidades - Básicas',
            'habilidades_intermediarias' => 'Conhecimentos e habilidades - Intermediárias',
            'habilidades_avancadas' => 'Conhecimentos e habilidades - Avançadas',
            'ambiente_trabalho' => 'Especificações gerais - Ambiente de trabalho',
            'condicoes_trabalho' => 'Especificações gerais - Condições de trabalho',
            'esforcos_fisicos' => 'Especificações gerais - Esforços físicos',
            'grau_autonomia' => 'Especificações gerais - Grau de autonomia',
            'grau_complexidade' => 'Especificações gerais - Grau de complexidade',
            'grau_iniciativa' => 'Especificações gerais - Grau de iniciativa',
            'competencias_tecnicas' => 'Competências Técnicas',
            'competencias_comportamentais' => 'Competências Comportamentais',
            'tempo_experiencia' => 'Tempo de experiência no cargo/função',
            'formacao_minima' => 'Formação/escolaridade mínima',
            'formacao_plena' => 'Formação/escolaridade para exercício pleno',
            'esforcos_mentais' => 'Esforços mentais',
            'grau_pressao' => 'Grau de pressão/estresse',
            'campo_livre1' => $data['jobDescriptor']->campo_livre1,
            'campo_livre2' => $data['jobDescriptor']->campo_livre2,
            'campo_livre3' => $data['jobDescriptor']->campo_livre3,
            'campo_livre4' => $data['jobDescriptor']->campo_livre4,
            'campo_livre5' => $data['jobDescriptor']->campo_livre5
        );

        $estruturas = array_intersect_key(array_filter((array)$data['jobDescriptor'], function ($v, $k) {
            $matches = preg_match('/campo_livre/', $k);
            return ($matches and strlen($v) > 0 or !$matches and $v === '1');
        }, ARRAY_FILTER_USE_BOTH), $data['estruturas']);

        $data['estruturas'] = array_intersect_key($data['estruturas'], $estruturas);
        $data['consolidado'] = array();


        if ($this->session->userdata('tipo') == 'empresa') {
            $consolidados = $this->db->get_where('job_descriptor_consolidados', ['id_descritor' => $id])->row_array();
            $data['id_consolidado'] = $consolidados['id_descritor'] ?? null;

            foreach (array_keys($estruturas) as $estrutura) {
                $data['consolidado'][$estrutura] = $consolidados[$estrutura] ?? null;
                foreach ($respondentes as $respondente) {
                    $data['respondentes'][$estrutura][$respondente->id] = $respondente->$estrutura;
                }
            }
        } else {
            $this->db->where('id_usuario', $this->session->userdata('id'));
            $this->db->where('id_descritor', $id);
            $consolidados = $this->db->get('job_descriptor_respondentes')->row_array();
            $data['id_consolidado'] = $consolidados['id_descritor'] ?? null;

            foreach (array_keys($estruturas) as $estrutura) {
                $data['consolidado'][$estrutura] = $consolidados[$estrutura] ?? null;
                $data['respondentes'][$estrutura][$respondente->id] = $respondente->$estrutura;
            }
        }


        if ($pdf) {
            return $this->load->view('jobDescriptor_pdf', $data, true);
        } else {
            $this->load->view('jobDescriptor_relatorio', $data);
        }
    }

    public function criarConsolidado()
    {
        $idDescritor = $this->input->post('id_descritor');

        $this->db->select('a.id');
        $this->db->where('job_descriptor b', 'b.id_versao_anterior = a.id');
        $this->db->where('b.id_versao_anterior', $idDescritor);
        $versaoAnterior = $this->db->get('job_descriptor a')->row();

        if ($versaoAnterior) {
            $this->db->where('id_descritor', $versaoAnterior->id);
            $data = $this->db->get_where('job_descriptor_consolidados')->row_array();

            unset($data['id'], $data['id_descritor'], $data['id_usuario']);
        } else {
            $rows = $this->db->get_where('job_descriptor_respondentes', array('id_descritor' => $idDescritor))->result();

            $consolidados = array();
            foreach ($rows as $k => $row) {
                foreach ($row as $estrutura => $descritivo) {
                    $consolidados[$estrutura][$k] = $descritivo;
                }
            }

            unset($consolidados['id'], $consolidados['id_descritor'], $consolidados['id_usuario']);

            $data = array();
            foreach ($consolidados as $k => $consolidado) {
                $data[$k] = implode(chr(10), array_filter($consolidado, function ($value) {
                    return strlen($value) > 0;
                }, ARRAY_FILTER_USE_BOTH));
            }
        }


        echo json_encode($data);
    }

    public function editarIndividual()
    {
        $idDescritor = $this->input->post('id_descritor');
        $data = $this->db->where('id_descritor', $idDescritor);
        $data = $this->db->where('id_usuario', $this->session->userdata('id'));
        $data = $this->db->get('job_descriptor_respondentes')->row();

        echo json_encode($data);
    }

    public function editarConsolidado()
    {
        $id = $this->input->post('id');

        $data = $this->db->get_where('job_descriptor_consolidados', array('id' => $id))->row();
        unset($data['id'], $data['id_descritor']);

        echo json_encode($data);
    }

    public function salvarIndividual()
    {
        $data = $this->input->post();
        $where['id_usuario'] = $this->session->userdata('id');
        $where['id_descritor'] = $data['id_descritor'];
        unset($data['id_descritor']);

        foreach ($data as $field => $value) {
            if (strlen($value) == 0) {
                $data[$field] = null;
            }
        }

        $status = $this->db->update('job_descriptor_respondentes', $data, $where);

        echo json_encode(array("status" => $status !== false));
    }

    public function salvarConsolidado()
    {
        $data = $this->input->post();
        $id = $this->input->post('id');
        unset($data['id']);

        if ($id) {
            $status = $this->db->update('job_descriptor_consolidados', $data, array('id' => $id));
        } else {
            $status = $this->db->insert('job_descriptor_consolidados', $data);
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.descritivos tr { border-width: 5px; border-color: #ddd; } ';

        $stylesheet .= 'table.respondentes tr th, table.respondentes tr td { font-size: 12px; padding: 5px; } ';
        $stylesheet .= 'table.respondentes thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.respondentes thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.respondentes tbody tr th { background-color: #dff0d8; } ';

        $this->m_pdf->pdf->setTopMargin(54);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio(true));

        $this->db->select('a.versao, b.nome AS cargo, c.nome AS funcao');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('empresa_funcoes c', 'c.id = a.id_funcao AND c.id_cargo = b.id');
        $this->db->select('a.versao, b.nome AS cargo, c.nome AS funcao');
        $this->db->where('a.id', $this->uri->rsegment(3));
        $row = $this->db->get('job_descriptor a')->row();

        $this->m_pdf->pdf->Output('DCF - ' . $row->cargo . ' - ' . $row->funcao . '.pdf', 'D');
    }

    public function pdfIndividual()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.descritivos tr { border-width: 5px; border-color: #ddd; } ';

        $stylesheet .= 'table.respondentes tr th, table.respondentes tr td { font-size: 12px; padding: 5px; } ';
        $stylesheet .= 'table.respondentes thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.respondentes thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.respondentes tbody tr th { background-color: #dff0d8; } ';

        $this->m_pdf->pdf->setTopMargin(54);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio(true));

        $this->db->select('versao');
        $this->db->where('id', $this->uri->rsegment(3));
        $row = $this->db->get('job_descriptor')->row();

        $this->m_pdf->pdf->Output('Job Descriptor - Relatório individual ' . $row->versao . '.pdf', 'D');
    }

}
