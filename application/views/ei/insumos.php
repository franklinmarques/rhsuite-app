<?php require_once APPPATH . "views/header.php"; ?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('ei/apontamento') ?>">Apontamentos diários</a></li>
                        <li class="active">Gerenciar insumos</li>
                    </ol>
                    <button class="btn btn-success" onclick="add_insumo()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar insumo
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Nome do insumo</th>
                            <th>Tipo de insumo</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de insumo</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $empresa ?>" name="id_empresa"/>
                                <div class="form-body">
                                    <!--<div class="row form-group">
                                        <label class="control-label col-md-2">Aluno(a)</label>
                                        <div class="col-md-10">
                                            <?php /*//echo form_dropdown('id_aluno', $alunos, $aluno_selecionado, 'class="form-control"'); */ ?>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>-->
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome</label>
                                        <div class="col-md-10">
                                            <input name="nome" placeholder="Nome do insumo" class="form-control"
                                                   type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Tipo</label>
                                        <div class="col-md-10">
                                            <input name="tipo" placeholder="Tipo de insumo" class="form-control"
                                                   type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>

<?php require_once APPPATH . "views/end_js.php"; ?>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar insumos' + '<?= $nome_aluno ?>';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

    <script>

        var save_method;
        var table;

        $(document).ready(function () {

            table = $('#table').DataTable({
                info: false,
                processing: true,
                serverSide: true,
                order: [],
                language: {
                    url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                ajax: {
                    url: '<?php echo site_url('ei/insumos/ajax_list/') ?>',
                    type: 'POST'
                },
                columnDefs: [
                    {
                        width: '50%',
                        targets: [0, 1]
                    },
                    {
                        className: 'text-nowrap',
                        targets: [-1],
                        orderable: false,
                        searchable: false
                    }
                ]
            });

        });

        function add_insumo() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar insumo');
            $('.combo_nivel1').hide();
        }

        function edit_insumo(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                url: '<?php echo site_url('ei/insumos/ajax_edit') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (json) {
                    $.each(json, function (key, value) {
                        $('[name="' + key + '"]').val(value);
                    });
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar insumo');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }

        function reload_table() {
            table.ajax.reload(null, false);
        }

        function save() {
            $('#btnSave').text('Salvando...');
            $('#btnSave').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('ei/insumos/ajax_add') ?>';
            } else {
                url = '<?php echo site_url('ei/insumos/ajax_update') ?>';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: $('#form').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if (data.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }

                    $('#btnSave').text('Salvar');
                    $('#btnSave').attr('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar');
                    $('#btnSave').attr('disabled', false);
                }
            });
        }

        function delete_insumo(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: '<?php echo site_url('ei/insumos/ajax_delete') ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                        alert('Error deleting data');
                    }
                });

            }
        }

    </script>

<?php require_once APPPATH . "views/end_html.php"; ?>