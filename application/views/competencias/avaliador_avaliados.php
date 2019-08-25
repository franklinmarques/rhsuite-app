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
                    <li class="active">Lista de avaliados</li>
                    <button style="float: right;" class="btn btn-default btn-xs" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                </ol>
                <br/>
                <div class="form-group hidden-md hidden-lg">
                    <label class="form-label">Legenda:</label>
                    <p>
                        <button class="btn btn-warning btn-xs" type="button">
                            <i class="glyphicon glyphicon-check"></i> Ct
                        </button>
                        <small> Competências técnicas</small>
                    </p>
                    <p>
                        <button class="btn btn-warning btn-xs" type="button">
                            <i class="glyphicon glyphicon-check"></i> Cc
                        </button>
                        <small> Competências comportamentais</small>
                    </p>
                    <hr>
                </div>
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Colaboradores a serem avaliados</th>
                        <th class="hidden-xs hidden-sm">Cargo/função</th>
                        <th>Avaliar competências</th>
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
            'info': false,
            'processing': true,
            'serverSide': true,
            'iDisplayLength': -1,
            'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
            'lengthChange': (is_mobile === false),
            'searching': (is_mobile === false),
            'order': [], //Initial no order.
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('competencias/avaliador/ajax_avaliados/' . $id_avaliacao . '/' . $id_usuario) ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'width': (is_mobile === false ? '50%' : '64%'),
                    'targets': [0]
                },
                {
                    'width': (is_mobile === false ? '50%' : '0%'),
                    'visible': (is_mobile === false),
                    'targets': [1]
                },
                {
                    'className': (is_mobile === false ? 'text-nowrap' : ''),
                    'orderable': false,
                    'searchable': false,
                    'targets': [-1]
                }
            ]

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

        $.ajax({
            'url': '<?php echo site_url('avaliacao/avaliacao/ajax_edit') ?>/' + id,
            'type': 'GET',
            'dataType': 'json',
            'success': function (json) {
                $('[name="id"]').val(json.id);
                $('[name="nome"]').val(json.nome);
                $('[name="data"]').val(json.data);

                $('.modal-title').text('Editar avaliação');
                $('#modal_form').modal('show');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }


    function save() {
        var url;
        if (save_method === 'add') {
            url = '<?php echo site_url('avaliacao/avaliacao/ajax_add') ?>';
        } else {
            url = '<?php echo site_url('avaliacao/avaliacao/ajax_update') ?>';
        }

        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form').serialize(),
            'dataType': 'json',
            'beforeSend': function () {
                $('#btnSave').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_form').modal('hide');
                    reload_table();
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            },
            'complete': function () {
                $('#btnSave').text('Salvar').attr('disabled', false);
            }
        });
    }


    function delete_avaliacao(id) {
        if (confirm('Deseja remover?')) {
            $.ajax({
                'url': '<?php echo site_url('avaliacao/avaliacao/ajax_delete') ?>/' + id,
                'type': 'POST',
                'dataType': 'json',
                'success': function (json) {
                    $('#modal_form').modal('hide');
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }
    }

</script>

<?php
require_once APPPATH . 'views/end_html.php';
?>
