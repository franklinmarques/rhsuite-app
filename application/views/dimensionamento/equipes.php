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
                    <li class="active">Gerenciar Equipes</li>
                </ol>
                <div class="row">
                    <div class="col-md-4">
                        <label for="depto">Departamento</label>
                        <?php echo form_dropdown('', $depto, '', 'id="depto" class="form-control input-sm filtro" onchange="montar_filtros();" autocomplete="off"'); ?>
                    </div>
                    <div class="col-md-4">
                        <label for="area">Area</label>
                        <?php echo form_dropdown('', $area, '', 'id="area" class="form-control input-sm filtro" onchange="montar_filtros();" autocomplete="off"'); ?>
                    </div>
                    <div class="col-md-4">
                        <label for="setor">Setor</label>
                        <?php echo form_dropdown('', $setor, '', 'id="setor" class="form-control input-sm filtro" onchange="reload_table();" autocomplete="off"'); ?>
                    </div>
                </div>
                <hr>
                <div id="esconder_itens" class="form-inline">
                    <button id="btnAdd" class="btn btn-info" onclick="add_equipe()"><i
                                class="glyphicon glyphicon-plus" disabled></i> Nova equipe
                    </button>
                </div>
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                        <th>Colaboradores</th>
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
                        <h3 class="modal-title">Gerenciar equipe</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="" name="id_depto"/>
                            <input type="hidden" value="" name="id_area"/>
                            <input type="hidden" value="" name="id_setor"/>
                            <div class="form-body" style="padding-top: 0px;">
                                <div class="row form-group">
                                    <label class="control-label col-sm-2">Nome</label>
                                    <div class="col-sm-7">
                                        <input name="nome" class="form-control" type="text"
                                               placeholder="Nome da equipe">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-sm-3 text-right">
                                        <button type="button" class="btn btn-success" id="btnSave" onclick="save()">
                                            Salvar
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-sm-2 text-nowrap">Qtde. componentes</label>
                                    <div class="col-sm-2">
                                        <input name="total_componentes" class="form-control valor" type="text" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                        <?php echo form_multiselect('id_usuario[]', [], [], 'id="id_usuarios" class="form-control demo1"'); ?>
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
<link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar Equipes de Dimensionamento';
    });
</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>

<script>

    var save_method;
    var table;


    $(document).ready(function () {

        table = $('#table').DataTable({
            'processing': true,
            'serverSide': true,
            'order': [[0, 'asc']],
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('dimensionamento/equipes/ajaxList/') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.depto = $('#depto').val();
                    d.area = $('#area').val();
                    d.setor = $('#setor').val();

                    return d;
                }
            },
            'columnDefs': [
                {
                    'width': '50%',
                    'targets': [0, 2]
                },
                {
                    'className': 'text-center text-nowrap',
                    'targets': [1],
                    'orderable': false,
                    'searchable': false
                }
            ],
            'rowsGroup': [0, 1],
            'preDrawCallback': function () {
                $('#btnAdd').prop('disabled', $('#depto').val() === '');
            }
        });


        demo1 = $('.demo1').bootstrapDualListbox({
            'nonSelectedListLabel': 'Colaboradores disponíveis',
            'selectedListLabel': 'Colaboradores selecionados',
            'preserveSelectionOnMove': 'moved',
            'moveOnSelect': false,
            'filterPlaceHolder': 'Filtrar',
            'helperSelectNamePostfix': false,
            'selectorMinimalHeight': 132,
            'infoText': false
        });

    });


    function montar_filtros() {
        $.ajax({
            'url': '<?php echo site_url('dimensionamento/equipes/filtrarEstrutura') ?>',
            'type': 'POST',
            'data': {
                'depto': $('#depto').val(),
                'area': $('#area').val(),
                'setor': $('#setor').val()
            },
            'dataType': 'json',
            'beforeSend': function () {
                $('.filtro, #btnAdd, .btnEdit').prop('disabled', true);
            },
            'success': function (json) {
                $('#area').html($(json.area).html());
                $('#setor').html($(json.setor).html());
                reload_table();
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            },
            'complete': function () {
                $('.filtro, #btnAdd, .btnEdit').prop('disabled', false);
            }
        });
    }


    function add_equipe() {
        save_method = 'add';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            'url': '<?php echo site_url('dimensionamento/equipes/ajaxNew') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'depto': $('#depto').val(),
                'area': $('#area').val(),
                'setor': $('#setor').val()
            },
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                    return false;
                }
                $.each(json, function (key, value) {
                    $('#form [name="' + key + '"]').val(value);
                });

                $('#id_usuarios').html($(json.membros).html());
                demo1.bootstrapDualListbox('refresh', true);

                $('.modal-title').text('Adicionar equipe');
                $('#modal_form').modal('show');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_equipe(id) {
        save_method = 'update';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            'url': '<?php echo site_url('dimensionamento/equipes/ajaxEdit') ?>',
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

                $('#id_usuarios').html($(json.membros).html());
                demo1.bootstrapDualListbox('refresh', true);

                $('.modal-title').text('Gerenciar equipe');
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
        $('#btnSave').text('Salvando...').attr('disabled', true);
        var url;

        if (save_method === 'add') {
            url = '<?php echo site_url('dimensionamento/equipes/ajaxAdd') ?>';
        } else {
            url = '<?php echo site_url('dimensionamento/equipes/ajaxUpdate') ?>';
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


    function delete_equipe(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('dimensionamento/equipes/ajaxDelete') ?>',
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

