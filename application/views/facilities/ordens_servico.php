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

        #status li a {
            border: 1px solid #ccc;
            font-size: 12px;
            font-weight: bold;
        }

        #status li.active a {
            border-color: #2e6da4;
        }

        #status li.disable a,
        #dados li.disable a {
            color: #777;
            text-decoration: none;
            background-color: transparent;
            cursor: not-allowed;
        }

        #status li.disable.active a
        #dados li.disable.active a {
            background-color: #78a6ce;
            border: 1px solid #77a5cd;
            color: #fdfdfd;
            border-radius: 4px;
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
                        <li class="active">Gerenciar Ordens de Serviço</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_os()"><i class="glyphicon glyphicon-plus"></i>
                        Nova O. S.
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th rowspan="2" nowrap>O.S.</th>
                            <th colspan="4" class="text-center">Datas</th>
                            <th rowspan="2">Prioridade</th>
                            <th rowspan="2">Status</th>
                            <th rowspan="2">Requisitante</th>
                            <th rowspan="2">Problema/solicitação</th>
                            <th rowspan="2">Ações</th>
                        </tr>
                        <tr>
                            <th>Abertura</th>
                            <th>Estimada</th>
                            <th>Tratada</th>
                            <th>Fechamento</th>
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
                            <div class="col-md-8">
                                <h3 class="modal-title">Gerenciar Ordem de Serviço</h3>
                            </div>
                            <div class="col-md-3 text-right" style="width:290px;">
                                <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                    Salvar
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                        <div class="modal-body form" style="padding-top:0px;">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" value="<?= $idUsuario; ?>" name="id_usuario">
                                <input type="hidden" value="" name="status">
                                <div class="form-body">
                                    <div class="form-group">
                                        <div class="col-md-1 controls">
                                            <label class="text-nowrap">O. S.</label>
                                            <input name="numero_os" class="form-control text-right" type="text"
                                                   readonly>
                                        </div>

                                        <div class="col-md-11">
                                            <label>Status da Ordem de Serviço</label>
                                            <ul class="nav nav-pills" role="tablist" id="status">
                                                <?php if ($vistoriador): ?>
                                                    <li role="presentation" class="active">
                                                        <a href="#aberta" aria-controls="aberta" role="tab"
                                                           data-toggle="pill" data-value="A">1. Aberta</a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a href="#tratamento" aria-controls="tratamento" role="tab"
                                                           data-toggle="pill" data-value="E">2. Em tratamento</a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a href="#tratada" aria-controls="tratada" role="tab"
                                                           data-toggle="pill" data-value="G">3. Tratada - Aguardando
                                                            aprovação</a>
                                                    </li>
                                                    <li role="presentation" class="disable">
                                                        <a href="#fechada" aria-controls="fechada" role="tab"
                                                           data-toggle="pill" data-value="F">4. Fechada</a>
                                                    </li>
                                                    <li role="presentation" class="disable">
                                                        <a href="#fechada_parcialmente"
                                                           aria-controls="fechada_parcialmente"
                                                           role="tab" data-toggle="pill" data-value="P">5. Fechada
                                                            parcialmente</a>
                                                    </li>
                                                <?php else: ?>
                                                    <li role="presentation" class="disable">
                                                        <a href="#aberta" aria-controls="aberta" role="tab"
                                                           data-toggle="pill" data-value="A">1. Aberta</a>
                                                    </li>
                                                    <li role="presentation" class="disable">
                                                        <a href="#tratamento" aria-controls="tratamento" role="tab"
                                                           data-toggle="pill" data-value="E">2. Em tratamento</a>
                                                    </li>
                                                    <li role="presentation" class="disable">
                                                        <a href="#tratada" aria-controls="tratada" role="tab"
                                                           data-toggle="pill" data-value="G">3. Tratada - Aguardando
                                                            aprovação</a>
                                                    </li>
                                                    <li role="presentation" class="active">
                                                        <a href="#fechada" aria-controls="fechada" role="tab"
                                                           data-toggle="pill" data-value="F">4. Fechada</a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a href="#fechada_parcialmente"
                                                           aria-controls="fechada_parcialmente"
                                                           role="tab" data-toggle="pill" data-value="P">5. Fechada
                                                            parcialmente</a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <br>
                                    <span id="alert_fechada" class="help-block text-primary"></span>
                                    <ul id="dados" class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#dados_os" aria-controls="dados_os" role="tab"
                                               data-toggle="tab"><strong>Dados
                                                    da O. S.</strong></a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#pesquisa_satisfacao" aria-controls="pesquisa_satisfacao"
                                               role="tab" data-toggle="tab"><strong>Pesquisa de satisfação</strong></a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="dados_os">
                                            <br>
                                            <div class="form-group">
                                                <label class="control-label col-md-2">Status atual</label>
                                                <div class="col-md-5">
                                                    <select id="status_atual" class="form-control" disabled>
                                                        <option value="A">Aberta</option>
                                                        <option value="F">Fechada</option>
                                                        <option value="P">Parcialmente fechada</option>
                                                        <option value="E">Em tratamento</option>
                                                        <option value="G">Tratada - aguardando aprovação requisitante
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="control-label col-md-2">Prioridade da O.S.</label>
                                                <div class="col-md-2">
                                                    <select name="prioridade" class="form-control">
                                                        <option value="0">selecione...</option>
                                                        <option value="1">Baixa</option>
                                                        <option value="2">Média</option>
                                                        <option value="3">Alta</option>
                                                        <option value="4">Urgente</option>
                                                    </select>
                                                </div>
                                                <label class="control-label col-md-4">Data estimada de resolução do
                                                    problema</label>
                                                <div class="col-md-2">
                                                    <input name="data_resolucao_problema"
                                                           class="form-control text-center date"
                                                           type="text" <?= $vistoriador ? '' : 'disabled'; ?>>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="control-label col-md-2">Data abertura</label>
                                                <div class="col-md-2">
                                                    <input name="data_abertura" class="form-control text-center date"
                                                           type="text">
                                                </div>
                                                <label class="control-label col-md-2">Data tratamento</label>
                                                <div class="col-md-2">
                                                    <input name="data_tratamento" class="form-control text-center date"
                                                           type="text">
                                                </div>
                                                <label class="control-label col-md-2">Data fechamento</label>
                                                <div class="col-md-2">
                                                    <input name="data_fechamento" class="form-control text-center date"
                                                           readonly type="text" <?= $vistoriador ? '' : 'disabled'; ?>>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Departamento</label>
                                                <div class="col-md-9 controls">
                                                    <?php echo form_dropdown('id_depto', $deptos, $id_depto, 'id="depto" class="form-control" onchange="montar_estrutura()"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Área</label>
                                                <div class="col-md-9 controls">
                                                    <?php echo form_dropdown('id_area', $areas, $id_area, 'id="area" class="form-control" onchange="montar_estrutura()"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Setor</label>
                                                <div class="col-md-9 controls">
                                                    <?php echo form_dropdown('id_setor', $setores, $id_setor, 'id="setor" class="form-control" onchange="montar_estrutura()"'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Requisitante</label>
                                                <div class="col-md-9 controls">
                                                    <?php echo form_dropdown('id_requisitante', $requisitantes, $id_requisitante, 'id="requisitante" class="form-control"'); ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row form-group">
                                                <div class="col-md-10 col-md-offset-1">
                                                    <label>Problema/solicitação</label>
                                                    <textarea name="descricao_problema"
                                                              class="form-control" <?= $vistoriador ? 'disabled' : ''; ?> rows="6"></textarea>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-10 col-md-offset-1">
                                                    <label>Solicitação/complemento</label>
                                                    <textarea name="complemento"
                                                              class="form-control" <?= $vistoriador ? 'disabled' : ''; ?> rows="6"></textarea>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-10 col-md-offset-1">
                                                    <label>Observações/andamento</label>
                                                    <textarea name="observacoes"
                                                              class="form-control" <?= $vistoriador ? '' : 'disabled'; ?> rows="6"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="pesquisa_satisfacao">
                                            <br>
                                            <div class="row form-group">
                                                <label class="control-label col-md-3">Resolução satisfatória</label>
                                                <div class="col-md-3">
                                                    <select name="resolucao_satisfatoria"
                                                            class="form-control <?= $vistoriador ? 'disabled' : ''; ?>">
                                                        <option value="">selecione...</option>
                                                        <option value="S">Sim</option>
                                                        <option value="N">Não</option>
                                                        <option value="P">Parcialmente</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="control-label col-md-3">Observações positivas</label>
                                                <div class="col-md-8">
                                            <textarea name="observacoes_positivas"
                                                      class="form-control <?= $vistoriador ? 'disabled' : ''; ?>"
                                                      cols="2"></textarea>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="control-label col-md-3">Observações negativas</label>
                                                <div class="col-md-8">
                                            <textarea name="observacoes_negativas"
                                                      class="form-control <?= $vistoriador ? 'disabled' : ''; ?>"
                                                      cols="2"></textarea>
                                                </div>
                                            </div>

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

<?php
require_once APPPATH . 'views/end_js.php';
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Ordens de Serviço';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $(document).ready(function () {
            $('.date').mask('00/00/0000');


            //datatables
            table = $('#table').DataTable({
                'dom': "<'row'<'.col-sm-3'l><'#status.col-sm-3'><'#ano.col-sm-2'><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('facilities/ordensServico/ajaxList/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        if ($('#status [name="busca_status"]').val() !== undefined) {
                            d.status = $('#status [name="busca_status"]').val();
                        } else {
                            d.status = '';
                        }
                        if ($('#ano [name="busca_ano"]').val() !== undefined) {
                            d.ano = $('#ano [name="busca_ano"]').val();
                        } else {
                            d.ano = '';
                        }

                        return d;
                    },
                    'dataSrc': function (json) {
                        if (json.draw === 1) {
                            $("#status").append('<br>Status&nbsp;' + json.status);
                            $('#ano').append('<br>Ano&nbsp;' + json.ano);
                        }

                        return json.data;
                    }
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'className': 'text-center',
                        'targets': [0, 1, 2, 3, 4]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[10] === '4' || rowData[10] === '3') {
                                // $(td).addClass('danger');
                                $(td).css({'background-color': '#f00', 'color': '#fff'});
                            } else if (rowData[10] === '2') {
                                // $(td).addClass('warning');
                                $(td).css('background-color', '#ffad1c');
                            } else if (rowData[10] === '1') {
                                // $(td).addClass('warning');
                                $(td).css('background-color', '#ff0');
                            }
                        },
                        'className': 'text-center',
                        'targets': [5]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[11] === 'A') {
                                // $(td).addClass('danger');
                                $(td).css({'background-color': '#f00', 'color': '#fff'});
                            } else if (rowData[11] === 'P' || rowData[11] === 'G') {
                                // $(td).addClass('warning');
                                $(td).css('background-color', '#ff0');
                            } else if (rowData[11] === 'E') {
                                // $(td).addClass('info');
                                $(td).css({'background-color': '#2ba8ff', 'color': '#fff'});
                            } else if (rowData[11] === 'F') {
                                // $(td).addClass('success');
                                $(td).css({'background-color': '#0c0', 'color': '#fff'});
                            }
                        },
                        'className': 'text-center',
                        'targets': [6]
                    },
                    {
                        'width': '40%',
                        'targets': [7]
                    },
                    {
                        'width': '60%',
                        'targets': [8]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ]
            });

        });


        $('#status a').click(function (e) {
            if ($(this).parent().hasClass('disable')) {
                return false;
            }
            var value = $(this).data('value');
            $('#form [name="status"], #status_atual').val(value);
            if (value === 'G' && $('#form [name="data_tratamento"]').val().length === 0) {
                $('#form [name="data_tratamento"]').prop('disabled', false).val(moment().format('DD/MM/YYYY'));
            } else if (value === 'F' && $('#form [name="data_fechamento"]').val().length === 0) {
                $('#form [name="data_fechamento"]').prop('disabled', false).val(moment().format('DD/MM/YYYY'));
            }

            if (value === 'F') {
                alert('Para finalizar a Ordem de Serviço preencha a pesquisa de satisfação.');
                $('#dados li').eq(1).find('a').tab('show');
            }
        })


        $('#dados li a').click(function (e) {
            if ($(this).parent().hasClass('disable')) {
                return false;
            }
        });


        $('#modal_form').on('shown.bs.modal', function () {
            var vistoriador = <?= $vistoriador ? 'true' : 'false'; ?>;
            if (vistoriador) {
                // $('[name="data_resolucao_problema"]').css();
            }
        })


        function add_os() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            $.ajax({
                'url': '<?php echo site_url('facilities/ordensServico/ajaxNovo') ?>',
                'type': 'POST',
                'dataType': 'json',
                'success': function (json) {
                    $('#form input[name="numero_os"]').val(json.numero_os);
                    $('#depto').html($(json.deptos).html());
                    $('#area').html($(json.areas).html());
                    $('#setor').html($(json.setores).html());
                    $('#requisitante').html($(json.requisitantes).html());

                    $('#status li').removeClass('active').addClass('disable');
                    $('#status li').first().removeClass('disable');
                    $('#status li a[data-value="A"]').parent('li').addClass('active');
                    $('#dados li').eq(0).find('a').trigger('click');
                    $('#dados li').eq(1).removeClass('active').addClass('disable');
                    $('#status_atual, #form [name="status"]').val('A');
                    $('#form select, #form textarea, #form input').prop('disabled', false);
                    if ('<?= $vistoriador; ?>' === '1') {
                        $('[name="descricao_problema"],[name="data_fechamento"]').prop('disabled', true);
                    } else {
                        $('[name="data_resolucao_problema"],[name="data_fechamento"],[name="observacoes"]').prop('disabled', true);
                    }

                    $('[name="data_abertura"]').val(moment().format('DD/MM/YYYY'));
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Nova ordem de serviço'); // Set title to Bootstrap modal title
                    $('.combo_nivel1').hide();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_os(numero_os) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('facilities/ordensServico/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'numero_os': numero_os
                },
                'success': function (json) {
                    $.each(json.input, function (key, value) {
                        $('#' + key).html($(value).html());
                    });

                    $.each(json.data, function (key, value) {
                        if ($('#form [name="' + key + '"]').is(':checkbox') === false) {
                            $('#form [name="' + key + '"]').val(value);
                        } else {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        }
                    });

                    $('#form select, #form textarea, #form input.date').prop('disabled', true);
                    // $('#form input[type="hidden"]').prop('disabled', false);
                    $('#status li').removeClass('active').addClass('disable');
                    if ('<?= $vistoriador; ?>' !== '1') {
                        $('[name="complemento"]').prop('disabled', false);
                    }
                    switch (json.data.status) {
                        case 'A':
                            $('#status li').eq(0).removeClass('disable');
                            if ('<?= $vistoriador; ?>' !== '1') {
                                $('[name="descricao_problema"]').prop('disabled', false);
                            }
                        case 'E':
                            if ('<?= $vistoriador; ?>' === '1') {
                                $('[name="data_resolucao_problema"]').prop('disabled', false);
                            }
                        case 'G':
                            // $('#dados li').eq(1).removeClass('active').addClass('disable');
                            if ('<?= $vistoriador; ?>' === '1') {
                                $('#status li').eq(1).removeClass('disable');
                                $('#status li').eq(2).removeClass('disable');
                                $('[name="observacoes"]').prop('disabled', false);
                            } else {
                                $('#status li').eq(3).removeClass('disable');
                                $('#status li').eq(4).removeClass('disable');
                                $('#dados li').eq(1).removeClass('disable');
                                $('[name="resolucao_satisfatoria"],[name="observacoes_positivas"],[name="observacoes_negativas"]').prop('disabled', false);
                            }
                            break;
                        case 'F':
                            $('[name="data_fechamento"]').prop('disabled', false);
                        // $('#dados li').eq(1).addClass('active');
                        case 'P':
                            if ('<?= $vistoriador; ?>' !== '1') {
                                $('#status li').eq(3).removeClass('disable');
                                $('#status li').eq(4).removeClass('disable');
                                $('#dados li').eq(1).removeClass('disabled');
                                $('[name="resolucao_satisfatoria"],[name="observacoes_positivas"],[name="observacoes_negativas"]').prop('disabled', false);
                            }
                            $('#dados li').eq(1).removeClass('disable');
                            break;
                    }
                    /*$('#status li').removeClass('active').addClass('disable');
                    $('#aba_pesquisa_satisfacao').removeClass('active').addClass('disable');
                    $('[name="data_fechamento"]').prop('disabled', json.data.status === 'F' || json.data.status === 'P');
                    $('[name="data_abertura"], [name="prioridade"], [name="data_fechamento"], #depto, #area, #setor, #requisitante').prop('disabled', true);
                    if ('' === '1') {
                        // $('#status li').eq(0).removeClass('disable');
                        $('#status li').eq(1).removeClass('disable');
                        $('#status li').eq(2).removeClass('disable');
                        $('ul.nav-tabs li').eq(1).addClass('disable');
                    } else {
                        $('[name="descricao_problema"]').prop('disabled', json.data.status !== 'A');
                        $('#status li').eq(3).removeClass('disable');
                        $('#status li').eq(4).removeClass('disable');
                        $('ul.nav-tabs li').eq(1).removeClass('disable');
                    }*/

                    $('#status li a[data-value="' + json.data.status + '"]').parent('li').addClass('active');
                    $('#status_atual').val(json.data.status);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar ordem de serviço'); // Set title to Bootstrap modal title
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function montar_estrutura() {
            var depto = $('#depto').val();
            var area = $('#area').val();
            var setor = $('#setor').val();
            var requisitante = $('#requisitante').val();
            $('#depto,#area, #setor').prop('disabled', true);

            $.ajax({
                'url': '<?php echo site_url('facilities/ordensServico/montarEstrutura') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'depto': depto,
                    'area': area,
                    'setor': setor,
                    'requisitante': requisitante
                },
                'success': function (json) {
                    $('#depto,#area, #setor').prop('disabled', false);

                    $('#area').html($(json.area).html());
                    $('#setor').html($(json.setor).html());
                    $('#requisitante').html($(json.requisitante).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#depto,#area, #setor').prop('disabled', false);
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
                url = "<?php echo site_url('facilities/ordensServico/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/ordensServico/ajaxUpdate') ?>";
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'success': function (json) {
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
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }


        function delete_os(numero_os) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('facilities/ordensServico/ajaxDelete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'numero_os': numero_os},
                    'success': function () {
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }


        function notificar_fechamento(numero_os) {
            $.ajax({
                'url': '<?php echo site_url('facilities/ordensServico/notificarFechamento') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'numero_os': numero_os},
                'beforeSend': function () {
                    $('.notificarFechamento').prop('disabled', true);
                },
                'success': function () {
                    if (json.status) {
                        alert('Notificação enviada com sucesso.');
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                },
                'complete': function () {
                    $('.notificarFechamento').prop('disabled', false);
                }
            });
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>