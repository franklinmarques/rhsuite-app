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


<br>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-success" onclick="add_avaliado()"><i class="glyphicon glyphicon-plus"></i> Adicionar
            avaliados x avaliadores
        </button>
        <?php if ($id_avaliado): ?>
            <button class="btn btn-success" onclick="ver_modelos()"><i class="glyphicon glyphicon-list-alt"></i> Ver
                modelos de avaliação
            </button>
        <?php endif; ?>
        <br/>
        <br/>
        <?php if ($id_avaliado): ?>
            <table id="table_avaliacao" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Modelo de avaliação</th>
                    <th>Data programada</th>
                    <th>Avaliador(a)</th>
                    <th>Data de realização</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        <?php else: ?>
            <table id="table_avaliacao" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Colaborador avaliado</th>
                    <th>Cargo/função</th>
                    <th>Depto/área/setor</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<!-- page end-->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_avaliacao" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
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
                                        <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">Limpar
                                            filtros
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="#" id="form_avaliacao" class="form-horizontal">
                    <div class="form-body">
                        <?php if ($tipo == '1'): ?>
                            <div class="row form-group">
                                <div class="col-xs-9 alert alert-info" style="padding: 7px 12px;">
                                    <div class="row">
                                        <div class="col col-xs-6 text-center" id="data_inicio">Data de
                                            início: <?= $data_inicio; ?></div>
                                        <div class="col col-xs-6 text-center" id="data_termino">Data de
                                            término: <?= $data_termino; ?></div>
                                    </div>
                                </div>
                                <div class="col col-xs-3" style="padding-right: 0;">
                                    <p class="text-right">
                                        <button type="button" id="btnSaveAvaliacao" onclick="save()" class="btn btn-primary">
                                            Salvar
                                        </button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar
                                        </button>
                                    </p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row form-group">
                                <div class="col col-xs-3 col-xs-offset-9" style="padding-right: 0;">
                                    <p class="text-right">
                                        <button type="button" id="btnSaveAvaliacao" onclick="save()" class="btn btn-primary">
                                            Salvar
                                        </button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar
                                        </button>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($id_avaliado): ?>
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="<?= $id_avaliado; ?>" name="id_avaliado"/>
                            <input type="hidden" value="" name="id_avaliacao"/>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Modelo de avaliação</label>
                                <div class="col-md-6">
                                    <?php echo form_dropdown('id_modelo', $id_modelo, '', 'class="form-control"') ?>
                                </div>
                                <label class="control-label col-md-2">Nota de corte</label>
                                <div class="col-md-2">
                                    <input name="nota_corte" id="nota_corte" class="form-control" type="number">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="<?= $id_modelo; ?>" name="id_modelo"/>
                            <input type="hidden" value="<?= $id_avaliacao; ?>" name="id_avaliacao"/>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Colaborador avaliado</label>
                                <div class="col-md-6">
                                    <?php echo form_dropdown('id_avaliado', $colaboradores, '', 'id="id_avaliado" class="form-control"') ?>
                                </div>
                                <label class="control-label col-md-2">Nota de corte</label>
                                <div class="col-md-2">
                                    <input name="nota_corte" id="nota_corte" class="form-control" type="number">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($tipo == '2'): ?>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Supervisor(a)</label>
                                <div class="col-md-6">
                                    <?php echo form_dropdown('id_supervisor', $colaboradores, '', 'id="id_supervisor" class="form-control"') ?>
                                </div>
                                <label class="control-label col-md-2">Data de início de atividades</label>
                                <div class="col-md-2">
                                    <input name="data_atividades" id="data_atividades" placeholder="dd/mm/aaaa"
                                           class="form-control" type="text">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="row form-group avaliador">
                            <input type="hidden" value="" name="id_avaliador[]"/>
                            <label class="control-label col-md-2">Avaliador(a) 1</label>
                            <div class="col-md-6">
                                <?php echo form_dropdown('avaliador[]', $colaboradores, '', 'class="form-control"') ?>
                            </div>
                            <label class="control-label col-md-2">Data 1&ordf; avaliação</label>
                            <div class="col-md-2">
                                <input name="data_avaliacao[]" placeholder="dd/mm/aaaa"
                                       class="form-control data_avaliacao" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="row form-group avaliador">
                            <input type="hidden" value="" name="id_avaliador[]"/>
                            <label class="control-label col-md-2">Avaliador(a) 2</label>
                            <div class="col-md-6">
                                <?php echo form_dropdown('avaliador[]', $colaboradores, '', 'class="form-control"') ?>
                            </div>
                            <label class="control-label col-md-2">Data 2&ordf; avaliação</label>
                            <div class="col-md-2">
                                <input name="data_avaliacao[]" placeholder="dd/mm/aaaa"
                                       class="form-control data_avaliacao" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="row form-group avaliador">
                            <input type="hidden" value="" name="id_avaliador[]"/>
                            <label class="control-label col-md-2">Avaliador(a) 3</label>
                            <div class="col-md-6">
                                <?php echo form_dropdown('avaliador[]', $colaboradores, '', 'class="form-control"') ?>
                            </div>
                            <label class="control-label col-md-2">Data 3&ordf; avaliação</label>
                            <div class="col-md-2">
                                <input name="data_avaliacao[]" placeholder="dd/mm/aaaa"
                                       class="form-control data_avaliacao" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
