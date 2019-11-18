<?php
require_once APPPATH . 'views/header.php';
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
                    <li class="active">Tipo de competências</li>
                    <button style="float: right;" class="btn btn-default btn-xs" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                </ol>
                <br/>
                <div class="form-group hidden-md hidden-lg">
                    <label class="form-label">Legenda:</label>
                    <p>
                        <button class="btn btn-warning btn-xs" type="button">
                            <i class="glyphicon glyphicon-check"></i>
                        </button>
                        <small> Avaliar dimensão</small>
                    </p>
                    <hr>
                </div>
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Competência <?= $stringCompetencia; ?></th>
                        <th>Status</th>
                        <th>Ação</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- page end-->

    </section>
</section>
<!--main content end-->

<?php
require_once APPPATH . 'views/end_js.php';
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
            'processing': true, //Feature control the processing indicator.
            'serverSide': true, //Feature control DataTables' server-side processing mode.
            'order': [], //Initial no order.
            'iDisplayLength': -1,
            'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
            'lengthChange': (is_mobile === false),
            'searching': (is_mobile === false),
            // Load data for the table's content from an Ajax source
            'ajax': {
                'url': '<?php echo site_url('competencias/avaliador/ajax_tipo/' . $id_usuario . '/' . $id_avaliado . '/' . $tipo_competencia) ?>',
                'type': 'POST'
            },
            //Set column definition initialisation properties.
            'columnDefs': [
                {
                    'width': '100%',
                    'targets': [0]
                },
                {
                    'className': 'text-center',
                    'targets': [1]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1], //last column
                    'orderable': false, //set not orderable
                    'searchable': false //set not orderable
                }
            ],
            'createdRow': function (row, data, index) {
                if (data[1] === 'Avaliado') {
                    $('td', row).eq(1).addClass('text-success').html('<strong>' + data[1] + '</strong>');
                } else {
                    $('td', row).eq(1).addClass('text-danger').html('<strong>' + data[1] + '</strong>');
                }
            }
        });

        //datepicker
        $('.datepicker').datepicker({
            'autoclose': true,
            'format': 'yyyy-mm-dd',
            'todayHighlight': true,
            'orientation': 'top auto',
            'todayBtn': true
        });

    });

    function add_avaliacao() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar avaliação'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_avaliacao(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            'url': '<?php echo site_url('avaliacao/avaliacao/ajax_edit') ?>/' + id,
            'type': 'GET',
            'dataType': 'json',
            'success': function (json) {
                $('[name="id"]').val(json.id);
                $('[name="nome"]').val(json.nome);
                $('[name="data"]').val(json.data);

                $('#modal_form').modal('show');
                $('.modal-title').text('Editar avaliação'); // Set title to Bootstrap modal title
            }
        });
    }


    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }


    function save() {
        var url;
        if (save_method === 'add') {
            url = "<?php echo site_url('avaliacao/avaliacao/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('avaliacao/avaliacao/ajax_update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form').serialize(),
            'dataType': 'json',
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

    function delete_avaliacao(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                'url': '<?php echo site_url('avaliacao/avaliacao/ajax_delete') ?>/' + id,
                'type': 'POST',
                'dataType': 'json',
                'success': function (data) {
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
