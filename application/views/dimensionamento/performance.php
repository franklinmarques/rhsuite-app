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
                        <li class="active">Análise Performance - Crono Análise</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="processo">Processo</label>
                            <?php echo form_dropdown('', $processos, '', 'id="processo" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="atividade">Atividade</label>
                            <?php echo form_dropdown('', $atividades, '', 'id="atividade" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="etapa">Etapa</label>
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
                        <div class="col-md-5">
                            <div id="equipes">
                                <label for="equipe">Equipes</label>
                                <?php echo form_dropdown('', $equipes, '', 'id="equipe" class="form-control input-sm" onchange="filtrar_estrutura();" autocomplete="off"'); ?>
                            </div>
                            <div id="colaboradores" style="display: none;">
                                <label for="colaborador">Colaborador(a)</label>
                                <?php echo form_dropdown('', $colaboradores, '', 'id="colaborador" class="form-control input-sm" onchange="reset_valores();" autocomplete="off"'); ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="crono_analise">Crono Análise</label>
                            <?php echo form_dropdown('', $cronoAnalises, '', 'id="crono_analise" class="form-control input-sm" onchange="reload_table();" autocomplete="off"'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="radio">
                                <label>
                                    <input type="radio" class="tipo_medicao" name="medicao_calculada" value="0"
                                           onchange="reset_valores();" autocomplete="off" checked> Ver medições
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" class="tipo_medicao" name="medicao_calculada" value="1"
                                           onchange="reset_valores();" autocomplete="off"> Ver cálculos
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="complexidade">Complexidade</label>
                            <select id="complexidade" class="form-control input-sm" onchange="reset_valores();"
                                    autocomplete="off">
                                <option value="">Todas</option>
                                <option value="1">Extremamente baixa</option>
                                <option value="2">Baixas</option>
                                <option value="3">Média</option>
                                <option value="4">Alta</option>
                                <option value="5">Extremamente alta</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tipo_item">Tipo item</label>
                            <select id="tipo_item" class="form-control input-sm" onchange="reset_valores();"
                                    autocomplete="off">
                                <option value="">Todos</option>
                                <option value="1">Extremamente pequeno</option>
                                <option value="2">Pequeno</option>
                                <option value="3">Médio</option>
                                <option value="4">Grande</option>
                                <option value="5">Extremamente grande</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div id="valores" class="well well-sm">
                        <p>Quadro resumo da cronoAnálise</p>
                        <div class="row form-group">
                            <label class="col-sm-1 control-label">Menor</label>
                            <div class="col-md-2">
                                <input name="valor_min_calculado" class="form-control input-sm valor" type="text"
                                       maxlength="10" autocomplete="off">
                            </div>
                            <label class="col-sm-1 control-label">Médio</label>
                            <div class="col-md-2">
                                <input name="valor_medio_calculado" class="form-control input-sm valor" type="text"
                                       maxlength="10" autocomplete="off">
                            </div>
                            <label class="col-sm-1 control-label">Maior</label>
                            <div class="col-md-2">
                                <input name="valor_max_calculado" class="form-control input-sm valor" type="text"
                                       maxlength="10" autocomplete="off">
                            </div>
                            <div class="col-md-3 text-right">
                                <button type="button" class="btn btn-sm btn-info" id="btnEditValores"
                                        onclick="editar_calculos()">Editar valores
                                </button>
                                <button type="button" class="btn btn-sm btn-success" id="btnSaveValores"
                                        onclick="salvar_calculos()">Salvar valores
                                </button>
                            </div>
                        </div>
                        <hr style="border-color: #ddd;">
                        <div class="row form-group">
                            <label class="col-sm-1 control-label text-nowrap">Soma menores</label>
                            <div class="col-md-2">
                                <input name="soma_menor" class="form-control input-sm valor" type="text" maxlength="10"
                                       autocomplete="off">
                            </div>
                            <label class="col-sm-1 control-label text-nowrap">Soma médias</label>
                            <div class="col-md-2">
                                <input name="soma_media" class="form-control input-sm valor" type="text" maxlength="10"
                                       autocomplete="off">
                            </div>
                            <label class="col-sm-1 control-label text-nowrap">Soma maiores</label>
                            <div class="col-md-2">
                                <input name="soma_maior" class="form-control input-sm valor" type="text" maxlength="10"
                                       autocomplete="off">
                            </div>
                            <div class="col-md-3 text-right text-nowrap">
                                <button type="button" class="btn btn-sm btn-info" id="btnEditResultados"
                                        onclick="editar_calculos()">Editar valores
                                </button>
                                <button type="button" class="btn btn-sm btn-success" id="btnSaveResutados"
                                        onclick="salvar_crono_analise()">Salvar cronoAnálise
                                </button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Colaborador(a)</th>
                            <th nowrap>IHh/Un (seg./un.)</th>
                            <th nowrap>Atividade/Etapa</th>
                            <th nowrap>Tipo Medição</th>
                            <th nowrap>Menor</th>
                            <th nowrap>Médio</th>
                            <th nowrap>Maior</th>
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
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">T1 (início)</label>
                                        <div class="col-md-4">
                                            <input name="tempo_inicio" class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">T2 (término)</label>
                                        <div class="col-md-4">
                                            <input name="tempo_termino" class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">TT (tempo gasto)</label>
                                        <div class="col-md-4">
                                            <input name="tempo_gasto" class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Qtd. (unidades)</label>
                                        <div class="col-md-4">
                                            <input name="quantidade" class="form-control" type="text">
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
            document.title = 'CORPORATE RH - LMS - Análise Performance - Crono Análise';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;
        var tipo = 'E';


        $(document).ready(function () {

            table = $('#table').DataTable({
                'lengthChange': false,
                'searching': false,
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'processing': true,
                'serverSide': true,
                'order': [],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('dimensionamento/performance/ajaxList/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.id_crono_analise = $('#crono_analise').val();
                        d.id_processo = $('#processo').val();
                        d.id_atividade = $('#atividade').val();
                        d.id_etapa = $('#etapa').val();
                        d.tipo = tipo;
                        if (tipo === 'E') {
                            d.id_executor = $('#equipe').val();
                        } else if (tipo === 'C') {
                            d.id_executor = $('#colaborador').val();
                        }
                        d.complexidade = $('#complexidade').val();
                        d.tipo_item = $('#tipo_item').val();
                        d.medicao_calculada = $('.tipo_medicao:checked').val();

                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '50%',
                        'targets': [0, 2]
                    },
                    {
                        'className': 'text-center',
                        'targets': [3]
                    },
                    {
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ],
                'preDrawCallback': function () {
                    $('#btnEditValores, #btnSaveValores').prop('disabled', $('.tipo_medicao:checked').val() === '1');
                    $('#btnEditResultados, #btnSaveResutados').prop('disabled', $('.tipo_medicao:checked').val() === '0');
                },
                'drawCallback': function () {
                    table.column(1).visible($('.tipo_medicao:checked').val() === '0');
                }
            });


            $('.valor').mask('#####0,000');

        });


        function filtrar_estrutura() {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/performance/filtrarEstrutura') ?>',
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


        function reset_valores() {
            $('#valores input').val('');
            reload_table();
        }


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function editar_calculos() {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/performance/editarCalculos') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_crono_analise': $('#crono_analise').val(),
                    'id_processo': $('#processo').val(),
                    'id_atividade': $('#atividade').val(),
                    'id_etapa': $('#etapa').val(),
                    'tipo': tipo,
                    'id_executor': tipo === 'E' ? $('#equipe').val() : $('#colaborador').val(),
                    'complexidade': $('#complexidade').val(),
                    'tipo_item': $('#tipo_item').val(),
                    'medicao_calculada': $('.tipo_medicao:checked').val()
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }
                    $.each(json, function (key, value) {
                        $('#valores [name="' + key + '"]').val(value);
                    });
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function salvar_calculos() {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/performance/salvarCalculos') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_crono_analise': $('#crono_analise').val(),
                    'id_processo': $('#processo').val(),
                    'id_atividade': $('#atividade').val(),
                    'id_etapa': $('#etapa').val(),
                    'tipo': tipo,
                    'id_executor': tipo === 'E' ? $('#equipe').val() : $('#colaborador').val(),
                    'complexidade': $('#complexidade').val(),
                    'tipo_item': $('#tipo_item').val(),
                    'valor_min_calculado': $('#valores [name="valor_min_calculado"]').val(),
                    'valor_medio_calculado': $('#valores [name="valor_medio_calculado"]').val(),
                    'valor_max_calculado': $('#valores [name="valor_max_calculado"]').val()
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    } else {
                        $('#valores input').val('');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function salvar_crono_analise() {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/performance/salvarCronoAnalise') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_crono_analise': $('#crono_analise').val(),
                    'id_processo': $('#processo').val(),
                    'id_atividade': $('#atividade').val(),
                    'id_etapa': $('#etapa').val(),
                    'id_executor': tipo === 'E' ? $('#equipe').val() : $('#colaborador').val(),
                    'complexidade': $('#complexidade').val(),
                    'tipo_item': $('#tipo_item').val(),
                    'soma_menor': $('#valores [name="soma_menor"]').val(),
                    'soma_media': $('#valores [name="soma_media"]').val(),
                    'soma_maior': $('#valores [name="soma_maior"]').val()
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    } else {
                        $('#valores input').val('');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function excluir_calculo(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('dimensionamento/performance/excluirCalculo') ?>',
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