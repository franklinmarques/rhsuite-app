<?php require_once APPPATH . 'views/header.php'; ?>

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

        #table_periodo > thead > tr > th {
            padding-right: 5px;
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
                        <li class="active">Gestão Comercial - Gerenciar Sessões de Libras</li>
                    </ol>
                    <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-2">
                                <label>Filtrar por mês</label>
                                <?php echo form_dropdown('mes', $meses, date('m'), 'onchange="reload_table();" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-2">
                                <label>Filtrar por ano</label>
                                <input name="ano" type="text" class="form-control text-center input-sm ano"
                                       onchange="reload_table();" value="<?= date('Y'); ?>" placeholder="aaaa">
                            </div>
                            <div class="col-md-4">
                                <label>Filtrar por cliente</label>
                                <?php echo form_dropdown('cliente', $clientes, '', 'onchange="reload_table();" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label>Filtrar por produto</label>
                                <?php echo form_dropdown('produto', $produtos, '', 'onchange="reload_table();" class="form-control input-sm"'); ?>
                            </div>
                        </div>
                        <br>
                    </form>
                    <table id="table_periodo" class="table table-hover table-condensed table-bordered" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th rowspan="2" class="warning">Período</th>
                            <td colspan="31" class="text-center date-width" id="nome_mes_ano">
                                <strong>Total de eventos no mês de <?= $mes_ano; ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                <?php if (date('N', mktime(0, 0, 0, date('m'), $i, date('Y'))) < 6): ?>
                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                <?php else: ?>
                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <hr>
                    <button id="btnAdd" type="button" class="btn btn-info" onclick="add_sessao()" autocomplete="off"><i
                                class="glyphicon glyphicon-plus"></i> Nova sessão
                    </button>
                    <br>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Data</th>
                            <th>Produto</th>
                            <th>Cliente</th>
                            <th>Horário início</th>
                            <th>Qtde. horas</th>
                            <th>Profissional</th>
                            <th nowrap>Faturamento (R$)</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- page end-->

                <!-- Bootstrap modal -->
                <div class="modal fade" id="modal_form" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div style="float:right;">
                                    <button type="button" class="btn btn-success" id="btnSave" onclick="save()">Salvar
                                    </button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                </div>
                                <h3 class="modal-title">Gerenciar produto</h3>
                            </div>
                            <div class="modal-body form">
                                <div id="alert"></div>
                                <form action="#" id="form" class="form-horizontal">
                                    <input type="hidden" value="" name="id"/>
                                    <div class="form-body">
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Cliente <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <?php echo form_dropdown('id_cliente', $clientes, '', 'class="form-control" onchange="filtrar_contratos();"'); ?>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Produto <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <?php echo form_dropdown('id_produto', $produtos, '', 'class="form-control" onchange="calcular_valor_produto();"'); ?>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Contrato <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-md-3">
                                                <?php echo form_dropdown('codigo_contrato', ['' => 'selecione...'], '', 'class="form-control"'); ?>
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label col-md-2">Data evento <span class="text-danger">*</span></label>
                                            <div class="col-md-2">
                                                <input name="data_evento" class="form-control text-center date"
                                                       type="text" placeholder="dd/mm/aaaa">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Horário início <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-md-2">
                                                <input name="horario_inicio" class="form-control text-center hora"
                                                       type="text" placeholder="hh:mm">
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label col-md-2">Horário término <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-md-2">
                                                <input name="horario_termino" class="form-control text-center hora"
                                                       type="text" placeholder="hh:mm">
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label col-md-2">Qtde. horas</label>
                                            <div class="col-md-1">
                                                <input name="qtde_horas" class="form-control qtde" type="text" readonly>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">

                                            <label class="control-label col-md-2">Desconto</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="valor_desconto" type="text" class="form-control valor"
                                                           aria-describedby="basic-addon1"
                                                           onchange="calcular_valor_produto();">
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label col-md-3">Valor a ser faturado</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="valor_faturamento" type="text"
                                                           class="form-control valor"
                                                           aria-describedby="basic-addon1">
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Custo operacional</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="custo_operacional" type="text"
                                                           class="form-control valor"
                                                           aria-describedby="basic-addon1">
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                            <label class="control-label col-md-1">Impostos</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="custo_impostos" type="text" class="form-control valor"
                                                           aria-describedby="basic-addon1">
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Local do evento</label>
                                            <div class="col-md-9">
                                                <textarea name="local_evento" class="form-control"></textarea>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Profissional alocado <span
                                                        class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <input name="profissional_alocado" class="form-control" type="text"
                                                       maxlength="255">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Valor a ser pago ao
                                                profissional</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon1">R$</span>
                                                    <input name="valor_pagamento_profissional" type="text"
                                                           class="form-control valor"
                                                           aria-describedby="basic-addon1">
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-2">Observações sobre o evento</label>
                                            <div class="col-md-9">
                                                <textarea name="observacoes" class="form-control"></textarea>
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

        </section>
    </section>
    <!--main content end-->

<?php require_once APPPATH . 'views/end_js.php'; ?>

    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gestão Comercial: Gerenciar Sessões de Libras';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

    <script>

        var save_method;
        var table, table_periodo;

        $('.date').mask('00/00/0000');
        $('.ano').mask('0000');
        $('.qtde').mask('00');
        $('.valor').mask('#.###.##0,00', {reverse: true});
        $('.hora').on('change', function () {
            var horario_inicio = moment.duration($('#form [name="horario_inicio"]').val(), 'HH:mm').asSeconds();
            var horario_termino = moment.duration($('#form [name="horario_termino"]').val(), 'HH:mm').asSeconds();
            if (horario_inicio > 0 && horario_termino > 0) {
                $('#form [name="qtde_horas"]').val(parseInt((horario_termino - horario_inicio) / 3600));
            } else {
                $('#form [name="qtde_horas"]').val('');
            }
            calcular_valor_produto();
        }).mask('00:00');


        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'order': [],
                'ajax': {
                    'url': '<?php echo site_url('icom/sessoes/listar') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '30%',
                        'targets': [1, 2, 5]
                    },
                    {
                        'className': 'text-center',
                        'targets': [0, 3, 4]
                    },
                    {
                        'className': 'text-right',
                        'targets': [6]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1],
                        'orderable': false,
                        'searchable': false
                    }
                ]
            });

        });

        table_periodo = $('#table_periodo').DataTable({
            'processing': true,
            'serverSide': true,
            'lengthChange': false,
            'iDisplayLength': -1,
            'ordering': false,
            'searching': false,
            'info': false,
            'paging': false,
            'ajax': {
                'url': '<?php echo site_url('icom/sessoes/listarPeriodos') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.busca = $('#busca').serialize();
                    return d;
                },
                'dataSrc': function (json) {
                    var dataEvento = moment(json.lastDayOfMonth, 'YYYY-MM-DD', 'pt-br');

                    $('#nome_mes_ano').html('<strong>Total de eventos no mês de ' +
                        dataEvento.format('MMMM YYYY').replace(' ', ' de ') + '</strong>');

                    var dataAtual = moment().format('YYYY-MM-DD');
                    var totalDias = dataEvento.date();
                    dataEvento.date(1);

                    for (i = 1; i <= 31; i++) {
                        if (totalDias < i) {
                            table_periodo.column(i).visible(false);
                        } else {
                            table_periodo.column(i).visible(true);

                            var coluna = $(table_periodo.columns(i).header());
                            coluna.removeClass('text-danger').css('background-color', '')
                                .attr('title', dataEvento.format('dddd, DD # MMMM # YYYY').replace(/#/g, 'de'));

                            if (dataEvento.day() === 6 || dataEvento.day() === 0) {
                                coluna.addClass('text-danger').css('background-color', '#dbdbdb');
                            }

                            if (dataEvento.isSame(dataAtual)) {
                                coluna.css('background-color', '#0f0');
                            }

                            dataEvento.add(1, 'days');
                        }
                    }

                    return json.data;
                }
            },
            'columnDefs': [
                {
                    'width': '100%',
                    'targets': [0]
                },
                {
                    'createdCell': function (td, cellData, rowData, row, col) {
                        if (rowData[col]) {
                            $(td).css({'color': '#fff', 'background-color': '#47a447'});
                        } else if ($(table_periodo.column(col).header()).hasClass('text-danger')) {
                            $(td).css('background-color', '#e9e9e9');
                        }
                    },
                    'className': 'text-center',
                    'targets': 'date-width'
                }
            ]
        });


        function add_sessao() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form [name="id"]').val('');
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar sessão de atividades');
            $('.combo_nivel1').hide();
        }


        function edit_sessao(id) {
            $.ajax({
                'url': '<?php echo site_url('icom/sessoes/editar') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'beforeSend': function () {
                    save_method = 'update';
                    $('#form')[0].reset();
                    $('.form-group').removeClass('has-error');
                    $('.help-block').empty();
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        $('#form [name="' + key + '"]').val(value);
                    });

                    $('#form [name="codigo_contrato"]').html($(json.contratos).html());

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar sessão de atividades');
                }
            });
        }


        function filtrar_contratos() {
            $.ajax({
                'url': '<?php echo site_url('icom/sessoes/filtrarContratos') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_cliente': $('#form [name="id_cliente"]').val(),
                    'codigo_contrato': $('#form [name="codigo_contrato"]').val()
                },
                'beforeSend': function () {
                    $('#form [name="codigo_contrato"]').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        $('#form [name="codigo_contrato"]').html($(json.contratos).html());
                    }
                },
                'complete': function () {
                    $('#form [name="codigo_contrato"]').attr('disabled', false);
                }
            });
        }


        function calcular_valor_produto() {
            $.ajax({
                'url': '<?php echo site_url('icom/sessoes/calcularValorProduto') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_produto': $('#form [name="id_produto"]').val()
                },
                'beforeSend': function () {
                    $('#form .hora, #form [name="valor_desconto"], #form [name="valor_faturamento"]').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.preco) {
                        var qtde_horas = parseInt($('#form [name="qtde_horas"]').val());
                        var desconto = parseFloat($('#form [name="valor_desconto"]').val().replace('.', '').replace(',', '.'));
                        var valor_faturamento = 0;
                        var valor_pagamento_profissional = 0;
                        if (qtde_horas > 0) {
                            valor_faturamento = json.preco * qtde_horas;
                            valor_pagamento_profissional = json.custo * qtde_horas;
                        }
                        if (desconto > 0) {
                            valor_faturamento -= desconto;
                        }
                        $('#form [name="valor_faturamento"]').val(valor_faturamento.toLocaleString('pt-BR', {'minimumFractionDigits': 2}));
                        $('#form [name="valor_pagamento_profissional"]').val(valor_pagamento_profissional.toLocaleString('pt-BR', {'minimumFractionDigits': 2}));
                    } else {
                        $('#form [name="valor_faturamento"], #form [name="valor_pagamento_profissional"]').val('');
                    }
                },
                'complete': function () {
                    $('#form .hora, #form [name="valor_desconto"], #form [name="valor_faturamento"]').attr('disabled', false);
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false);
            table_periodo.ajax.reload(null, false);
        }


        function save() {
            $.ajax({
                'url': '<?php echo site_url('icom/sessoes/salvar') ?>',
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
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_sessao(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('icom/sessoes/excluir') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else if (json.erro) {
                            alert(json.erro);
                        }
                    }
                });
            }
        }


    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>
