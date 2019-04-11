<?php require_once APPPATH . 'views/header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('ei/apontamento') ?>">Apontamentos diários</a></li>
                        <li class="active">Gerenciar semestres</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_semestre()"><i class="glyphicon glyphicon-plus"></i>
                        Alocar semestre
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>

                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Ano/semestre</th>
                            <th>Dia semana</th>
                            <th>Disciplina</th>
                            <th>Cuidador</th>
                            <th>Horário</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Alocar semestre</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2"><strong>Ano</strong><span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-3">
                                            <input name="ano" placeholder="aaaa" class="form-control text-center"
                                                   type="number"
                                                   min="1">
                                        </div>
                                        <label class="control-label col-md-3">Semestre<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col col-lg-2">
                                            <select name="semestre" class="form-control">
                                                <option value="1">1&ordm;</option>
                                                <option value="2">2&ordm;</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Início</label>
                                        <div class="col-md-3">
                                            <input name="data_inicio" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date"
                                                   type="text">
                                        </div>
                                        <label class="control-label col-md-2">Término</label>
                                        <div class="col-md-3">
                                            <input name="data_termino" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Módulo</label>
                                        <div class="col-md-8">
                                            <input name="modulo" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save_semestre()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal_turma" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Alocar turma</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_turma" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_semestre"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2"><strong>Disciplina</strong><span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_disciplina', $disciplinas, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cuidador</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_cuidador', $cuidadores, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Dia da semana</label>
                                        <div class="col-md-4">
                                            <select name="dia_semana" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="1">Segunda-feira</option>
                                                <option value="2">Terça-feira</option>
                                                <option value="3">Quarta-feira</option>
                                                <option value="4">Qinta-feira</option>
                                                <option value="5">Sexta-feira</option>
                                                <option value="6">Sábado</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Horário início</label>
                                        <div class="col-md-3">
                                            <input name="hora_inicio" placeholder="hh:mm"
                                                   class="form-control text-center hour"
                                                   type="text">
                                        </div>
                                        <label class="control-label col-md-2">Horário término</label>
                                        <div class="col-md-3">
                                            <input name="hora_termino" placeholder="hh:mm"
                                                   class="form-control text-center hour"
                                                   type="text">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveTurma" onclick="save_turma()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </section>

