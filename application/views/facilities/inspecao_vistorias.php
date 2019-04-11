<?php require_once APPPATH . 'views/header.php'; ?>

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
                        <li><a href="<?= site_url('facilities/vistorias'); ?>">Gestão de Vistorias<a></a></li>
                        <li class="active">Inspeção de Itens</li>
                    </ol>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Unidade</th>
                            <th>Andar</th>
                            <th>Sala</th>
                            <th>Facility</th>
                            <th>Tipo</th>
                            <th>Item</th>
                            <th>Status</th>
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
            document.title = 'CORPORATE RH - LMS - Facilities: Gestão de Vistorias';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>

    <script>
        var table, table_itens;

        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'order': [[1, 'asc'], [0, 'desc']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('facilities/vistorias/ajaxListInspecao/' . $this->uri->rsegment(3)) ?>',
                    'type': 'POST'
                },
                'columnDefs': [
                    {
                        'width': '20%',
                        'targets': [0, 1, 2, 3, 5]
                    },
                    {
                        'className': 'text-center',
                        'orderable': false,
                        'targets': [4]
                    },
                    {
                        'className': 'text-center text-nowrap',
                        'targets': [-1],
                        'orderable': false
                    }
                ],
                'rowsGroup': [0, 1, 2, 3, 4, 5]
            });

        });


        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }


        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/vistorias/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/vistorias/ajaxUpdate') ?>";
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': "POST",
                'data': $('#form').serialize(),
                'dataType': "json",
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>