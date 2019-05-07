<?php require_once APPPATH . 'views/header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li class="active">Avaliação de Treinamento de Clientes</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-2">
                            <button class="btn btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-4 text-right">
                            <button id="btnSave" class="btn btn-success" onclick="save();"><i class="fa fa-save"></i>
                                Salvar
                            </button>
                            <a id="pdf" class="btn btn-info"
                               href="<?= site_url('ead/clientes_treinamentos/pdf/q?' . $query_string); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                        </div>
                    </div>
                    <br>
                    <div id="alert"></div>
                    <table class="table avaliacao table-condensed">
                        <tbody>
                        <tr>
                            <td><strong>Cliente:</strong> <?= $cliente ?></td>
                        </tr>
                        <tr>
                            <td><strong>Usuário:</strong> <?= $usuario ?></td>
                        </tr>
                        <tr>
                            <td><strong>Treinamento:</strong> <?= $treinamento ?></td>
                        </tr>
                        <tr>
                            <td><strong>Período:</strong> <?= implode(' às ', [$data_inicio, $data_maxima]) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Data de realização:</strong> <?= $data_finalizacao ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tempo total de estudo:</strong> <?= $tempo_estudo ?></td>
                        </tr>
                        <?php if (strlen($avaliacao_final)): ?>
                            <tr>
                                <td><strong>Avaliação
                                        final:</strong> <?= str_replace('.', ',', $avaliacao_final . '%') ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if ($is_pdf == false): ?>
                    <form action="#" id="form" class="form-horizontal" autocomplete="off">
                        <?php endif; ?>

                        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Questões</th>
                                <th>Respostas</th>
                                <th>Notas (0% a 100%)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($resultados as $resultado): ?>
                                <tr>
                                    <td><?= $resultado->conteudo ?></td>
                                    <td><?= $resultado->resposta ?></td>
                                    <?php if ($is_pdf): ?>
                                        <td><?= $resultado->nota ?></td>
                                    <?php else: ?>
                                        <?php if ($resultado->data_finalizacao): ?>
                                            <td>
                                                <input type="text" name="nota[<?= $resultado->id ?>]"
                                                       class="form-control nota" value="<?= $resultado->nota ?>">
                                            </td>
                                        <?php else: ?>
                                            <td><input type="text" name="nota" class="form-control nota" disabled></td>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?php if ($is_pdf == false): ?>
                    </form>
                <?php endif; ?>
                </div>
            </div>
            <!-- page end-->

        </section>
    </section>
    <!--main content end-->

    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>">

<?php require_once APPPATH . 'views/end_js.php'; ?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'RhSuite - Corporate RH Tools: Gerenciar Treinamentos de Clientes';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>
        var table;
        var save_method;

        $('.nota').mask('#00', {
            translation: {
                '#': {pattern: /1/, optional: true}
            }
        });

        $(document).ready(function () {

            table = $('#table').DataTable({
                'info': false,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'paging': false,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'columnDefs': [
                    {
                        'width': '50%',
                        'targets': [0, 1]
                    }
                ]
            });

        });


        function save() {
            $('#btnSave').html('<i class="fa fa-save"></i> Salvando').prop('disabled', true);
            $.ajax({
                'url': "<?php echo site_url('ead/clientes_treinamentos/ajaxSaveAvaliacao/' . $id_cliente . '/' . $id) ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form').serialize(),
                'success': function (json) {
                    if (json.retorno === 1) {
                        $('#alert').html('<div class="alert alert-success">' + json.aviso + '</div>').hide().fadeIn('slow', function () {
                            top.location.href = json.pagina;
                        });
                    } else {
                        if (json.retorno === 0) {
                            $('#alert').html('<div class="alert alert-danger">' + json.aviso + '</div>').hide().fadeIn('slow');
                        } else if (json.retorno === 2) {
                            $('#alert').html('<div class="alert alert-warning">' + json.aviso + '</div>').hide().fadeIn('slow');
                        }
                    }

                    $('#btnSave').html('<i class="fa fa-save"></i> Salvar').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Erro ao salvar a avaliação');
                    $('#btnSave').html('<i class="fa fa-save"></i> Salvar').prop('disabled', false);
                }
            });
        }


    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>