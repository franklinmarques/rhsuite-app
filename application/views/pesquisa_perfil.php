<?php
require_once "header.php";
?>
    <style>
        <?php if ($this->agent->is_mobile()): ?>

        #table, .modal-header, #form {
            font-size: x-small;
        }

        <?php endif; ?>

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
                        <li class="active">Perfil Profissional - Pessoal</li>
                    </ol>
                    <br/>
                    <div class="form-group hidden-md hidden-lg">
                        <label class="form-label">Legenda:</label>
                        <p>
                            <button class="btn btn-success btn-xs" type="button">
                                <i class="glyphicon glyphicon-plus"></i> Resp.
                            </button>
                            <small> Responder pesquisa de perfil</small>
                        </p>
                        <hr>
                    </div>
                    <p class="text-danger">&emsp;<strong>REALIZE A AVALIAÇÃO DE PERFIL PROFISSIONAL PARA TODOS OS
                            COLABORADORES ABAIXO</strong></p>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Colaboradores avaliados</th>
                            <th>Cargo/função</th>
                            <th>Depto/área/setor</th>
                            <th class="text-center">Data início</th>
                            <th class="text-center">Data término</th>
                            <th>Ação</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- page end-->


            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulário de pesquisa</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" id="id_avaliador" name="id_avaliador" value="">
                                <table id="table_pesquisa" class="table table-striped table-condensed" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th colspan="2"><span id="instrucoes"></span></th>
                                    </tr>
                                    <tr>
                                        <th>Critérios de avaliação</th>
                                        <th>Opções</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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
            document.title = 'CORPORATE RH - LMS - Perfil Profissional - Pessoal';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

    <script>

        var save_method; //for save method string
        var table, table_pesquisa;
        var is_mobile = <?= $this->agent->is_mobile() ? 'true' : 'false'; ?>;

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "info": false,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "iDisplayLength": -1,
                "lengthMenu": [[5, 10, 25, 50, 100, 250, 500, -1], [5, 10, 25, 50, 100, 250, 500, 'Todos']],
                "lengthChange": (is_mobile === false),
                "searching": (is_mobile === false),
                "order": [], //Initial no order.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('pesquisa_perfil/ajax_list/') ?>",
                    "type": "POST"
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '40%',
                        targets: [0]
                    },
                    {
                        visible: (is_mobile === false),
                        width: '30%',
                        targets: [1, 2]
                    },
                    {
                        className: 'text-center',
                        orderable: (is_mobile === false),
                        targets: [3, 4]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ]
            });

            table_pesquisa = $('#table_pesquisa').DataTable({
                info: false,
                searching: false,
                ordering: false,
                iDisplayLength: -1,
                lengthChange: false,
                paging: false,
                bAutoWidth: false,
                "language": {
                    "loadingRecords": "Carregando...",
                    "processing": "Processando...",
                    "info": "Mostrando de _START_ até _END_ de _MAX_ critérios",
                    "infoEmpty": "Mostrando de 0 até 0 de 0 critérios",
                    "emptyTable": "Nenhum critério encontrado",
                    "paginate": {
                        "first": "Primeira",
                        "last": "Última",
                        "next": "Próximo",
                        "previous": "Anterior"
                    }
                },
                "columnDefs": [
                    {
                        width: '70%',
                        targets: [0]
                    },
                    {
                        width: '30%',
                        targets: [1]
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

        function edit_pesquisa(id) {
            $.ajax({
                url: "<?php echo site_url('pesquisa_perfil/ajax_edit/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (json) {
                    table_pesquisa.clear();
                    table_pesquisa.rows.add(json.data).draw();

                    $('#id_avaliador').val(id);
                    $('#instrucoes').html(json.instrucoes);
                    $('#btnSave').prop('disabled', json.data.length === 0);
                    $('#modal').modal('show');
                    $('.modal-title').text(json.title); // Set title to Bootstrap modal title
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

            var data = $.merge($('#id_avaliador'), table_pesquisa.$('input')).serialize();

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('pesquisa_perfil/ajax_save') ?>",
                type: "POST",
                data: data,
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal').modal('hide');
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

    </script>

<?php
require_once "end_html.php";
?>