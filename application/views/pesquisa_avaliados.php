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
                        <li><a href="<?= site_url('pesquisa/perfil') ?>">Pesquisa de Perfil Profissional</a></li>
                        <li class="active">Gestão de Perfil de Colaboradores</li>
                    </ol>
                    <button class="btn btn-success" onclick="add_participante()"><i
                                class="glyphicon glyphicon-plus"></i> Adicionar avaliados e pesquisados
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Colaborador pesquisado</th>
                            <th>Cargo/função</th>
                            <th>Depto/área/setor</th>
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
                            <h3 class="modal-title">Formulario de pesquisa</h3>
                        </div>
                        <div class="modal-body form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="control-label">Filtrar por departamento</label>
                                                <?php echo form_dropdown('depto', $depto, '', 'class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Filtrar por área</label>
                                                <?php echo form_dropdown('area', $area, '', 'class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Filtrar por setor</label>
                                                <?php echo form_dropdown('setor', $setor, '', 'class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Filtrar por cargo</label>
                                                <?php echo form_dropdown('cargo', $cargo, '', 'class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Filtrar por função</label>
                                                <?php echo form_dropdown('funcao', $funcao, '', 'class="form-control filtro input-sm"'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label>&nbsp;</label><br>
                                                <div class="btn-group" role="group" aria-label="...">
                                                    <button type="button" id="limpa_filtro"
                                                            class="btn btn-sm btn-default">Limpar filtros
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form action="#" id="form" class="form-horizontal">
                                <div class="form-body" style="padding: 0 20px 20px;">
                                    <input type="hidden" value="" name="id"/>
                                    <input type="hidden" value="<?= $pesquisa ?>" name="pesquisa"/>
                                    <div class="row">
                                        <div class="col col-xs-9 form-group">
                                            <label class="control-label">Colaborador pesquisado</label>
                                            <?php echo form_dropdown('avaliado', array('' => 'selecione...') + $avaliado, '', 'id="avaliado" class="form-control"') ?>
                                        </div>
                                        <div class="col col-xs-3" style="padding-right: 0;">
                                            <label class="control-label">&nbsp;</label>
                                            <p class="text-right">
                                                <button type="button" id="btnSave" onclick="save()"
                                                        class="btn btn-primary">Salvar
                                                </button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                    Cancelar
                                                </button>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <?php echo form_multiselect('avaliadores[]', $avaliadores, array(), 'size="10" id="avaliadores" class="demo2"') ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

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
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Avaliações por período de experiência - ';
        });</script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>

    <script>

        var save_method; //for save method string
        var table, demo2;
        var avaliado, avaliadores, selecionados;

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
                    "url": "<?php echo site_url('pesquisa_avaliados/ajax_list/' . $pesquisa) ?>",
                    "type": "POST"
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '40%',
                        targets: [0]
                    },
                    {
                        width: '30%',
                        targets: [1, 2]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ]
            });

            //datepicker
            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
                orientation: "top auto",
                todayBtn: true
            });

            demo2 = $('#avaliadores').bootstrapDualListbox({
                filterPlaceHolder: 'Filtrar',
                moveOnSelect: false,
                preserveSelectionOnMove: 'moved',
                selectedListLabel: 'Colaboradores pesquisados',
                nonSelectedListLabel: 'Colaboradores habilitados',
                helperSelectNamePostfix: false,
                selectorMinimalHeight: 182,
                infoText: false
            });

        });

        $('#avaliado').on('change', function () {
            avaliado = $(this).val();
        });

        $('#avaliadores').on('change', function () {
            avaliadores = $(this).val();
        });

        $('.filtro').on('change', function () {
            filtra_participantes();
        });

        $('#limpa_filtro').on('click', function () {
            $('.filtro').val('');
            filtra_participantes();
        });

        function filtra_participantes() {
            $.ajax({
                url: "<?php echo site_url('pesquisa_avaliados/ajax_avaliadores/') ?>/",
                type: "POST",
                dataType: "JSON",
                data: $('.filtro').serialize() + '&selecionados=' + avaliadores,
                success: function (data) {
                    $('#avaliado').html(data.avaliado);
                    if ($('#avaliado option[value="' + avaliado + '"]').length > 0) {
                        $('#avaliado').val(avaliado);
                    } else {
                        $('#avaliado').val('');
                    }
                    if (save_method === 'update') {
                        $('[name="avaliado"] option').prop('disabled', true);
                        $('[name="avaliado"] option:selected').prop('disabled', false);
                    } else {
                        $('[name="avaliado"] option').prop('disabled', false);
                    }
                    $('#avaliadores').html(data.avaliadores).val(avaliadores);

                    demo2.bootstrapDualListbox('refresh', true);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function add_participante() {
            save_method = 'add';
            avaliado = '';
            avaliadores = '';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="pesquisa"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            $.ajax({
                url: "<?php echo site_url('pesquisa_avaliados/ajax_avaliados/' . $pesquisa) ?>",
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $(data).each(function (index, value) {
                        $('[name="avaliado"] option[value="' + value + '"]').hide();
                    });
                    $('[name="avaliado"] option').prop('disabled', false);
                    $('.filtro').val('');
                    demo2.bootstrapDualListbox('refresh', true);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Adicionar avaliados e pesquisados'); // Set title to Bootstrap modal title
                    $('.combo_nivel1').hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_participante(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="pesquisa"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('pesquisa_avaliados/ajax_edit/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('[name="id"]').val(data.id);
                    $('[name="avaliado"]').val(data.avaliado);
                    $('[name="avaliado"] option').prop('disabled', true).show();
                    $('[name="avaliado"] option:selected').prop('disabled', false);
                    $('#avaliadores').val(data.avaliadores);
                    $('.filtro').val('');
                    demo2.bootstrapDualListbox('refresh', true);
                    avaliado = $('#avaliado').val();
                    avaliadores = $('#avaliadores').val();

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar  avaliados e pesquisados'); // Set title to Bootstrap modal title
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
                url = "<?php echo site_url('pesquisa_avaliados/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('pesquisa_avaliados/ajax_update') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.aviso) {
                        alert(data.aviso);
					} else if (data.status) //if success close modal and reload ajax table
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

        function delete_participante(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('pesquisa_avaliados/ajax_delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
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
require_once "end_html.php";
?>
