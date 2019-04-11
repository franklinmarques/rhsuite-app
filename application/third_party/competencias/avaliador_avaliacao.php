<?php
require_once APPPATH . "views/header.php";
?>
<style>
    <?php if ($this->agent->is_mobile()): ?>

    #table {
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
</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <ol class="breadcrumb" style="margin-bottom: 5px;">
                    <li class="active">Avaliações de desempenho</li>
                </ol>
                <br/>
                <div class="form-group hidden-md hidden-lg">
                    <label class="form-label">Legenda:</label>
                    <p>
                        <button class="btn btn-success btn-xs" type="button">
                            <i class="glyphicon glyphicon-plus"></i>
                        </button>
                        <small> Realizar avaliação</small>
                    </p>
                    <hr>
                </div>
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Avaliações</th>
                        <th class="hidden-xs hidden-sm">Cargo/função</th>
                        <th>Periodo da avaliação</th>
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
        <div class="modal fade" id="modal_form" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Formulario de cargos</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="<?= $id_usuario; ?>" id="id_usuario_EMPRESA"
                                   name="id_usuario_EMPRESA"/>
                            <input type="hidden" value="" name="id"/>
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Nome</label>
                                    <div class="col-md-9">
                                        <input name="nome" placeholder="digite o cargo/função" class="form-control"
                                               type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Peso competências técnicas</label>
                                    <div class="col-md-9">
                                        <input name="peso_competências_técnicas"
                                               placeholder="digite o Peso Competencias Técnicas" class="form-control"
                                               type="text">00 - 100
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Peso competências comportamentais</label>
                                    <div class="col-md-9">
                                        <input name="peso_competencias_comportamentais"
                                               placeholder="digite o Peso Competencias Comportamentais"
                                               class="form-control" type="text"> 00 - 100
                                        <span class="help-block"></span>
                                    </div>
                                </div>


                                <div class="form-group combo_nivel1" style="display:none">
                                    <label class="control-label col-md-3">Nível 1</label>
                                    <div class="col-md-9">
                                        <select name="id_nivel1" class="form-control">
                                            <option value="">--Selecione Nível 1--</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_help" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Ajuda</h3>
                        <?php
                        if (file_exists('AD2.pdf')) {
                            ?>
                            <iframe src="https://docs.google.com/gview?embedded=true&url=<?= base_url('AD2.pdf'); ?>"
                                    style="width:100%; height:450px;" frameborder="0"></iframe>
                            <?php
                        }
                        ?>
                    </div>
                    <div class='modal-footer' style="margin-top: 0;">
                        <button type='button' class='btn btn-default' data-dismiss="modal" id='fechaModal'>
                            Fechar
                        </button>
                    </div>
                    <div class="modal-body form">

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
        document.title = 'CORPORATE RH - LMS - Avaliações de desempenho';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<script>

    var save_method; //for save method string
    var table;
    var is_mobile = <?= $this->agent->is_mobile() ? 'true' : 'false'; ?>;

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            "iDisplayLength": -1,
            "lengthMenu": [[5, 10, 25, 50, 100, 250, 500, -1], [5, 10, 25, 50, 100, 250, 500, 'Todos']],
            "lengthChange": (is_mobile === false),
            "searching": (is_mobile === false),
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('competencias/avaliador/ajax_list/' . $id_usuario) ?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    visible: <?= ($this->agent->is_mobile() ? 'false' : 'true') ?>,
                    targets: [1]
                },
                {
                    width: '50%',
                    targets: [0, 1]
                },
                {
                    className: "text-nowrap",
                    cellType: 'th',
                    targets: [2]
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

    function add_cargo() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar cargo/função'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function help() {
        $('#modal_help').modal('show'); // show bootstrap modal
        $('.modal-title').text('Ajuda'); // Set Title to Bootstrap modal title
    }

    function edit_cargo(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('avaliacao/cargos/ajax_edit/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {

                $('[name="id"]').val(data.id);
                $('[name="nome"]').val(data.nome);
                $('[name="peso_competencias_tecnicas"]').val(data.peso_competencias_tecnicas);
                $('[name="peso_competencias_comportamentais"]').val(data.peso_competencias_comportamentais);

                $('#modal_form').modal('show');
                $('.modal-title').text('Editar cargo/função'); // Set title to Bootstrap modal title

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
            url = "<?php echo site_url('avaliacao/cargos/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('avaliacao/cargos/ajax_update') ?>";
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

    function delete_cargo(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('avaliacao/cargos/ajax_delete') ?>/" + id,
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
require_once APPPATH . "views/end_html.php";
?>
