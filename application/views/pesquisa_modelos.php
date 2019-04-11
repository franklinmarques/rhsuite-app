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
                            <li class="active">Modelos de Pesquisa/Avaliação</li>
                        </ol>
                        <button class="btn btn-info" onclick="add_pesquisa()"><i class="glyphicon glyphicon-plus"></i>
                            Adicionar modelo de pesquisa de clima
                        </button>
                        <button class="btn btn-default" onclick="javascript:history.back()"><i
                                    class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                        </button>
                    <?php elseif ($tipo === 'perfil'): ?>
                        <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                            <li><a href="<?= site_url('pesquisa/perfil') ?>">Pesquisa de Perfil Profissional</a></li>
                            <li class="active">Modelos de Pesquisa/Avaliação</li>
                        </ol>
                        <button class="btn btn-info" onclick="add_pesquisa()"><i class="glyphicon glyphicon-plus"></i>
                            Adicionar modelo de pesquisa de perfil
                        </button>
                        <button class="btn btn-default" onclick="javascript:history.back()"><i
                                    class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                        </button>
                    <?php else: ?>
                        <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                            <li class="active">Modelos de Pesquisa/Avaliação</li>
                        </ol>
                        <button class="btn btn-info" onclick="add_pesquisa()"><i class="glyphicon glyphicon-plus"></i>
                            Adicionar modelo
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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de modelo de pesquisa</h3>
                        </div>
                        <div class="modal-body form">
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
                                            <label class="control-label col-md-3">Tipo do modelo</label>
                                            <div class="col-md-9">
                                                <select name="tipo" class="form-control">
                                                    <option value="C">Pesquisa de Clima Organizacional</option>
                                                    <option value="P">Pesquisa de Perfil Profissional (uma única
                                                        resposta)
                                                    </option>
                                                    <option value="M">Pesquisa de Perfil Profissional (múltiplas
                                                        respostas)
                                                    </option>
                                                    <option value="E">Avaliação de Personalidade (Eneagrama)</option>
                                                    <option value="D">Avaliação de Personalidade (Tipologia Junguiana)
                                                    </option>
                                                    <option value="O">Avaliação de Personalidade (Orientações de Vida)
                                                    </option>
                                                    <option value="N">Avaliação de Potencial (NineBox)</option>
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
                                        <div class="checkbox col-md-offset-3">
                                            <label>
                                                <input type="checkbox" name="exclusao_bloqueada" value="1"> Bloquear
                                                alterações
                                            </label>
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
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Modelos de Peesquisa';
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
                    "url": "<?php echo site_url('pesquisa_modelos/ajax_list/' . $empresa . ($tipo ? '/' . $tipo : '')) ?>",
                    "type": "POST"
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '50%',
                        targets: [0]
                    },
                    {
                        width: '50%',
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

        function add_pesquisa() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('[name="tipo"]').val('<?= ($tipo == 'clima' ? 'C' : ($tipo == 'eneagrama' ? 'E' : ($tipo == 'perfil' ? 'P' : 'C'))) ?>');
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar modelo' + '<?= ($tipo ? " de $tipo" : "") ?>'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_pesquisa(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('pesquisa_modelos/ajax_edit/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('[name="id"]').val(data.id);
                    $('[name="empresa"]').val(data.id_usuario_EMPRESA);
                    $('[name="nome"]').val(data.nome);
                    $('[name="tipo"]').val(data.tipo);
                    $('[name="observacoes"]').val(data.observacoes);
                    $('[name="exclusao_bloqueada"]').prop('checked', data.exclusao_bloqueada === '1');

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar modelo' + '<?= ($tipo ? " de $tipo" : "") ?>'); // Set title to Bootstrap modal title

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
                url = "<?php echo site_url('pesquisa_modelos/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('pesquisa_modelos/ajax_update') ?>";
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

        function delete_pesquisa(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('pesquisa_modelos/ajax_delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
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

<?php
require_once "end_html.php";
?>