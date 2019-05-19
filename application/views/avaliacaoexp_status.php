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
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <?php if ($tipo == 1): ?>
                            <li class="active">Status - Avaliações periódicas de desempenho</li>
                        <?php elseif ($tipo == 2): ?>
                            <li class="active">Status - Avaliações por período de experiência</li>
                        <?php endif; ?>
                    </ol>
                    <button class="btn btn-info" onclick="relatorios()"><i class="glyphicon glyphicon-list-alt"></i>
                        Relatórios
                    </button>
                    <button class="btn btn-warning" onclick="notificar()"><i class="glyphicon glyphicon-bell"></i>
                        Notificar avaliadores
                    </button>
                    <br/>
                    <br/>
                    <div class="row">
                        <form action="#" id="busca" autocomplete="off">
                            <div class="col-md-4">
                                <label class="control-label">Filtrar por departamento</label>
                                <?php echo form_dropdown('depto', $depto, '', 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Filtrar por área</label>
                                <?php echo form_dropdown('area', $area, '', 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Filtrar por setor</label>
                                <?php echo form_dropdown('setor', $setor, '', 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Filtrar por cargo</label>
                                <?php echo form_dropdown('cargo', $cargo, '', 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Filtrar por função</label>
                                <?php echo form_dropdown('funcao', $funcao, '', 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Filtrar a partir de</label>
                                <input class="form-control input-sm text-center" name="data_avaliacao"
                                       placeholder="dd/mm/aaaa" type="text" onchange="reload_table();">
                            </div>
                            <div class="col-md-2">
                                <br>
                                <button type="button" id="limpar" class="btn btn-default">Limpar filtro</button>
                            </div>
                            <div class="col-md-12">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="resultado" onchange="reload_table();" checked>
                                    Filtrar apenas resultados pendentes
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="status" onchange="reload_table();" checked> Filtrar
                                    somente colaboradores ativos
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="ultimo_semestre" onchange="reload_table();"> Filtrar
                                    últimos seis meses
                                </label>
                            </div>
                        </form>
                    </div>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Colaborador(a) avaliado(a)</th>
                            <th>Data programada</th>
                            <th>Avaliador</th>
                            <th>Data de realização</th>
                            <th>Nota</th>
                            <th>Observações</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- page end-->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar observações</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Colaborador(a) avaliado(a)</label>
                                        <div class="col-md-9">
                                            <label class="sr-only" style="margin-top: 7px;"></label>
                                            <p class="form-control-static" id="nome"></p>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Observações</label>
                                        <div class="col-md-9">
                                            <textarea name="observacoes" class="form-control" rows="2"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </section>
    </section>
    <!--main content end-->

<?php
require_once "end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Status - Avaliações <?= $tipo == 1 ? 'periódicas de desempenho' : 'por período de experiência' ?>';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var table;

        $('[name="data_avaliacao"]').mask('00/00/0000');

        $('#limpar').on('click', function () {
            $('#busca')[0].reset();
            $('[name="data_avaliacao"]').prop('readonly', false);
            reload_table();
        });

        $('[name="ultimo_semestre"]').on('change', function () {
            $('[name="data_avaliacao"]').val('').prop('readonly', this.checked);
        });

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                iDisplayLength: -1,
                lengthMenu: [[5, 10, 25, 50, 100, 500, 1000, -1], [5, 10, 25, 50, 100, 500, 1000, 'Todos']],
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                "oLanguage": {
                    "sSearch": 'Pesquisar nome/matrícula'
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('avaliacaoexp_avaliados/ajax_status/' . $tipo) ?>",
                    "type": "POST",
                    timeout: 9000,
                    data: function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '30%',
                        targets: [0, 2]
                    },
                    {
                        width: '40%',
                        targets: [5]
                    },
                    {
                        className: 'text-center',
                        targets: [1, 3]
                    },
                    {
                        className: 'text-right',
                        targets: [4]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ],
                rowsGroup: [0, 5, -1]
            });

            //datepicker
            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
                orientation: "top auto",
                todayBtn: true
            });

        });

        function atualizarFiltro() {
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliados/atualizar_filtro/') ?>",
                type: "POST",
                dataType: "JSON",
                data: $('#busca').serialize(),
                success: function (data) {
                    if (data.area !== undefined) {
                        $('[name="area"]').html($(data.area).html());
                    }
                    if (data.setor !== undefined) {
                        $('[name="setor"]').html($(data.setor).html());
                    }
                    $('[name="cargo"]').html($(data.cargo).html());
                    $('[name="funcao"]').html($(data.funcao).html());

                    // $('[name="data_avaliacao"]').prop('readonly', $('[name="resultado"]').is(':checked'));

                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        $('#limpar').on('click', function () {
            var busca = $('#busca').serialize();
            $.each(busca.split('&'), function (index, elem) {
                var vals = elem.split('=');
                if (vals[0] === 'data_avaliacao') {
                    $("[name='" + vals[0] + "']").val('');
                } else {
                    $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
                }
            });
            atualizarFiltro();
        });

        function edit_status(id) {
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliados/edit_status/') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data) {
                    $('[name="id"]').val(data.id);
                    $('#nome').text(data.nome);
                    $('[name="observacoes"]').val(data.observacoes);

                    $('#modal_form').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliados/update_status') ?>",
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function notificar(id = null) {
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliados/status_notificar') ?>",
                type: "POST",
                data: {
                    tipo: '<?= $tipo ?>',
                    id_avaliado: id,
                    busca: $('#busca').serialize()
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.status) {
                        alert('Notificação enviada com sucesso');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Erro ao notificar colaboradores');
                }
            });
        }

    </script>

<?php
require_once "end_html.php";
?>