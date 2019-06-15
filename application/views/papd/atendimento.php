<?php
require_once APPPATH . "views/header.php";
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
                        <li class="active">Gerenciar meus atendimentos a pacientes</li>
                        <?php $this->load->view('modal_processos', ['url' => 'papd/atendimentos']); ?>
                    </ol>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-info" onclick="add_atendimento()"><i
                                        class="glyphicon glyphicon-plus"></i> Cadastrar novo atendimento
                            </button>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="control-label">Data início</label>
                                        <input name="data_inicio" type="text" id="data_inicio" placeholder="dd/mm/aaaa"
                                               class="form-control filtro input-sm text-center data">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Data término</label>
                                        <input name="data_término" type="text" id="data_termino"
                                               placeholder="dd/mm/aaaa"
                                               class="form-control filtro input-sm text-center data">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Paciente</label>
                                        <?php echo form_dropdown('paciente', $pacientes, '', 'id="paciente" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label><br>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <button type="button" id="pesquisar" class="btn btn-sm btn-default"><i
                                                        class="glyphicon glyphicon-search"></i> Pesquisar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="control-label">Atividade/procedimento</label>
                                        <?php echo form_dropdown('atividade', $atividades, '', 'id="atividade" class="form-control filtro input-sm"'); ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="control-label">Deficiência</label>
                                        <?php echo form_dropdown('deficiencia', $deficiencia, '', 'id="deficiencia" class="form-control filtro input-sm"'); ?>
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
                    <h4>Atendimentos realizados</h4>
                    <table id="table" class="table table-hover table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Paciente</th>
                            <th class="text-center">Data/hora atendimento</th>
                            <th>Atividade/procedimento</th>
                            <th>Deficiência</th>
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
                            <h3 class="modal-title">Adicionar atendimento(s)</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome paciente</label>
                                        <div class="col-md-7">
                                            <?php echo form_dropdown('id_paciente', $pacientes, '', 'id="id_paciente" class="form-control"'); ?>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <fieldset id="atendimento0">
                                        <legend>
                                        </legend>
                                        <input type="hidden" value="" name="id[]"/>
                                        <div class="row form-group">
                                            <div class="col-md-2 text-nowrap">
                                                <h4 style="margin-top: 0px;">Atendimento 1</h4>
                                            </div>
                                            <label class="control-label col-md-2">Data e hora</label>
                                            <div class="col-md-3 form-inline">
                                                <input name="data_atendimento[]" id="data_atendimento"
                                                       placeholder="dd/mm/aaaa" class="form-control text-center data"
                                                       type="text" style="width: 110px;">
                                                <input name="hora_atendimento[]" id="hora_atendimento"
                                                       placeholder="hh:mm" class="form-control text-center hora"
                                                       type="text"
                                                       style="width: 80px;">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-4">Atividade/procedimento</label>
                                            <div class="col-md-8">
                                                <?php echo form_dropdown('id_atividade[]', $atividades, '', 'id="id_atividade" class="form-control"'); ?>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset id="atendimento1">
                                        <legend>
                                        </legend>
                                        <input type="hidden" value="" name="id[]"/>
                                        <div class="row form-group">
                                            <div class="col-md-2 text-nowrap">
                                                <h4 style="margin-top: 0px;">Atendimento 2</h4>
                                            </div>
                                            <label class="control-label col-md-2">Data e hora</label>
                                            <div class="col-md-3 form-inline">
                                                <input name="data_atendimento[]" id="data_atendimento"
                                                       placeholder="dd/mm/aaaa" class="form-control text-center data"
                                                       type="text" style="width: 110px;">
                                                <input name="hora_atendimento[]" id="hora_atendimento"
                                                       placeholder="hh:mm" class="form-control text-center hora"
                                                       type="text"
                                                       style="width: 80px;">
                                            </div>
                                            <div class="col-md-5 text-right">
                                                <button id="btnAtendimento1" type="button" class="btn btn-sm btn-info">
                                                    Copiar e incrementar valores do atendimento acima
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-4">Atividade/procedimento</label>
                                            <div class="col-md-8">
                                                <?php echo form_dropdown('id_atividade[]', $atividades, '', 'id="id_atividade" class="form-control"'); ?>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset id="atendimento2">
                                        <legend>
                                        </legend>
                                        <input type="hidden" value="" name="id[]"/>
                                        <div class="row form-group">
                                            <div class="col-md-2 text-nowrap">
                                                <h4 style="margin-top: 0px;">Atendimento 3</h4>
                                            </div>
                                            <label class="control-label col-md-2">Data e hora</label>
                                            <div class="col-md-3 form-inline">
                                                <input name="data_atendimento[]" id="data_atendimento"
                                                       placeholder="dd/mm/aaaa" class="form-control text-center data"
                                                       type="text" style="width: 110px;">
                                                <input name="hora_atendimento[]" id="hora_atendimento"
                                                       placeholder="hh:mm" class="form-control text-center hora"
                                                       type="text"
                                                       style="width: 80px;">
                                            </div>
                                            <div class="col-md-5 text-right">
                                                <button id="btnAtendimento2" type="button" class="btn btn-sm btn-info">
                                                    Copiar e incrementar valores do atendimento acima
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-4">Atividade/procedimento</label>
                                            <div class="col-md-8">
                                                <?php echo form_dropdown('id_atividade[]', $atividades, '', 'id="id_atividade" class="form-control"'); ?>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset id="atendimento3">
                                        <legend>
                                        </legend>
                                        <input type="hidden" value="" name="id[]"/>
                                        <div class="row form-group">
                                            <div class="col-md-2 text-nowrap">
                                                <h4 style="margin-top: 0px;">Atendimento 4</h4>
                                            </div>
                                            <label class="control-label col-md-2">Data e hora</label>
                                            <div class="col-md-3 form-inline">
                                                <input name="data_atendimento[]" id="data_atendimento"
                                                       placeholder="dd/mm/aaaa" class="form-control text-center data"
                                                       type="text" style="width: 110px;">
                                                <input name="hora_atendimento[]" id="hora_atendimento"
                                                       placeholder="hh:mm" class="form-control text-center hora"
                                                       type="text"
                                                       style="width: 80px;">
                                            </div>
                                            <div class="col-md-5 text-right">
                                                <button id="btnAtendimento3" type="button" class="btn btn-sm btn-info">
                                                    Copiar e incrementar valores do atendimento acima
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-4">Atividade/procedimento</label>
                                            <div class="col-md-8">
                                                <?php echo form_dropdown('id_atividade[]', $atividades, '', 'id="id_atividade" class="form-control"'); ?>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave2" onclick="save()" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </section>
    </section>
    <!--main content end-->

<?php
require_once APPPATH . "views/end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">


    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar pacientes';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/moment-timezone.js'); ?>"></script>

    <script>

        var table;

        $(document).ready(function () {
            $('.data').mask('00/00/0000');
            $('.hora').mask('00:00', {
                onKeyPress: function (v, ev, curField, opts) {
                    var mask = curField.data('mask').mask;
                    if (curField.data('mask-isZero') && curField.data('mask-keycode') == 8)
                        $(curField).val('');
                    else if (v) {
                        if (!/^(([0-9]$)|([0-9]{2}$))|(([0-9]{2})((:$)|(:[0-5]$)|(:[0-5][0-9]$)))$/.test(v))
                            v = v.substring(0, v.length - 1);
                        $(curField).val(v).data('mask-isZero', (v == '00:00'));
                    }
                }
            });
            $('.hora').on('blur', function () {
                var $this = $(this),
                    v = $this.val();
                v = v.length == 0 ? '00:00' :
                    (v.length == 1 ? '0' + v + ':00' :
                        (v.length == 2 ? v + ':00' :
                            (v.length == 3 ? v + '00' :
                                (v.length == 4 ? v + '0' : v))));
                $this.val(v);
            });


            //datatables
            table = $('#table').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "iDisplayLength": 25,
                fixedColumns: {
                    leftColumns: 1
                },
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('papd/atendimento/ajax_list') ?>",
                    "type": "POST",
                    timeout: 90000,
                    data: function (d) {
                        d.data_inicio = $('#data_inicio').val();
                        d.data_termino = $('#data_termino').val();
                        d.paciente = $('#paciente').val();
                        d.atividade = $('#atividade').val();
                        d.deficiencia = $('#deficiencia').val();
                        return d;
                    }
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '40%',
                        "targets": [0, 2] //last column
                    },
                    {
                        className: "text-center text-nowrap",
                        "targets": [1] //last column
                    },
                    {
                        width: '20%',
                        "targets": [3] //last column
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false
                    }
                ]
            });

            $('#pesquisar').on('click', function () {
                reload_table();
            });

            $('#limpa_filtro').on('click', function () {
                $('.filtro').val('');
                reload_table();
            });
        });

        $('#btnAtendimento1').on('click', function () {
            var dataAtendimento = $('#atendimento0 [name="data_atendimento[]"]').val();
            var horaAtendimento = $('#atendimento0 [name="hora_atendimento[]"]').val();
            var dt = moment.tz(dataAtendimento + ' ' + horaAtendimento + ':00', 'DD/MM/YYYY HH:mm:ss', 'America/Sao_Paulo').add(1, 'hour');
            var idAtividade = $('#atendimento0 [name="id_atividade[]"]').val();

            if (dt.isValid()) {
                $('#atendimento1 [name="data_atendimento[]"]').val(dt.format('DD/MM/YYYY'));
                $('#atendimento1 [name="hora_atendimento[]"]').val(dt.format('HH:mm'));
            } else {
                if (dataAtendimento && horaAtendimento) {
                    alert('O formato da data e hora no atendimento acima são inválidos');
                } else if (dataAtendimento) {
                    alert('O formato da data no atendimento acima é inválido');
                } else if (horaAtendimento) {
                    alert('O formato da hora no atendimento acima é inválido');
                }
            }
            $('#atendimento1 [name="id_atividade[]"]').val(idAtividade);
        });


        $('#btnAtendimento2').on('click', function () {
            var dataAtendimento = $('#atendimento1 [name="data_atendimento[]"]').val();
            var horaAtendimento = $('#atendimento1 [name="hora_atendimento[]"]').val();
            var dt = moment.tz(dataAtendimento + ' ' + horaAtendimento + ':00', 'DD/MM/YYYY HH:mm:ss', 'America/Sao_Paulo').add(1, 'hour');
            var idAtividade = $('#atendimento1 [name="id_atividade[]"]').val();

            if (dt.isValid()) {
                $('#atendimento2 [name="data_atendimento[]"]').val(dt.format('DD/MM/YYYY'));
                $('#atendimento2 [name="hora_atendimento[]"]').val(dt.format('HH:mm'));
            } else {
                if (dataAtendimento && horaAtendimento) {
                    alert('O formato da data e hora no atendimento acima são inválidos');
                } else if (dataAtendimento) {
                    alert('O formato da data no atendimento acima é inválido');
                } else if (horaAtendimento) {
                    alert('O formato da hora no atendimento acima é inválido');
                }
            }
            $('#atendimento2 [name="id_atividade[]"]').val(idAtividade);
        });


        $('#btnAtendimento3').on('click', function () {
            var dataAtendimento = $('#atendimento2 [name="data_atendimento[]"]').val();
            var horaAtendimento = $('#atendimento2 [name="hora_atendimento[]"]').val();
            var dt = moment.tz(dataAtendimento + ' ' + horaAtendimento + ':00', 'DD/MM/YYYY HH:mm:ss', 'America/Sao_Paulo').add(1, 'hour');
            var idAtividade = $('#atendimento2 [name="id_atividade[]"]').val();

            if (dt.isValid()) {
                $('#atendimento3 [name="data_atendimento[]"]').val(dt.format('DD/MM/YYYY'));
                $('#atendimento3 [name="hora_atendimento[]"]').val(dt.format('HH:mm'));
            } else {
                if (dataAtendimento && horaAtendimento) {
                    alert('O formato da data e hora no atendimento acima são inválidos');
                } else if (dataAtendimento) {
                    alert('O formato da data no atendimento acima é inválido');
                } else if (horaAtendimento) {
                    alert('O formato da hora no atendimento acima é inválido');
                }
            }
            $('#atendimento3 [name="id_atividade[]"]').val(idAtividade);
        });


        function add_atendimento() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#atendimento0 div h4').html('Atendimento 1');
            $('#atendimento1, #atendimento2, #atendimento3, #modal_form .modal-footer').show();
            $('#modal_form').modal('show'); // show bootstrap modal
            $('#modal_form .modal-title').text('Adicionar atendimento'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_atendimento(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#form input[type="hidden"]').val(''); // reset hidden input form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('papd/atendimento/ajax_edit') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data) {
                    $('#atendimento0 div h4').html('Atendimento');
                    $('#atendimento1, #atendimento2, #atendimento3, #modal_form .modal-footer').hide();
                    $('[name="id[]"]').val(data.id);
                    $('[name="id_paciente"]').val(data.id_paciente);
                    $('[name="id_atividade[]"]').val(data.id_atividade);
                    $('[name="data_atendimento[]"]').val(data.data_atendimento);
                    $('[name="hora_atendimento[]"]').val(data.hora_atendimento);

                    $('#modal_form').modal('show');
                    $('#modal_form .modal-title').text('Editar atendimento'); // Set title to Bootstrap modal title
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
            $('#btnSave, #btnSave2').text('Salvando...'); //change button text
            $('#btnSave, #btnSave2').attr('disabled', true); //set button disable
            var url;
            if (save_method === 'add') {
                url = "<?php echo site_url('papd/atendimento/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('papd/atendimento/ajax_update') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSave, #btnSave2').text('Salvar'); //change button text
                    $('#btnSave, #btnSave2').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave, #btnSave2').text('Salvar'); //change button text
                    $('#btnSave, #btnSave2').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_atendimento(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('papd/atendimento/ajax_delete') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id
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
require_once APPPATH . "views/end_html.php";
?>