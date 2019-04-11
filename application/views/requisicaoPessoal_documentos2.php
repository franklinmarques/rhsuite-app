<?php require_once 'header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <?php if ($modulo): ?>
                            <li>Gerenciar Requisições de Pessoal - <?= $modulo; ?></li>
                        <?php else: ?>
                            <li>Gerenciar Requisições de Pessoal</li>
                        <?php endif; ?>
                        <li class="active">Gerenciar documentos</li>
                    </ol>
                    <?php echo form_open_multipart('requisicaoPessoal_documentos/salvar', 'method="POST" data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                    <div class="form-body">
                        <input type="hidden" name="id_candidato" value="<?= $idCandidato; ?>">
                        <div class="row form-group">
                            <label class="control-label col-sm-3" style="width: 20%;">Adicionar documento</label>
                            <div class="col-sm-7 controls">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecionar arquivo</span>
                                        <span class="fileinput-exists">Alterar</span>
                                        <input type="file" name="arquivo" accept=".*"/>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                       data-dismiss="fileinput">Remover</a>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" name="submit" class="btn btn-success">
                                    <i class="fa fa-upload"></i> Importar
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <table id="table" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Candidato(a)</th>
                            <th>Documento</th>
                            <th>Tipo</th>
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
                            <h3 class="modal-title">Adicionar documento</h3>
                        </div>
                        <div class="modal-body form">

                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>

<?php require_once 'end_js.php'; ?>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Requisições de Pessoal';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>

    <script>
        var table;

        $(document).ready(function () {

            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: -1,
                lengthMenu: [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, 'Todos']],
                language: {
                    url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                ajax: {
                    url: '<?php echo site_url('requisicaoPessoal_documentos/ajaxList/' . $idCandidato) ?>/',
                    type: 'POST'
                },
                columnDefs: [
                    {
                        'width': '50%',
                        'targets': [0, 1]
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


        function add_documento() {
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $('#modal_form').modal('show');
            $('.combo_nivel1').hide();
        }


        function reload_table() {
            table.ajax.reload(null, false);
        }

        function save() {
            $('#btnSave').text('Salvando...').attr('disabled', true);

            $.ajax({
                url: '<?php echo site_url('requisicaoPessoal_documentos/ajax_add') ?>',
                type: 'POST',
                data: $('#form').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    if (data.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }

                    $('#btnSave').text('Salvar').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_documento(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: '<?php echo site_url('requisicaoPessoal_documentos/ajax_delete') ?>/',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

    </script>

<?php require_once 'end_html.php'; ?>