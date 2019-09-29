<?php
require_once APPPATH . 'views/header.php';
?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('cd/apontamento') ?>">Cuidadores - Apontamentos diários</a></li>
                        <li class="active">Gerenciar diretorias</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_diretoria()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar diretoria
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
                                            <?php echo form_dropdown('depto', $depto, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Diretoria de ensino/prefeitura</label>
                                            <?php echo form_dropdown('diretoria', $diretoria, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">coordenador</label>
                                            <?php echo form_dropdown('coordenador', $coordenador, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Contrato</label>
                                            <?php echo form_dropdown('contrato', $contrato, '', 'class="form-control input-sm filtro"'); ?>
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
                    <table id="table" class="table table-striped table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Diretoria de ensino/Prefeitura</th>
                            <th>Contrato</th>
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
                            <h3 class="modal-title">Editar diretoria</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Diretoria de Ensino</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Nome da Diretoria de Ensino"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Diretoria de Ensino (alias)</label>
                                        <div class="col-md-9">
                                            <input name="alias"
                                                   placeholder="Nome resumido da Diretoria de Ensino (alias)"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Departamento</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('depto', $deptos_disponiveis, $cuidadores, 'id="depto" class="estrutura form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Município</label>
                                        <div class="col-md-9">
                                            <input name="municipio" placeholder="Nome da área" id="area"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Contrato</label>
                                        <div class="col-md-9">
                                            <input name="contrato" placeholder="Nome do contrato" id="contrato"
                                                   class="form-control" type="text" size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Coordenador(a)</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_coordenador', $coordenadores, '', 'id="id_coordenador" class="form-control"'); ?>
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

        </section>
    </section>

<?php require_once APPPATH . 'views/end_js.php'; ?>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Cuidadores - Gerenciar diretorias';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

    <script>

        var save_method;
        var table;

        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 100,
                'lengthMenu': [[5, 10, 25, 50, 100, 500], [5, 10, 25, 50, 100, 500]],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('cd/diretorias/listar') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '60%',
                        'targets': [0]
                    },
                    {
                        'width': '40%',
                        'targets': [1]
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

        function atualizarFiltro() {
            $.ajax({
                'url': '<?php echo site_url('cd/diretorias/atualizarFiltro') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        $('#busca [name="diretoria"]').html($(json.diretoria).html());
                        $('#busca [name="coordenador"]').html($(json.coordenador).html());
                        $('#busca [name="contrato"]').html($(json.contrato).html());
                        reload_table();
                    }
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
                $("#busca [name='" + vals[0] + "']").val($("#busca [name='" + vals[0] + "'] option:first").val());
            });
            atualizarFiltro();
        });

        $('.estrutura').on('change', function () {
            atualizar_estrutura();
        });

        function atualizar_estrutura(id_coordenador = '') {
            $.ajax({
                'url': '<?php echo site_url('cd/diretorias/atualizarEstrutura') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'depto': $('#depto').val(),
                    'id_coordenador': id_coordenador
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        $('#form [name="id_coordenador"]').html($(json.id_coordenador).html());
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function add_diretoria() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form [name="id"]').val('');
            $('[name="tipo"] option').prop('disabled', false);
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar nova diretoria');
            $('.combo_nivel1').hide();
        }

        function edit_diretoria(id) {
            $('#form')[0].reset();
            $('#form input[name="id"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('cd/diretorias/editar') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $.each(json, function (key, value) {
                        if (key !== 'id_coordenador') {
                            $('#form [name="' + key + '"]').val(value);
                        }
                    });
                    atualizar_estrutura(json.id_coordenador);

                    $('#modal_form').modal('show');
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
                url = '<?php echo site_url('cd/diretorias/ajax_add') ?>';
            } else {
                url = '<?php echo site_url('cd/diretorias/ajax_update') ?>';
            }

            $.ajax({
                'url': '<?php echo site_url('cd/diretorias/salvar') ?>',
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSave').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        atualizarFiltro();
                    } else if (json.erro) {
                        alert(json.erro);
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


        function delete_diretoria(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('cd/diretorias/excluir') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        if (json.erro) {
                            alert(json.erro);
                        } else {
                            atualizarFiltro();
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>