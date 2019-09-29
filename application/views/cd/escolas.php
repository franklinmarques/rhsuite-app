<?php require_once APPPATH . 'views/header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('cd/apontamento') ?>">Cuidadores - Apontamentos diários</a></li>
                        <li class="active">Gerenciar Unidades de Ensino</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_escola()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar unidade de ensino
                    </button>
                    <a class="btn btn-primary" href="<?= site_url('cd/escolas/importacao') ?>"><i
                                class="glyphicon glyphicon-import"></i>
                        Importar escolas
                    </a>
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
                                        <div class="col-md-5">
                                            <label class="control-label">Diretoria de ensino/Prefeitura</label>
                                            <?php echo form_dropdown('diretoria', $diretoria, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="control-label">Supervisor(a)</label>
                                            <?php echo form_dropdown('supervisor', $supervisor, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
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
                            <th>Diretoria de Ensino</th>
                            <th>Unidade de ensino</th>
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
                            <h3 class="modal-title">Editar unidade escolar</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome unidade escolar<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Nome da unidade escolar"
                                                   class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Endereço</label>
                                        <div class="col-md-9">
                                            <input name="endereco" placeholder="Endereço" class="form-control"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Número</label>
                                        <div class="col-md-2">
                                            <input name="numero" class="form-control" type="number" min="0">
                                        </div>
                                        <label class="control-label col-md-1">Compl.</label>
                                        <div class="col-md-6">
                                            <input name="complemento" placeholder="Complemento" class="form-control"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Bairro</label>
                                        <div class="col-md-9">
                                            <input name="bairro" placeholder="Bairro" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Município<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <input name="municipio" placeholder="Município" class="form-control"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Telefone</label>
                                        <div class="col-md-3">
                                            <input name="telefone" placeholder="Telefone" class="form-control"
                                                   type="text">
                                        </div>
                                        <label class="control-label col-md-3">Telefone contato</label>
                                        <div class="col-md-3">
                                            <input name="telefone_contato" placeholder="Tel. contato"
                                                   class="form-control"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">CEP</label>
                                        <div class="col-md-2">
                                            <input name="cep" placeholder="CEP" class="form-control" type="text">
                                        </div>
                                        <label class="control-label col-md-1">E-mail</label>
                                        <div class="col-md-6">
                                            <input name="email" placeholder="E-mail" class="form-control" type="text"
                                                   size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cliente/Diretoria de ensino<span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_diretoria', $id_diretoria, '', 'class="form-control"'); ?>
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
            document.title = 'CORPORATE RH - LMS - Cuidadores - Gerenciar Unidades de Ensino';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;

        $(document).ready(function () {

            $('[name="cep"]').mask('00000-000');

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 500,
                'lengthMenu': [[5, 10, 25, 50, 100, 500, 1000], [5, 10, 25, 50, 100, 500, 1000]],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('cd/escolas/listar') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#busca').serialize();
                        d.id_diretoria = '<?= $this->uri->rsegment(3, '') ?>';
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '40%',
                        'targets': [0, 1]
                    },
                    {
                        'width': '20%',
                        'targets': [2]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ]
            });

            atualizarFiltro();
        });


        $('#limpa_filtro').on('click', function () {
            var busca = unescape($('#busca').serialize());
            $.each(busca.split('&'), function (index, elem) {
                var vals = elem.split('=');
                $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
            });
            atualizarFiltro();
        });


        function atualizarFiltro() {
            $.ajax({
                'url': '<?php echo site_url('cd/escolas/atualizarFiltro/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        $('#busca [name="diretoria"]').html($(json.diretoria).html());
                        $('#busca [name="supervisor"]').html($(json.supervisor).html());
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function add_escola() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('[name="tipo"] option').prop('disabled', false);
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar unidade escolar');
            $('.combo_nivel1').hide();
        }


        function edit_escola(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('cd/escolas/editar') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }
                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').is(':checkbox') === false) {
                            $('#form [name="' + key + '"]').val(value);
                        } else {
                            $('#form [name="' + key + '"]').prop('checked', value === '1');
                        }
                    });

                    $('.modal-title').text('Editar unidade escolar');
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
            $.ajax({
                'url': '<?php echo site_url('cd/escolas/salvar') ?>',
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
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_escola(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('cd/escolas/excluir') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        if (json.erro) {
                            alert(json.erro);
                        } else {
                            reload_table();
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