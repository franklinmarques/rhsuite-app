<?php
require_once APPPATH . 'views/header.php';
?>
<!--main content start-->
<section id="main-content" class="<?= $this->session->userdata('tipo') === 'cliente' ? 'merge-left' : ''; ?>">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <section class="panel">
                    <header class="panel-heading">
                        <i class="icon-table"></i> Gerenciador de Treinamento
                        <button class="btn btn-default btn-sm" onclick="javascript:history.back()"
                                style="float: right; margin-top: -0.6%;"><i
                                    class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                        </button>
                    </header>
                    <div class="panel-body" id="html-funcionarios">
                        <div class="row">
                            <div class="col-sm-12 text-danger text-right">
                                <p>
                                    <small><em>* Notas em itálico não são contablilzadas para o total.</em></small>
                                </p>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover fill-head">
                            <thead>
                            <tr>
                                <th>Página-unidade</th>
                                <th class="hidden-xs hidden-sm">Data primeiro acesso</th>
                                <th>Data finalização</th>
                                <th class="hidden-xs hidden-sm">Tempo de estudo</th>
                                <th>Notas das avaliações <span class="text-danger">*</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?= $row->titulo; ?></td>
                                    <td class="text-center hidden-xs hidden-sm"><?= $row->data_acesso; ?></td>
                                    <td class="text-center"><?= $row->data_finalizacao; ?></td>
                                    <td class="text-center hidden-xs hidden-sm"><?= $row->tempo_estudo; ?></td>
                                    <?php if ($row->data_finalizacao): ?>
                                        <?php //if ($row->modulo == 'atividades' && ($row->tipo == 1 && $row->tipo == 3)): ?>
                                        <?php if ($row->modulo == 'quiz'): ?>
                                            <td class="text-center">
                                                <em><?= str_replace('.', ',', round($row->nota_avaliacao, 2)); ?></em>
                                            </td>
                                        <?php elseif ($row->modulo == 'atividades'): ?>
                                            <td class="text-center"><?= str_replace('.', ',', round($row->nota_avaliacao, 2)); ?></td>
                                        <?php else: ?>
                                            <td class="text-center">_ _</td>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <td class="text-right"><?= $row->nota_avaliacao; ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <th colspan="5">Nenhum curso encontrado</th>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Total de unidades encontradas: <?php echo count($rows); ?></th>
                                <th>Finalizado: <?= round($total->finalizado, 2); ?>%</th>
                                <th colspan="2" class="hidden-xs hidden-sm">Tempo
                                    total: <?= $total->tempo_curso; ?></th>
                                <th><?= ($total->finalizado < 100 ? 'Total parcial: ' : 'Total: ') . str_replace('.', ',', round($total->nota_final, 2)); ?></th>
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
require_once APPPATH . 'views/end_js.php';
?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar Funcionários';
    });
</script>
<?php
require_once APPPATH . 'views/end_html.php';
?>
