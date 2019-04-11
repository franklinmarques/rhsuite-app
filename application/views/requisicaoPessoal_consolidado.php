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
                                    <div class="col-md-4">
                                        <label class="control-label">Departamento</label>
                                        <?php echo form_dropdown('id_depto', $deptos, '', 'class="form-control filtro input-sm" onchange="atualizar_filtro(this);" autocomplete="off"'); ?>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="control-label">Cargo/função</label>
                                        <?php echo form_dropdown('id_funcao', $cargosFuncoes, '', 'id="id_funcao" class="form-control filtro input-sm" autocomplete="off"'); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Ano</label>
                                        <input type="text" name="ano" id="ano" value="<?= date('Y'); ?>"
                                               class="form-control input-sm text-center filtro" autocomplete="off"
                                               placeholder="aaaa">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-bordered table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr class="active">
                            <th rowspan="2" style="vertical-align: middle;">Mês</th>
                            <th colspan="2" class="text-center">Abertas</th>
                            <th colspan="2" class="text-center">Fechadas</th>
                            <th colspan="2" class="text-center">Suspensas</th>
                            <th class="text-center">Canceladas</th>
                        </tr>
                        <tr class="active">
                            <th class="text-center">RPs</th>
                            <th class="text-center">Vagas</th>
                            <th class="text-center">RPs</th>
                            <th class="text-center">Vagas</th>
                            <th class="text-center">RPs</th>
                            <th class="text-center">Vagas</th>
                            <th class="text-center">RPs</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr class="active">
                            <th>Total</th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                        </tr>
                        </tfoot>
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

            $('#ano').mask('0000');

            //datatables
            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('requisicaoPessoal_consolidado/ajaxList/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('.filtro').serialize();
                        return d;
                    },
                    'dataSrc': function (json) {
                        $.each(json.total, function (index, value) {
                            $(table.columns(index + 1).footer()).html(value);
                        });

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'className': 'text-center',
                        'width': '10%',
                        'targets': [1, 2, 3, 4, 5, 6, 7]
                    }
                ]
            });

        });

        $('#id_funcao, #ano').on('change', function () {
            reload_table();
        });

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function atualizar_filtro(elem) {
            $.ajax({
                'url': "<?php echo site_url('requisicaoPessoal_consolidado/atualizarFiltro') ?>",
                'type': 'POST',
                'dataType': 'json',
                'data': {'id_depto': elem.value},
                'success': function (json) {
                    $('#id_funcao').html($(json.funcao).html());

                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>