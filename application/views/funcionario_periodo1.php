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
        <div class="row">
            <div class="col-md-7">
                <button class="btn btn-info" onclick="add_periodo()"><i class="glyphicon glyphicon-plus"></i>
                    Adicionar
                    avaliados x avaliadores
                </button>
                <?php if ($id_usuario): ?>
                    <button class="btn btn-primary" onclick="ver_modelos()"><i class="glyphicon glyphicon-list-alt"></i>
                        Modelos de avaliação
                    </button>
                <?php endif; ?>
                <button class="btn btn-info" data-toggle="modal" data-target="#modal_periodo_obs"><i
                            class="glyphicon glyphicon-pencil"></i> Obs.
                </button>
            </div>
            <div class="col-md-5">
                <textarea id="observacoes_avaliacao_exp2" rows="1" class="form-control" placeholder="Observações"
                          readonly autocomplete="off"><?= $observacoes_avaliacao_exp; ?></textarea>
            </div>
        </div>
        <br/>
        <br/>
        <table id="table_periodo" class="table table-striped table-bordered"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th rowspan="2">Modelo de avaliação</th>
                <th rowspan="2">Data programada</th>
                <th colspan="2">Avaliador(a)</th>
                <th rowspan="2">Data de realização</th>
                <th rowspan="2">Ações</th>
            </tr>
            <tr>
                <th>Nome</th>
                <th>Ação</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<!-- page end-->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_periodo" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Formulario de Período de Experiência</h3>
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
                <form action="#" id="form_periodo" class="form-horizontal">
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
                                        <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                            Salvar
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                        </button>
                                    </p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row form-group">
                                <div class="col col-xs-3 col-xs-offset-9" style="padding-right: 0;">
                                    <p class="text-right">
                                        <button type="button" id="btnSavePeriodo" onclick="save_periodo()"
                                                class="btn btn-success">Salvar
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
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
                                           class="data form-control text-center" type="text">
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
                                       class="data form-control data_avaliacao text-center" type="text">
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
                                       class="data form-control data_avaliacao text-center" type="text">
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
                                       class="data form-control data_avaliacao text-center" type="text">
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

