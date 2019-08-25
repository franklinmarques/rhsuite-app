<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequisicaoPessoal_candidatos extends MY_Controller
{

    public function index()
    {
        $this->load->view('requisicaoPessoal_candidatos');
    }

    // -------------------------------------------------------------------------

    public function ajaxList()
    {
        $mesAnoInicio = explode('/', $this->input->post('mes_ano_inicio'));
        $mesAnoTermino = explode('/', $this->input->post('mes_ano_termino'));

        $query = $this->listar($mesAnoInicio, $mesAnoTermino);

        $config = [
            'search' => ['selecionador', 'cargo', 'depto', 'area', 'setor', 'requisitante', 'candidato', 'fonte_contratacao']
        ];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $status = [
            'A' => 'Agendado',
            'P' => 'Em processo',
            'F' => 'Fora do perfil',
            'N' => 'Não atende ou recado',
            'S' => 'Sem interesse',
            'I' => 'Telefone errado'
        ];
        $resultadoSelecao = [
            'A' => 'Selecionado',
            'D' => 'Desistiu',
            'N' => 'Não compareceu',
            'X' => 'Aprovado',
            'R' => 'Reprovado',
            'S' => 'Stand by'
        ];
        $resultadoRequisitante = [
            'A' => 'Selecionado',
            'C' => 'Aprovado',
            'D' => 'Desistiu',
            'N' => 'Não compareceu',
            'X' => 'Aprovado',
            'R' => 'Reprovado',
            'S' => 'Stand by'
        ];

        $data = [];

        foreach ($output->data as $row) {
            $data[] = [
                $row->id,
                $row->data_abertura_de,
                $row->selecionador,
                $row->cargo,
                $row->numero_vagas,
                $row->depto,
                $row->area,
                $row->setor,
                $row->requisitante,
                $row->previsao_inicio_de,
//                $row->candidato,
                $row->deficiencia,
                $row->fonte_contratacao,
                $status[$row->status] ?? null,
                $row->data_selecao_de,
                $resultadoSelecao[$row->resultado_selecao] ?? null,
                $row->data_requisitante_de,
                $resultadoRequisitante[$row->resultado_requisitante] ?? null,
                $row->antecedentes_criminais,
                $row->restricoes_financeiras,
                $row->data_exame_admissional_de,
                $row->resultado_exame_admissional,
                $row->data_admissao_de
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }


    private function listar($mesAnoInicio = [], $mesAnoTermino = [])
    {
        $mesInicio = $mesAnoInicio[0] ?? null;
        $anoInicio = $mesAnoInicio[1] ?? null;
        $mesTermino = $mesAnoTermino[0] ?? null;
        $anoTermino = $mesAnoTermino[1] ?? null;

        $this->db
            ->select('a.id, a.data_abertura, a.selecionador')
            ->select(['IFNULL(b.nome, a.cargo_funcao_alternativo) AS cargo, a.numero_vagas'], false)
            ->select('c.nome AS depto, d.nome AS area, d2.nome AS setor')
            ->select(["IF(a.tipo_vaga = 'I', e.nome, f.nome) AS requisitante"], false)
            ->select('a.previsao_inicio, IFNULL(h.nome, j.nome) AS candidato', false)
            ->select(['IFNULL(i.tipo, j.deficiencia) AS deficiencia'], false)
            ->select(["IFNULL(h.fonte_contratacao, IFNULL(j.fonte_contratacao, 'Nenhuma')) AS fonte_contratacao"], false)
            ->select('g.status, g.data_selecao, g.resultado_selecao, g.data_requisitante, g.resultado_requisitante')
            ->select("(CASE g.antecedentes_criminais WHEN 1 THEN 'Antecedentes' WHEN 0 THEN 'Nada consta' END) AS antecedentes_criminais", false)
            ->select("(CASE g.restricoes_financeiras WHEN 1 THEN 'Com restrições' WHEN 0 THEN 'Sem restrições' END) AS restricoes_financeiras", false)
            ->select('g.data_exame_admissional')
            ->select("(CASE g.resultado_exame_admissional WHEN 1 THEN 'Apto' WHEN 0 THEN 'Não apto' END) AS resultado_exame_admissional", false)
            ->select('g.data_admissao')
            ->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura_de"], false)
            ->select(["DATE_FORMAT(a.previsao_inicio, '%d/%m/%Y') AS previsao_inicio_de"], false)
            ->select(["DATE_FORMAT(g.data_selecao, '%d/%m/%Y') AS data_selecao_de"], false)
            ->select(["DATE_FORMAT(g.data_requisitante, '%d/%m/%Y') AS data_requisitante_de"], false)
            ->select(["DATE_FORMAT(g.data_exame_admissional, '%d/%m/%Y') AS data_exame_admissional_de"], false)
            ->select(["DATE_FORMAT(g.data_admissao, '%d/%m/%Y') AS data_admissao_de"], false)
            ->join('empresa_cargos b', 'b.id = a.id_cargo', 'left')
            ->join('empresa_departamentos c', 'c.id = a.id_depto', 'left')
            ->join('empresa_areas d', 'd.id = a.id_area', 'left')
            ->join('empresa_setores d2', 'd2.id = a.id_setor', 'left')
            ->join('usuarios e', 'e.id = a.requisitante_interno', 'left')
            ->join('usuarios f', 'f.id = a.requisitante_externo', 'left')
            ->join('requisicoes_pessoal_candidatos g', 'g.id_requisicao = a.id')
            ->join('recrutamento_usuarios h', 'h.id = g.id_usuario', 'left')
            ->join('deficiencias i', 'i.id = h.deficiencia', 'left')
            ->join('recrutamento_google j', 'j.id = g.id_usuario_banco', 'left');
        if ($mesInicio) {
            $this->db->where('MONTH(a.data_abertura)', $mesInicio);
        }
        if ($anoInicio) {
            $this->db->where('YEAR(a.data_abertura)', $anoInicio);
        }
        if ($mesTermino) {
            $this->db->where('MONTH(a.data_fechamento)', $mesTermino);
        }
        if ($anoTermino) {
            $this->db->where('YEAR(a.data_fechamento)', $anoTermino);
        }

        return $this->db->group_by(['a.id', 'g.id'])->get('requisicoes_pessoal a');
    }


    public function exportarXlxs()
    {
        $mesAnoInicio = explode('/', $this->input->get('mes_ano_inicio'));
        $mesAnoTermino = explode('/', $this->input->get('mes_ano_termino'));

        $data = $this->listar($mesAnoInicio, $mesAnoTermino)->result();

        $html = '<table border="1" style="background-color:transparent;">
                    <thead>
                        <tr style="background-color: #eee; font-weight: bold; text-align: center; vertical-align: middle;">
                            <td rowspan="2">Req.</td>
                            <td rowspan="2">Abertura</td>
                            <td rowspan="2">Selecionador(a)</td>
                            <td rowspan="2">Cargo da vaga</td>
                            <td rowspan="2">Total de vagas</td>
                            <td rowspan="2">Departamento</td>
                            <td rowspan="2">&Aacute;rea</td>
                            <td rowspan="2">Setor</td>
                            <td rowspan="2">Empresa/Requisitante</td>
                            <td rowspan="2">Previs&atilde;o in&iacute;cio</td>
                            <td colspan="3">Candidato</td>
                            <td colspan="2">Sele&ccedil;&atilde;o</td>
                            <td colspan="2">Requisitante</td>
                            <td colspan="1">Antecedentes criminais</td>
                            <td colspan="1">Restri&ccedil;&otilde;es financeiras</td>
                            <td colspan="2">Exame m&eacute;dico admissional</td>
                            <td rowspan="2">Data admiss&atilde;o</td>
                        </tr>
                        <tr style="background-color: #eee; font-weight: bold; text-align: center; ">
                            <td>Defici&ecirc;ncia</td>
                            <td>Fonte</td>
                            <td>Status</td>
                            <td>Data</td>
                            <td>Resultado</td>
                            <td>Data</td>
                            <td>Resultado</td>
                            <td>Resultado</td>
                            <td>Resultado</td>
                            <td>Data</td>
                            <td>Resultado</td>
                        </tr>
                    </thead>
                    <tbody>';

        $status = [
            'A' => 'Agendado',
            'P' => 'Em processo',
            'F' => 'Fora do perfil',
            'N' => 'N&atilde;o atende ou recado',
            'S' => 'Sem interesse',
            'I' => 'Telefone errado'
        ];
        $resultadoSelecao = [
            'A' => 'Selecionado',
            'D' => 'Desistiu',
            'N' => 'N&atilde;o compareceu',
            'X' => 'Aprovado',
            'R' => 'Reprovado',
            'S' => 'Stand by'
        ];
        $resultadoRequisitante = [
            'A' => 'Selecionado',
            'C' => 'Aprovado',
            'D' => 'Desistiu',
            'N' => 'N&atilde;o compareceu',
            'X' => 'Aprovado',
            'R' => 'Reprovado',
            'S' => 'Stand by'
        ];

        foreach ($data as $row) {
            $html .= '<tr style="vertical-align: top;"">';
            $html .= '<td style="text-align: right;">' . $row->id . '</td>';
            $html .= '<td style="text-align: center;">' . $row->data_abertura_de . '</td>';
            $html .= '<td>' . htmlentities($row->selecionador) . '</td>';
            $html .= '<td>' . htmlentities($row->cargo) . '</td>';
            $html .= '<td style="text-align: right;">' . $row->numero_vagas . '</td>';
            $html .= '<td>' . htmlentities($row->depto) . '</td>';
            $html .= '<td>' . htmlentities($row->area) . '</td>';
            $html .= '<td>' . htmlentities($row->setor) . '</td>';
            $html .= '<td>' . htmlentities($row->requisitante) . '</td>';
            $html .= '<td style="text-align: center;">' . $row->previsao_inicio_de . '</td>';
            $html .= '<td>' . htmlentities($row->deficiencia) . '</td>';
            $html .= '<td>' . htmlentities($row->fonte_contratacao) . '</td>';
            $html .= '<td>' . ($status[$row->status] ?? '') . '</td>';
            $html .= '<td style="text-align: center;">' . $row->data_selecao_de . '</td>';
            $html .= '<td>' . ($resultadoSelecao[$row->resultado_selecao] ?? '') . '</td>';
            $html .= '<td style="text-align: center;">' . $row->data_requisitante_de . '</td>';
            $html .= '<td>' . ($resultadoRequisitante[$row->resultado_requisitante] ?? '') . '</td>';
            $html .= '<td>' . htmlentities($row->antecedentes_criminais) . '</td>';
            $html .= '<td>' . htmlentities($row->restricoes_financeiras) . '</td>';
            $html .= '<td style="text-align: center;">' . $row->data_exame_admissional_de . '</td>';
            $html .= '<td>' . htmlentities($row->resultado_exame_admissional) . '</td>';
            $html .= '<td style="text-align: center;">' . $row->data_admissao_de . '</td>';

            $html .= '</tr>';
        }

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D,d M YH:i:s') . ' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="Gestão Processs Seletivos - Relatórios de Gestão.xls"');
        header('Content-Description: PHP Generated Data');

        $html .= '</body></table>';

        echo $html;
    }

}
