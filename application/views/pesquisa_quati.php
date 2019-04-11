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

        tr.group, tr.group:hover {
            background-color: #ddd !important;
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
                        <li><a href="<?= site_url('pesquisa_modelos') ?>">Modelos de pesquisa</a></li>
                        <li class="active">Questões para Teste de Personalidade - Tipologia Junguiana</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-info" onclick="add_pergunta()"><i
                                        class="glyphicon glyphicon-plus"></i> Adicionar pergunta
                            </button>
                            <button class="btn btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        </div>
                    </div>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Pergunta</th>
                            <th nowrap>Resposta</th>
                            <th nowrap>Peso</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- page end-->

            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de perguntas</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $id_modelo; ?>" id="id_modelo" name="id_modelo"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Pergunta</label>
                                        <div class="col-md-10">
                                            <textarea name="pergunta" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group alternativa">
                                        <input type="hidden" value="" name="id_alternativa[]"/>
                                        <label class="control-label col-md-2">Resposta A</label>
                                        <div class="col-md-7">
                                            <input name="alternativa[]" class="form-control" type="text" value="">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Peso</label>
                                        <div class="col-md-2">
                                            <input name="peso[]" class="form-control peso" type="text" value="">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group alternativa">
                                        <input type="hidden" value="" name="id_alternativa[]"/>
                                        <label class="control-label col-md-2">Resposta B</label>
                                        <div class="col-md-7">
                                            <input name="alternativa[]" class="form-control" type="text" value="">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Peso</label>
                                        <div class="col-md-2">
                                            <input name="peso[]" class="form-control peso" type="text" value="">
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

<?php
require_once "end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Questões para Teste de Personalidade - Tipologia Junguiana';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $(document).ready(function () {

            $('.peso').mask('00');

            //datatables
            table = $('#table').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "iDisplayLength": -1,
                "lengthMenu": [[5, 10, 25, 50, 100, 250, 500, -1], [5, 10, 25, 50, 100, 250, 500, 'Todos']],
                "order": [], //Initial no order.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('pesquisaQuati/ajaxList2/' . $id_modelo) ?>",
                    "type": "POST"
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '50%',
                        targets: [0, 1]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ],
                rowsGroup: [0, 1, -1]

            });

        });

        function add_pergunta() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_modelo"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar pergunta'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_pergunta(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_modelo"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('pesquisaQuati/ajaxEdit/') ?>/" + id,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $('[name="id"]').val(data.id);
                    $('[name="id_modelo"]').val(data.id_modelo);
                    $('[name="pergunta"]').val(data.pergunta);

                    $(data.alternativas).each(function (index, field) {
                        $('.alternativa:eq(' + index + ') input[name="id_alternativa[]"]').val(field.id);
                        $('.alternativa:eq(' + index + ') input[name="alternativa[]"]').val(field.alternativa);
                        $('.alternativa:eq(' + index + ') input[name="peso[]"]').val(field.peso);
                    });

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar pergunta'); // Set title to Bootstrap modal title

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('pesquisaQuati/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('pesquisaQuati/ajaxUpdate') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "json",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_pergunta(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('pesquisaQuati/ajaxDelete') ?>/" + id,
                    type: "POST",
                    dataType: "json",
                    success: function (json) {
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

<?php
require_once "end_html.php";
?>