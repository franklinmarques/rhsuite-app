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
                <button class="btn btn-success" onclick="add_avaliacao()"><i class="glyphicon glyphicon-plus"></i> Adicionar avaliação</button>
                <button class="btn btn-success" onclick="ver_modelos()"><i class="glyphicon glyphicon-list-alt"></i> Ver modelos de avaliação</button>
                <br />
                <br />
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Nome avaliação</th>
                            <th>Modelo</th>
                            <th>Início</th>
                            <th>Término</th>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Formulario de avaliação</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="" name="id"/> 
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Nome avaliação</label>
                                    <div class="col-md-9">
                                        <input name="nome" id="nome" placeholder="Nome da avaliação" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Modelo avaliação</label>
                                    <div class="col-md-9">
                                        <select name="id_modelo" class="form-control">
                                            <option value="">-- selecione --</option>
                                            <?php foreach ($modelos as $modelo) { ?>
                                                <option value="<?= $modelo->id ?>"><?= $modelo->nome ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Data de início</label>
                                    <div class="col-md-3">
                                        <input name="data_inicio" id="data_inicio" placeholder="dd/mm/aaaa" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Data de término</label>
                                    <div class="col-md-3">
                                        <input name="data_termino" id="data_termino" placeholder="dd/mm/aaaa" class="form-control" type="text">
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
        document.title = 'CORPORATE RH - LMS - <?= $titulo; ?>';
    });</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>

    var save_method; //for save method string
    var table;
    var avaliadores;

    $('#data_inicio, #data_termino').mask('00/00/0000');

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            "info": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('avaliacaoexp/ajax_list/' . $empresa) ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '50%',
                    targets: [0, 1]
                },
                {
                    className: 'text-center',
                    targets: [2, 3]
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

    function add_avaliacao()
    {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('#modal_form .modal-title').text('Adicionar avaliação'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_avaliacao(id)
    {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp/ajax_edit/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data)
            {
                $('[name="id"]').val(data.id);
                $('[name="id_modelo"]').val(data.id_modelo);
                $('[name="nome"]').val(data.nome);
                $('[name="data_inicio"]').val(data.data_inicio);
                $('[name="data_termino"]').val(data.data_termino);

                $('#modal_form').modal('show');
                $('#modal_form .modal-title').text('Editar avaliação'); // Set title to Bootstrap modal title
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
            url = "<?php echo site_url('avaliacaoexp/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('avaliacaoexp/ajax_update') ?>";
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

    function delete_avaliacao(id)
    {
        if (confirm('Deseja remover?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('avaliacaoexp/ajax_delete') ?>/" + id,
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

    function ver_modelos() {
        location.href = "<?php echo site_url('avaliacaoexp_modelos/gerenciar/1'); ?>";
    }

</script>

<?php
require_once "end_html.php";
?>