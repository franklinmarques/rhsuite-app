<?php
require_once APPPATH . 'views/header.php';
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
                <ol class="breadcrumb" style="margin-bottom: 5px;">
                    <li class="active">Matrix de Avaliadores X Avaliados</li>
                </ol>
                <a class="btn btn-sm btn-success"
                   href="<?= site_url('competencias/avaliados/novo/' . $id_competencia); ?>"
                   title="Adicionar Avaliados"><i class="glyphicon glyphicon-plus"></i>Adicionar avaliados e avaliadores</a>
                <a class="btn btn-sm btn-primary" onclick="javascript:history.back()">Voltar</a>
                <br/>
                <br/>
                <table id="table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Avaliados</th>
                        <th>Cargo/função atual</th>
                        <th>Ações</th>
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
        document.title = 'CORPORATE RH - LMS - Matrix de Avaliadores X Avaliados';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<script>

    var table;

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            'processing': true, //Feature control the processing indicator.
            'serverSide': true, //Feature control DataTables' server-side processing mode.
            'iDisplayLength': 25,
            'order': [], //Initial no order.
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            // Load data for the table's content from an Ajax source
            'ajax': {
                'url': '<?php echo site_url('competencias/avaliados/ajax_list/' . $id_competencia . '/' . $id_empresa) ?>',
                'type': 'POST',
                'timeout': 9000
            },
            //Set column definition initialisation properties.
            'columnDefs': [
                {
                    'width': '50%',
                    'targets': [0, 1]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1], //last column
                    'orderable': false, //set not orderable
                    'searchable': false //set not orderable
                }
            ]
        });

    });

    function delete_avaliado(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                'url': '<?php echo site_url('competencias/avaliados/ajax_delete') ?>',
                'type': 'POST',
                'dataType': 'json',
                'timeout': 9000,
                'data': {
                    'id': id
                },
                'success': function (json) {
                    table.ajax.reload(null, false);
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
