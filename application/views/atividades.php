<?php
require_once "header.php";
?>
    <style>
        <?php if ($this->agent->is_mobile()): ?>
        #busca, #table {
            font-size: x-small;
        }

        <?php endif; ?>

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

        /*    .table-hover > tbody > tr:not([data-mae]):hover > td:not(.active) {
                background-color: #75b3d0;
            }*/
        .table-hover > tbody > tr > td.atv-success,
        .table-hover > tbody > tr.active > td.atv-success {
            color: #fff;
            background-color: #5cb85c !important;
        }

        .table-hover > tbody > tr:hover > td.atv-success,
        .table-hover > tbody > tr.active:hover > td.atv-success {
            background-color: #47a447 !important;
        }

        .table-hover > tbody > tr > td.atv-warning,
        .table-hover > tbody > tr.active > td.atv-warning {
            color: #fff;
            background-color: #f0ad4e !important;
        }

        .table-hover > tbody > tr:hover > td.atv-warning,
        .table-hover > tbody > tr.active:hover > td.atv-warning {
            background-color: #ed9c28 !important;
        }

        .table-hover > tbody > tr > td.atv-danger,
        .table-hover > tbody > tr.active > td.atv-danger {
            color: #fff;
            background-color: #d9534f !important;
        }

        .table-hover > tbody > tr:hover > td.atv-danger,
        .table-hover > tbody > tr.active:hover > td.atv-danger {
            background-color: #d2322d !important;
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
                        <li class="active">Lista de Pendências</li>
                        <?php $this->load->view('modal_processos', ['url' => 'atividades']); ?>
                        <?php if ($this->agent->is_mobile()): ?>
                            <button style="float:right;" class="btn btn-info btn-xs" onclick="add_atividade()">
                                <i class="glyphicon glyphicon-plus"></i> Cadastrar atividade
                            </button>
                        <?php endif; ?>
                    </ol>
                    <div class="row form-inline">
                        <div class="col-sm-5 col-md-4">
                            <?php if ($this->agent->is_mobile() == false): ?>
                                <button class="btn btn-info" onclick="add_atividade()"><i
                                            class="glyphicon glyphicon-plus"></i> Cadastrar atividade mãe
                                </button>
                                <a class="btn btn-primary" href="<?= site_url('atividades/relatorio'); ?>"><i
                                            class="glyphicon glyphicon-list-alt"></i>
                                    Relatório
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-7 col-md-8 right hidden-xs hidden-sm">
                            <?php if ($this->agent->is_mobile() == false): ?>
                                <p class="bg-info text-info" id="alerta" style="padding: 5px;">
                                    <small>* ID - Identificador &nbsp; | &nbsp; * ST - Status: [ NF - Não-finalizado;
                                        &nbsp;
                                        DL - Próximo à data limite; &nbsp; L - Limite expirado; &nbsp; F - Finalizado ]
                                    </small>
                                    <br>
                                    <small>* TP - Tipo: [ G - Gestão; &nbsp; O - Operação ] &nbsp; | &nbsp; * PR -
                                        Prioridade: [ AL - Alta; &nbsp; MD - Média; &nbsp; BX - Baixa ]
                                    </small>
                                    <br>
                                </p>
                            <?php else: ?>
                                <p class="bg-info text-info" id="alerta" style="font-size: x-small; padding: 5px;">
                                    <small>* ID - Identificador &nbsp; | &nbsp; * ST - Status: [ NF - Não-finalizado;
                                        &nbsp;
                                        DL - Próximo à data limite; &nbsp; L - Limite expirado; &nbsp; F - Finalizado ]
                                    </small>
                                    <br>
                                    <small>* TP - Tipo: [ G - Gestão; &nbsp; O - Operação ] &nbsp; | &nbsp; * PR -
                                        Prioridade: [ AL - Alta; &nbsp; MD - Média; &nbsp; BX - Baixa ]
                                    </small>
                                    <br>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <br>
                    <div class="row" id="busca">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label">Prioridades</label>
                                        <?php echo form_dropdown('busca[prioridades]', $prioridades, '', 'id="deficiencia" class="form-control filtro input-sm" autocomplete="off"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Status</label>
                                        <?php echo form_dropdown('busca[status]', $status, '', 'id="status" class="form-control filtro input-sm" autocomplete="off"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Data início</label>
                                        <input name="busca[data_inicio]" type="text" value=""
                                               id="data_inicio" placeholder="dd/mm/aaaa"
                                               class="form-control filtro input-sm text-center" autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Data término</label>
                                        <input name="busca[data_termino]" type="text" value=""
                                               id="data_termino" placeholder="dd/mm/aaaa"
                                               class="form-control filtro input-sm text-center" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Colaborador/empresa</label>
                                        <?php echo form_dropdown('busca[usuarios]', $usuarios, '', 'id="contrato" class="form-control filtro input-sm" autocomplete="off"'); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;</label><br>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                Limpar filtros
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <i class="glyphicon glyphicon-stop" style="color: #151570;"></i> Atividades mães &emsp;
                            <i class="glyphicon glyphicon-stop" style="color: #87bcd6;"></i> Atividades filhas
                        </div>
                        <div class="col-md-8">
                            <?php if ($this->agent->is_mobile() == false): ?>
                                <em class="text-danger">* Clique na linha que possua a descrição da atividade em fundo
                                    azul para exibir ou ocultar as atividades filhas. &nbsp;</em>
                            <?php endif; ?>
                        </div>
                    </div>

                    <table id="table" class="table table-hover table-bordered table-condensed" width="100%">
                        <thead>
                        <tr>
                            <th style="padding: 5px;" class="hidden-xs hidden-sm" nowrap>ID<span
                                        class="text-info"> *</span></th>
                            <th nowrap>Atividade</th>
                            <th style="padding: 5px;" class="hidden-xs hidden-sm" nowrap>PR<span
                                        class="text-info"> *</span></th>
                            <th style="padding: 5px;" class="hidden-xs hidden-sm" nowrap>ST<span
                                        class="text-info"> *</span></th>
                            <th>Responsável execução</th>
                            <th style="padding: 5px;" class="hidden-xs hidden-sm">Data cadastro</th>
                            <th style="padding: 5px;" class="hidden-xs hidden-sm">Data limite</th>
                            <th style="padding: 5px;" class="hidden-xs hidden-sm">Data fechamento</th>
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
                            <div style="float: right;">
                                <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            </div>
                            <h3 class="modal-title">Cadastrar atividade mãe</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_mae"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <div id="id">
                                            <label class="control-label col-md-2">ID</label>
                                            <div class="col-md-2">
                                                <input class="form-control" type="text" value="" readonly>
                                            </div>
                                        </div>
                                        <div id="id_atividade_mae">
                                            <label class="control-label col-md-2">ID atividade mãe</label>
                                            <div class="col-md-2">
                                                <input class="form-control" type="text" value="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group" id="colaborador">
                                        <label class="control-label col-md-2">Colaborador</label>
                                        <div class="col col-md-9">
                                            <?php echo form_dropdown('id_usuario', array('' => 'selecione...') + $id_usuario, '', 'class="form-control empresa"') ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Atividade</label>
                                        <div class="col-md-9">
                                            <textarea name="atividade" class="form-control empresa" rows="2"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Prioridade</label>
                                        <div class="col-md-3">
                                            <select name="prioridade" class="form-control empresa">
                                                <option value="">selecione...</option>
                                                <option value="0">Baixa</option>
                                                <option value="1">Média</option>
                                                <option value="2">Alta</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2">Tipo atividade</label>
                                        <div class="col-md-3">
                                            <select name="tipo" class="form-control empresa">
                                                <option value="">selecione...</option>
                                                <option value="G">Gestão</option>
                                                <option value="O">Operacional</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Data limite</label>
                                        <div class="col-md-3">
                                            <input name="data_limite" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date empresa" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-3">Lembrar dias antes</label>
                                        <div class="col-md-2">
                                            <input name="data_lembrete" min="0" step="1"
                                                   class="form-control text-right empresa"
                                                   type="number">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Observações do realizador</label>
                                        <div class="col-md-9">
                                            <textarea name="observacoes" class="form-control" rows="2"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave1" onclick="save()" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_form_filha" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div style="float: right;">
                                <button type="button" id="btnSaveFilha" onclick="save_filha()" class="btn btn-success">
                                    Salvar
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            </div>
                            <h3 class="modal-title">Cadastrar atividade(s) filha(s)</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_filha" class="form-horizontal">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">ID atividade mãe</label>
                                        <div class="col-md-2">
                                            <input name="id_mae" class="form-control" type="text" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Atividade</label>
                                        <div class="col-md-9">
                                            <textarea name="atividade" class="form-control" rows="2"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Prioridade</label>
                                        <div class="col-md-3">
                                            <select name="prioridade" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="0">Baixa</option>
                                                <option value="1">Média</option>
                                                <option value="2">Alta</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2">Tipo atividade</label>
                                        <div class="col-md-3">
                                            <select name="tipo" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="G">Gestão</option>
                                                <option value="O">Operacional</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Data limite</label>
                                        <div class="col-md-3">
                                            <input name="data_limite" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-3">Lembrar dias antes</label>
                                        <div class="col-md-2">
                                            <input name="data_lembrete" min="0" step="1" class="form-control text-right"
                                                   type="number">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-12">
                                            <?php echo form_multiselect('id_usuario[]', $id_usuario, array(), 'size="10" id="id_usuario" class="demo2"') ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveFilha1" onclick="save_filha()" class="btn btn-success">
                                Salvar
                            </button>
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
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Lista de Pendências';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var demo2;
        var id_atividade = null;
        var table;

        $('.date').mask('00/00/0000');

        $(document).ready(function () {

            table = $('#table').DataTable({
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, 500, 1000, -1], [5, 10, 25, 50, 100, 500, 1000, 'Todos']],
                'processing': true,
                'serverSide': true,
                'searching': false,
                'bLengthChange': false,
                'order': [[1, 'desc']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('atividades/ajax_list/') ?>',
                    'type': 'POST',
                    'timeout': 90000,
                    'data': function (d) {
                        d.prioridade = $('[name="busca[prioridades]"]').val();
                        d.status = $('[name="busca[status]"]').val();
                        d.data_inicio = $('[name="busca[data_inicio]"]').val();
                        d.data_termino = $('[name="busca[data_termino]"]').val();
                        d.usuario = $('[name="busca[usuarios]"]').val();
                        return d;
                    }
                },
                'createdRow': function (row, data, index) {
                    if (data[10] === '<?= $id ?>') {
                        if (data[11] === '1') {
                            $('td:eq(0)', row).css({'background-color': '#151570', 'color': '#fff'});
                            $('td:lt(8)', row).on('click touchend', function () {
                                if ($('td:eq(0)', row).hasClass('active')) {
                                    $('td:eq(0)', row).removeClass('active');
                                    $('tr[data-mae="' + data[0] + '"]').slideUp();
                                } else {
                                    $('td:eq(0)', row).addClass('active');
                                    $('tr[data-mae="' + data[0] + '"]').slideDown();
                                }
                            }).css('cursor', 'pointer');
                        }
                    } else {
                        $(row).attr('data-mae', data[9]).hide();
                    }
                    if (data[9] !== null) {
                        $(row).addClass('info');
                    }
                },
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [1]
                    },
                    {
                        'visible': <?php echo $this->agent->is_mobile() ? 'false' : 'true'; ?>,
                        'targets': [0, 2, 4, 5, 6, 7]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).css({'font-weight': 'bold', 'cursor': 'pointer'});
                            switch (rowData[col]) {
                                case '0':
                                    $(td).addClass('atv-success').html('BX');
                                    break;
                                case '1':
                                    $(td).addClass('atv-warning').html('MD');
                                    break;
                                case '2':
                                    $(td).addClass('atv-danger').html('AL');
                            }
                        },
                        'targets': [2]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            $(td).css({'font-weight': 'bold', 'cursor': 'pointer'});
                            switch (rowData[col]) {
                                case '0':
                                    $(td).html('NF');
                                    break;
                                case '1':
                                    $(td).addClass('atv-success').html('F');
                                    break;
                                case '2':
                                    $(td).addClass('atv-warning').html('DL');
                                    break;
                                case '3':
                                    $(td).addClass('atv-danger').html('L');
                            }
                        },
                        'targets': [3]
                    },
                    {
                        'className': 'text-center',
                        'targets': [0, 2, 3, 5, 6, 7]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [8]
                    }
                ]
            });

            demo2 = $('#id_usuario').bootstrapDualListbox({
                'nonSelectedListLabel': 'Colaboradores disponíveis',
                'selectedListLabel': 'Colaboradores selecionados',
                'moveOnSelect': false,
                'helperSelectNamePostfix': false,
                'filterPlaceHolder': 'Filtrar',
                'selectorMinimalHeight': 182,
                'infoText': false
            });

            setPdf_atributes();
        });

        function add_atividade() {
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('#form .form-group').removeClass('has-error');
            $('#form .help-block').empty();
            $('#colaborador, #id, #id_atividade_mae').hide();
            $('input.empresa, textarea.empresa').prop('readonly', false);
            $('select.empresa').prop('disabled', false);
            $('#modal_form .modal-title').text('Cadastrar atividade mãe');
            $('#modal_form').modal('show');
            save_method = 'add';
        }

        function edit_atividade_mae(id) {
            $.ajax({
                'url': '<?php echo site_url('atividades/ajax_edit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    if (json.id_usuario === '<?= $id ?>') {
                        $('#colaborador').hide();
                    } else {
                        $('#colaborador').show();
                    }
                    $('input.empresa, textarea.empresa').prop('readonly', '<?= $tipo ?>' !== 'empresa');
                    $('select.empresa').prop('disabled', '<?= $tipo ?>' !== 'empresa');

                    $('#modal_form [name="id"]').val(json.id);
                    $('#modal_form [name="id_usuario"]').val(json.id_usuario);
                    $('#modal_form [name="id_mae"]').val(json.id_mae);
                    $('#modal_form [name="id_usuario"]').val(json.id_usuario);
                    $('#modal_form [name="atividade"]').val(json.atividade);
                    $('#modal_form [name="prioridade"]').val(json.prioridade);
                    $('#modal_form [name="tipo"]').val(json.tipo);
                    $('#modal_form [name="data_limite"]').val(json.data_limite);
                    $('#modal_form [name="data_lembrete"]').val(json.data_lembrete);
                    $('#modal_form [name="observacoes"]').val(json.observacoes);
                    $('#id').show().find('input').val(json.id);
                    $('#id_atividade_mae').hide().find('input').val(json.id_mae);

                    $('#modal_form .modal-title').text('Editar atividade mãe');
                    $('#modal_form').modal('show');
                    $('#modal_form .modal-title');
                    save_method = 'update';
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function add_atividade_filha(id) {
            $('#form_filha')[0].reset();
            $('#form_filha input[type="hidden"]').val('');

            $.ajax({
                'url': '<?php echo site_url('atividades/ajax_edit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'beforeSend': function () {
                    $('input.empresa, textarea.empresa').prop('readonly', false);
                    $('select.empresa').prop('disabled', false);
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $('#modal_form_filha [name="id_mae"]').val(json.id);
                    $('#modal_form_filha [name="atividade"]').val(json.atividade);
                    $('#modal_form_filha [name="prioridade"]').val(json.prioridade);
                    $('#modal_form_filha [name="tipo"]').val(json.tipo);
                    $('#modal_form_filha [name="data_limite"]').val(json.data_limite);
                    $('#modal_form_filha [name="data_lembrete"]').val(json.data_lembrete);
                    $('#modal_form_filha [name="observacoes"]').val(json.observacoes);
                    demo2.bootstrapDualListbox('refresh', true);

                    $('#modal_form_filha').modal('show');
                    $('#modal_form_filha .modal-title');
                    save_method = 'add';
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_atividade_filha(id) {
            $.ajax({
                'url': '<?php echo site_url('atividades/ajax_edit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    if (json.id_usuario === '<?= $id ?>') {
                        $('#colaborador').hide();
                    } else {
                        $('#colaborador').show();
                    }
                    $('input.empresa, textarea.empresa').prop('readonly', '<?= $tipo ?>' !== 'empresa');
                    $('select.empresa').prop('disabled', '<?= $tipo ?>' !== 'empresa');

                    $('#modal_form [name="id"]').val(json.id);
                    $('#modal_form [name="id_usuario"]').val(json.id_usuario);
                    $('#modal_form [name="id_mae"]').val(json.id_mae);
                    $('#modal_form [name="id_usuario"]').val(json.id_usuario);
                    $('#modal_form [name="atividade"]').val(json.atividade);
                    $('#modal_form [name="prioridade"]').val(json.prioridade);
                    $('#modal_form [name="tipo"]').val(json.tipo);
                    $('#modal_form [name="data_limite"]').val(json.data_limite);
                    $('#modal_form [name="data_lembrete"]').val(json.data_lembrete);
                    $('#modal_form [name="observacoes"]').val(json.observacoes);
                    $('#id').show().find('input').val(json.id);
                    $('#id_atividade_mae').show().find('input').val(json.id_mae);

                    $('#modal_form .modal-title').text('Editar atividade filha');
                    $('#modal_form').modal('show');
                    $('#modal_form .modal-title');
                    save_method = 'update';
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        $('.filtro').on('change', function () {
            reload_table();
        });

        $('#limpa_filtro').on('click', function () {
            $('.filtro').val('');
            reload_table();
        });

        function reload_table() {
            table.ajax.reload(null, false);
        }

        function save() {
            var url = '';
            if (save_method === 'add') {
                url = "<?php echo site_url('atividades/ajax_add_mae') ?>";
            } else if (save_method === 'update') {
                url = "<?php echo site_url('atividades/ajax_update') ?>";
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSave, #btnSave1').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    }

                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSave, #btnSave1').text('Salvar').attr('disabled', false);
                }
            });
        }

        function save_filha() {
            $.ajax({
                'url': '<?php echo site_url('atividades/ajax_add_filha') ?>',
                'type': 'POST',
                'data': $('#form_filha').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveFilha, #btnSaveFilha1').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    }

                    if (json.status) {
                        $('#modal_form_filha').modal('hide');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveFilha, #btnSaveFilha1').attr('disabled', false);
                }
            });
        }

        function delete_atividade(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('atividades/ajax_delete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        if (json.erro) {
                            alert(json.erro);
                        }

                        if (json.status) {
                            reload_table();
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

        function finaliza_atividade(id) {
            if (confirm('Deseja finalizar a atividade?')) {
                $.ajax({
                    'url': '<?php echo site_url('atividades/ajax_finalizar') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        if (json.erro) {
                            alert(json.erro);
                        }

                        if (json.status) {
                            reload_table();
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error update data');
                    }
                });
            }
        }

        function setPdf_atributes() {
            var search = '';
            var q = new Array();

            $('.filtro').each(function (i, v) {
                if (v.value.length > 0) {
                    q[i] = v.name + "=" + v.value;
                }
            });

            q = q.filter(function (v) {
                return v.length > 0;
            });
            if (q.length > 0) {
                search = '/q?' + q.join('&');
            }

            $('#pdf').prop('href', "<?= site_url('atividades/pdf/'); ?>" + search);
        }
    </script>

<?php
require_once "end_html.php";
?>