<div class="modal fade" id="modal_periodo_obs" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Editar observações</h3>
            </div>
            <div class="modal-body form">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-1">
                            <textarea id="observacoes_avaliacao_exp" rows="3"
                                      class="form-control"><?= $observacoes_avaliacao_exp; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSavePeriodoObs" onclick="save_periodo_obs()" class="btn btn-success">
                    Salvar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script>
    var table_periodo;
    var save_method; //for save method string
    var avaliado, supervisor, avaliador1, avaliador2, avaliador3;

    $('.data').mask('00/00/0000');

    var id_avaliado = <?= ($id_avaliado ? 'true' : 'false') ?>;

    //datatables
    table_periodo = $('#table_periodo').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "language": {
            "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('avaliacaoexp/ajax_avaliado/' . $id_usuario) ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            {
                width: '50%',
                targets: [0, 2]
            },
            {
                className: "text-nowrap",
                "targets": [-1], //last column
                "orderable": false, //set not orderable
                "searchable": false //set not orderable
            }
        ],
        rowsGroup: [-1, 0]
    });

    $('#form_periodo #data_atividades').on('change', function () {
        var str = this.value.split('/');
        var date = new Date(this.value.replace(/(\d{2})\/(\d{2})\/(\d{4})/, '$2/$1/$3'));
        if ((isNaN(date.getDate()) || parseInt(str[0]) !== date.getDate())) {
            $('#form_periodo .data_avaliacao').val('');
            return false;
        }
        date.setDate(date.getDate() + 25);
        $('#form_periodo .data_avaliacao:eq(0)').val(('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear());
        date.setDate(date.getDate() + 25);
        $('#form_periodo .data_avaliacao:eq(1)').val(('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear());
        date.setDate(date.getDate() + 20);
        $('#form_periodo .data_avaliacao:eq(2)').val(('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear());
    });

    $('#form_periodo #id_avaliado').on('change', function () {
        avaliado = $(this).val();
    });
    $('#form_periodo #id_supervisor').on('change', function () {
        supervisor = $(this).val();
    });

    $('#form_periodo .avaliador:eq(0) select[name="avaliador[]"]').on('change', function () {
        avaliador1 = $(this).val();
    });
    $('#form_periodo .avaliador:eq(1) select[name="avaliador[]"]').on('change', function () {
        avaliador2 = $(this).val();
    });
    $('#form_periodo .avaliador:eq(2) select[name="avaliador[]"]').on('change', function () {
        avaliador3 = $(this).val();
    });

    $('#modal_periodo .filtro').on('change', function () {
        filtra_participantes();
    });

    $('#modal_periodo #limpa_filtro').on('click', function () {
        $('#modal_periodo .filtro').val('');
        filtra_participantes();
    });

    function filtra_participantes() {
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_avaliados/ajax_avaliadores/') ?>/",
            type: "POST",
            dataType: "JSON",
            data: $('#modal_periodo .filtro').serialize(),
            success: function (data) {
                $('#form_periodo #id_avaliado').html(data.avaliado);
                if ($('#form_periodo #id_avaliado option[value="' + avaliado + '"]').length > 0) {
                    $('#form_periodo #id_avaliado').val(avaliado);
                } else {
                    $('#form_periodo #id_avaliado').val('');
                }
                if (save_method === 'update') {
                    $('#form_periodo [name="avaliado"] option').prop('disabled', true);
                    $('#form_periodo [name="avaliado"] option:selected').prop('disabled', false);
                } else {
                    $('#form_periodo [name="avaliado"] option').prop('disabled', false);
                }

                $('#form_periodo #id_supervisor').html(data.supervisor);
                if ($('#form_periodo #id_supervisor option[value="' + supervisor + '"]').length > 0) {
                    $('#form_periodo #id_supervisor').val(supervisor);
                } else {
                    $('#form_periodo #id_supervisor').val('');
                }

                $('#form_periodo .avaliador').find('select[name="avaliador[]"]').html(data.avaliador);
                if ($('#form_periodo .avaliador:eq(0) select[name="avaliador[]"] option[value="' + avaliador1 + '"]').length > 0) {
                    $('#form_periodo .avaliador:eq(0) select[name="avaliador[]"]').val(avaliador1);
                } else {
                    $('#form_periodo .avaliador:eq(0) select[name="avaliador[]"]').val('');
                }
                if ($('#form_periodo .avaliador:eq(1) select[name="avaliador[]"] option[value="' + avaliador2 + '"]').length > 0) {
                    $('#form_periodo .avaliador:eq(1) select[name="avaliador[]"]').val(avaliador2);
                } else {
                    $('#form_periodo .avaliador:eq(1) select[name="avaliador[]"]').val('');
                }
                if ($('#form_periodo .avaliador:eq(2) select[name="avaliador[]"] option[value="' + avaliador3 + '"]').length > 0) {
                    $('#form_periodo .avaliador:eq(2) select[name="avaliador[]"]').val(avaliador3);
                } else {
                    $('#form_periodo .avaliador:eq(2) select[name="avaliador[]"]').val('');
                }

                /*
                if ($('#form_periodo ' + avaliador1 + '"]').length > 0) {
                    $('#form_periodo .avaliador:eq(0) select[name="avaliador[]"]').val(avaliador1);
                } else {
                    $('#form_periodo .avaliador:eq(0) select[name="avaliador[]"]').val('');
                }
                if ($('#form_periodo .avaliador:eq(1) select[name="avaliador[]"] option[value="' + avaliador2 + '"]').length > 0) {
                    $('#form_periodo .avaliador:eq(1) select[name="avaliador[]"]').val(avaliador2);
                } else {
                    $('#form_periodo .avaliador:eq(1) select[name="avaliador[]"]').val('');
                }
                if ($('#form_periodo .avaliador:eq(2) select[name="avaliador[]"] option[value="' + avaliador3 + '"]').length > 0) {
                    $('#form_periodo .avaliador:eq(2) select[name="avaliador[]"]').val(avaliador3);
                } else {
                    $('#form_periodo .avaliador:eq(2) select[name="avaliador[]"]').val('');
                }*/

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function add_periodo() {
        save_method = 'add';
        avaliado = '';
        supervisor = '';
        avaliador1 = '';
        avaliador2 = '';
        avaliador3 = '';
        $('#form_periodo')[0].reset(); // reset form on modals
        if (id_avaliado === true) {
            $('#form_periodo input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
        } else {
            $('#form_periodo input[type="hidden"]:not([name="id_modelo"], [name="id_avaliacao"])').val(''); // reset hidden input form on modals
        }
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_periodo').modal('show');
        $('.modal-title').text('Adicionar avaliado e avaliadores'); // Set title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_avaliado(id) {
        save_method = 'update';
        $('#form_periodo')[0].reset(); // reset form on modals
        $('#form_periodo input[type="hidden"]:not([name="id_avaliacao"])').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_avaliados/ajax_edit/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#form_periodo [name="id"]').val(data.id);
                $('#form_periodo [name="id_modelo"]').val(data.id_modelo);
                $('#form_periodo [name="id_avaliado"]').val(data.id_avaliado);
                $('#form_periodo [name="id_supervisor"]').val(data.id_supervisor);
                $('#form_periodo [name="data_atividades"]').val(data.data_atividades);
                $('#form_periodo [name="nota_corte"]').val(data.nota_corte);
                $('#form_periodo [name="id_avaliacao"]').val(data.id_avaliacao);

                $(data.avaliadores).each(function (index, field) {
                    $('#form_periodo .avaliador:eq(' + index + ') input[name="id_avaliador[]"]').val(field.id);
                    $('#form_periodo .avaliador:eq(' + index + ') select[name="avaliador[]"]').val(field.id_avaliador);
                    $('#form_periodo .avaliador:eq(' + index + ') input[name="data_avaliacao[]"]').val(field.data_avaliacao);
                });
                $('.filtro').val('');
                avaliado = $('#form_periodo #id_avaliado').val();
                supervisor = $('#form_periodo #id_supervisor').val();
                avaliador1 = $('#form_periodo .avaliador:eq(0) select[name="avaliador[]"]').val();
                avaliador2 = $('#form_periodo .avaliador:eq(1) select[name="avaliador[]"]').val();
                avaliador3 = $('#form_periodo .avaliador:eq(2) select[name="avaliador[]"]').val();

                $('#modal_periodo').modal('show');
                $('.modal-title').text('Editar  avaliado e avaliadores'); // Set title to Bootstrap modal title
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function save_periodo() {
        $('#btnSavePeriodo').text('Salvando...'); //change button text
        $('#btnSavePeriodo').attr('disabled', true); //set button disable
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
            data: $('#form_periodo').serialize(),
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_periodo').modal('hide');
                    reload_periodo();
                    // notificar(data.avaliacao);
                }

                $('#btnSavePeriodo').text('Salvar'); //change button text
                $('#btnSavePeriodo').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSavePeriodo').text('Salvar'); //change button text
                $('#btnSavePeriodo').attr('disabled', false); //set button enable
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
                    reload_periodo();
                    // notificar(data.avaliacao);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }
    }

    function reload_periodo() {
        table_periodo.ajax.reload(null, false);
    }

    //function notificar(data) {
    //    if (data === null || data === undefined) {
    //        return false;
    //    }
    //    $.ajax({
    //        url: "<?php //echo site_url('avaliacaoexp_avaliados/ajax_notificar/') ?>///",
    //        type: "POST",
    //        dataType: "JSON",
    //        data: {
    //            modelo: data.modelo,
    //            avaliado: data.avaliado,
    //            avaliadores: data.avaliadores
    //        },
    //        error: function (jqXHR, textStatus, errorThrown) {
    //            alert(textStatus);
    //        }
    //    });
    //}

    function notificarAvaliador(id = null) {
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_avaliados/notificarAvaliador') ?>",
            type: "POST",
            data: {id: id},
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

    function save_periodo_obs(id = null) {
        $('#btnSavePeriodoObs').text('Salvando...').attr('disabled', true); //change button text
        $.ajax({
            url: "<?php echo site_url('funcionario/salvarObservacoesAvaliacaoExp') ?>",
            type: "POST",
            data: {
                id: <?= $id_usuario ?>,
                observacoes_avaliacao_exp: $('#observacoes_avaliacao_exp').val()
            },
            dataType: "json",
            success: function (json) {
                $('#btnSavePeriodoObs').text('Salvar').attr('disabled', false); //change button text
                if (json.status) {
                    $('#observacoes_avaliacao_exp2').val($('#observacoes_avaliacao_exp').val());
                    $('#modal_periodo_obs').modal('hide');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Erro ao notificar colaboradores');
                $('#btnSavePeriodoObs').text('Salvar').attr('disabled', false); //change button text
            }
        });
    }

    function ver_modelos() {
        location.href = "<?php echo site_url('avaliacaoexp_modelos/gerenciar/2'); ?>";
    }
</script>
