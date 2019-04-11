<?php
require_once APPPATH . "views/header.php";
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
                        <li><a href="<?= site_url('cd/apontamento') ?>">Apontamentos diários</a></li>
                        <li class="active">Gerenciar cuidadores</li>
                    </ol>
                    <button class="btn btn-success" onclick="add_cuidadores()"><i
                                class="glyphicon glyphicon-plus"></i> Vincular cuidador
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="control-label">Departamento</label>
                                            <?php echo form_dropdown('busca[depto]', $depto, $depto_atual, 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Diretoria de ensino/prefeitura</label>
                                            <?php echo form_dropdown('busca[area]', $area, $area_atual, 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Supervisor</label>
                                            <?php echo form_dropdown('busca[setor]', $setor, $setor_atual, 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Contrato</label>
                                            <?php echo form_dropdown('busca[contrato]', $contrato, '', 'class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp;</label><br>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                    Limpar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Diretoria de ensino/Prefeitura</th>
                            <th>Supervisor</th>
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
                            <h3 class="modal-title">Vincular cuidador a unidades de ensino</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <div class="row form-group">
                                    <div class="col-md-4 text-right"><strong>Supervisor:</strong></div>
                                    <div class="col-md-7">
                                        <?php echo form_dropdown('id_usuario', $supervisores, '', 'id="id_usuario" class="form-control input-sm"'); ?>
                                        <span id="supervisor"></span>
                                    </div>
                                </div>
                                <hr style="margin-top: 10px; margin-bottom: 0px;">
                                <div class="row form-group">
                                    <div class="col-md-4 text-right"><strong>Diretoria de ensino/prefeitura:</strong>
                                    </div>
                                    <div class="col-md-7">
                                        <?php echo form_dropdown('id_diretoria', $diretorias, '', 'id="id_diretoria" class="form-control input-sm"'); ?>
                                        <span id="diretoria"></span>
                                    </div>
                                </div>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <?php echo form_multiselect('id_unidade[]', array(), array(), 'id="id_unidade" class="demo2" size="8"') ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

        </section>
    </section>
    <!--main content end-->

<?php
require_once APPPATH . "views/end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar funcionários';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table, demo2;

        //    $('.data_reajuste').mask('00/00/0000');
        //    $('.valor').mask('##.###.##0,00', {reverse: true});

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "info": false,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('cd/cuidadores/ajax_list/') ?>",
                    "type": "POST",
                    data: function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '50%',
                        targets: [0, 1]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ]
            });

            demo2 = $('#id_unidade').bootstrapDualListbox({
                nonSelectedListLabel: 'Unidades disponíveis',
                selectedListLabel: 'Unidades selecionadas',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                filterPlaceHolder: 'Filtrar',
                helperSelectNamePostfix: false,
                selectorMinimalHeight: 132,
                infoText: false
            });

            atualizarFiltro();
        });

        $('#id_diretoria').on('change', function () {
            $.ajax({
                url: "<?php echo site_url('cd/cuidadores/atualizar_unidades/') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    id_diretoria: $('#id_diretoria').val()
                },
                success: function (data) {
                    $('#id_usuario').html($(data.id_cuidador).html());
                    $('#id_unidade').html(data.id_unidade);
                    demo2.bootstrapDualListbox('refresh', true);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        function atualizarFiltro() {
            $.ajax({
                url: "<?php echo site_url('cd/cuidadores/atualizar_filtro/') ?>",
                type: "POST",
                dataType: "JSON",
                data: $('#busca').serialize(),
                success: function (data) {
                    $('[name="busca[area]"]').html($(data.area).html());
                    $('[name="busca[setor]"]').html($(data.setor).html());
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        $('#limpa_filtro').on('click', function () {
            var busca = unescape($('#busca').serialize());
            $.each(busca.split('&'), function (index, elem) {
                var vals = elem.split('=');
                $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
            });
            atualizarFiltro();
        });

        $('.estrutura').on('change', function () {
            atualizar_estrutura();
        });

        function atualizar_estrutura() {
            $.ajax({
                url: "<?php echo site_url('cd/cuidadores/ajax_estrutura/') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    depto: $('#depto').val(),
                    area: $('#area').val(),
                    setor: $('#setor').val()
                },
                success: function (data) {
                    $('#area').html($(data.area).html());
                    $('#setor').html($(data.setor).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function add_cuidadores() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('[name="tipo"] option').prop('disabled', false);
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#id_diretoria, #id_usuario').val('');
            $('#id_diretoria, #id_usuario, #id_unidade').find('option').prop('disabled', false);
            $('#id_unidade').html('').val('');
            demo2.bootstrapDualListbox('refresh', true);
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.combo_nivel1').hide();
        }

        function edit_cuidadores(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('cd/cuidadores/ajax_edit/') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data) {
                    $('#id_diretoria').val(data.id_diretoria);
                    $('#id_usuario').html($(data.id_cuidador).html());
                    $('#id_diretoria option:not(:selected), #id_usuario option:not(:selected)').prop('disabled', true);
                    $('#id_unidade').html(data.id_unidade);
                    demo2.bootstrapDualListbox('refresh', true);

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
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('cd/cuidadores/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('cd/cuidadores/ajax_update') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
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

        function delete_cuidadores(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('cd/cuidadores/ajax_delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    data: {id: id},
                    success: function (data) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

    </script>

<?php
require_once APPPATH . "views/end_html.php";
?>