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
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li class="active">Gerenciar Medições</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="processo">Processos</label>
                            <?php echo form_dropdown('', $processos, '', 'id="processo" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="atividade">Atividades</label>
                            <?php echo form_dropdown('', $atividades, '', 'id="atividade" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="etapa">Etapas</label>
                            <?php echo form_dropdown('', ['' => 'Todas'], '', 'id="etapa" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="tipo" value="E" autocomplete="off"
                                           onchange="filtrar_por_tipo(this);" checked> Equipes
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="tipo" value="C" autocomplete="off"
                                           onchange="filtrar_por_tipo(this);"> Colaboradores
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="equipes">
                                <label for="equipe">Equipes</label>
                                <?php echo form_dropdown('', $equipes, '', 'id="equipe" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                            </div>
                            <div id="colaboradores" style="display: none;">
                                <label for="colaborador">Colaboradores</label>
                                <?php echo form_dropdown('', $colaboradores, '', 'id="colaborador" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="crono_analise">CronoAnálises</label>
                            <?php echo form_dropdown('', $cronoAnalises, '', 'id="crono_analise" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                        </div>
                        <div class="col-md-2">
                            <label for="status">Status da medição</label>
                            <?php echo form_dropdown('', $status, '', 'id="status" class="form-control input-sm" onchange="reload_table();" autocomplete="off"'); ?>
                        </div>
                    </div>
                    <br>
                    <div id="esconder_itens" class="form-inline">
                        <button id="btnAdd" class="btn btn-info" onclick="add_medicao()"><i
                                    class="glyphicon glyphicon-plus" disabled></i>
                            Adicionar medição
                        </button>
                        &emsp;
                        <label>Esconder itens: &nbsp;</label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="1" autocomplete="off"> Qtde. colaboradres
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="2" autocomplete="off"> Processo
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="3" autocomplete="off"> Atividade
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="4" autocomplete="off"> Etapa
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="11" autocomplete="off"> Complexidade
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" class="toggle-vis" value="12" autocomplete="off"> Tipo item
                        </label>
                    </div>
                    <hr>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Equipe</th>
                            <th nowrap>Qtde. col.</th>
                            <th>Processo</th>
                            <th>Atividade</th>
                            <th>Etapa</th>
                            <th nowrap>T1 (<?= $baseTempo; ?>)</th>
                            <th nowrap>T2 (<?= $baseTempo; ?>)</th>
                            <th nowrap>TT (<?= $baseTempo; ?>)</th>
                            <th nowrap>Qtd. (<?= $unidadeProducao; ?>)</th>
                            <th nowrap>Ind.Produção (<?= $unidadeProducao; ?>/H.<?= $baseTempo; ?>)</th>
                            <th nowrap>Ind.MãoObra (H.<?= $baseTempo; ?>/<?= $unidadeProducao; ?>)</th>
                            <th>Complexidade</th>
                            <th nowrap>Tipo item</th>
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
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Incluir medição</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_executor"/>
                                <input type="hidden" value="" name="id_etapa"/>
                                <div class="form-body" style="padding-top: 0px;">
                                    <div class="row" id="nome_equipe">
                                        <label class="control-label col-md-4">Equipe:</label>
                                        <div class="col-md-7" style="padding-top: 7px;">
                                            <span class="form-control-static"></span>
                                        </div>
                                    </div>
                                    <div class="row" id="nome_colaborador">
                                        <label class="control-label col-md-4">Colaborador(a):</label>
                                        <div class="col-md-7" style="padding-top: 7px;">
                                            <span class="form-control-static"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="control-label col-md-4">Processo:</label>
                                        <div class="col-md-7" style="padding-top: 7px;">
                                            <span id="nome_processo" class="form-control-static"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="control-label col-md-4">Atividade:</label>
                                        <div class="col-md-7" style="padding-top: 7px;">
                                            <span id="nome_atividade" class="form-control-static"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="control-label col-md-4">Etapa:</label>
                                        <div class="col-md-7" style="padding-top: 7px;">
                                            <span id="nome_etapa" class="form-control-static"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="control-label col-md-4">CronoAnálise:</label>
                                        <div class="col-md-7" style="padding-top: 7px;">
                                            <span id="nome_crono_analise" class="form-control-static"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">T1 (início)</label>
                                        <div class="col-md-4">
                                            <input name="tempo_inicio" class="form-control valor" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">T2 (término)</label>
                                        <div class="col-md-4">
                                            <input name="tempo_termino" class="form-control valor" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">TT (tempo gasto)</label>
                                        <div class="col-md-4">
                                            <input name="tempo_gasto" class="form-control valor" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Qtd. (unidades)</label>
                                        <div class="col-md-4">
                                            <input name="quantidade" class="form-control valor" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Status</label>
                                        <div class="col-md-3">
                                            <select name="status" class="form-control">
                                                <option value="1">Ativa</option>
                                                <option value="0">Inativa</option>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnSave" onclick="save()">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
            document.title = 'CORPORATE RH - LMS - Gerenciar Medições';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;
        var tipo = 'E';

        $('.valor').mask('#####0,000', {reverse: true});


        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'searching': false,
                'iDisplayLength': -1,
                'order': [],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('dimensionamento/medicoes/ajaxList/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.id_processo = $('#processo').val();
                        d.id_atividade = $('#atividade').val();
                        d.id_etapa = $('#etapa').val();
                        d.tipo = tipo;
                        if (tipo === 'E') {
                            d.id_executor = $('#equipe').val();
                        } else if (tipo === 'C') {
                            d.id_executor = $('#colaborador').val();
                        }
                        d.id_crono_analise = $('#crono_analise').val();
                        d.status = $('#status').val();

                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '25%',
                        'targets': [0, 2, 3, 4]
                    },
                    {
                        'className': 'text-center',
                        'targets': [1, 5, 6, 7, 8, 9, 10, 11, 12]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1],
                        'orderable': false,
                        'searchable': false
                    }
                ],
                'preDrawCallback': function () {
                    $('.filtro, #btnAdd').prop('disabled', true);
                },
                'drawCallback': function () {
                    $('.filtro').prop('disabled', false);
                    if (tipo === 'E') {
                        $('#btnAdd').prop('disabled', $('#processo').val().length === 0 || $('#equipe').val().length === 0 || $('#crono_analise').val().length === 0);
                    } else if (tipo === 'C') {
                        $('#btnAdd').prop('disabled', $('#processo').val().length === 0 || $('#colaborador').val().length === 0 || $('#crono_analise').val().length === 0);
                    }
                }
            });

        });


        $('#esconder_itens input.toggle-vis').on('change', function (e) {
            var value = parseInt($(this).val());
            var column = table.column(value);
            column.visible(!column.visible());
        });


        function filtrar_por_tipo(elem) {
            tipo = elem.value;
            if (tipo === 'E') {
                $('#equipes').show();
                $('#colaboradores').hide();
                table.column(0).header().textContent = 'Equipe'
            } else if (tipo === 'C') {
                $('#equipes').hide();
                $('#colaboradores').show();
                table.column(0).header().textContent = 'Colaborador(a)'
            }
            reload_table();
        }


        function filtrar_estrutura() {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/medicoes/filtrarEstrutura') ?>',
                'type': 'POST',
                'data': {
                    'processo': $('#processo').val(),
                    'atividade': $('#atividade').val(),
                    'etapa': $('#etapa').val()
                },
                'dataType': 'json',
                'beforeSend': function () {
                    $('.filtro, #btnAdd').prop('disabled', true);
                },
                'success': function (json) {
                    $('#atividade').html($(json.atividade).html());
                    $('#etapa').html($(json.etapa).html());
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        }


        function add_medicao() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form [name="id"]').val('');
            if (tipo === 'E') {
                $('#form [name="id_executor"]').val($('#equipe').val());
                $('#nome_equipe').show();
                $('#nome_colaborador').hide();
            } else if (tipo === 'C') {
                $('#form [name="id_executor"]').val($('#colaborador').val());
                $('#nome_equipe').hide();
                $('#nome_colaborador').show();
            }
            $('#form [name="id_etapa"]').val($('#etapa').val());
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $('#nome_equipe span').text($('#equipe option:selected').text());
            $('#nome_colaborador span').text($('#colaborador option:selected').text());
            $('#nome_processo').text($('#processo option:selected').text());
            $('#nome_atividade').text($('#atividade option:selected').text());
            $('#nome_etapa').text($('#etapa option:selected').text());
            $('#nome_crono_analise').text($('#crono_analise option:selected').text());

            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar medição');
            $('.combo_nivel1').hide();
        }


        function edit_medicao(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('dimensionamento/medicoes/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }
                    $.each(json, function (key, value) {
                        $('#form [name="' + key + '"]').val(value);
                    });

                    if (tipo === 'E') {
                        $('#nome_equipe').show();
                        $('#nome_colaborador').hide();
                    } else if (tipo === 'C') {
                        $('#nome_equipe').hide();
                        $('#nome_colaborador').show();
                    }
                    $('#nome_equipe span').text(json.equipe);
                    $('#nome_colaborador span').text(json.colaborador);
                    $('#nome_processo').text(json.processo);
                    $('#nome_atividade').text(json.atividade);
                    $('#nome_etapa').text(json.etapa);
                    $('#nome_crono_analise').text(json.crono_analise);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar medição');

                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function save() {
            $('#btnSave').text('Salvando...').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('dimensionamento/medicoes/ajaxAdd') ?>';
            } else {
                url = '<?php echo site_url('dimensionamento/medicoes/ajaxUpdate') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSave').text('Salvar').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_medicao(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('dimensionamento/medicoes/ajaxDelete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else if (json.erro) {
                            alert(json.erro);
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });

            }
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>