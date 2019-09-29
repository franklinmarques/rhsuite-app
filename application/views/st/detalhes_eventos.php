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
                    <li><a href="<?= site_url('st/apontamento') ?>">Serviços Terceirizados - Apontamentos diários</a>
                    </li>
                    <li class="active">Gerenciar detalhes de eventos</li>
                </ol>
                <button class="btn btn-info" onclick="add_detalhe_evento()"><i class="glyphicon glyphicon-plus"></i>
                    Adicionar detalhe de evento
                </button>
                <button class="btn btn-default" onclick="javascript:history.back()"><i
                            class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                </button>
                <br/>
                <br/>
                <table id="table" class="table table-striped table-condensed" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
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
                        <h3 class="modal-title">Formulario de evento de apontamento</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                            <input type="hidden" value="" name="id"/>
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Código</label>
                                    <div class="col-md-5">
                                        <input name="codigo" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Nome</label>
                                    <div class="col-md-10">
                                        <input name="nome" placeholder="Digite o nome do evento" class="form-control"
                                               type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar</button>
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
        document.title = 'CORPORATE RH - LMS - Gerenciar detalhes de eventos';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script>

    var save_method; //for save method string
    var table;

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            'processing': true,
            'serverSide': true,
            'order': [['0', 'asc']],
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('st/detalhesEventos/listar') ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'width': '80%',
                    'targets': [1]
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


    function add_detalhe_evento() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar detalhe de evento de apontamento'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }


    function edit_detalhe_evento(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            'url': '<?php echo site_url('st/detalhesEventos/editar') ?>',
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

                $('#modal_form').modal('show');
                $('.modal-title').text('Editar detalhe de evento de apontamento'); // Set title to Bootstrap modal title

            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }


    function save() {
        $.ajax({
            'url': '<?php echo site_url('st/detalhesEventos/salvar') ?>',
            'type': 'POST',
            'data': $('#form').serialize(),
            'dataType': 'json',
            'beforeSend': function () {
                $('#btnSave').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                } else if (json.status) {
                    $('#modal_form').modal('hide');
                    reload_table();
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


    function delete_detalhe_evento(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('st/detalhesEventos/excluir') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                }
            });

        }
    }

</script>

<?php require_once APPPATH . 'views/end_html.php'; ?>
