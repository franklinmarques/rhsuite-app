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
                        <li class="active">Gerenciamento de candidatos</li>
                    </ol>
                    <a class="btn btn-primary" href="<?= site_url('recrutamento_candidatos/novo') ?>"><i
                                class="glyphicon glyphicon-plus"></i> Adicionar candidato</a>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="control-label">Filtrar por estado</label>
                                        <?php echo form_dropdown('estado', $estado, '', 'id="estado" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="control-label">Filtrar por cidade</label>
                                        <?php echo form_dropdown('cidade', $cidade, '', 'id="cidade" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="control-label">Filtrar por bairro</label>
                                        <?php echo form_dropdown('bairro', $bairro, '', 'id="bairro" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="control-label">Filtrar por deficiência</label>
                                        <?php echo form_dropdown('deficiencia', $deficiencia, '', 'id="deficiencia" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="control-label">Filtrar por escolaridade</label>
                                        <?php echo form_dropdown('escolaridade', $escolaridade, '', 'id="escolaridade" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label><br>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                Limpar filtros
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Candidato</th>
                            <th>Cidade</th>
                            <th>Bairro</th>
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
                            <h3 class="modal-title">Formulario de inscrição</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome processo seletivo</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Nome do processo seletivo"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome requisitante externo</label>
                                        <div class="col-md-9">
                                            <input name="requisitante" placeholder="Nome do requisitante externo"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Data de início</label>
                                        <div class="col-md-3">
                                            <input name="data_inicio" id="data_inicio" placeholder="dd/mm/aaaa"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-2">Data de término</label>
                                        <div class="col-md-3">
                                            <input name="data_termino" id="data_termino" placeholder="dd/mm/aaaa"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Status do processo seletivo</label>
                                        <div class="col-md-3">
                                            <select name="status" class="form-control">
                                                <option value="">selecione ...</option>
                                                <option value="N">Não iniciado</option>
                                                <option value="A">Ativo</option>
                                                <option value="C">Cancelado</option>
                                                <option value="F">Fechado</option>

                                            </select>
                                        </div>
                                        <label class="control-label col-md-2">Tipo de vaga</label>
                                        <div class="col-md-3">
                                            <select name="tipo_vaga" class="form-control">
                                                <option value="">selecione ...</option>
                                                <option value="N">Interna</option>
                                                <option value="A">Externa</option>
                                            </select>
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
            document.title = 'CORPORATE RH - LMS - Gestão de processos seletivos';
        });</script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.s
                iDisplayLength: -1,
                lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>",
                    'searchPlaceholder': 'Nome, e-mail, cidade, bairro'
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('recrutamento_candidatos/ajax_list/' . $empresa) ?>",
                    "type": "POST",
                    "data": function (d) {
                        d.estado = $('#estado').val();
                        d.cidade = $('#cidade').val();
                        d.bairro = $('#bairro').val();
                        d.deficiencia = $('#deficiencia').val();
                        d.escolaridade = $('#escolaridade').val();

                        return d;
                    },
                    "dataSrc": function (json) {

                        $('#estado').html($(json.filtros.estado).html());
                        $('#cidade').html($(json.filtros.cidade).html());
                        $('#bairro').html($(json.filtros.bairro).html());
                        $('#deficiencia').html($(json.filtros.deficiencia).html());
                        $('#escolaridade').html($(json.filtros.escolaridade).html());

                        return json.data;
                    }
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '34%',
                        targets: [0]
                    },
                    {
                        width: '33%',
                        targets: [1, 2]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ]
            });

        });

        $('.filtro').on('change', function () {
            reload_table();
        });

        $('#limpa_filtro').on('click', function () {
            $('.filtro').val('');
            reload_table();
        });

        function add_candidato() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('#modal_form .modal-title').text('Adicionar candidato'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_candidato(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('recrutamento_candidatos/ajax_edit/') ?>/" + id,
                type: "POST",
                dataType: "json",
                success: function (data) {
                    $('[name="id"]').val(data.id);
                    $('[name="nome"]').val(data.nome);
                    $('[name="requisitante"]').val(data.requisitante);
                    $('[name="data_inicio"]').val(data.data_inicio);
                    $('[name="data_termino"]').val(data.data_termino);
                    $('[name="status"]').val(data.status);
                    $('[name="tipo_vaga"]').val(data.tipo_vaga);

                    $('#modal_form').modal('show');
                    $('#modal_form .modal-title').text('Editar candidato'); // Set title to Bootstrap modal title
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
                url = "<?php echo site_url('recrutamento_candidatos/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('recrutamento_candidatos/ajax_update') ?>";
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

        function delete_candidato(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('recrutamento_candidatos/ajax_delete/') ?>/" + id,
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

        function ver_modelos() {
            location.href = "<?php echo site_url('recrutamento_candidatos_modelos'); ?>";
        }

    </script>

<?php
require_once "end_html.php";
?>