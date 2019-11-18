<?php
require_once APPPATH . 'views/header.php';
?>
<style>
    @media screen and (min-width: 768px) {
        #modal_help .modal-dialog {
            min-width: 900px;
        }
    }

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
                    <li class="active">Cargo/função</li>
                </ol>
                <div class="row form-inline">
                    <div class="col-sm-5 col-md-4">
                        <button class="btn btn-success" onclick="add_cargo();"><i class="glyphicon glyphicon-plus"></i>
                            Adicionar cargo/função
                        </button>
                        <button class="btn btn-info" onclick="help();"><i class="glyphicon glyphicon-comment"></i> Ajuda
                        </button>
                    </div>
                    <div class="col-sm-7 col-md-8 right">
                        <label class="visible-xs"></label>
                        <p class="bg-info text-info" id="alerta" style="padding: 5px;">
                            <small>* NCTf - Nível de competência técnica do cargo-função &nbsp; | &nbsp; * Peso (CT) -
                                Peso das competências técnicas</small><br>
                            <small>* NCCf - Nível de competência comportamental do cargo-função &nbsp; | &nbsp; * Peso
                                (CC) - Peso das competências comportamentais</small><br>
                            <small>* IDcf - Índice de desempenho do cargo-função</small>
                        </p>
                        <!--                        <p class="text-danger">
                                                    <small><strong>* Obs.: Para vincular funcionários a um cargo, primeiramente é necessário cadastrar as competências (técnicas e comportamentais) e comportamentos ao referido cargo-função.</strong></small>
                                                </p>-->
                    </div>
                </div>
                <br/>
                <table id="table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Cargo/função</th>
                        <th>NCTf<span class="text-info"> *</span></th>
                        <th>Peso (CT)<span class="text-info"> *</span></th>
                        <th>NCCf<span class="text-info"> *</span></th>
                        <th>Peso (CC)<span class="text-info"> *</span></th>
                        <th>IDcf<span class="text-info"> *</span></th>
                        <th>Ações<span class="text-danger"> *</span></th>
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
                                    <label class="control-label col-md-3">Cargo</label>
                                    <div class="col-md-8">
                                        <?php echo form_dropdown('cargo', $cargo, '', 'id="cargo" class="form-control"'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Função</label>
                                    <div class="col-md-8">
                                        <?php echo form_dropdown('funcao', $funcao, '', 'id="funcao" class="form-control"'); ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label class="control-label col-md-8">Peso das competências técnicas *</label>
                                    <div class="col-md-3 input-group">
                                        <input name="peso_competencias_tecnicas" placeholder="0 - 100"
                                               class="form-control text-right" type="number" min="0" max="100">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-8">Peso das competências comportamentais
                                        *</label>
                                    <div class="col-md-3 input-group">
                                        <input name="peso_competencias_comportamentais" placeholder="0 - 100"
                                               class="form-control text-right" type="number" min="0" max="100">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="text-right text-danger"><i>* A soma dos pesos deve ser igual a 100</i></div>
                                <!--                                <div class="form-group combo_nivel1" style="display:none">
                                                                    <label class="control-label col-md-3">Nível 1</label>
                                                                    <div class="col-md-8">
                                                                        <select name="id_nivel1" class="form-control">
                                                                            <option value="">--Selecione Nível 1--</option>
                                                                        </select>
                                                                        <span class="help-block"></span>
                                                                    </div>
                                                                </div>-->
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
        <div class="modal fade" id="modal_help" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Ajuda</h3>
                        <?php if (file_exists('AD1.pdf')): ?>
                            <object type="text/html"
                                    data="http://docs.google.com/gview?embedded=true&frameborder=0&url=<?= base_url('AD1.pdf'); ?>"
                                    width="100%" height="450px"></object>
                        <?php endif; ?>
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
require_once APPPATH . 'views/end_js.php';
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Cargo/função';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>

    var save_method; //for save method string
    var table;
    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'iDisplayLength': 25,
                'order': [], //Initial no order.
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('competencias/cargos/ajax_list/' . $id_usuario) ?>',
                    'type': 'POST',
                    'timeout': 9000
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '100%',
                        'targets': [0]
                    },
                    {
                        'className': 'text-right text-nowrap',
                        'cellType': 'td',
                        'targets': [1, 2, 3, 4, 5]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1], //last column
                        'searchable': false, //set not orderable
                        'orderable': false //set not orderable
                    }
                ]
            }
        );
        //datepicker
        $('.datepicker').datepicker({
            'autoclose': true,
            'format': 'yyyy-mm-dd',
            'todayHighlight': true,
            'orientation': 'top auto',
            'todayBtn': true
        });
    });

    $('#cargo').change(function () {
//                                var cargo = $('#cargo').val();
//                                $('#funcao option').show();
//                                if (cargo.length > 0) {
//                                    $('#funcao option[data-cargo!="' + cargo + '"]').hide();
//                                    $('#funcao').val('');
//                                }
        $.ajax({
            'url': '<?php echo site_url('competencias/cargos/ajax_funcoes') ?>',
            'type': 'POST',
            'dataType': 'json',
            'timeout': 9000,
            'data': {
                'cargo': $('#cargo').val()
            },
            'success': function (data) {
                $('#funcao').html(data.funcao);
            }
        });
    });

    function add_cargo() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('[name="id"]').val('');
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
            'url': '<?php echo site_url('competencias/cargos/ajax_edit') ?>',
            'type': 'POST',
            'dataType': 'json',
            'timeout': 9000,
            'data': {
                'id': id
            },
            'success': function (json) {
                $('[name="id"]').val(json.id);
                $('[name="cargo"]').val(json.cargo);
                $('[name="funcao"]').val(json.funcao);
                $('[name="peso_competencias_tecnicas"]').val(json.peso_competencias_tecnicas);
                $('[name="peso_competencias_comportamentais"]').val(json.peso_competencias_comportamentais);

                $('.modal-title').text('Editar cargo/função'); // Set title to Bootstrap modal title
                $('#modal_form').modal('show');

            }
        });
    }

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    function save() {
        var url;
        if (save_method === 'add') {
            url = "<?php echo site_url('competencias/cargos/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('competencias/cargos/ajax_update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form').serialize(),
            'dataType': 'json',
            'timeout': 9000,
            'beforeSend': function () {
                $('#btnSave').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                }
            },
            'complete': function () {
                $('#btnSave').text('Salvar').attr('disabled', false);
            }
        });
    }

    function delete_cargo(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                'url': '<?php echo site_url('competencias/cargos/ajax_delete') ?>',
                'type': 'POST',
                'dataType': 'json',
                'timeout': 9000,
                'data': {
                    'id': id
                },
                'success': function (json) {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                }
            });
        }
    }

</script>

<?php
require_once APPPATH . 'views/end_html.php';
?>
