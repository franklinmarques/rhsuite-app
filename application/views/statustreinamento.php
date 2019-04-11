<?php
require_once "header.php";
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <section class="panel">
                    <header class="panel-heading">
                        <i class="icon-table"></i> Gerenciador de Treinamento
                    </header>
                    <div class="panel-body" id="html-funcionarios">
                        <table class="table table-striped table-hover fill-head">
                            <thead>
                                <tr>
                                    <th>Página-Unidade</th>
                                    <th>Data Primeiro Acesso</th>
                                    <th>Data Finalização</th>
                                    <th>Tempo de estudo</th>
                                    <th>Notas das avaliações</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                function mintohora($minutos)
                                {
                                    $hora = floor($minutos / 60);
                                    $resto = $minutos % 60;
                                    return $hora . ':' . $resto;
                                }

                                $total_paginas = $row->num_rows();
                                $quant_atividades = 0;
                                $media_aluno = 0;
                                $total_tempo = 0;
                                $andamento = 0;

                                # Calcula as atividades
                                foreach ($atividades->result() as $row_atividade) {
                                    # Calcula a quantidade de avaliações
                                    if (!isset($atividade[$row_atividade->pagina])) {
                                        $quant_atividades += 1;
                                    }
                                    $atividade[$row_atividade->pagina][] = array('status' => $row_atividade->status);
                                }

                                # Nota por avaliação
                                $nota_final = $quant_atividades ? 100 / $quant_atividades : 0;

                                # Mostra resultados
                                foreach ($row->result() as $paginas) {

                                    # Variáveis para os cálculos
                                    $andamento = (int) $paginas->andamento / (int) ($total_paginas - 1) * 100;
                                    $nota_avaliacao = null;
                                    $media_parcial = 0;
                                    $total_acertos = 0;
                                    $total_questoes = 0;
                                    $tempo_estudo = "00:00:00";

                                    # Soma de atividades e acertos
                                    if (isset($atividade[$paginas->id])) {

                                        $nota_avaliacao = 0;

                                        foreach ($atividade[$paginas->id] as $linha) {
                                            $total_questoes += 1;
                                            if ($linha['status'] == 1) {
                                                $total_acertos += 1;
                                            }
                                        }

                                        # Calcula média geral
                                        $media_parcial = ($nota_final / $total_questoes) * $total_acertos;
                                        $media_aluno += $media_parcial;

                                        # Calcula média por avaliação
                                        $nota_avaliacao = (100 / $total_questoes) * $total_acertos;
                                        $nota_avaliacao = round($nota_avaliacao, 2) . "%";
                                    }

                                    if (!empty($paginas->finalizacao) && !empty($paginas->cadastro)) {

                                        $data1 = $paginas->cadastro;
                                        $data2 = $paginas->finalizacao;

                                        $unix_data1 = strtotime($data1);
                                        $unix_data2 = strtotime($data2);

                                        $nHoras = ($unix_data2 - $unix_data1) / 3600;
                                        $nMinutos = (($unix_data2 - $unix_data1) % 3600) / 60;
                                        $nSeconds = ($unix_data2 - $unix_data1) - ($nHoras * (-1)) * 60 * 60 - $nMinutos * 60;
                                        $nSeconds = str_pad($nSeconds, 2, "0", STR_PAD_LEFT);

                                        $times[] = $tempo_estudo = sprintf('%02d:%02d:%02d', substr($nHoras, 0, 2), substr($nMinutos, 0, 2), substr($nSeconds, 0, 2));

                                        $seconds = 0;

                                        foreach ($times as $time) {

                                            list($g, $i, $s) = explode(':', $time);
                                            $seconds += $g * 3600;
                                            $seconds += $i * 60;
                                            $seconds += $s;
                                        }

                                        $hours = floor($seconds / 3600);
                                        $seconds -= $hours * 3600;
                                        $minutes = floor($seconds / 60);
                                        $seconds -= $minutes * 60;
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $paginas->titulo; ?></td>
                                        <td><?= (!empty($paginas->cadastro) ? date('d/m/Y', strtotime($paginas->cadastro)) : ''); ?></td>
                                        <td><?= (!empty($paginas->finalizacao) ? date('d/m/Y', strtotime($paginas->finalizacao)) : ''); ?></td>
                                        <td><?= $tempo_estudo; ?></td>
                                        <td><?= $nota_avaliacao; ?></td>
                                    </tr>
                                    <?php
                                }
                                if ($row->num_rows() == 0) {
                                    ?>
                                    <tr>
                                        <th colspan="5">Nenhuma unidade encontrada</th>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <th colspan="2">Total de unidades encontradas: <?php echo $row->num_rows(); ?></th>
                                    <th>
                                        Finalizado: <?= ($andamento >= 100 ? 100 : round($andamento, 2)); ?>%
                                    </th>
                                    <th><?= (!empty($times) ? str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT) : "00:00:00"); ?></th>
                                    <th><?php echo round($media_aluno, 2); ?>%</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<?php
require_once "end_js.php";
?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar Funcionários';
    });
</script>
<?php
require_once "end_html.php";
?>
