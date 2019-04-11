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

        .table > tbody > tr > td.colaborador-success,
        .table > tbody > tr > td.date-width-success {
            color: #fff;
            background-color: #5cb85c !important;
        }

        .table > tbody > tr > td.colaborador-success:hover,
        .table > tbody > tr > td.date-width-success:hover {
            background-color: #47a447 !important;
        }

        .table > tbody > tr > td.colaborador-primary,
        .table > tbody > tr > td.date-width-primary {
            color: #fff;
            background-color: #027EEA !important;
        }

        .table > tbody > tr > td.colaborador-primary:hover,
        .table > tbody > tr > td.date-width-primary:hover {
            background-color: #007EEB;
        }

        .table > tbody > tr > td.colaborador-disabled,
        .table > tbody > tr > td.date-width-disabled {
            color: #fff;
            background-color: #5C679A !important;
        }

        .table > tbody > tr > td.colaborador-disabled:hover,
        .table > tbody > tr > td.date-width-disabled:hover {
            background-color: #576192;
        }

        .table > tbody > tr > td.date-width-warning {
            /*color: #fff;*/
            background-color: #f0ad4e !important;
        }

        .table > tbody > tr > td.date-width-warning:hover {
            background-color: #ed9c28 !important;
        }

        .table > tbody > tr > td.date-width-danger {
            color: #fff;
            background-color: #d9534f !important;
        }

        .table > tbody > tr > td.date-width-danger:hover {
            background-color: #d2322d !important;
        }

        .table > tbody > tr > td.date-width-disabled {
            color: #fff;
            background-color: #8866bb !important;
        }

        .table > tbody > tr > td.date-width-disabled:hover {
            background-color: #7253b0 !important;
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
                        <li class="active">Log de usuários</li>
                    </ol>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="control-label">Departamento</label>
                                            <?php echo form_dropdown('depto', $depto, $depto_atual, 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Área</label>
                                            <?php echo form_dropdown('area', $area, $area_atual, 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Setor</label>
                                            <?php echo form_dropdown('setor', $setor, $setor_atual, 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="control-label">Cargo</label>
                                            <?php echo form_dropdown('cargo', $cargo, '', 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Função</label>
                                            <?php echo form_dropdown('funcao', $funcao, '', 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <label>&nbsp;</label><br>
                                            <button type="button" id="pesquisar" class="btn btn-sm btn-default"><i
                                                        class="glyphicon glyphicon-search"></i> Pesquisar
                                            </button>
                                            <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                Limpar
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                    data-target="#modal_delete">
                                                <i class="glyphicon glyphicon-trash"></i> Excluir todos
                                            </button>
                                            <!--<a id="pdf" style="float: right;" class="btn btn-sm btn-danger"
                                               href="<? /*= site_url('log_usuarios/pdf/'); */ ?>" title="Exportar PDF"><i
                                                        class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Nome do perfil</th>
                                <th nowrap>Data e hora de acesso</th>
                                <th nowrap>Data e hora de saída</th>
                                <th class="text-center">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
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
                            <h3 class="modal-title">Detalhes do log de usuário</h3>
                        </div>
                        <div class="modal-body">
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-4">Nome do perfil:</label>
                                    <div class="col-md-7">
                                        <span id="nome"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-4">Data e hora de acesso:</label>
                                    <div class="col-md-7">
                                        <span id="data_acesso"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-4">Última atualização:</label>
                                    <div class="col-md-7">
                                        <span id="data_atualizacao"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-4">Data e hora de saída:</label>
                                    <div class="col-md-7">
                                        <span id="data_saida"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-4">Endereço IP:</label>
                                    <div class="col-md-7">
                                        <span id="endereco_ip"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-4">Interface:</label>
                                    <div class="col-md-7">
                                        <span id="agente_usuario"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-4">Status da sessão:</label>
                                    <div class="col-md-7">
                                        <span id="status"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" id="atualizar" onclick="atualizar()">
                                <i class="glyphicon glyphicon-refresh"></i> Atualizar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Modal -->
            <div class='modal fade' id='modal_delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
                 aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Excluir todos</h3>
                        </div>
                        <div class='modal-body form'>
                            <form id="form" autocomplete="off">
                                <div class="row form-group">
                                    <div class="col-md-12 form-inline">
                                        A partir da data &ensp;<input name="data_inicio" id="data_inicio"
                                                                      placeholder="dd/mm/aaaa"
                                                                      class="form-control text-center"
                                                                      style="width: 150px;"
                                                                      maxlength="10"
                                                                      autocomplete="off" type="text">
                                        &ensp; até a data &ensp;<input name="data_termino" id="data_termino"
                                                                       placeholder="dd/mm/aaaa"
                                                                       class="form-control text-center"
                                                                       style="width: 150px;"
                                                                       maxlength="10"
                                                                       autocomplete="off" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-12">
                                            <span>
                                                Obs.: Para excluir todos os logs, deixar os campos em branco.
                                            </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-danger' onclick="delete_all()">Excluir
                            </button>
                            <button type='button' class='btn btn-default' data-dismiss="modal">Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Bootstrap modal -->

        </section>
    </section>
    <!--main content end-->

<?php
require_once "end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Log de usuários';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var id_usuario;
        var table;

        $('#data_inicio, #data_termino').mask('00/00/0000');

        $(document).ready(function () {

            table = $('#table').DataTable({
                "info": false,
                "processing": true,
                "serverSide": true,
                iDisplayLength: 1000,
                lengthMenu: [[5, 10, 25, 50, 100, 500, 1000, 1500, 2000, -1], [5, 10, 25, 50, 100, 500, 1000, 1500, 2000, 'Todos']],
                "order": [[1, 'desc'], [2, 'desc']], //Initial no order.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                "ajax": {
                    "url": "<?php echo site_url('log_usuarios/listar/') ?>",
                    "type": "POST",
                    data: function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                "columnDefs": [
                    {
                        width: '100%',
                        targets: [0]
                    },
                    {
                        className: 'text-center',
                        targets: [1, 2]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1],
                        "orderable": false,
                        "searchable": false
                    }
                ]
            });

        });

        $('#pesquisar').on('click', function () {
            reload_table();
        });

        $('#limpa_filtro').on('click', function () {
            $('#busca select').val('');
            reload_table();
        });

        function atualizarFiltro() {
            $.ajax({
                url: "<?php echo site_url('log_usuarios/atualizarFiltro') ?>",
                type: "POST",
                dataType: "JSON",
                data: $('#busca').serialize(),
                success: function (json) {
                    $('[name="area"]').html($(json.area).html());
                    $('[name="setor"]').html($(json.setor).html());
                    $('[name="cargo"]').html($(json.cargo).html());
                    $('[name="funcao"]').html($(json.funcao).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function detalhes(id) {
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                url: "<?php echo site_url('log_usuarios/detalhes') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (json) {
                    $('#nome').html(json.nome);
                    $('#data_acesso').html(json.data_hora_acesso);
                    $('#data_atualizacao').html(json.data_hora_atualizacao);
                    $('#data_saida').html(json.data_hora_saida);
                    $('#endereco_ip').html(json.endereco_ip);
                    $('#agente_usuario').html(json.agente_usuario);
                    if (json.status === 'logado') {
                        $('#status').html('<i class="fa fa-circle" style="color: #5cb85c;"></i> &ensp;' + json.status);
                    } else {
                        $('#status').html('<i class="fa fa-circle" style="color: #ccc;"></i> &ensp;' + json.status);
                    }

                    $('#modal_form').modal('show');
                    id_usuario = id;

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                complete: function () {
                    $('#atualizar').attr('disabled', false).html('<i class="glyphicon glyphicon-refresh"></i> Atualizar');
                }
            });
        }

        function atualizar() {
            $('#atualizar').attr('disabled', true).html('<i class="glyphicon glyphicon-refresh"></i> Atualizando...');
            detalhes(id_usuario);
        }

        function reload_table() {
            table.ajax.reload(null, false);
        }

        function delete_log(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: "<?php echo site_url('log_usuarios/excluir') ?>",
                    type: "POST",
                    dataType: "JSON",
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

        function delete_all() {
            if (confirm('Deseja remover todos?')) {
                $.ajax({
                    url: "<?php echo site_url('log_usuarios/limpar') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: $('#form').serialize(),
                    success: function (data) {
                        $('#modal_delete').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });

            }
        }

    </script>

<?php
require_once "end_html.php";
?>