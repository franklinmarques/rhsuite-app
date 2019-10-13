<?php
require_once "header.php";
?>
    <style>
        <?php foreach($notas_maximas as $nota_maxima): ?>
        .tipo_<?= $nota_maxima ?> {
            color: #fff;
            background-color: #1e3946;
            border-color: #123946;
        }

        <?php /*endforeach; */?>
        <!--
        <?php /*foreach($notas_maximas2 as $nota_maxima2): */?>
        .tipo_<?/*= $nota_maxima2 */?> {
            background-color: #417c92;
            border-color: #347892;
            color: #fff;
        }

        <?php /*endforeach; */?>
        <?php /*foreach($notas_maximas3 as $nota_maxima3): */?>
        .tipo_<?/*= $nota_maxima3 */?> {
            background-color: #afdade;
            border-color: #90ccde;
            color: #fff;
        }

        -->

        <?php endforeach; ?>

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

        #table_laudo tr th, #table_laudo tr td {
            font-size: 14px !important;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div style="color: #000;">
                <table class="table table-condensed pesquisa">
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
                            <h2 class="text-center" style="margin-top: 10px;">AVALIAÇÃO DE PERSONALIDADE</h2>
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
                            <a id="pdf" class="btn btn-sm btn-info"
                               href="<?= site_url('pesquisa_lifo/pdf/' . $this->uri->rsegment(3)); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                            <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        </td>
                    </tr>
                    <tr style='border-top: 5px solid #ddd;'>
                        <td><strong>Profissional avaliado:</strong> <?= $teste->candidato ?></td>
                        <td colspan="2"><strong>Cargo/função alvo:</strong> <?= $teste->cargo_funcao ?></td>
                    </tr>
                    </tbody>
                </table>

                <?php echo form_open('pesquisa_lifo/savePerfilComportamento/' . $teste->id, 'id="form" data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                <input type="hidden" id="estilo_personalidade_majoritario"
                       value="<?= $laudoPerfil->estilo_personalidade_majoritario; ?>">
                <input type="hidden" id="estilo_personalidade_secundario"
                       value="<?= $laudoPerfil->estilo_personalidade_secundario; ?>">
                <div id="alert"></div>
                <div class="row">
                    <div class="col-md-7">
                        <h3>Traços de Personalidade Mapeados no Avaliado</h3>
                    </div>
                    <div class="col-md-5 text-right">
                        <button type="button" class="btn btn-success avaliador" onclick="recuperar_laudo();">Recuperar
                            laudo padrão
                        </button>
                        <button type="button" class="btn btn-info avaliador" onclick="personalizar_laudo();">
                            Personalizar laudo
                        </button>
                        <button type="submit" id="btnSave" class="btn btn-success editar" style="display: none;">
                            Salvar
                        </button>
                        <button type="button" class="btn btn-default editar" style="display: none;"
                                onclick="cancelar_edicao()">Cancelar
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table id="table_laudo" class="table table-striped table-bordered" cellspacing="0"
                               width="100%"
                               style="border-radius: 0 !important;">
                            <thead>
                            <tr class="success">
                                <th colspan="2" class="text-center">
                                    <h3><strong>LAUDO DA AVALIAÇÃO</strong></h3>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="active">
                                <th colspan="2" tyle="vertical-align: middle;">Perfil - comportamentos majoritário -
                                    Estilo "<?= $laudoPerfil->nome; ?>"
                                </th>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p class="avaliador">
                                        <?php if (strlen($laudoPerfil->estilo_personalidade_majoritario) > 0): ?>
                                            <?= nl2br($laudoPerfil->estilo_personalidade_majoritario); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Nenhum comportamento apresentado</span>
                                        <?php endif; ?>
                                    </p>
                                    <textarea name="estilo_personalidade_majoritario" class="form-control editar"
                                              style="display: none;"
                                              rows="5"><?= $laudoPerfil->estilo_personalidade_majoritario; ?></textarea>
                                </td>
                            </tr>
                            <tr class="active">
                                <th colspan="2" tyle="vertical-align: middle;">Perfil - comportamentos secundário -
                                    Estilo "<?= $laudoPerfil->nome; ?>"
                                </th>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p class="avaliador">
                                        <?php if (strlen($laudoPerfil->estilo_personalidade_secundario) > 0): ?>
                                            <?= nl2br($laudoPerfil->estilo_personalidade_secundario); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Nenhum comportamento apresentado</span>
                                        <?php endif; ?>
                                    </p>
                                    <textarea name="estilo_personalidade_secundario" class="form-control editar"
                                              style="display: none;"
                                              rows="5"><?= $laudoPerfil->estilo_personalidade_secundario; ?></textarea>
                                </td>
                            </tr>
                            <tr class="active">
                                <th style="vertical-align: middle;">Posicionamentos possíveis percebidos/observávies em
                                    situações normais
                                </th>
                                <th style="vertical-align: middle;">Posicionamentos possíveis percebidos/observávies em
                                    situações de pressão/estresse
                                </th>
                            </tr>
                            <tr>
                                <?php if (strlen($laudoPerfil->comportamentos_normais) > 0): ?>
                                    <td><?= nl2br($laudoPerfil->comportamentos_normais); ?></td>
                                <?php else: ?>
                                    <td><span class="text-muted">Nenhum posicionamento apresentado</span></td>
                                <?php endif; ?>
                                <?php if (strlen($laudoPerfil->comportamentos_estresse) > 0): ?>
                                    <td><?= nl2br($laudoPerfil->comportamentos_estresse); ?></td>
                                <?php else: ?>
                                    <td><span class="text-muted">Nenhum posicionamento apresentado</span></td>
                                <?php endif; ?>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </section>
    </section>
    <!--main content end-->

<?php require_once "end_js.php"; ?>

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Laudo de Avaliação';
        });
    </script>

    <script>

        function recuperar_laudo() {
            if (confirm('Deseja recuperar as configurações padrão do laudo?')) {
                $('#form textarea[name="estilo_personalidade_majoritario"]').val('');
                $('#form textarea[name="estilo_personalidade_secundario"]').val('');
                $('#form').submit();
            }
        }

        function personalizar_laudo() {
            $('#form textarea[name="estilo_personalidade_majoritario"]').val($('#estilo_personalidade_majoritario').val());
            $('#form textarea[name="estilo_personalidade_secundario"]').val($('#estilo_personalidade_secundario').val());
            $('.avaliador').hide();
            $('.editar').show();
        }

        function cancelar_edicao() {
            $('.editar').hide();
            $('.avaliador').show();
        }
    </script>


<?php require_once "end_html.php"; ?>