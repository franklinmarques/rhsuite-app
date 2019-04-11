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
                    <?php if ($tipo === 'clima'): ?>
                        <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                            <li><a href="<?= site_url('pesquisa/clima') ?>">Pesquisa de Clima Organizacional</a></li>
                            <li class="active">Modelos de pesquisa</li>
                        </ol>
                        <button class="btn btn-success" onclick="add_teste()"><i class="glyphicon glyphicon-plus"></i>
                            Adicionar modelo de pesquisa de clima
                        </button>
                        <button class="btn btn-default" onclick="javascript:history.back()"><i
                                    class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                        </button>
                    <?php elseif ($tipo === 'perfil'): ?>
                        <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                            <li><a href="<?= site_url('pesquisa/perfil') ?>">Pesquisa de Perfil Profissional</a></li>
                            <li class="active">Modelos de pesquisa</li>
                        </ol>
                        <button class="btn btn-success" onclick="add_teste()"><i class="glyphicon glyphicon-plus"></i>
                            Adicionar modelo de pesquisa de perfil
                        </button>
                        <button class="btn btn-default" onclick="javascript:history.back()"><i
                                    class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                        </button>
                    <?php else: ?>
                        <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                            <li class="active">Modelos de testes de seleção</li>
                        </ol>
                        <button class="btn btn-success" onclick="add_teste()"><i class="glyphicon glyphicon-plus"></i>
                            Adicionar modelo de teste
                        </button>
                    <?php endif; ?>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Nome do modelo</th>
                            <th>Tipo do modelo</th>
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
                            <h3 class="modal-title">Formulario de modelo de teste de seleção</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="<?= $empresa; ?>" id="empresa" name="empresa"/>
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome modelo</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Digite o nome do modelo de pesquisa"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <?php if ($tipo): ?>
                                        <input type="hidden" value="" name="tipo"/>
                                    <?php else: ?>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Tipo de teste</label>
                                            <div class="col-md-7">
                                                <select name="tipo" class="form-control">
                                                    <option value="M">Matemática</option>
                                                    <option value="R">Raciocínio Lógico</option>
                                                    <option value="P">Português</option>
                                                    <option value="C">Personalidade-Eneagrama</option>
                                                    <option value="L">Liderança</option>
                                                    <option value="D">Digitação</option>
                                                    <option value="I">Interpretação</option>
                                                    <option value="T">Conhecimento técnico</option>
                                                    <option value="A">Conhecimento comportamental</option>
                                                    <option value="E">Entrevista por competência</option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Observações</label>
                                        <div class="col-md-9">
                                            <textarea name="observacoes" class="form-control" rows="2"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-9 col-md-offset-3">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="perguntas" value="P">
                                                    Permitir a exibição de perguntas em ordem aleatória
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="alternativas" value="A">
                                                    Permitir a exibição de alternativas em ordem aleatória
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
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
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Modelos de Testes de Seleção';
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
                "info": false,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('recrutamento_modelos/ajax_list/' . $empresa) ?>",
                    "type": "POST"
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '80%',
                        targets: [0]
                    },
                    {
                        width: '20%',
                        targets: [1],
                        visible: '<?= empty($tipo) ?>'
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ]

            });

            //datepicker
            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
                orientation: "top auto",
                todayBtn: true
            });

        });

        function add_teste() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('[name="tipo"] option').prop('disabled', false);
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar modelo de teste de seleção'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_teste(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('recrutamento_modelos/ajax_edit/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('[name="id"]').val(data.id);
                    $('[name="empresa"]').val(data.id_usuario_EMPRESA);
                    $('[name="nome"]').val(data.nome);
                    $('[name="tipo"]').val(data.tipo);
                    $('[name="tipo"] option').prop('disabled', true);
                    $('[name="tipo"] option:selected').prop('disabled', false);
                    $('[name="observacoes"]').val(data.observacoes);
                    if (data.aleatorizacao === 'P' || data.aleatorizacao === 'T') {
                        $('[name="perguntas"]').prop('checked', true);
                    }
                    if (data.aleatorizacao === 'A' || data.aleatorizacao === 'T') {
                        $('[name="alternativas"]').prop('checked', true);
                    }

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar modelo de teste de seleção'); // Set title to Bootstrap modal title

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
                url = "<?php echo site_url('recrutamento_modelos/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('recrutamento_modelos/ajax_update') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
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

        function delete_teste(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('recrutamento_modelos/ajax_delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
//                    alert('Error deleting data');
                    }
                });

            }
        }

    </script>

<?php
require_once "end_html.php";
?>