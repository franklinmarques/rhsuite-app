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
                        <?php if ($tipo == 'perfil'): ?>
                            <li class="active">Gestão do Perfil Profissional</li>
                        <?php elseif ($tipo == 'lifo'): ?>
                            <li class="active">Testes de Personalidade - Orientações Para a Vida</li>
                        <?php else: ?>
                            <li class="active">Gestão do Clima Organizacional</li>
                        <?php endif; ?>
                    </ol>
                    <button class="btn btn-info" onclick="add_pesquisa()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar avaliação
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th colspan="4" class="text-center">Avaliação</th>
                            <th colspan="2" class="text-center">Colaborador(a) avaliado(a)</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Início</th>
                            <th>Término</th>
                            <th>Ações</th>
                            <th>Nome</th>
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
                            <h3 class="modal-title">Adicionar avaliação</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_pesquisa"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome avaliação</label>
                                        <div class="col-md-9">
                                            <input name="nome" id="nome" placeholder="Nome da pesquisa"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Modelo avaliação</label>
                                        <div class="col-md-9">
                                            <select name="id_modelo" class="form-control">
                                                <option value="">-- selecione --</option>
                                                <?php foreach ($modelos as $modelo) { ?>
                                                    <option value="<?= $modelo->id ?>"><?= $modelo->nome ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Data de início</label>
                                        <div class="col-md-3">
                                            <input name="data_inicio" id="data_inicio" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-2">Data de término</label>
                                        <div class="col-md-3">
                                            <input name="data_termino" id="data_termino" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="well well-sm">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="control-label">Filtrar por
                                                            departamento</label>
                                                        <?php echo form_dropdown('', $depto, '', 'id="depto" class="form-control filtro input-sm"'); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="control-label">Filtrar por área</label>
                                                        <?php echo form_dropdown('', $area, '', 'id="area" class="form-control filtro input-sm"'); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="control-label">Filtrar por setor</label>
                                                        <?php echo form_dropdown('', $setor, '', 'id="setor" class="form-control filtro input-sm"'); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="control-label">Filtrar por cargo</label>
                                                        <?php echo form_dropdown('', $cargo, '', 'id="cargo" class="form-control filtro input-sm"'); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="control-label">Filtrar por função</label>
                                                        <?php echo form_dropdown('', $funcao, '', 'id="funcao" class="form-control filtro input-sm"'); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>&nbsp;</label><br>
                                                        <div class="btn-group" role="group" aria-label="...">
                                                            <button type="submit" id="limpa_filtro"
                                                                    class="btn btn-sm btn-default">Limpar filtros
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group" id="id_avaliadores">
                                        <?php echo form_multiselect('avaliador[]', $avaliadores, array(), 'size="10" id="avaliadores" class="demo2"'); ?>
                                    </div>
                                    <div class="row form-group" id="id_avaliador">
                                        <label class="control-label col-md-2">Colaborador avaliado</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_avaliador', $avaliador, '', 'id="avaliador" class="form-control"'); ?>
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
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Testes de Personalidade - Orientações Para a Vida';
        });</script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table, demo2;
        var avaliadores = [];
        var avaliadores_salvos = [];

        $('#data_inicio, #data_termino').mask('00/00/0000');

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('pesquisa/ajaxLifo/' . $empresa) ?>",
                    "type": "POST"
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '50%',
                        targets: [0, 4]
                    },
                    {
                        className: 'text-center',
                        targets: [1, 2]
                    },
                    {
                        mRender: function (data) {
                            if (data === null) {
                                data = '<span class="text-muted">Nenhum(a) colaborador(a) encontrado(a)</span>';
                            }
                            return data;
                        },
                        targets: [3]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1, -3], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ],
                rowsGroup: [0, 1, 2, 3]
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
                nonSelectedListLabel: 'Colaboradores habilitados',
                selectedListLabel: 'Colaboradores avaliados',
                moveOnSelect: false,
                helperSelectNamePostfix: false,
                selectorMinimalHeight: 182,
                filterPlaceHolder: 'Filtrar',
                infoText: false
            });
        });

        $('#avaliadores').on('change', function () {
            avaliadores = $(this).val();
        });

        $('.filtro').on('change', function () {
            filtra_colaboradores();
        });

        $('#limpa_filtro').on('click', function () {
            $('.filtro').val('');
            filtra_colaboradores();
        });

        function filtra_colaboradores() {
            $.ajax({
                url: "<?php echo site_url('pesquisa_avaliados/ajax_avaliadores/') ?>/",
                type: "POST",
                dataType: "JSON",
                data: {
                    depto: $('#depto').val(),
                    area: $('#area').val(),
                    setor: $('#setor').val(),
                    cargo: $('#cargo').val(),
                    funcao: $('#funcao').val(),
                    selecionados: avaliadores.join(',')
                },
                success: function (data) {
                    $('#avaliadores').html(data.avaliadores).val(avaliadores);
                    demo2.bootstrapDualListbox('refresh', true);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function add_pesquisa() {
            save_method = 'add';
            avaliadores_salvos = [];
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('#id_avaliador').hide();
            $('#id_avaliadores').show();
            $('#avaliador').prop('disabled', true);
            $('#avaliadores').prop('disabled', false);
            $('#modal_form .modal-title').text('Adicionar avaliação'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_pesquisa(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('pesquisa/ajax_editLifo/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (json) {
                    $('[name="id_pesquisa"]').val(json.id);
                    $('[name="id_modelo"]').val(json.id_modelo);
                    $('[name="nome"]').val(json.nome);
                    $('[name="data_inicio"]').val(json.data_inicio);
                    $('[name="data_termino"]').val(json.data_termino);
                    $('#id_avaliador').hide();
                    $('#id_avaliadores').show();
                    $('#avaliador').prop('disabled', true);
                    $('#avaliadores').prop('disabled', false).html(json.avaliadores);
                    demo2.bootstrapDualListbox('refresh', true);
                    avaliadores_salvos = $('#avaliadores').val();
                    avaliadores = avaliadores_salvos;

                    $('#modal_form').modal('show');
                    $('#modal_form .modal-title').text('Editar avaliação'); // Set title to Bootstrap modal title
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
            var url;
            if (save_method === 'add') {
                url = "<?php echo site_url('pesquisa/ajax_addLifo') ?>";
            } else if (save_method === 'update') {
                url = "<?php echo site_url('pesquisa/ajax_updateLifo') ?>";
                var total_avaliadores_mantidos = avaliadores_salvos.filter(function (n) {
                    return avaliadores.indexOf(n) > -1;
                }).length;
                if (avaliadores_salvos.length !== total_avaliadores_mantidos) {
                    if (!confirm('Alguns avaliadores foram removidos. Deseja continuar?')) {
                        return false;
                    }
                }
            }
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
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

        function salvar_participantes() {
            $('#btnSaveParticipantes').text('Salvando...'); //change button text
            $('#btnSaveParticipantes').attr('disabled', true); //set button disable
            var url;
            if (save_method === 'add') {
                url = "<?php echo site_url('pesquisa/ajax_addParticipantes') ?>";
            } else {
                url = "<?php echo site_url('pesquisa/ajax_updateParticipantes') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_participantes').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_participantes').modal('hide');
                        reload_table();
                    }

                    $('#btnSaveParticipantes').text('Salvar'); //change button text
                    $('#btnSaveParticipantes').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveParticipantes').text('Salvar'); //change button text
                    $('#btnSaveParticipantes').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_pesquisa(id) {
            if (confirm('Deseja remover a avaliação?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('pesquisa/ajax_delete') ?>/" + id,
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

        function delete_avaliador(id) {
            if (confirm('Deseja remover o(a) colaborador(a) da avaliação?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('pesquisa/ajax_deleteLifo') ?>/" + id,
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

        function ver_modelos() {
            location.href = "<?php echo site_url('pesquisa_modelos/' . $tipo); ?>";
        }

    </script>

<?php
require_once "end_html.php";
?>