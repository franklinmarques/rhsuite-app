<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de financas</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>
    <style>
        @page {
            margin: 40px 20px;
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

        tr.group, tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
</head>
<body style="color: #000;">
<div class="container-fluid">

    <htmlpageheader name="myHeader">
        <table id="table" class="table table-condensed">
            <thead>
            <tr>
                <td style="width: auto;">
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="width: 100%; vertical-align: top;">
                    <p>
                        <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                        <!--<span style="font-weight: bold;">Associação dos Amigos Metroviários dos Excepcionais - AME</span><br>
                        <span style="font-size: small;">Rua Serra de Botucatu, 1.197 - São Paulo, Brasil ─ CEP 03317-001 ─ Tel.: 2360-8900</span><br>
                        <span style="font-size: small;">Site: www.ame-sp.org.br ─ e-mail: ame@ame-sp.org.br</span>-->
                    </p>
                </td>
                <?php if ($is_pdf == false): ?>
                    <td nowrap>
                        <button id="pdf" class="btn btn-sm btn-danger" onclick="gerarRelatorio();"
                                title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF
                        </button>
                    </td>
                <?php endif; ?>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                    <?php if ($is_pdf == false): ?>
                        <h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE CONSOLIDAÇÃO FINANCEIRA</h3>
                        <?php if ($contrato): ?>
                            <h4 class="text-center" style="font-weight: bold;">CONTRATO Nº <?= $contrato->contrato ?>
                                ─ <?= $contrato->nome ?> ─ <?= $contrato->setor ?></h4>
                        <?php endif; ?>
                    <?php else: ?>
                        <h4 class="text-center" style="font-weight: bold;">RELATÓRIO DE CONSOLIDAÇÃO FINANCEIRA</h4>
                        <?php if ($contrato): ?>
                            <h5 class="text-center" style="font-weight: bold;">CONTRATO Nº <?= $contrato->contrato ?>
                                ─ <?= $contrato->nome ?> ─ <?= $contrato->setor ?></h5>
                        <?php endif; ?>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
        </table>
        <?php if ($is_pdf == false): ?>
            <form id="busca" class="row form-inline">
                <input type="hidden" class="filtro" name="depto" value="<?= $depto ?>">
                <input type="hidden" class="filtro" name="area" value="<?= $area ?>">
                <input type="hidden" class="filtro" name="setor" value="<?= $setor ?>">
                <input type="hidden" class="filtro" name="cargo" value="<?= $cargo ?>">
                <input type="hidden" class="filtro" name="funcao" value="<?= $funcao ?>">
                <input type="hidden" name="mes" value="<?= $mes ?>">
                <input type="hidden" name="ano" value="<?= $ano ?>">
                <div class="col-md-4 form-group">&ensp;
                    <label for="exampleInputName2">Mês e ano inicial</label>
                    <?php echo form_dropdown('mes_inicial', $meses, '', 'class="form-control filtro"'); ?>
                    <input type="number" class="form-control filtro" name="ano_inicial" value="<?= $ano ?>"
                           placeholder="aaaa"
                           style="width: 120px;">
                </div>
                <div class="col-md-4 form-group">
                    <label for="exampleInputEmail2">Mês e ano final</label>
                    <?php echo form_dropdown('mes_final', $meses, '', 'class="form-control filtro"'); ?>
                    <input type="number" class="form-control filtro" name="ano_final" value="<?= $ano ?>"
                           placeholder="aaaa"
                           style="width: 120px;">
                </div>
                <div class="col-md-4 form-group">
                    <button type="button" id="pesquisar" class="btn btn-default"><i
                                class="glyphicon glyphicon-search"></i>
                        Pesquisar
                    </button>
                </div>
            </form>
            <br>
        <?php endif; ?>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <div>

        <!-- <div class="row">
            <div class="col-md-6">
                <table id="table_colaboradores" class="table table-striped table-bordered table-condensed"
                       cellspacing="0"
                       width="100%">
                    <thead>
                    <tr class="success">
                        <th colspan="4" class="text-center">
                            <h3><strong>Quantidade de colaboradores (RH)</strong></h3>
                        </th>
                    </tr>
                    <tr class="active">
                        <th class="text-center">Mês/ano</th>
                        <th class="text-center">Contratual</th>
                        <th class="text-center">Ativos</th>
                        <th class="text-center">Férias</th>
                        <th class="text-center">Substitutos</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table id="table_tempo" class="table table-striped table-bordered table-condensed" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr class="success">
                        <th colspan="4" class="text-center">
                            <h3><strong>Detalhamento de glosas</strong></h3>
                        </th>
                    </tr>
                    <tr class="active">
                        <th class="text-center" width="20%">Mês/ano</th>
                        <th class="text-center" width="20%">Minutos</th>
                        <th class="text-center" width="20%">Horas</th>
                        <th class="text-center">Minutos (diferença)</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div> -->

        <table id="table_faltas" class="table table-striped table-bordered table-condensed" cellspacing="0"
               width="100%">
            <thead>
            <tr class="success">
                <th colspan="11" class="text-center">
                    <h3><strong>Análise de Disponibilidade</strong></h3>
                </th>
            </tr>
            <tr class="active">
                <th class="text-center" style="vertical-align: middle;">Mês/ano</th>
                <th class="text-center" style="vertical-align: middle;">Dias úteis</th>
                <th class="text-center" style="vertical-align: middle;">Colaboradores potenciais</th>
                <th class="text-center" style="vertical-align: middle;">Colaboradores ativos</th>
                <th class="text-center" style="vertical-align: middle;">Total geral de faltas (dias)</th>
                <th class="text-center" style="vertical-align: middle;">Total dias cobertos (dias)</th>
                <th class="text-center" style="vertical-align: middle;">Total dias não cobertos dias + horas (dias)</th>
                <th class="text-center" style="vertical-align: middle;">Índice vacância sem cobertura (%)</th>
                <th class="text-center" style="vertical-align: middle;">Índice vacância com cobertura (%)</th>

                <th class="text-center">Glosas (dias)<br>Faltas em dias</th>
                <!-- <th class="text-center">Glosas (dias)<br>acima de 3 dias</th> -->
                <th class="text-center">Glosas (dias)<br>Faltas em minutos</th>
                <!-- <th class="text-center">Glosas (dias)<br>Postos descobertos</th> -->
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <table id="table_financas" class="table table-striped table-bordered table-condensed" cellspacing="0"
               width="100%">
            <thead>
            <tr class="success">
                <th colspan="6" class="text-center">
                    <h3><strong>Análise Financeira</strong></h3>
                </th>
            </tr>
            <tr class="active">
                <th class="text-center">Mês/ano</th>
                <th class="text-center">Valor projetado (contrato) (R$)</th>
                <th class="text-center">Valor realizado (R$)</th>
                <th class="text-center">Valor glosa (real) (R$)</th>
                <th class="text-center">Perda de receita (%)</th>
                <th class="text-center">Receita líquida (%)</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <table id="table_turnover" class="table table-striped table-bordered table-condensed" cellspacing="0"
               width="100%">
            <thead>
            <tr class="success">
                <th colspan="6" class="text-center">
                    <h3><strong>Turnover</strong></h3>
                </th>
            </tr>
            <tr class="active">
                <th class="text-center">Admissões para reposição</th>
                <th class="text-center">Admissões aumento quadro</th>
                <th class="text-center">Desligamentos AME</th>
                <th class="text-center">Desligamentos colaboradores</th>
                <th class="text-center">Turnover mensal (%)</th>
                <th class="text-center">Índice de evasão (%)</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <br>
        <div class="row">
            <div class="col-md-12">
                <div id="chart_valores"></div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div id="chart_vacancia"></div>
            </div>
        </div>
        <!-- <br>
        <div class="row">
            <div class="col-md-12">
                <div id="chart_perda_receita"></div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div id="chart_glosa_dias"></div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div id="chart_glosa_minutos"></div>
            </div>
        </div> -->

        <pagebreak odd-header-name="myHeader"></pagebreak>

    </div>

</div>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="https://www.gstatic.com/charts/loader.js"></script>

<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>


<script>
    //var table_colaboradores, table_tempo, table_faltas, table_valores;
    var table_faltas, table_valores, table_turnover;
    var pdf = "<?= site_url('apontamento_financas/pdf/' . $query_string); ?>";
    var busca;


    $(document).ready(function () {

        google.charts.load('current', {'packages': ['line'], 'language': 'pt-br'});
        google.charts.setOnLoadCallback(drawChart_valores);
        google.charts.setOnLoadCallback(drawChart_vacancia);
        //google.charts.setOnLoadCallback(drawChart_perdaReceita);
        //google.charts.setOnLoadCallback(drawChart_glosaDias);
        //google.charts.setOnLoadCallback(drawChart_glosaMinutos);

        $('[name="mes_inicial"],[name="mes_final"]').val('<?= $mes ?>');
        busca = $('#busca').serialize();

        /*
        table_colaboradores = $('#table_colaboradores').DataTable({

            "info": false,
            "processing": true,
            "serverSide": true,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "searching": false,
            "ajax": {
                "url": "*/<?php //echo site_url('apontamento_financas/ajax_colaboradores') ?>/*",
                "type": "POST",
                timeout: 90000,
                data: function (d) {
                    d.busca = busca;
                    return d;
                }
            },
            "columnDefs": [
                {
                    "className": 'text-center',
                    "targets": '_all'
                }
            ]
        });

        table_tempo = $('#table_tempo').DataTable({
            "info": false,
            "processing": true,
            "serverSide": true,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "searching": false,
            "ajax": {
                "url": "*/<?php //echo site_url('apontamento_financas/ajax_tempo') ?>/*",
                "type": "POST",
                timeout: 90000,
                data: function (d) {
                    d.busca = busca;
                    return d;
                }
            },
            "columnDefs": [
                {
                    "className": 'text-center',
                    "targets": '_all'
                }
            ]
        });
        */

        table_faltas = $('#table_faltas').DataTable({
            "info": false,
            "processing": true,
            "serverSide": true,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "searching": false,
            "ajax": {
                "url": "<?php echo site_url('apontamento_financas/ajax_faltas') ?>",
                "type": "POST",
                timeout: 90000,
                data: function (d) {
                    d.busca = busca;
                    return d;
                },
                dataSrc: function (json) {
                    drawChart_vacancia(json.chart.vacancia);
                    //drawChart_glosaDias(json.chart.glosaDias);
                    //drawChart_glosaMinutos(json.chart.glosaMinutos);

                    return json.data;
                }
            },
            "columnDefs": [
                {
                    "className": 'text-center',
                    "targets": '_all'
                }
            ]
        });

        table_valores = $('#table_financas').DataTable({
            "info": false,
            "processing": true,
            "serverSide": true,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "searching": false,
            "ajax": {
                "url": "<?php echo site_url('apontamento_financas/ajax_valores') ?>",
                "type": "POST",
                timeout: 90000,
                data: function (d) {
                    d.busca = busca;
                    return d;
                },
                dataSrc: function (json) {
                    drawChart_valores(json.chart.valores);
                    //drawChart_perdaReceita(json.chart.perdaReceita);

                    return json.data;
                }
            },
            "columnDefs": [
                {
                    "className": 'text-center',
                    "targets": '_all'
                }
            ]
        });

        table_turnover = $('#table_turnover').DataTable({
            "info": false,
            "processing": true,
            "serverSide": true,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "searching": false,
            "ajax": {
                "url": "<?php echo site_url('apontamento_financas/ajax_turnover') ?>",
                "type": "POST",
                timeout: 90000,
                data: function (d) {
                    d.busca = busca;
                    return d;
                },
                dataSrc: function (json) {
                    return json.data;
                }
            },
            "columnDefs": [
                {
                    "className": 'text-center',
                    "targets": '_all'
                }
            ]
        });

    });


    function drawChart_valores(jsonData) {
        if (jsonData === undefined) {
            return false;
        }

        var data = new google.visualization.DataTable();
        data.addColumn('string', '');
        data.addColumn('number', 'Valor projetado');
        data.addColumn('number', 'Valor realizado');
        // data.addColumn('number', 'Valor glosa');

        data.addRows(jsonData);

        var options = {
            chart: {
                title: 'FINANÇAS',
                subtitle: 'Valor projetado / Valor realizado / Valor glosa (R$)'
            },
            width: '90%',
            height: 230,
            legend: {
                position: 'bottom'
            }
        };

        var chart = new google.charts.Line(document.getElementById('chart_valores'));
        chart.draw(data, google.charts.Line.convertOptions(options));
    }

    function drawChart_vacancia(jsonData) {
        if (jsonData === undefined) {
            return false;
        }

        var data = new google.visualization.DataTable();
        data.addColumn('string', '');
        data.addColumn('number', 'Índice sem cobertura');
        data.addColumn('number', 'Índice com cobertura');

        data.addRows(jsonData);

        var options = {
            chart: {
                title: 'ÍNDICE DE VACÂNCIA',
                subtitle: 'Índice de vacância sem cobertura / Índice de vacância com cobertura'
            },
            width: '90%',
            height: 230,
            legend: {
                position: 'bottom'
            },
            trendlines: {
                0: {
                    type: 'linear',
                    opacity: 0.5
                },
                1: {
                    type: 'exponential',
                    opacity: 0.5
                }
            }
        };

        var chart = new google.charts.Line(document.getElementById('chart_vacancia'));
        chart.draw(data, google.charts.Line.convertOptions(options));
    }

    /*function drawChart_perdaReceita(jsonData) {
        if (jsonData === undefined) {
            return false;
        }

        var data = new google.visualization.DataTable();
        data.addColumn('string', '');
        data.addColumn('number', 'Índice');

        data.addRows(jsonData);

        var options = {
            chart: {
                title: 'PERDA DE RECEITA',
                subtitle: 'Índice de projetividade'
            },
            width: '90%',
            height: 230,
            legend: {
                position: 'bottom'
            }
        };

        var chart = new google.charts.Line(document.getElementById('chart_perda_receita'));
        chart.draw(data, google.charts.Line.convertOptions(options));
    }

    function drawChart_glosaDias(jsonData) {
        if (jsonData === undefined) {
            return false;
        }

        var data = new google.visualization.DataTable();
        data.addColumn('string', '');
        data.addColumn('number', 'Glosa');

        data.addRows(jsonData);

        var options = {
            chart: {
                title: 'GLOSAS POR DIA',
                subtitle: 'Glosas diárias'
            },
            width: '90%',
            height: 230,
            legend: {
                position: 'bottom'
            }
        };

        var chart = new google.charts.Line(document.getElementById('chart_glosa_dias'));
        chart.draw(data, google.charts.Line.convertOptions(options));
    }

    function drawChart_glosaMinutos(jsonData) {
        if (jsonData === undefined) {
            return false;
        }

        var data = new google.visualization.DataTable();
        data.addColumn('string', '');
        data.addColumn('number', 'Glosa');

        data.addRows(jsonData);

        var options = {
            chart: {
                title: 'GLOSAS POR MINUTO',
                subtitle: 'Total de glosas por minuto'
            },
            width: '90%',
            height: 230,
            legend: {
                position: 'bottom'
            }
        };

        var chart = new google.charts.Line(document.getElementById('chart_glosa_minutos'));
        chart.draw(data, google.charts.Line.convertOptions(options));
    }*/


    $('#pesquisar').on('click', function () {
        busca = $('#busca').serialize();
        reload_table();
        setPdf_atributes();
    });

    $('#limpa_filtro').on('click', function () {
        $(".filtro").val('');
        busca = $('#busca').serialize();
        reload_table();
    });

    function reload_table() {
        //table_colaboradores.ajax.reload(null, false); //reload datatable ajax
        //table_tempo.ajax.reload(null, false); //reload datatable ajax
        table_faltas.ajax.reload(null, false); //reload datatable ajax
        table_valores.ajax.reload(null, false); //reload datatable ajax
        table_turnover.ajax.reload(null, false); //reload datatable ajax
    }


    function setPdf_atributes() {
        var search = '';
        var q = new Array();

        $('.filtro').each(function (i, v) {
//            if (i === 0){
//                q[i] = v.name + "=" + $('#medicao_inicio').html();
//            } else if (i === 1){
//                q[i] = v.name + "=" + $('#medicao_termino').html();
//            } else 
            if (v.value.length > 0 && (v.value !== 'Todos' || v.value !== 'Todas')) {
                q[i] = v.name + "=" + v.value;
            }
        });
        q[q.length] = 'order[0][0]=' + (table_valores.order()[0][0] + 1) + '&order[0][1]=' + table_valores.order()[0][1];
        //q[q.length] = 'order[1][0]=' + (table_colaboradores.order()[0][0] + 1) + '&order[1][1]=' + table_colaboradores.order()[0][1];
        //q[q.length] = 'order[2][0]=' + (table_tempo.order()[0][0] + 1) + '&order[2][1]=' + table_tempo.order()[0][1];
        q[q.length] = 'order[1][0]=' + (table_faltas.order()[0][0] + 1) + '&order[1][1]=' + table_faltas.order()[0][1];
        q[q.length] = 'order[2][0]=' + (table_turnover.order()[0][0] + 1) + '&order[2][1]=' + table_turnover.order()[0][1];

        q = q.filter(function (v) {
            return v.length > 0;
        });
        if (q.length > 0) {
            search = '/q?' + q.join('&');
        }

        //$('#pdf').prop('href', "<?php // site_url('apontamento_financas/pdf/'); ?>" + search);
        pdf = "<?= site_url('apontamento_financas/pdf/'); ?>" + search;
    }


    function gerarRelatorio() {
        $('#pdf').prop('disabled', true);
        $.ajax({
            url: pdf,
            type: "POST",
            dataType: "json",
            data: {
                busca: busca,
                chart_valores: $('#chart_valores').html(),
                chart_vacancia: $('#chart_vacancia').html()
                //chart_perdaReceita: $('#chart_perda_receita').html(),
                //chart_glosaDias: $('#chart_glosa_dias').html(),
                //chart_glosaMinutos: $('#chart_glosa_minutos').html()
            },
            success: function (dat) {
                location.href = '<?= site_url('apontamento_financas/downloadPdf'); ?>/' + dat.pacote;
                setTimeout(function () {
                    $('#pdf').prop('disabled', false);
                }, 3000);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Não foi possível gerar o relatório.');
                $('#pdf').prop('disabled', false);
            }
        });
    }

</script>
</body>
</html>