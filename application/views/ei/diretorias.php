<?php require_once APPPATH . 'views/header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('ei/apontamento') ?>">Apontamentos diários</a></li>
                        <li class="active">Gerenciar contratos</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_cliente()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar cliente
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="control-label">Departamento</label>
                                            <?php echo form_dropdown('busca[depto]', $depto, '', 'onchange="atualizar_filtro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Área/cliente</label>
                                            <?php echo form_dropdown('busca[diretoria]', $diretoria, '', 'onchange="atualizar_filtro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">coordenador</label>
                                            <?php echo form_dropdown('busca[coordenador]', $coordenador, '', 'onchange="atualizar_filtro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Contrato</label>
                                            <?php echo form_dropdown('busca[contrato]', $contrato, '', 'onchange="atualizar_filtro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-1">
                                            <label>&nbsp;</label><br>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                    Limpar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th colspan="2">Área/cliente</th>
                            <th colspan="2" class="text-center">Contrato</th>
                            <th colspan="7" class="text-center">Valor período</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Ações</th>
                            <th>Nome</th>
                            <th>Ações</th>
                            <th>Ano/semestre</th>
                            <th>Tipo funcionário</th>
                            <th>Valor Fat.1</th>
                            <th>Valor Pagto.1</th>
                            <th>Valor Fat.2</th>
                            <th>Valor Pagto.2</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar cliente</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cliente<span class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Nome da Diretoria de Ensino"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cliente (alias)</label>
                                        <div class="col-md-9">
                                            <input name="alias"
                                                   placeholder="Nome resumido da Diretoria de Ensino (alias)"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Departamento<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('depto', $deptos_disponiveis, $cuidadores, 'id="depto" class="estrutura form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Município/Estado<span class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <input name="municipio" placeholder="Nome do município e do estado"
                                                   id="area"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Coordenador(a)</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_coordenador', $coordenadores, '', 'id="id_coordenador" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Senha exclusão</label>
                                        <div class="col-md-7">
                                            <input name="senha_exclusao" placeholder="Senha para exclusão de O.S."
                                                   class="form-control" type="text" size="255">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal_contrato" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar contrato</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_contrato" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_cliente"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Contrato<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <input name="contrato" placeholder="Nome do contrato"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Data início<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-3">
                                            <input name="data_inicio"
                                                   placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" type="text" size="100">
                                        </div>
                                        <label class="control-label col-md-3">Data término<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-3">
                                            <input name="data_termino"
                                                   placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" type="text" size="100">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3"><strong>1&ordm; reajuste</strong></label>
                                        <label class="control-label col-md-1">Data</label>
                                        <div class="col-md-3">
                                            <input name="data_reajuste1" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" maxlength="10"
                                                   autocomplete="off" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Índice</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input name="indice_reajuste1" type="text"
                                                       class="form-control text-right reajuste">
                                                <span class="input-group-addon" id="basic-addon1">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3"><strong>2&ordm; reajuste</strong></label>
                                        <label class="control-label col-md-1">Data</label>
                                        <div class="col-md-3">
                                            <input name="data_reajuste2" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" maxlength="10"
                                                   autocomplete="off" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Índice</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input name="indice_reajuste2" type="text"
                                                       class="form-control text-right reajuste">
                                                <span class="input-group-addon" id="basic-addon1">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3"><strong>3&ordm; reajuste</strong></label>
                                        <label class="control-label col-md-1">Data</label>
                                        <div class="col-md-3">
                                            <input name="data_reajuste3" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" maxlength="10"
                                                   autocomplete="off" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Índice</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input name="indice_reajuste3" type="text"
                                                       class="form-control text-right reajuste">
                                                <span class="input-group-addon" id="basic-addon1">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3"><strong>4&ordm; reajuste</strong></label>
                                        <label class="control-label col-md-1">Data</label>
                                        <div class="col-md-3">
                                            <input name="data_reajuste4" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" maxlength="10"
                                                   autocomplete="off" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Índice</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input name="indice_reajuste4" type="text"
                                                       class="form-control text-right reajuste">
                                                <span class="input-group-addon" id="basic-addon1">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3"><strong>5&ordm; reajuste</strong></label>
                                        <label class="control-label col-md-1">Data</label>
                                        <div class="col-md-3">
                                            <input name="data_reajuste5" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center data" maxlength="10"
                                                   autocomplete="off" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Índice</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input name="indice_reajuste5" type="text"
                                                       class="form-control text-right valor">
                                                <span class="input-group-addon" id="basic-addon1">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save_contrato()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal_valores" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar valores de contrato</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_valores" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_contrato"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Contrato:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-static contrato"></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Ano/semestre<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-2">
                                            <input name="ano" class="form-control text-center ano" placeholder="aaaa"
                                                   type="text">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="radio-inline">
                                                <input type="radio" name="semestre" value="1" checked> 1&ordm; semestre
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="semestre" value="2"> 2&ordm; semestre
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Tipo prestador<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-7">
                                            <?php echo form_dropdown('id_funcao', array(), '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Valor de faturamento 1</label>
                                        <div class="col-sm-4 input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input name="valor" type="text" value=""
                                                   class="form-control text-right valor">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Valor de pagamento 1</label>
                                        <div class="col-sm-4 input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input name="valor_pagamento" type="text" value=""
                                                   class="form-control text-right valor">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Valor de faturamento 2</label>
                                        <div class="col-sm-4 input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input name="valor2" type="text" value=""
                                                   class="form-control text-right valor">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Valor de pagamento 2</label>
                                        <div class="col-sm-4 input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input name="valor_pagamento2" type="text" value=""
                                                   class="form-control text-right valor">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveValores" onclick="save_valores()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>

<?php require_once APPPATH . 'views/end_js.php'; ?>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar diretorias';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;

        $(document).ready(function () {

            $('.data').mask('00/00/0000');
            $('.reajuste').mask('##0,00000000');
            $('.valor').mask('###.###.##0,00', {'reverse': true});
            $('.ano').mask('0000', {'reverse': true});


            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 100,
                'lengthMenu': [[5, 10, 25, 50, 100, 500], [5, 10, 25, 50, 100, 500]],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('ei/diretorias/ajax_list') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '50%',
                        'targets': [0, 5]
                    },
                    {
                        'mRender': function (data) {
                            if (data === null) {
                                data = '<span class="text-muted">Nenhum contrato encontrado</span>';
                            }
                            return data;
                        },
                        'targets': [2]
                    },
                    {
                        'className': 'text-center',
                        'targets': [4, 6, 7, 8, 9]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [1, 3, -1]
                    }
                ],
                'rowsGroup': [1, 0, 3, 2, 4]
            });

        });


        function atualizar_filtro() {
            $.ajax({
                'url': '<?php echo site_url('ei/diretorias/atualizar_filtro') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'success': function (json) {
                    $('[name="busca[diretoria]"]').html($(json.diretoria).html());
                    $('[name="busca[coordenador]"]').html($(json.coordenador).html());
                    $('[name="busca[contrato]"]').html($(json.contrato).html());
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        $('#limpa_filtro').on('click', function () {
            var busca = unescape($('#busca').serialize());
            $.each(busca.split('&'), function (index, elem) {
                var vals = elem.split('=');
                $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
            });
            atualizar_filtro();
        });


        $('.estrutura').on('change', function () {
            atualizar_estrutura();
        });


        function atualizar_estrutura(id_coordenador = '') {
            $.ajax({
                'url': '<?php echo site_url('ei/diretorias/ajax_estrutura') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'depto': $('#depto').val(),
                    'id_coordenador': id_coordenador
                },
                'success': function (json) {
                    $('[name="id_coordenador"]').html($(json.id_coordenador).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function add_cliente() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('[name="tipo"] option').prop('disabled', false);
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar cliente');
            $('.combo_nivel1').hide();
        }


        function add_contrato(id) {
            save_method = 'add';
            $('#form_contrato')[0].reset();
            $('#form_contrato input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('[name="id_cliente"]').val(id);
            $('#modal_contrato').modal('show');
            $('.modal-title').text('Adicionar contrato');
            $('.combo_nivel1').hide();
        }


        function add_valor_faturamento(id) {
            save_method = 'add';
            $('#form_valores')[0].reset();
            $('#form_valores input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                'url': '<?php echo site_url('ei/diretorias/ajax_valores') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('#modal_valores [name="id_contrato"]').val(id);
                    $('.contrato').html(json.contrato);
                    $('#modal_valores [name="id_funcao"]').html($(json.funcoes).html());

                    $('.modal-title').text('Adicionar valores para faturamento');
                    $('.combo_nivel1').hide();
                    $('#modal_valores').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_cliente(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('ei/diretorias/ajax_edit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $.each(json, function (key, value) {
                        if (key !== 'id_coordenador') {
                            $('#modal_form [name="' + key + '"]').val(value);
                        }
                    });
                    atualizar_estrutura(json.id_coordenador);

                    $('.modal-title').text('Editar cliente');
                    $('#modal_form').modal('show');

                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_contrato(id) {
            $('#form_contrato')[0].reset();
            $('#form_contrato input[type="hidden"]').val('');
            $('#form_contrato .form-group').removeClass('has-error');
            $('#form_contrato .help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('ei/diretorias/ajax_editContrato') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $.each(json, function (key, value) {
                        $('#modal_contrato [name="' + key + '"]').val(value);
                    });

                    $('.modal-title').text('Editar contrato - ' + json.contrato);
                    $('#modal_contrato').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_valor_faturamento(id) {
            $('#form_valores')[0].reset();
            $('#form_valores input[type="hidden"]').val('');
            $('#form_valores .form-group').removeClass('has-error');
            $('#form_valores .help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('ei/diretorias/ajax_editValores') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('#modal_valores [name="id"]').val(json.id);
                    $('#modal_valores [name="id_contrato"]').val(json.id_contrato);
                    $('.contrato').html(json.contrato);
                    $('#modal_valores [name="ano"]').val(json.ano);
                    $('#modal_valores [name="semestre"][value="' + json.semestre + '"]').prop('checked', true);
                    $('#modal_valores [name="id_funcao"]').html($(json.funcoes).html());
                    $('#modal_valores [name="valor"]').val(json.valor);
                    // $('#modal_valores [name="valor_faturamento"]').val(json.valor_faturamento);
                    $('#modal_valores [name="valor_pagamento"]').val(json.valor_pagamento);
                    $('#modal_valores [name="valor2"]').val(json.valor2);
                    $('#modal_valores [name="valor_pagamento2"]').val(json.valor_pagamento2);

                    $('.contrato').html(json.contrato);
                    $('.modal-title').text('Editar valores para faturamento');
                    $('#modal_valores').modal('show');
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
            var url;
            if (save_method === 'add') {
                url = '<?php echo site_url('ei/diretorias/ajax_add') ?>';
            } else {
                url = '<?php echo site_url('ei/diretorias/ajax_update') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSave').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        atualizar_filtro();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_contrato() {
            var url;
            if (save_method === 'add') {
                url = '<?php echo site_url('ei/diretorias/ajax_addContrato') ?>';
            } else {
                url = '<?php echo site_url('ei/diretorias/ajax_updateContrato') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form_contrato').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveContrato').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_contrato').modal('hide');
                        atualizar_filtro();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveContrato').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_valores() {
            var url;
            if (save_method === 'add') {
                url = '<?php echo site_url('ei/diretorias/ajax_addValores') ?>';
            } else {
                url = '<?php echo site_url('ei/diretorias/ajax_updateValores') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form_valores').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveValores').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_valores').modal('hide');
                        atualizar_filtro();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSaveValores').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_cliente(id) {
            if (confirm('Deseja remover o cliente?')) {
                $.ajax({
                    'url': '<?php echo site_url('ei/diretorias/ajax_delete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        $('#modal_form').modal('hide');
                        atualizar_filtro();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }


        function delete_contrato(id) {
            if (confirm('Deseja remover o contrato?')) {
                $.ajax({
                    'url': '<?php echo site_url('ei/diretorias/ajax_deleteContrato') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        $('#modal_contrato').modal('hide');
                        atualizar_filtro();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }


        function delete_valor_faturamento(id) {
            if (confirm('Deseja remover o valor de faturamento?')) {
                $.ajax({
                    'url': '<?php echo site_url('ei/diretorias/ajax_deleteValores') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>