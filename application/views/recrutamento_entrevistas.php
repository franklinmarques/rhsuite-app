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
                        <li><a href="<?= site_url('recrutamento_modelos') ?>">Modelos de Teste de Seleção</a></li>
                        <li class="active">Questões para teste de seleção - <?= $row->modelo ?></li>
                    </ol>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-success" onclick="add_pergunta()"><i
                                        class="glyphicon glyphicon-plus"></i> Adicionar pergunta
                            </button>
                        </div>
                    </div>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Pergunta</th>
                            <th>Competências</th>
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
                            <h5>Observação: As questões de uma entrevista por competências são compostas por quatro
                                perguntas complementares conforme modelo abaixo.</h5>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="<?= $row->id_modelo; ?>" id="id_modelo" name="id_modelo"/>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Cargo</label>
                                        <?php echo form_dropdown('cargo', $cargo, '', 'id="cargo"  class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Função</label>
                                        <?php echo form_dropdown('funcao', $funcao, '', 'id="funcao"  class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Competência</label>
                                        <?php echo form_dropdown('id_competencia', $id_competencia, '', 'id="id_competencia"  class="form-control filtro input-sm"'); ?>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <label class="control-label col-md-2">Competência</label>
                                    <div class="col-md-6">
                                        <input type="text" value="" name="competencia" class="form-control"/>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-body">
                                    <div class="form-group pergunta">
                                        <input type="hidden" value="" name="id[]"/>
                                        <h5><strong>Parte 1/4 - Situação</strong></h5>
                                        <span class="col-md-offset-1 col-md-11">Exemplo de pergunta: Descreva brevemente
                                            uma situação real de sua vida pessoal/profissional a qual você teve que
                                            lidar com a seguinte situação... (Complete a pergunta a partir deste ponto
                                            expondo a situação onde a competência alvo a ser identificada no candidato
                                            deveria ser colocada em prática).</span>
                                        <div class="col-md-offset-1 col-md-11">
                                            <textarea name="pergunta[]" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group pergunta">
                                        <input type="hidden" value="" name="id[]"/>
                                        <h5><strong>Parte 2/4 - Tarefas</strong></h5>
                                        <span class="col-md-offset-1 col-md-11">Exemplo de pergunta: Para a situação que você mencionou acima, descreva brevemente quais foram as tarefas, atividades, posturas as quais você teve que assumir para resolver a situação apresentada com sucesso pleno ou parcial.</span>
                                        <div class="col-md-offset-1 col-md-11">
                                            <textarea name="pergunta[]" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group pergunta">
                                        <input type="hidden" value="" name="id[]"/>
                                        <h5><strong>Parte 3/4 - Ações</strong></h5>
                                        <span class="col-md-offset-1 col-md-11">Exemplo de pergunta: Complementando as perguntas anteriores; descreva brevemente quais foram as ações e atitudes as quais você desenvolveu, participou ou coordenou. Mencione que tipos de dificuldaes ou problemas eventualmente apareceram e como você os resolveu.</span>
                                        <div class="col-md-offset-1 col-md-11">
                                            <textarea name="pergunta[]" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group pergunta">
                                        <input type="hidden" value="" name="id[]"/>
                                        <h5><strong>Parte 4/4 - Resultados</strong></h5>
                                        <span class="col-md-offset-1 col-md-11">Exemplo de pergunta: Descreva brevemente os resultados que você alcançou; mesmo que não tenham sido plenos ou apenas parciais. Com base nesta experiência, cite o que você teria feito de diferente e o porquê desta mudança; cite o que aprendeu com a experiência desta situação.</span>
                                        <div class="col-md-offset-1 col-md-11">
                                            <textarea name="pergunta[]" class="form-control" rows="1"></textarea>
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
            document.title = 'CORPORATE RH - LMS - Questões para teste de seleção - <?= $row->modelo ?>';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>

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
                    "url": "<?php echo site_url('recrutamento_questoes/ajax_entrevistas/' . $row->id_modelo) ?>",
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
                rowsGroup: [1, -1]

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

        $('.filtro').on('change', function () {
            $.ajax({
                url: '<?php echo site_url('recrutamento_questoes/filtrarCompetencias'); ?>',
                type: "POST",
                data: $('.filtro').serialize(),
                dataType: "JSON",
                success: function (json) {
                    $('#funcao').html($(json.funcao).html());
                    $('#id_competencia').html($(json.id_competencia).html());
                    if ($('#id_competencia').val().length > 0) {
                        $('[name="competencia"]').val($('#id_competencia').text());
                    } else {
                        $('[name="competencia"]').val('');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error select data');
                }
            });
        });

        $('[name="tipo_resposta"]').on('change', function () {
            ativar_campos(this.value);
        });

        function ativar_campos(value) {
            $('.alternativa input').prop('disabled', value === 'A' || value === 'N');
            $('#valor_min, #valor_max').prop('disabled', value === 'A' || value === 'U' || value === 'V');
            $('[name="justificativa"]').prop('disabled', value === 'A');
            $('.filtro').val('').trigger('change');
            if (value === 'N') {
                $('#valor_min').val('1').prev('label').html('Valor de ');
                $('#valor_max').val('10').next('span').html('');
            }
            if (value === 'M') {
                $('#valor_min').val('').prev('label').html('De ');
                $('#valor_max').val('').next('span').html(' seleções permitidas');
                $('#valor_min, #valor_max').prop({min: 1, max: 6});
            } else {
                $('#valor_min, #valor_max').prop({min: '', max: ''});
            }
        }

        function add_pergunta() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_modelo"])').val(''); // reset hidden input form on modals
            ativar_campos('A');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Adicionar pergunta'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_pergunta(id_modelo, competencia) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]:not([name="id_modelo"])').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('recrutamento_questoes/edit_entrevista/') ?>/",
                type: "GET",
                dataType: "JSON",
                data: {
                    id_modelo: id_modelo,
                    competencia: competencia
                },
                success: function (json) {
                    //ativar_campos(json.tipo_resposta);
                    $('[name="id_modelo"]').val(json.id_modelo);
                    $('[name="id_competencia"]').val(json.id_competencia);
                    $('[name="competencia"]').val(json.competencia);

                    $(json.perguntas).each(function (index, field) {
                        $('.pergunta:eq(' + index + ') input[name="id[]"]').val(field.id);
                        $('.pergunta:eq(' + index + ') textarea[name="pergunta[]"]').val(field.pergunta);
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
                url = "<?php echo site_url('recrutamento_questoes/add_entrevista') ?>";
            } else {
                url = "<?php echo site_url('recrutamento_questoes/update_entrevista') ?>";
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

        function delete_pergunta(id_modelo, competencia) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: '<?php echo site_url('recrutamento_questoes/delete_entrevista') ?>',
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id_modelo: id_modelo,
                        competencia: competencia
                    },
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