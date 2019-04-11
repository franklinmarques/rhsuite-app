<?php
require_once "header.php";
?>
    <style>
        .btn-success {
            background-color: #5cb85c;
            border-color: #4cae4c;
            color: #fff;
        }

        .btn-primary {
            background-color: #337ab7 !important;
            border-color: #2e6da4 !important;
            color: #fff;
        }

        .btn-info {
            color: #fff;
            background-color: #5bc0de;
            border-color: #46b8da;
        }

        .btn-warning {
            color: #fff;
            background-color: #f0ad4e;
            border-color: #eea236;
        }

        .btn-danger {
            color: #fff;
            background-color: #d9534f;
            border-color: #d43f3a;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        tr.group, tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div style="color: #000;">
                <table class="table table-condensed recrutamento">
                    <thead>
                    <tr style='border-top: 5px solid #ddd;'>
                        <th colspan="3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <img src="<?= base_url($foto) ?>" align="left"
                                         style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                    <p class="text-left">
                                        <img src="<?= base_url($foto_descricao) ?>" align="left"
                                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                    </p>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr style='border-top: 5px solid #ddd;'>
                        <th colspan="3">
                            <h2 class="text-center" style="margin-top: 10px;">RELATÓRIO DE PROCESSO SELETIVO</h2>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    </tr>
                    <tr style='border-top: 5px solid #ddd;'>
                        <td nowrap>
                            <h5><strong>Teste aplicado: </strong><?= $teste->modelo ?></h5>
                            <h5><strong>Data atual: </strong><?= date('d/m/Y') ?></h5>
                        </td>
                        <td nowrap>
                            <h5><strong>Data início teste: </strong><?= $teste->data_inicio ?></h5>
                            <h5><strong>Data término teste: </strong><?= $teste->data_termino ?></h5>
                        </td>
                        <td class="text-right">
                            <a id="pdf" class="btn btn-sm btn-danger"
                               href="<?= site_url('recrutamento/pdfRelatorio/' . $this->uri->rsegment(3)); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                            <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        </td>
                    </tr>
                    <tr style='border-top: 5px solid #ddd;'>
                        <td><strong>Requisitante:</strong> <?= $teste->requisitante ?></td>
                        <td colspan="2"><strong>Cargo/função alvo:</strong> <?= $teste->cargo ?></td>
                    </tr>
                    <tr>
                        <td><strong>Candidato avaliado:</strong> <?= $teste->candidato ?></td>
                        <?php if ($teste->tipo === 'D'): ?>
                            <td colspan="2"><strong>Desempenho:</strong> <span
                                        style="font-size: 15px;" id="total"><?= $teste->total ?>%</span>
                                (<?= $teste->caracteres . ' caractere' . ($teste->caracteres > 1 ? 's' : '') ?>
                                em <?= $teste->minutos . ' minuto' . ($teste->minutos > 1 ? 's' : '') ?>)
                            </td>
                        <?php else: ?>
                            <td colspan="2"><strong>Percentual de desempenho observado no teste:</strong> <span
                                        style="font-size: 15px;" id="total"><?= $teste->total ?>%</span>
                            </td>
                        <?php endif; ?>
                    </tr>
                    </tbody>
                </table>

                <!--<div class="table-responsive">-->
                <?php if ($teste->tipo === 'D' or $teste->tipo === 'I'): ?>
                    <?php if ($teste->tipo === 'I'): ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form-inline" id="form" autocomplete="off">
                                    <input type="hidden" name="id_teste" value="<?= $teste->id_teste; ?>">
                                    <div class="form-group">
                                        <label for="nota[<?= $teste->id_pergunta ?>]">Nota de avaliação:</label>&ensp;
                                        <div class="input-group">
                                            <input name="nota[<?= $teste->id_pergunta ?>]" class="form-control"
                                                   type="number" style="width: 100px;"
                                                   min="0" max="100" value="<?= $teste->total ?>">
                                            <span class="input-group-addon" id="basic-addon1">%</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="salvar()">Salvar nota
                                    </button>
                                </form>
                                <br>
                            </div>
                        </div>
                    <?php endif; ?>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%"
                           style="border-radius: 0 !important;">
                        <thead>
                        <tr class="active">
                            <th>Interpretação do texto</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="background-color: #fff; height: 400px;"><?= $resposta ?></td>
                        </tr>
                        </tbody>
                    </table>

                <?php else: ?>

                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%"
                           style="border-radius: 0 !important;">
                        <thead>
                        <tr class="active">
                            <th>Critérios de avaliação</th>
                            <th class="text-center">Peso</th>
                            <th class="text-center">Resposta</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($perguntas as $pergunta): ?>
                            <tr class="success">
                                <th colspan="3"><?= $pergunta->pergunta ?></th>
                            </tr>
                            <?php foreach ($pergunta->alternativas as $alternativa): ?>
                                <tr>
                                    <td><?= $alternativa->alternativa ?></td>
                                    <td class="text-center"><?= $alternativa->peso ?></td>
                                    <?php if ($alternativa->resposta === null): ?>
                                        <!--                                        <td class="text-center text-muted">0</td>-->
                                        <td></td>
                                    <?php else: ?>
                                        <td class="text-center"><?= $alternativa->resposta ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
                <!--</div>-->

            </div>
        </section>
    </section>
    <!--main content end-->

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - PROCESSO SELETIVO - <?= $teste->candidato ?>';
        });

        function salvar() {
            $('#salvar').html('Salvando...').prop('disabled', true);
            $.ajax({
                url: '<?php echo site_url('recrutamento_testes/avaliar'); ?>',
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (json) {
                    $('#total').html(json.total + '%');
                    $('#salvar').html('Salvar notas').prop('disabled', false).focusout();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error update data');
                    $('#salvar').html('Salvar notas').prop('disabled', false).focusout();
                }
            });
        }
    </script>
<?php
require_once "end_js.php";
require_once "end_html.php";
?>