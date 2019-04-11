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
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li class="active">Testes de Personalidade - Orientações Para a Vida</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_estilo()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar estilo
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Índice</th>
                            <th>Estilos - Orientações para a vida</th>
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
                            <h3 class="modal-title">Adicionar estilo</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Índice da resposta</label>
                                        <div class="col-md-2">
                                            <input name="indice_resposta" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome</label>
                                        <div class="col-md-10">
                                            <input name="nome" id="nome" placeholder="Nome do estilo"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Estilo de personalidade
                                            majoritário</label>
                                        <div class="col-md-10">
                                            <textarea name="estilo_personalidade_majoritario" class="form-control"
                                                      rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Estilo de personalidade secundário</label>
                                        <div class="col-md-10">
                                            <textarea name="estilo_personalidade_secundario" class="form-control"
                                                      rows="2"></textarea>
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

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_estilo_personalidade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar estilo personalidade</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_estilo_personalidade" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="estilo_personalidade"/>
                                <div class="form-body">
                                    <div class="row form-group" id="estilo_personalidade_majoritario">
                                        <label class="control-label col-md-2">Estilo </label>
                                        <div class="col-md-9">
                                            <textarea name="estilo_personalidade_majoritario" class="form-control"
                                                      rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group" id="estilo_personalidade_secundario">
                                        <label class="control-label col-md-2">Nome</label>
                                        <div class="col-md-9">
                                            <textarea name="estilo_personalidade_majoritario" class="form-control"
                                                      rows="2"></textarea>
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

        </section>
    </section>
    <!--main content end-->

<?php
require_once "end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Testes de Personalidade - Orientações Para a Vida';
        });</script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "info": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('pesquisa_lifo/ajaxEstilos/') ?>",
                    "type": "POST"
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        className: 'text-center',
                        targets: [0]
                    },
                    {
                        width: '100%',
                        targets: [1]
                    },
                    {
                        className: "text-center text-nowrap",
                        "targets": [-1], //last column
                    }
                ]
            });
        });

        function add_estilo() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $.ajax({
                url: "<?php echo site_url('pesquisa_lifo/ajaxCriarEstilo/') ?>",
                type: "POST",
                dataType: "json",
                success: function (json) {
                    $('[name="indice_resposta"]').val(json.indice_resposta);


                    $('#modal_form').modal('show'); // show bootstrap modal
                    $('#modal_form .modal-title').text('Adicionar estilo'); // Set Title to Bootstrap modal title
                    $('.combo_nivel1').hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_estilo(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('pesquisa_lifo/ajaxEditEstilo/') ?>",
                type: "POST",
                dataType: "json",
                data: {'id': id},
                success: function (json) {
                    $('[name="id"]').val(json.id);
                    $('[name="nome"]').val(json.nome);
                    $('[name="indice_resposta"]').val(json.indice_resposta);
                    $('[name="estilo_personalidade_majoritario"]').val(json.estilo_personalidade_majoritario);
                    $('[name="estilo_personalidade_secundario"]').val(json.estilo_personalidade_secundario);

                    $('#modal_form').modal('show');
                    $('#modal_form .modal-title').text('Editar estilo'); // Set title to Bootstrap modal title
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
                url = "<?php echo site_url('pesquisa_lifo/ajax_addEstilo') ?>";
            } else {
                url = "<?php echo site_url('pesquisa_lifo/ajax_updateEstilo') ?>";
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

        function delete_estilo(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('pesquisa_lifo/ajax_deleteEstilo') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {'id': id},
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

        function comportamentos(id_estilo) {
            var logado = <?php echo $this->session->userdata('logado') ? 'true' : 'false'; ?>;
            if (logado) {
                window.open("<?php echo site_url('pesquisa_lifo/comportamentos'); ?>/" + id_estilo, 'Comportamentos de estilos LIFO', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=100, LEFT=200, WIDTH=1000, HEIGHT=500');
            } else {
                window.open("<?php echo site_url('home/sair'); ?>");
            }
        }

    </script>

<?php
require_once "end_html.php";
?>