<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Gerenciar Documentos de Candidatos</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>
    <style>
        @page {
            margin: 40px 20px;
        }

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

        tr.group, tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
</head>
<body style="color: #000;">
<div class="container-fluid">

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <br>
                    <div id="alert"></div>
                    <?php echo form_open_multipart('requisicaoPessoal_documentos/salvar', 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                    <div class="form-body">
                        <input type="hidden" name="id_candidato" value="<?= $idCandidato; ?>">
                        <div class="row form-group">
                            <label class="control-label col-sm-3" style="width: 20%;">Adicionar documento</label>
                            <div class="col-sm-6 controls">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">Selecionar arquivo</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="nome_arquivo" accept=".pdf"/>
                                            </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                       data-dismiss="fileinput">Remover</a>
                                </div>
                                <i class="help-block">Somente arquivos .pdf</i>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" name="submit" class="btn btn-success">
                                    <i class="fa fa-upload"></i> Importar
                                </button>
                            </div>
                            <div class="col-sm-1 text-right">
                                <button class="btn btn-default" onclick="javascript:window.close()"><i
                                            class="glyphicon glyphicon-remove"></i> Fechar
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <br>
                    <table id="table" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Candidato(a)</th>
                            <th>Documento</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal" id="modal_form" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-body form">
                            <p>Para visualizar, clique no arquivo abaixo apresentado.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="baixar(idArquivo)"
                                    data-dismiss="modal"><i class="glyphicon glyphicon-download-alt"></i> Continuar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>

    <div id="script_js" style="display: none;"></div>
    <script src="<?= base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileDownload-master/src/Scripts/jquery.fileDownload.js'); ?>"></script>

    <script src="<?php echo base_url("assets/js/ajax/ajax.form.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/ajax/ajax.upload.js"); ?>"></script>
    <script src="<?php echo base_url('assets/js/ajax/ajax.custom.js'); ?>"></script>

    <script src="<?php echo base_url("assets/js/jquery-migrate-1.2.1.js"); ?>"></script>

    <script>
        var table;
        var idArquivo;

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
                        'mRender': function (data) {
                            if (data === null) {
                                data = '<span class="text-muted">Nenhum documento encontrado</span>';
                            }
                            return data;
                        },
                        'targets': [1]
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


        function visualizar(id) {
            // $('#form')[0].reset();
            // $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            //$.ajax({
            //    url: '<?php //echo site_url('requisicaoPessoal_documentos/visualizar')   ?>//',
            //    type: 'POST',
            //    data: $('#form').serialize(),
            //    async: false,
            //    dataType: 'JSON',
            //    data: {id: id},
            //    success: function (json) {
            //        // $('#arquivox').attr('src', 'https:docs.google.com/gview?embedded=true&url=' + json.file);
            //
            //    },
            //    error: function (jqXHR, textStatus, errorThrown) {
            //        alert('Error adding / update data');
            //    }
            //});
            idArquivo = id;
            $('#modal_form').modal('show');
            $('.combo_nivel1').hide();
        }


        function reload_table() {
            table.ajax.reload(null, false);
        }

        //        function save() {
        //            $('#btnSave').text('Salvando...').attr('disabled', true);
        //
        //            $.ajax({
        //                url: '<?php //echo site_url('requisicaoPessoal_documentos/ajax_add')   ?>',
        //                type: 'POST',
        //                data: $('#form').serialize(),
        //                dataType: 'JSON',
        //                success: function (data) {
        //                    if (data.status) {
        //                        $('#modal_form').modal('hide');
        //                        reload_table();
        //                    }
        //
        //                    $('#btnSave').text('Salvar').attr('disabled', false); //set button enable
        //                },
        //                error: function (jqXHR, textStatus, errorThrown) {
        //                    alert('Error adding / update data');
        //                    $('#btnSave').text('Salvar').attr('disabled', false); //set button enable
        //                }
        //            });
        //        }

        function delete_documento(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: '<?php echo site_url('requisicaoPessoal_documentos/ajaxDelete') ?>/',
                    type: 'POST',
                    dataType: 'json',
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

        function baixar(id_documento) {
            $.fileDownload('<?= site_url('requisicaoPessoal_documentos/baixar') ?>/', {
                //            preparingMessageHtml: "Preparando o arquivo solicitado, aguarde...",
                //            failMessageHtml: "Erro ao baixar o arquivo, tente novamente.",
                httpMethod: "POST",
                data: {id: id_documento}
            });
        }

    </script>

</body>
</html>