<?php
require_once "header.php";
?>
<style>
    .btn-success{
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
    .text-nowrap{
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
                    <li><a href="<?= site_url('avaliacaoexp_modelos/gerenciar/' . $id_avaliado) ?>">Modelos de avaliação</a></li>
                    <li class="active">Questões Avaliação Período de Experiência - <?= $avaliacao ?></li>
                </ol>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-success" onclick="add_pergunta()"><i class="glyphicon glyphicon-plus"></i> Adicionar pergunta</button>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-info text-right"><i class="glyphicon glyphicon-comment"></i> Ver modelo de avaliação</button>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-6">
                        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Pergunta</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <button id="buu" class="btn btn-primary" onclick="editar_resposta()" style="margin:15px 0;"><i class="glyphicon glyphicon-pencil"></i> Editar respostas</button>
                        <table id="table2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Resposta</th>
                                    <th class="text-left">Fator de ponderação</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- page end-->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_form" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                                        <textarea name="pergunta" class="form-control" rows="3"></textarea>
                                        <span class="help-block"></span>
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

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_resposta" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Formulario de respostas</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form_resposta" class="form-horizontal">
                            <input type="hidden" value="<?= $id_modelo; ?>" id="id_modelo" name="id_modelo"/>
                            <div class="form-body">
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 1</label>
                                    <div class="col-md-6">
                                        <input name="alternativa[]" class="form-control" type="text" value="">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Fator de ponderação</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 2</label>
                                    <div class="col-md-6">
                                        <input name="alternativa[]" class="form-control" type="text" value="">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Fator de ponderação</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 3</label>
                                    <div class="col-md-6">
                                        <input name="alternativa[]" class="form-control" type="text" value="">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Fator de ponderação</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 4</label>
                                    <div class="col-md-6">
                                        <input name="alternativa[]" class="form-control" type="text" value="">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Fator de ponderação</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 5</label>
                                    <div class="col-md-6">
                                        <input name="alternativa[]" class="form-control" type="text" value="">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Fator de ponderação</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 6</label>
                                    <div class="col-md-6">
                                        <input name="alternativa[]" class="form-control" type="text" value="">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Fator de ponderação</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave2" onclick="salvar_resposta()" class="btn btn-primary">Salvar</button>
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
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Avaliações por período de experiência';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>

<script>

    var save_method; //for save method string
    var table, table2;

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
                "url": "<?php echo site_url('avaliacaoexp_questoes/ajax_list/' . $id_modelo) ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '100%',
                    targets: [0]
                },
                {
                    className: "text-nowrap",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                }
            ]
        });

        //datatables
        table2 = $('#table2').DataTable({
            "info": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            searching: false,
            paging: false,
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('avaliacaoexp_questoes/ajax_respostaList/' . $id_modelo) ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '100%',
                    targets: [0]
                },
//                {
//                    className: 'text-right',
//                    targets: [1]
//                }
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

    function add_pergunta()
    {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('#form input[type="hidden"]:not([name="id_modelo"])').val(''); // reset hidden input form on modals
        $('#form .form-group').removeClass('has-error'); // clear error class
        $('#form .help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('#modal_form .modal-title').text('Adicionar pergunta'); // Set Title to Bootstrap modal title
        $('#modal_form .combo_nivel1').hide();
    }

    function edit_pergunta(id)
    {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('#form input[type="hidden"]:not([name="id_modelo"])').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_questoes/ajax_edit/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data)
            {
                $('[name="id"]').val(data.id);
                $('[name="id_modelo"]').val(data.id_modelo);
                $('[name="pergunta"]').val(data.pergunta);
                
                $('#modal_form').modal('show');
                $('#modal_form .modal-title').text('Editar pergunta'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function editar_resposta()
    {
        save_method = 'update';
        $('#form_resposta')[0].reset(); // reset form on modals
        $('#form_resposta input[type="hidden"]:not([name="id_modelo"])').val(''); // reset hidden input form on modals
        $('#form_resposta .form-group').removeClass('has-error'); // clear error class
        $('#form_resposta .help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_questoes/ajax_respostaEdit/' . $id_modelo) ?>",
            type: "GET",
            dataType: "JSON",
            success: function (data)
            {
                $(data).each(function (index, field) {
                    $('.alternativa:eq(' + index + ') input[name="id_alternativa[]"]').val(field.id);
                    $('.alternativa:eq(' + index + ') input[name="alternativa[]"]').val(field.alternativa);
                    $('.alternativa:eq(' + index + ') input[name="peso[]"]').val(field.peso);
                });

                $('#modal_resposta').modal('show');
                $('#modal_resposta .modal-title').text('Editar respostas'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    function save()
    {
        $('#btnSave').text('Salvando...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable 
        var url;

        if (save_method === 'add') {
            url = "<?php echo site_url('avaliacaoexp_questoes/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('avaliacaoexp_questoes/ajax_update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                }

                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 
            }
        });
    }

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    function salvar_resposta()
    {
        $('#btnSave2').text('Salvando...'); //change button text
        $('#btnSave2').attr('disabled', true); //set button disable 

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_questoes/ajax_respostaUpdate') ?>",
            type: "POST",
            data: $('#form_resposta').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_resposta').modal('hide');
                    table2.ajax.reload(null, false); //reload datatable ajax 
                }

                $('#btnSave2').text('Salvar'); //change button text
                $('#btnSave2').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave2').text('Salvar'); //change button text
                $('#btnSave2').attr('disabled', false); //set button enable 
            }
        });
    }

    function delete_pergunta(id)
    {
        if (confirm('Deseja remover?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp_questoes/ajax_delete') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data)
                {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });

        }
    }

</script>

<?php
require_once "end_html.php";
?>