<?php
require_once APPPATH . "views/header.php";
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
                <ol class="breadcrumb" style="margin-bottom: 5px;">
                    <li class="active">Avaliações de desempenho por competências</li>
                </ol>
                <button class="btn btn-success" onclick="add_avaliacao()"><i class="glyphicon glyphicon-plus"></i> Adicionar avaliação</button>
                <br />
                <br />
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Avaliação</th>
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
                        <h3 class="modal-title">Formulario de avaliações</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="<?= $id_usuario; ?>" id="id_usuario_EMPRESA" name="id_usuario_EMPRESA"/>
                            <input type="hidden" value="" name="id"/> 
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="control-label col-md-2">Avaliação</label>
                                    <div class="col-md-9">
                                        <input name="nome" placeholder="Digite a Avaliação" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Cargo/função</label>
                                    <div class="col-md-9">
                                        <?php echo form_dropdown('id_cargo', $id_cargo, '', 'id="id_cargo" class="form-control"'); ?>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Descrição</label>
                                    <div class="col-md-9">
                                        <textarea name="descricao" class="form-control" rows="3"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Data início</label>
                                    <div class="col-md-3">
                                        <input name="data_inicio" id="data_inicio" placeholder="dd/mm/aaaa" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-2">Data término</label>
                                    <div class="col-md-3">
                                        <input name="data_termino" id="data_termino" placeholder="dd/mm/aaaa" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Status</label>
                                    <div class="col-md-9">
                                        <label><input type="checkbox" name="status" class="status" value="1"> Ativo</label>
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
require_once APPPATH . "views/end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Avaliações de desempenho por competências';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

<script>

    var save_method; //for save method string
    var table;

    $(document).ready(function () {

        $('#data_inicio, #data_termino').mask('00/00/0000');

        //datatables
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "iDisplayLength": 25,
            "order": [], //Initial no order.
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('competencias/avaliacao/ajax_list/' . $id_usuario) ?>",
                "type": "POST",
                "timeout": 9000
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
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar avaliação'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_avaliacao(id)
    {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('competencias/avaliacao/ajax_edit/') ?>",
            type: "POST",
            dataType: "JSON",
            "timeout": 9000,
            data: {
                id: id
            },
            success: function (data)
            {
                $('[name="id"]').val(data.id);
                $('[name="nome"]').val(data.nome);
                $('[name="id_cargo"]').val(data.id_cargo);
                $('[name="descricao"]').val(data.descricao);
                $('[name="data_inicio"]').val(data.data_inicio);
                $('[name="data_termino"]').val(data.data_termino);

                if (data.status === '1') {
                    $('.status').prop('checked', true);
                } else {
                    $('.status').prop('checked', false);
                }

                $('#modal_form').modal('show');
                $('.modal-title').text('Editar avaliação'); // Set title to Bootstrap modal title

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
            url = "<?php echo site_url('competencias/avaliacao/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('competencias/avaliacao/ajax_update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            "timeout": 9000,
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
                url: "<?php echo site_url('competencias/avaliacao/ajax_delete') ?>",
                type: "POST",
                dataType: "JSON",
                "timeout": 9000,
                data: {
                    id: id
                },
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
require_once APPPATH . "views/end_html.php";
?>
