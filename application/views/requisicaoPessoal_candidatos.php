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
                        <li class="active">Gestão Processs Seletivos - Relatórios de Gestão</li>
                    </ol>
                    <br>
                    <div class="form-horizontal">
                        <div class="row form-group">
                            <label class="col-md-2 control-label">Mês/ano início</label>
                            <div class="col-md-2">
                                <input type="text" id="mes_ano_inicio"
                                       class="form-control input-sm text-center mes_ano" autocomplete="off"
                                       placeholder="mm/aaaa" onchange="reload_table();">
                            </div>
                            <label class="col-md-2 control-label">Mês/ano término</label>
                            <div class="col-md-2">
                                <input type="text" id="mes_ano_termino"
                                       class="form-control input-sm text-center mes_ano" autocomplete="off"
                                       placeholder="mm/aaaa" onchange="reload_table();">
                            </div>
                            <div class="col-md-4 text-right">
                                <a id="xlxs" class="btn btn-success" href="#" target="_blank"><i
                                            class="glyphicon glyphicon-file"></i> Exportar planilha
                                </a>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-bordered table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr class="active">
                            <th rowspan="2" style="vertical-align: middle;">Req.</th>
                            <th rowspan="2" style="vertical-align: middle;">Abertura</th>
                            <th rowspan="2" style="vertical-align: middle;">Selecionador(a)</th>
                            <th rowspan="2" style="vertical-align: middle;">Cargo da vaga</th>
                            <th rowspan="2" style="vertical-align: middle;">Total vagas</th>
                            <th rowspan="2" style="vertical-align: middle;">Departamento</th>
                            <th rowspan="2" style="vertical-align: middle;">Área</th>
                            <th rowspan="2" style="vertical-align: middle;">Setor</th>
                            <th rowspan="2" style="vertical-align: middle;">Empresa/requisitante</th>
                            <th rowspan="2" style="vertical-align: middle;">Previsão início</th>
                            <th colspan="3" class="text-center">Candidato</th>
                            <th colspan="2" class="text-center">Seleção</th>
                            <th colspan="2" class="text-center">Requisitante</th>
                            <th class="text-center">Antecedentes criminais</th>
                            <th class="text-center">Restrições financeiras</th>
                            <th colspan="2" class="text-center">Exame médico admissional</th>
                            <th rowspan="2" style="vertical-align: middle;">Data admissão</th>
                        </tr>
                        <tr class="active">
                            <!--                            <th class="text-center">Nome</th>-->
                            <th class="text-center">Deficiência</th>
                            <th class="text-center">Fonte</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Data</th>
                            <th class="text-center">Resultado</th>
                            <th class="text-center">Data</th>
                            <th class="text-center">Resultado</th>
                            <th class="text-center">Resultado</th>
                            <th class="text-center">Resultado</th>
                            <th class="text-center">Data</th>
                            <th class="text-center">Resultado</th>
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
            document.title = 'CORPORATE RH - LMS - Gestão Processs Seletivos - Relatórios de Gestão';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>
        var table;

        $('.mes_ano').mask('00/0000');

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('requisicaoPessoal_candidatos/ajaxList') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.mes_ano_inicio = $('#mes_ano_inicio').val();
                        d.mes_ano_termino = $('#mes_ano_termino').val();
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
                        'width': '16%',
                        'targets': [2, 3, 5, 6, 7, 8]
                    },
                    {
                        'className': 'text-center',
                        'targets': [0, 1, 4, 9, 13, 15, 19, 21]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (rowData[col] === null) {
                                $(td).css('background-color', '#ff0');
                            }
                        },
                        'targets': [13, 14, 15, 16, 17, 18, 19, 20, 21]
                    }
                ],
                'preDrawCallback': function () {
                    var search = '';
                    var q = [];
                    var mes_ano_inicio = $('#mes_ano_inicio').val();
                    var mes_ano_termino = $('#mes_ano_termino').val();

                    if (mes_ano_inicio.length > 0) {
                        q.push("mes_ano_inicio=" + mes_ano_inicio);
                    }

                    if (mes_ano_termino.length > 0) {
                        q.push("mes_ano_inicio=" + mes_ano_termino);
                    }

                    if (q.length > 0) {
                        search = '/q?' + q.join('&');
                    }

                    $('#xlxs').prop('href', '<?= site_url('requisicaoPessoal_candidatos/exportarXlxs'); ?>' + search);
                },
                'rowsGroup': [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
            });

        });


        function reload_table() {
            table.ajax.reload(null, false);
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>