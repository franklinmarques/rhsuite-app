<?php
require_once APPPATH . 'views/header.php';
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
                        <li class="active">Vincular supervisores</li>
                    </ol>
                    <button class="btn btn-success" onclick="add_supervisores()"><i
                                class="glyphicon glyphicon-plus"></i> Vincular nova diretoria
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
                                            <?php echo form_dropdown('busca[depto]', $busca_depto, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Diretoria de ensino/prefeitura</label>
                                            <?php echo form_dropdown('busca[diretoria]', $busca_diretoria, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Supervisor</label>
                                            <?php echo form_dropdown('busca[supervisor]', $busca_supervisor, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-1" style="padding-left: 5px;">
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
                            <th>Período</th>
                            <th>Unidade de ensino</th>
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
                            <h3 class="modal-title">Vincular supervisor a unidades de ensino</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <div class="row form-group">
                                    <div class="col-md-4 text-right"><strong>Supervisor:</strong></div>
                                    <div class="col-md-7">
                                        <?php echo form_dropdown('id_supervisor', $id_supervisor, '', 'id="id_supervisor" class="form-control input-sm"'); ?>
                                    </div>
                                </div>
                                <hr style="margin-top: 3px; margin-bottom: 10px;">
                                <div class="row form-group">
                                    <div class="col-md-4 text-right"><strong>Departamento:</strong></div>
                                    <div class="col-md-7">
                                        <?php echo form_dropdown('', $depto, $depto_selecionado, 'id="depto" class="form-control input-sm"'); ?>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-4 text-right"><strong>Diretoria de ensino/prefeitura:</strong>
                                    </div>
                                    <div class="col-md-7">
                                        <?php echo form_dropdown('id_diretoria', $id_diretoria, '', 'id="id_diretoria" class="form-control input-sm"'); ?>
                                    </div>
                                </div>
                                <div class="form-body">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#manha" aria-controls="manha" role="tab" data-toggle="tab">Período
                                                manhã</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#tarde" aria-controls="tarde" role="tab" data-toggle="tab">Período
                                                tarde</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#noite" aria-controls="noite" role="tab" data-toggle="tab">Período
                                                noite</a>
                                        </li>
                                    </ul>
                                    <br>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="manha">
                                            <div class="row form-group">
                                                <?php echo form_multiselect('id_manha[]', $escolas_manha, array(), 'id="id_manha" class="demo2" size="8"') ?>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="tarde">
                                            <div class="row form-group">
                                                <?php echo form_multiselect('id_tarde[]', $escolas_tarde, array(), 'id="id_tarde" class="demo2" size="8"') ?>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="noite">
                                            <div class="row form-group">
                                                <?php echo form_multiselect('id_noite[]', $escolas_noite, array(), 'id="id_noite" class="demo2" size="8"') ?>
                                            </div>
                                        </div>
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
require_once APPPATH . 'views/end_js.php';
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
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table, demo2;

        //    $('.data_reajuste').mask('00/00/0000');
        //    $('.valor').mask('##.###.##0,00', {reverse: true});

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 500,
                'lengthMenu': [[5, 10, 25, 50, 100, 250, 500], [5, 10, 25, 50, 100, 250, 500]],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('cd/supervisores/ajax_list') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '33%',
                        'targets': [0, 1]
                    },
                    {
                        'className': 'text-center',
                        'targets': [2]
                    },
                    {
                        'width': '34%',
                        'targets': [3]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ],
                'rowsGroup': [0, 1, -1, 2]
            });

            demo2 = $('.demo2').bootstrapDualListbox({
                'nonSelectedListLabel': 'Unidades disponíveis',
                'selectedListLabel': 'Unidades selecionadas',
                'preserveSelectionOnMove': 'moved',
                'moveOnSelect': false,
                'filterPlaceHolder': 'Filtrar',
                'helperSelectNamePostfix': false,
                'selectorMinimalHeight': 132,
                'infoText': false
            });

        });

        $('#depto, #id_supervisor, #id_diretoria').on('mousedown', function (e) {
            if (save_method === 'update') {
                e.preventDefault();
            }
        });


        $('#depto').on('change', function () {
            $.ajax({
                'url': '<?php echo site_url('cd/supervisores/atualizar_supervisores') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'depto': $('#depto').val()
                },
                'success': function (json) {
                    $('#id_supervisor').html($(json.id_supervisor).html());
                    $('#depto').html($(json.depto).html());
                    $('#id_diretoria').html($(json.id_diretoria).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });


        $('#id_supervisor, #id_diretoria').on('change', function () {
            $.ajax({
                'url': '<?php echo site_url('cd/supervisores/atualizar_unidades') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_diretoria': $('#id_diretoria').val(),
                    'id_supervisor': $('#id_supervisor').val()
                },
                'success': function (json) {
                    if (json.erro !== undefined) {
                        alert(json.erro);
                        $('#id_diretoria, #id_supervisor').val('')
                        $('#modal_form').modal('hide');
                    } else {
                        $('#id_manha').html(json.id_manha).trigger('change');
                        $('#id_tarde').html(json.id_tarde).trigger('change');
                        $('#id_noite').html(json.id_noite).trigger('change');

                        demo2.bootstrapDualListbox('refresh', true);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        $('#id_manha').on('change', function () {
            var count = $('#id_manha :selected').length;
            if (count > 0) {
                $('.nav-tabs li:eq(0) a').html('Período manhã <span class="badge">' + count + '</span>');
            } else {
                $('.nav-tabs li:eq(0) a').html('Período manhã');
            }
        });

        $('#id_tarde').on('change', function () {
            var count = $('#id_tarde :selected').length;
            if (count > 0) {
                $('.nav-tabs li:eq(1) a').html('Período tarde <span class="badge">' + count + '</span>');
            } else {
                $('.nav-tabs li:eq(1) a').html('Período tarde');
            }
        });

        $('#id_noite').on('change', function () {
            var count = $('#id_noite :selected').length;
            if (count > 0) {
                $('.nav-tabs li:eq(2) a').html('Período noite <span class="badge">' + count + '</span>');
            } else {
                $('.nav-tabs li:eq(2) a').html('Período noite');
            }
        });


        function atualizarFiltro() {
            $.ajax({
                'url': '<?php echo site_url('cd/supervisores/atualizar_filtro') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'success': function (json) {
                    $('[name="busca[diretoria]"]').html($(json.diretoria).html());
                    $('[name="busca[supervisor]"]').html($(json.supervisor).html());
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
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

        function add_supervisores() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('[name="tipo"] option').prop('disabled', false);
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#depto').val('<?= $depto_selecionado ?>').change();
            $('#id_diretoria, #id_supervisor').val('');
            $('#depto, #id_diretoria, #id_supervisor').css('background-color', '#fff');
            $('#depto, #id_diretoria, #id_supervisor, #id').find('option').prop('disabled', false);
            $('#id_manha, #id_tarde, #id_noite').val('').change();
            demo2.bootstrapDualListbox('refresh', true);

            $('#modal_form').modal('show'); // show bootstrap modal
            $('.combo_nivel1').hide();
        }

        function edit_supervisores(id_diretoria, id_supervisor) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            $.ajax({
                'url': '<?php echo site_url('cd/supervisores/ajax_edit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'diretoria': id_diretoria,
                    'supervisor': id_supervisor
                },
                'success': function (json) {
                    $('#depto').html($(json.depto).html());
                    $('#id_supervisor').html($(json.id_supervisor).html());
                    $('#id_diretoria').html($(json.id_diretoria).html());
                    $('#depto option:not(:selected), #id_diretoria option:not(:selected), #id_supervisor option:not(:selected)').prop('disabled', true);
                    $('#depto, #id_diretoria, #id_supervisor').css('background-color', '#eee');
                    $('#id_manha').html(json.id_manha).trigger('change');
                    $('#id_tarde').html(json.id_tarde).trigger('change');
                    $('#id_noite').html(json.id_noite).trigger('change');
                    demo2.bootstrapDualListbox('refresh', true);

                    $('#form .nav-tabs a:first').tab('show');
                    $('#modal_form').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }


        function save() {
            $.ajax({
                'url': '<?php echo site_url('cd/supervisores/ajax_save') ?>',
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSave').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_supervisores(id_diretoria, id_supervisor) {
            if (confirm('Deseja desvincular o supervisor das unidades pertencentes à diretoria de ensino?')) {
                if (confirm('Tem certeza? Esta ação não poderá ser desfeita.')) {
                    $.ajax({
                        'url': '<?php echo site_url('cd/supervisores/ajax_delete') ?>',
                        'type': 'POST',
                        'dataType': 'json',
                        'data': {
                            'id_diretoria': id_diretoria,
                            'id_supervisor': id_supervisor
                        },
                        'success': function (json) {
                            $('#modal_form').modal('hide');
                            reload_table();
                        },
                        'error': function (jqXHR, textStatus, errorThrown) {
                            alert('Error deleting data');
                        }
                    });
                }
            }
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>