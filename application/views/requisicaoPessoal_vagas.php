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
                        <li><a href="<?= site_url('requisicaoPessoal'); ?>">Gerenciar Requisições de Pessoal</a></li>
                        <li class="active">Vagas em aberto</li>
                    </ol>
                    <div class="text-right">
                        <a class="btn btn-primary" href="#"><i class="glyphicon glyphicon-print"></i> Imprimir</a>
                        <button class="btn btn-default" onclick="javascript:history.back()"><i
                                    class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                        </button>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label">Status</label>
                                        <select name="status" class="form-control filtro input-sm"
                                                autocomplete="off">
                                            <option value="">Todas</option>
                                            <option value="A">Abertura</option>
                                            <option value="S">Suspensas</option>
                                            <option value="C">Canceladas</option>
                                            <option value="G">Aguardando aprovação</option>
                                            <option value="F">Fechadas</option>
                                            <option value="P">Fechadas parcialmente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Data início</label>
                                        <input type="text" name="data_inicio"
                                               class="form-control input-sm text-center filtro date"
                                               placeholder="dd/mm/aaaa">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Data término</label>
                                        <input type="text" name="data_termino"
                                               class="form-control input-sm text-center filtro date"
                                               placeholder="dd/mm/aaaa">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-striped table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th nowrap>Cargo/função</th>
                            <th nowrap>Qtde. vagas em aberto</th>
                            <th>Requisitante</th>
                            <th nowrap>Data abertura</th>
                            <th nowrap>Previsão início</th>
                            <th nowrap>Qtde. aprovados</th>
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
            document.title = 'CORPORATE RH - LMS - Vagas em Aberto';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>
        var table;

        $(document).ready(function () {

            $('.date').mask('00/00/0000');

            //datatables
            table = $('#table').DataTable({
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('requisicaoPessoal_vagas/ajaxList/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        return d;
                    }
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '50%',
                        'targets': [0, 2]
                    },
                    {
                        'className': 'text-center',
                        'targets': [1, 3, 4, 5]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ]
            });

        });

        $('.filtro').on('change', function () {
            reload_table();
        });

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>