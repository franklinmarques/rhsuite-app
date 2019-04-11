<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Certificado extends MY_Controller
{

    public function emissaoCertificado($id_curso, $id_funcionario)
    {
        //Setar região
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $perguntas = null;
        $total_acertos = 0;

        $cursos = $this->db->query("SELECT cs.*, u.nome AS nome_usuario, u.empresa, u.foto, e.nome AS empresa_emissora, e.foto AS foto_empresa_emissora,
                                    MIN(up.datacadastro) AS datainicial, MAX(up.dataconclusao) AS datafinal,
                                    (SELECT COUNT(*) FROM paginas p WHERE p.curso = '$id_curso' AND p.modulo = 'atividades') AS total_atividades,
                                    cs.id, u.assinatura_digital, e.assinatura_digital AS assinatura_emissora
                                   FROM usuarioscursos c
                                   INNER JOIN usuarios u ON u.id = c.usuario
                                   LEFT JOIN usuarios e ON e.id = u.empresa
                                   INNER JOIN cursos cs ON cs.id = c.curso
                                   LEFT JOIN usuariospaginas up ON up.curso = c.curso
                                   WHERE c.curso = ? AND c.usuario = ? LIMIT 1", array($id_curso, $id_funcionario));

        $questoes = $cursos->result();
        # Separa a quantidade de alternativas e acertos
        foreach ($questoes as $row) {
            $atividades[$row->id] = $this->db->query("SELECT status, pagina
                                                      FROM usuariosatividades
                                                      WHERE curso = ? AND usuario = ?
                                                      ", array($row->id, $this->uri->rsegment(4)))->result();
        }

        # Validação
        if ($id_curso < 1 || $id_funcionario < 1 || $cursos->num_rows() < 1) {
            header("location:" . site_url('home'));
        } else {
            foreach ($questoes as $row) {

                /* Calcular avaliação final */
                $total_atividades = $row->total_atividades ? 100 / $row->total_atividades : 0;

                if ($atividades[$row->id]) {

                    # Verifica a quantidade de alternativas e acertos
                    foreach ($atividades[$row->id] as $questao) {
                        if (!isset($perguntas[$questao->pagina])) {
                            $perguntas[$questao->pagina] = array('alternativas' => 0, 'acertos' => 0);
                        }

                        $perguntas[$questao->pagina]['alternativas'] += 1;
                        if ($questao->status == 1) {
                            $perguntas[$questao->pagina]['acertos'] += 1;
                        }
                    }

                    # Verifica o valor total
                    foreach ($perguntas as $questao) {
                        $total_acertos += ($total_atividades / $questao['alternativas']) * $questao['acertos'];
                    }
                }

                # Empresa
                $empresa = $row->empresa_emissora;
                $foto = $row->foto_empresa_emissora;
                $assinatura = $row->assinatura_emissora;

                if ($row->empresa == 0) {
                    $empresa = $row->nome_usuario;
                    $foto = $row->foto;
                    $assinatura = $row->assinatura_digital;
                }

                # Dados do banco
                $data = array(
                    'nome' => $row->nome_usuario,
                    'empresa' => $empresa,
                    'nome_treinamento' => $row->curso,
                    'duracao' => $row->duracao,
                    'data_inicial' => date('d', strtotime($row->datainicial)),
                    'data_final' => strftime('%d de %B de %Y', strtotime($row->datafinal)),
                    'foto' => $foto,
                    'assinatura' => $assinatura
                );

                # Verifica os acertos
                if ($total_acertos >= 75) {
                    $this->load->library('m_pdf');

                    $html = $this->load->view('certificado_view', $data, true);
                    $this->m_pdf->pdf->AddPage('L');
                    $this->m_pdf->pdf->writeHTML($html);

//                    $arquivo = substr(md5(uniqid(time())), 0, $data['nome']);
                    $this->m_pdf->pdf->Output('certificado.pdf', 'I');
                } else {
                    header("location:" . site_url('home'));
                }
            }
        }
    }

    public function emissaoCertificado2($id_curso, $id_funcionario)
    {
        //Setar região
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $perguntas = null;
        $total_acertos = 0;

        $cursos = $this->db->query("SELECT cs.*, u.nome AS nome_usuario, u.empresa, u.foto, e.nome AS empresa_emissora, e.foto AS foto_empresa_emissora,
                                    MIN(up.datacadastro) AS datainicial, MAX(up.dataconclusao) AS datafinal,
                                    (SELECT COUNT(*) FROM paginas p WHERE p.curso = '$id_curso' AND p.modulo = 'atividades') AS total_atividades,
                                    cs.id, u.assinatura_digital, e.assinatura_digital AS assinatura_emissora
                                   FROM usuarioscursos c
                                   INNER JOIN usuarios u ON u.id = c.usuario
                                   LEFT JOIN usuarios e ON e.id = u.empresa
                                   INNER JOIN cursos cs ON cs.id = c.curso
                                   LEFT JOIN usuariospaginas up ON up.curso = c.curso
                                   WHERE c.curso = ? AND c.usuario = ? LIMIT 1", array($id_curso, $id_funcionario));

        $questoes = $cursos->result();
        # Separa a quantidade de alternativas e acertos
        foreach ($questoes as $row) {
            $atividades[$row->id] = $this->db->query("SELECT status, pagina
                                                      FROM usuariosatividades
                                                      WHERE curso = ? AND usuario = ?
                                                      ", array($row->id, $this->uri->rsegment(4)))->result();
        }

        # Validação
        if ($id_curso < 1 || $id_funcionario < 1 || $cursos->num_rows() < 1) {
            header("location:" . site_url('home'));
        } else {
            foreach ($questoes as $row) {

                /* Calcular avaliação final */
                $total_atividades = 100 / $row->total_atividades;

                if ($atividades[$row->id]) {

                    # Verifica a quantidade de alternativas e acertos
                    foreach ($atividades[$row->id] as $questao) {
                        if (!isset($perguntas[$questao->pagina])) {
                            $perguntas[$questao->pagina] = array('alternativas' => 0, 'acertos' => 0);
                        }

                        $perguntas[$questao->pagina]['alternativas'] += 1;
                        if ($questao->status == 1) {
                            $perguntas[$questao->pagina]['acertos'] += 1;
                        }
                    }

                    # Verifica o valor total
                    foreach ($perguntas as $questao) {
                        $total_acertos += ($total_atividades / $questao['alternativas']) * $questao['acertos'];
                    }
                }

                # Empresa
                $empresa = $row->empresa_emissora;
                $foto = $row->foto_empresa_emissora;
                $assinatura = $row->assinatura_emissora;

                if ($row->empresa == 0) {
                    $empresa = $row->nome_usuario;
                    $foto = $row->foto;
                    $assinatura = $row->assinatura_digital;
                }

                # Dados do banco
                $data = array(
                    'nome' => $row->nome_usuario,
                    'empresa' => $empresa,
                    'nome_treinamento' => $row->curso,
                    'duracao' => $row->duracao,
                    'data_inicial' => date('d', strtotime($row->datainicial)),
                    'data_final' => strftime('%d de %B de %Y', strtotime($row->datafinal)),
                    'foto' => $foto,
                    'assinatura' => $assinatura
                );

                # Verifica os acertos
                if ($total_acertos >= 75) {
                    $this->load->view('certificado', $data);
                } else {
                    header("location:" . site_url('home'));
                }
            }
        }
    }

}
