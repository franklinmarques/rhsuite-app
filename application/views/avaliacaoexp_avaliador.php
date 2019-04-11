<?php
require_once "header.php";
?>
    <style>
        <?php if ($this->agent->is_mobile()): ?>

        #table, .modal-header, .form-horizontal {
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
                        <li class="active"><?= $titulo; ?></li>
                    </ol>
                    <?php if ($this->session->userdata('tipo') === 'empresa'): ?>
                        <button class="btn btn-success" onclick="add_pergunta()"><i
                                    class="glyphicon glyphicon-plus"></i> Adicionar pergunta
                        </button>
                    <?php endif; ?>
                    <!--<br/>
                    <br/>-->
                    <div class="form-group hidden-md hidden-lg">
                        <label class="form-label">Legenda:</label>
                        <br>
                        <button class="btn btn-success btn-xs" type="button">
                            <i class="glyphicon glyphicon-plus"></i><sup>1</sup>
                        </button>
                        <small> Avaliação parte 1</small>
                        <button class="btn btn-success btn-xs" type="button">
                            <i class="glyphicon glyphicon-plus"></i><sup>2</sup>
                        </button>
                        <small> Avaliação parte 2</small>
                        <button class="btn btn-info btn-xs" type="button">
                            <i class="glyphicon glyphicon-list-alt"></i>
                        </button>
                        <small> Relatório</small>
                        <hr>
                    </div>

                    <div class="row">
                        <form action="#" id="busca" style="padding-top: 0px; padding-bottom: 0px;">
                            <div class="col-md-6">
                                <div class="checkbox" style="padding-top: 0px; padding-bottom: 0px;">
                                    <label>
                                        <input type="checkbox" name="resultado" onchange="reload_table();"> Mostrar
                                        apenas avaliações não realizadas
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>

                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Colaborador avaliado</th>
                            <th>Cargo-função</th>
                            <th>Depto/área/setor</th>
                            <th>Data programada</th>
                            <th>Data de realização</th>
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
            <div class="modal fade" id="modal_avaliacao" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulário de avaliação</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_avaliacao" class="form-horizontal">
                                <input type="hidden" id="id_avaliador" name="id_avaliador" value="">
                                <table id="table_avaliacao" class="table table-striped table-condensed" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Critérios de avaliação</th>
                                        <th>Respostas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="salvar_avaliacao" onclick="save()" class="btn btn-primary">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_desempenho" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Resultado da avaliação </h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_desempenho" class="form-horizontal">
                                <input type="hidden" id="id_avaliador" name="id_avaliador" value="">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Aspectos positivos do colaborador</label>
                                    <div class="col-sm-9 controls">
                                        <textarea name="pontos_fortes" class="form-control" rows="1"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Aspectos negativos do colaborador</label>
                                    <div class="col-sm-9 controls">
                                        <textarea name="pontos_fracos" class="form-control" rows="1"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Feedback</label>
                                    <div class="col-sm-9 controls">
                                        <textarea name="observacoes" class="form-control" rows="1"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="salvar_desempenho" onclick="save_desempenho()"
                                    class="btn btn-primary">Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_periodo" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Resultado da avaliação</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_periodo" class="form-horizontal">
                                <input type="hidden" id="id_avaliado" name="id_avaliado" value="">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Parecer final</label>
                                    <?php if ($this->agent->is_mobile()): ?>
                                        <div class="col col-lg-9">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="parecer_final" id="parecer_final"
                                                           value="A"
                                                           checked=""> Em avaliação
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="parecer_final" id="parecer_final"
                                                           value="E">
                                                    Efetivado
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="parecer_final" id="parecer_final"
                                                           value="D">
                                                    Dispensado
                                                </label>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <label class="radio-inline">
                                            <input type="radio" name="parecer_final" id="parecer_final" value="A"
                                                   checked=""> Em avaliação
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="parecer_final" id="parecer_final" value="E">
                                            Efetivado
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="parecer_final" id="parecer_final" value="D">
                                            Dispensado
                                        </label>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Aspectos positivos do colaborador</label>
                                    <div class="col-sm-9 controls">
                                        <textarea name="pontos_fortes" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Aspectos negativos do colaborador</label>
                                    <div class="col-sm-9 controls">
                                        <textarea name="pontos_fracos" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Feedback da 1&ordf; Avaliação<br>
                                        <div class="help-block" id="data_feedback1">(data/feedback)</div>
                                    </label>
                                    <div class="col-sm-9 controls">
                                        <textarea name="feedback1" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Feedback da 2&ordf; Avaliação<br>
                                        <div class="help-block" id="data_feedback2">(data/feedback)</div>
                                    </label>
                                    <div class="col-sm-9 controls">
                                        <textarea name="feedback2" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Feedback da 3&ordf; Avaliação<br>
                                        <div class="help-block" id="data_feedback3">(data/feedback)</div>
                                    </label>
                                    <div class="col-sm-9 controls">
                                        <textarea name="feedback3" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="salvar_periodo" onclick="save_periodo()" class="btn btn-primary">
                                Salvar
                            </button>
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
            document.title = 'CORPORATE RH - LMS - <?= $titulo; ?>';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

    <script>

        var table, table_avaliacao;
        var isMobile = <?php echo $this->agent->is_mobile() ? 'false' : 'true'; ?>;

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "lengthChange": isMobile,
                iDisplayLength: -1,
                lengthMenu: [[5, 10, 25, 50, 100, 500, 1000, -1], [5, 10, 25, 50, 100, 500, 1000, 'Todos']],
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('avaliacaoexp_avaliador/ajax_list/' . $id_usuario . '/' . $tipo_modelo) ?>",
                    "type": "POST",
                    data: function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        visible: isMobile,
                        targets: [1, 2, 4]
                    },
                    {
                        width: '70%',
                        orderable: isMobile,
                        targets: [0]
                    },
                    {
                        width: '30%',
                        targets: [1, 2]
                    },
                    {
                        className: 'text-center',
                        orderable: isMobile,
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

            table_avaliacao = $('#table_avaliacao').DataTable({
                searching: false,
                ordering: false,
                lengthChange: false,
                "iDisplayLength": (isMobile ? 1000 : 100),
//            pageLength: 3,
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
                        width: '60%',
                        targets: [0]
                    },
                    {
                        width: '40%',
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

        function edit_avaliacao(id) {
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliador/ajax_edit/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (json) {
                    table_avaliacao.clear();
                    table_avaliacao.rows.add(json.data).draw();

                    $('#id_avaliador').val(id);
                    $('#salvar_avaliacao').prop('disabled', json.data.length === 0);
                    $('#modal_avaliacao').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_desempenho(id) {
            $('#form_desempenho')[0].reset(); // reset form on modals
            $('form_desempenho .form-group').removeClass('has-error'); // clear error class
            $('form_desempenho .help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliador/ajax_editDesempenho/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('[name="id_avaliador"]').val(data.id_avaliador);
                    $('[name="pontos_fortes"]').val(data.pontos_fortes);
                    $('[name="pontos_fracos"]').val(data.pontos_fracos);
                    $('[name="observacoes"]').val(data.observacoes);

                    $('#modal_desempenho').modal('show');

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }

        function edit_periodo(id) {
            $('#form_periodo')[0].reset(); // reset form on modals
            $('form_periodo .form-group').removeClass('has-error'); // clear error class
            $('form_periodo .help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliador/ajax_editPeriodo/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('[name="id_avaliado"]').val(data.id_avaliado);
                    $('[name="pontos_fortes"]').val(data.pontos_fortes);
                    $('[name="pontos_fracos"]').val(data.pontos_fracos);
                    $('[name="feedback1"]').val(data.feedback1);
                    $('[name="feedback2"]').val(data.feedback2);
                    $('[name="feedback3"]').val(data.feedback3);
                    $('#data_feedback1').html(data.data_feedback1 === null ? '(data/feedback)' : 'Editado em ' + data.data_feedback1);
                    $('#data_feedback2').html(data.data_feedback2 === null ? '(data/feedback)' : 'Editado em ' + data.data_feedback2);
                    $('#data_feedback3').html(data.data_feedback3 === null ? '(data/feedback)' : 'Editado em ' + data.data_feedback3);
                    $('[name="parecer_final"][value="' + data.parecer_final + '"]').attr("checked", true);

                    $('#modal_periodo').modal('show');

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
            $('#salvar_avaliacao').text('Salvando...'); //change button text
            $('#salvar_avaliacao').attr('disabled', true); //set button disable

            var data = $.merge($('#id_avaliador'), table_avaliacao.$('input, textarea')).serialize();

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliador/ajax_save') ?>",
                type: "POST",
                data: data,
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_avaliacao').modal('hide');
                        reload_table();
                    }

                    $('#salvar_avaliacao').text('Salvar'); //change button text
                    $('#salvar_avaliacao').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#salvar_avaliacao').text('Salvar'); //change button text
                    $('#salvar_avaliacao').attr('disabled', false); //set button enable
                }
            });
        }

        function save_desempenho() {
            $('#salvar_desempenho').text('Salvando...'); //change button text
            $('#salvar_desempenho').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliador/ajax_saveDesempenho') ?>",
                type: "POST",
                data: $('#form_desempenho').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_desempenho').modal('hide');
                        reload_table();
                    }

                    $('#salvar_desempenho').text('Salvar'); //change button text
                    $('#salvar_desempenho').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#salvar_desempenho').text('Salvar'); //change button text
                    $('#salvar_desempenho').attr('disabled', false); //set button enable
                }
            });
        }

        function save_periodo() {
            $('#salvar_periodo').text('Salvando...'); //change button text
            $('#salvar_periodo').attr('disabled', true); //set button disable

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_avaliador/ajax_savePeriodo') ?>",
                type: "POST",
                data: $('#form_periodo').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_periodo').modal('hide');
                        reload_table();
                    }

                    $('#salvar_periodo').text('Salvar'); //change button text
                    $('#salvar_periodo').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#salvar_periodo').text('Salvar'); //change button text
                    $('#salvar_periodo').attr('disabled', false); //set button enable
                }
            });
        }

    </script>

<?php
require_once "end_html.php";
?>