<?php require_once APPPATH . "views/end_js.php"; ?>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Turmas';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/extensions/dataTables.rowGroup.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;

        $(document).ready(function () {

            $('.date').mask('00/00/0000');
            $('.hour').mask('00:00');
            $('.nota').mask('##0,0');

            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 500,
                lengthChange: false,
                searching: false,
                orderFixed: [1, 'asc'],
                lengthMenu: [[5, 10, 25, 50, 100, 500, 1000], [5, 10, 25, 50, 100, 500, 1000]],
                language: {
                    url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                rowGroup: {
                    className: 'active',
                    startRender: function (rows, group) {
                        var col = rows.data().eq(0);
                        return '<strong>Ano/semestre: </strong>' + group + '&emsp;' +
                            '<strong>Início aulas: </strong>' + col[6] + '&emsp;' +
                            '<strong>Término aulas: </strong>' + col[7] + '&emsp;' +
                            '<strong>Módulo: </strong>' + col[8] + '&emsp;' +
                            '<div class="text-right">' + col[9] + '</div>';
                    },
                    dataSrc: 0
                },
                ajax: {
                    url: '<?php echo site_url('ei/semestres/ajax_list/') ?>',
                    type: 'POST',
                    data: function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                columnDefs: [
                    {
                        visible: false,
                        targets: [0, 2]
                    },
                    {
                        width: '100%',
                        targets: [3]
                    },
                    {
                        className: 'text-nowrap',
                        targets: [1]
                    },
                    {
                        className: 'text-center text-nowrap',
                        targets: [4]
                    },
                    {
                        className: 'text-center text-nowrap',
                        targets: [5],
                        orderable: false,
                        searchable: false
                    }
                ],
                rowsGroup: [0, 1, 2]
            });

        });

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
        $('#pesquisar').on('change', function () {
            atualizar_Filtro();
        });

        $('#id_diretoria').on('change', function () {
            atualizar_escolas();
        });

        $('#id_escola').on('change', function () {
            atualizar_periodos();
        });

        function atualizarFiltro() {
            $.ajax({
                url: '<?php echo site_url('ei/semestres/atualizar_filtro/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: $('#busca').serialize(),
                success: function (data) {
                    $('[name="busca[escola]"]').html($(data.escola).html());
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function atualizar_estrutura() {
            $.ajax({
                url: '<?php echo site_url('ei/semestres/ajax_estrutura/') ?>',
                type: 'POST',
                dataType: 'json',
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

        function atualizar_escolas() {
            $.ajax({
                url: '<?php echo site_url('ei/semestres/atualizar_escolas/') ?>',
                type: 'POST',
                dataType: 'html',
                data: {
                    id_diretoria: $('#id_diretoria').val(),
                    id_escola: $('#id_escola').val()
                },
                success: function (data) {
                    $('#id_escola').html($(data).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function atualizar_periodos() {
            var id = $('#id_escola').val()

            if (id.length > 0) {
                $.ajax({
                    url: '<?php echo site_url('ei/semestres/atualizar_periodos/') ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id
                    },
                    success: function (data) {
                        // $('#periodo_manha').prop('disabled', data.periodo_manha === '0');
                        // $('#periodo_tarde').prop('disabled', data.periodo_tarde === '0');
                        // $('#periodo_noite').prop('disabled', data.periodo_noite === '0');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            } else {
                $('#periodo_manha, #periodo_tarde, #periodo_noite').prop('disabled', false);
            }
        }

        function add_semestre() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form input[type="hidden"]:not([name="id_aluno_curso"])').val('');
            $('[name="tipo"] option').prop('disabled', false);
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Alocar novo semestre');
            $('.combo_nivel1').hide();
        }

        function add_turma(id_semestre) {
            save_method = 'add';
            $('#form_turma')[0].reset();
            $('#form_turma input[type="hidden"]').val('');
            $('#form_turma [name="id_semestre"]').val(id_semestre);
            $('[name="tipo"] option').prop('disabled', false);
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_turma').modal('show');
            $('.modal-title').text('Adicionar turma');
            $('.combo_nivel1').hide();
        }

        function edit_semestre(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                url: '<?php echo site_url('ei/semestres/ajax_edit/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (json) {
                    $('#id_diretoria').val(json.id_diretoria);
                    // $('#periodo_manha').prop('disabled', json.escola_manha === '0');
                    // $('#periodo_tarde').prop('disabled', json.escola_tarde === '0');
                    // $('#periodo_noite').prop('disabled', json.escola_noite === '0');
                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').is(':checkbox') === false) {
                            $('#form [name="' + key + '"]').val(value);
                        } else {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        }
                    });
                    $('.modal-title').text('Gerenciar semestre');
                    $('#modal_form').modal('show');

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_turma(id) {
            save_method = 'update';
            $('#form_turma')[0].reset();
            $('#form_turma input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                url: '<?php echo site_url('ei/semestres/ajax_editTurma/') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (json) {
                    // $('#periodo_manha').prop('disabled', json.escola_manha === '0');
                    // $('#periodo_tarde').prop('disabled', json.escola_tarde === '0');
                    // $('#periodo_noite').prop('disabled', json.escola_noite === '0');
                    $.each(json, function (key, value) {
                        if ($('#form_turma [name="' + key + '"]').is(':checkbox') === false) {
                            $('#form_turma [name="' + key + '"]').val(value);
                        } else {
                            $('#form_turma [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        }
                    });
                    $('.modal-title').text('Editar turma');
                    $('#modal_turma').modal('show');

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function reload_table() {
            table.ajax.reload(null, false);
        }

        function save_semestre() {
            $('#btnSave').text('Salvando...');
            $('#btnSave').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('ei/semestres/ajax_add') ?>';
            } else {
                url = '<?php echo site_url('ei/semestres/ajax_update') ?>';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: $('#form').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if (data.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }

                    $('#btnSave').text('Salvar');
                    $('#btnSave').attr('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (textStatus) {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Error adding / update data');
                    }
                    $('#btnSave').text('Salvar');
                    $('#btnSave').attr('disabled', false);
                }
            });
        }

        function save_turma() {
            $('#btnSaveTurma').text('Salvando...');
            $('#btnSaveTurma').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('ei/semestres/ajax_addTurma') ?>';
            } else {
                url = '<?php echo site_url('ei/semestres/ajax_updateTurma') ?>';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: $('#form_turma').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if (data.status) {
                        $('#modal_turma').modal('hide');
                        reload_table();
                    }

                    $('#btnSaveTurma').text('Salvar');
                    $('#btnSaveTurma').attr('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (textStatus) {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Error adding / update data');
                    }
                    $('#btnSaveTurma').text('Salvar');
                    $('#btnSaveTurma').attr('disabled', false);
                }
            });
        }

        function delete_semestre(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: '<?php echo site_url('ei/semestres/ajax_delete') ?>/',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

        function delete_turma(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: '<?php echo site_url('ei/semestres/ajax_deleteTurma') ?>/',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

    </script>

<?php require_once APPPATH . "views/end_html.php"; ?>