<script>
    var save_method; //for save method string
    var table;
    var avaliado, supervisor, avaliador1, avaliador2, avaliador3;

    var id_avaliado = <?= ($id_avaliado ? 'true' : 'false') ?>;

    $('#data_atividades, .data_avaliacao').mask('00/00/0000');

    $(document).ready(function () {

        if (id_avaliado === true) {
            var url = "<?php echo site_url('avaliacaoexp/ajax_avaliado/' . $id_avaliado) ?>";
        } else {
            var url = "<?php echo site_url('avaliacaoexp_avaliados/ajax_list/' . $id_avaliacao) ?>";
        }

        //datatables
        table = $('#table_avaliacao').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": url,
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: id_avaliado === true ? '50%' : '34%',
                    targets: [0]
                },
                {
                    width: id_avaliado === true ? '50%' : '33%',
                    targets: id_avaliado === true ? [2] : [1, 2]
                },
                {
                    className: "text-nowrap",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                }
            ],
            rowsGroup: [0, -1]
        });

    });

    $('#data_atividades').on('change', function () {
        var str = this.value.split('/');
        var date = new Date(this.value.replace(/(\d{2})\/(\d{2})\/(\d{4})/, '$2/$1/$3'));
        if ((isNaN(date.getDate()) || parseInt(str[0]) !== date.getDate())) {
            $('.data_avaliacao').val('');
            return false;
        }
        date.setDate(date.getDate() + 25);
        $('.data_avaliacao:eq(0)').val(('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear());
        date.setDate(date.getDate() + 25);
        $('.data_avaliacao:eq(1)').val(('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear());
        date.setDate(date.getDate() + 20);
        $('.data_avaliacao:eq(2)').val(('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear());
    });

    $('#id_avaliado').on('change', function () {
        avaliado = $(this).val();
    });
    $('#id_supervisor').on('change', function () {
        supervisor = $(this).val();
    });

    $('.avaliador:eq(0) select[name="avaliador[]"]').on('change', function () {
        avaliador1 = $(this).val();
    });
    $('.avaliador:eq(1) select[name="avaliador[]"]').on('change', function () {
        avaliador2 = $(this).val();
    });
    $('.avaliador:eq(2) select[name="avaliador[]"]').on('change', function () {
        avaliador3 = $(this).val();
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
            url: "<?php echo site_url('avaliacaoexp_avaliados/ajax_avaliadores/') ?>/",
            type: "POST",
            dataType: "JSON",
            data: $('.filtro').serialize(),
            success: function (data) {
                $('#id_avaliado').html(data.avaliado);
                if ($('#id_avaliado option[value="' + avaliado + '"]').length > 0) {
                    $('#id_avaliado').val(avaliado);
                } else {
                    $('#id_avaliado').val('');
                }
                if (save_method === 'update') {
                    $('[name="avaliado"] option').prop('disabled', true);
                    $('[name="avaliado"] option:selected').prop('disabled', false);
                } else {
                    $('[name="avaliado"] option').prop('disabled', false);
                }

                $('#id_supervisor').html(data.supervisor);
                if ($('#id_supervisor option[value="' + supervisor + '"]').length > 0) {
                    $('#id_supervisor').val(supervisor);
                } else {
                    $('#id_supervisor').val('');
                }

                $('.avaliador').find('select[name="avaliador[]"]').html(data.avaliador);
                if ($('.avaliador:eq(0) select[name="avaliador[]"] option[value="' + avaliador1 + '"]').length > 0) {
                    $('.avaliador:eq(0) select[name="avaliador[]"]').val(avaliador1);
                } else {
                    $('.avaliador:eq(0) select[name="avaliador[]"]').val('');
                }
                if ($('.avaliador:eq(1) select[name="avaliador[]"] option[value="' + avaliador2 + '"]').length > 0) {
                    $('.avaliador:eq(1) select[name="avaliador[]"]').val(avaliador2);
                } else {
                    $('.avaliador:eq(1) select[name="avaliador[]"]').val('');
                }
                if ($('.avaliador:eq(2) select[name="avaliador[]"] option[value="' + avaliador3 + '"]').length > 0) {
                    $('.avaliador:eq(2) select[name="avaliador[]"]').val(avaliador3);
                } else {
                    $('.avaliador:eq(2) select[name="avaliador[]"]').val('');
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function add_avaliado() {
        save_method = 'add';
        avaliado = '';
        supervisor = '';
        avaliador1 = '';
        avaliador2 = '';
        avaliador3 = '';
        $('#form_avaliacao')[0].reset(); // reset form on modals
        if (id_avaliado === true) {
            $('#form_avaliacao input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
        } else {
            $('#form_avaliacao input[type="hidden"]:not([name="id_modelo"], [name="id_avaliacao"])').val(''); // reset hidden input form on modals
        }
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_avaliacao').modal('show');
        $('.modal-title').text('Adicionar avaliado e avaliadores'); // Set title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_avaliado(id) {
        save_method = 'update';
        $('#form_avaliacao')[0].reset(); // reset form on modals
        $('#form_avaliacao input[type="hidden"]:not([name="id_avaliacao"])').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_avaliados/ajax_edit/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('[name="id"]').val(data.id);
                $('[name="id_modelo"]').val(data.id_modelo);
                $('[name="id_avaliado"]').val(data.id_avaliado);
                $('[name="id_supervisor"]').val(data.id_supervisor);
                $('[name="data_atividades"]').val(data.data_atividades);
                $('[name="nota_corte"]').val(data.nota_corte);
                $('[name="id_avaliacao"]').val(data.id_avaliacao);

                $(data.avaliadores).each(function (index, field) {
                    $('.avaliador:eq(' + index + ') input[name="id_avaliador[]"]').val(field.id);
                    $('.avaliador:eq(' + index + ') select[name="avaliador[]"]').val(field.id_avaliador);
                    $('.avaliador:eq(' + index + ') input[name="data_avaliacao[]"]').val(field.data_avaliacao);
                });
                $('.filtro').val('');
                avaliado = $('#id_avaliado').val();
                supervisor = $('#id_supervisor').val();
                avaliador1 = $('.avaliador:eq(0) select[name="avaliador[]"]').val();
                avaliador2 = $('.avaliador:eq(1) select[name="avaliador[]"]').val();
                avaliador3 = $('.avaliador:eq(2) select[name="avaliador[]"]').val();

                $('#modal_avaliacao').modal('show');
                $('.modal-title').text('Editar  avaliado e avaliadores'); // Set title to Bootstrap modal title
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
        $('#btnSaveAvaliacao').text('Salvando...'); //change button text
        $('#btnSaveAvaliacao').attr('disabled', true); //set button disable 
        var url;
        if (save_method === 'add') {
            url = "<?php echo site_url('avaliacaoexp_avaliados/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('avaliacaoexp_avaliados/ajax_update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form_avaliacao').serialize(),
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_avaliacao').modal('hide');
                    reload_table();
                    notificar(data.avaliacao);
                }

                $('#btnSaveAvaliacao').text('Salvar'); //change button text
                $('#btnSaveAvaliacao').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveAvaliacao').text('Salvar'); //change button text
                $('#btnSaveAvaliacao').attr('disabled', false); //set button enable 
            }
        });
    }

    function delete_avaliado(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliados/ajax_delete') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    //if success reload ajax table
                    $('#modal_avaliacao').modal('hide');
                    reload_table();
                    notificar(data.avaliacao);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }
    }

    function notificar(data) {
        if (data === null || data === undefined) {
            return false;
        }
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_avaliados/ajax_notificar/') ?>/",
            type: "POST",
            dataType: "JSON",
            data: {
                modelo: data.modelo,
                avaliado: data.avaliado,
                avaliadores: data.avaliadores
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function ver_modelos() {
        location.href = "<?php echo site_url('avaliacaoexp_modelos/gerenciar/2'); ?>";
    }

</script>
