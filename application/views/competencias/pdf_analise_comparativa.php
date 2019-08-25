<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajax CRUD with Bootstrap modals and Datatables</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">
    <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container-fluid">
    <?php if ($is_pdf == false): ?>
        <div>
            <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #f5f5f5;">
                <li class="active">Relatório de Análise Comparativa: <?= $avaliacao->nome ?></li>
            </ol>
            <br/>
            <div class="row">
                <div class="col col-xs-12">
                    <p class="text-right">
                        <button type="button" id="gerar_comparativo" onclick="gerar_comparativo()"
                                class="btn btn-primary">Gerar comparativo
                        </button>
                        <a class="btn btn-default" onclick="javascript:history.back()"><i
                                    class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</a>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="control-label">Filtrar por departamento</label>
                                <?php echo form_dropdown('depto', $depto, '', 'class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Filtrar por área</label>
                                <?php echo form_dropdown('area', $area, '', 'class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Filtrar por setor</label>
                                <?php echo form_dropdown('setor', $setor, '', 'class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label><br>
                                <div class="btn-group" role="group" aria-label="...">
                                    <button type="submit" id="limpa_filtro" class="btn btn-sm btn-default">Limpar
                                        filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-body" style="padding: 0 20px 20px;">
                <form action="#" id="form">
                    <input type="hidden" value="<?= $avaliacao->id ?>" name="id_avaliacao"/>
                    <div class="row form-group">
                        <?php echo form_multiselect('avaliados[]', $avaliadores, array(), 'size="10" id="avaliados" class="demo2"') ?>
                    </div>
                </form>
            </div>

        </div>
    <?php endif; ?>

    <table>
        <tr>
            <td>
                <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
            </td>
            <td style="vertical-align: top;">
                <p>
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </td>
        </tr>
    </table>
    <table class="table table-condensed avaliacao">
        <thead>
        <tr>
            <th colspan="3">
                <?php if ($is_pdf == false): ?>
                    <h2 class="text-center">AVALIAÇÃO DE DESEMPENHO POR COMPETÊNCIAS</h2>
                    <h3 class="text-center">COMPARATIVO ENTRE COLABORADORES AVALIADOS</h3>
                <?php else: ?>
                    <h3 class="text-center">AVALIAÇÃO DE DESEMPENHO POR COMPETÊNCIAS</h3>
                    <h4 class="text-center">COMPARATIVO ENTRE COLABORADORES AVALIADOS</h4>
                <?php endif; ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd;'>
            <?php if ($is_pdf == false): ?>
                <td>
                    <h5><strong>Avaliação: </strong><?= $avaliacao->nome ?></h5>
                    <h5><strong>Data atual: </strong><?= $data_atual ?></h5>
                </td>
                <td>
                    <h5><strong>Data início: </strong><?= $avaliacao->data_inicio ?></h5>
                    <h5><strong>Data término: </strong><?= $avaliacao->data_termino ?></h5>
                </td>
            <?php else: ?>
                <td>
                    <h6><strong>Avaliação: </strong><?= $avaliacao->nome ?></h6>
                    <h6><strong>Data atual: </strong><?= $data_atual ?></h6>
                </td>
                <td>
                    <h6><strong>Data início: </strong><?= $avaliacao->data_inicio ?></h6>
                    <h6><strong>Data término: </strong><?= $avaliacao->data_termino ?></h6>
                </td>
            <?php endif; ?>
            <td class="text-right">
                <?php if ($is_pdf == false): ?>
                    <a id="pdf" class="btn btn-sm btn-danger"
                       href="<?= site_url('avaliacao/relatorios/pdfAnalise_comparativa/' . $this->uri->rsegment(3)); ?>"
                       title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                <?php endif; ?>
            </td>
        </tr>
        </tbody>
    </table>
    <div id="barchart_values"></div>
    <!--<img src="data:image/png;base64,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">-->
    <hr>
    <!--<img id="buu" src="">-->
    <!--<object><div id='png'></div></object>-->

    <?php foreach ($cargos as $competencias): ?>
        <?php foreach ($competencias as $competencia): ?>
            <section class="panel panel-default">
                <div class="panel-heading no-border"
                     style="color: #333 !important; background-color: #f5f5f5 !important;">
                    <strong><?= $competencia->nome ?></strong></div>
                <!-- Table -->
                <div class="panel-body" style="padding: 10px 15px;">
                    <!--<div class="table-responsive">-->

                    <?php foreach ($competencia->dimensao as $k => $dimensao): ?>
                        <table id="table_<?= $dimensao->id ?>" class="competencias table table-condensed">
                            <thead>
                            <tr class='success text-success'
                                style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">
                                <td colspan="7"><strong><?= $dimensao->nome ?></strong></td>
                            </tr>
                            <tr style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">
                                <th class='text-left'>Colaborador</th>
                                <th class='text-center'>Nível (<?= $dimensao->nivel ?>)</th>
                                <th class='text-center'>Gap (%)</th>
                                <th class='text-center'>Atitude (<?= $dimensao->atitude ?>)</th>
                                <th class='text-center'>Gap (%)</th>
                                <th class='text-center'>IDc (<?= $dimensao->IDc ?>)</th>
                                <th class='text-center'>Gap (%)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($is_pdf): ?>
                                <?php foreach ($dimensao->colaboradores as $colaborador): ?>
                                    <tr class='text-right'>
                                        <td class='text-left'><?= $colaborador->nome ?></td>
                                        <?php switch (true): case $colaborador->nivel === null: ?>
                                            <td class="text-muted">0</td>
                                            <td class="text-muted">0</td>
                                            <?php break; ?>
                                        <?php case $colaborador->nivel > $dimensao->nivel: ?>
                                            <td class="text-success"><strong><?= $colaborador->nivel ?></strong></td>
                                            <td class="text-success"><strong><?= $colaborador->gapNivel ?></strong></td>
                                            <?php break; ?>
                                        <?php case $colaborador->nivel < $dimensao->nivel: ?>
                                            <td class="text-danger"><strong><?= $colaborador->nivel ?></strong></td>
                                            <td class="text-danger"><strong><?= $colaborador->gapNivel ?></strong></td>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <td><?= $colaborador->nivel ?></td>
                                            <td><?= $colaborador->gapNivel ?></td>
                                        <?php endswitch ?>

                                        <?php switch (true): case $colaborador->atitude === null: ?>
                                            <td class="text-muted">0</td>
                                            <td class="text-muted">0</td>
                                            <?php break; ?>
                                        <?php case $colaborador->atitude > $dimensao->atitude: ?>
                                            <td class="text-success"><strong><?= $colaborador->atitude ?></strong></td>
                                            <td class="text-success"><strong><?= $colaborador->gapAtitude ?></strong>
                                            </td>
                                            <?php break; ?>
                                        <?php case $colaborador->atitude < $dimensao->atitude: ?>
                                            <td class="text-danger"><strong><?= $colaborador->atitude ?></strong></td>
                                            <td class="text-danger"><strong><?= $colaborador->gapAtitude ?></strong>
                                            </td>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <td><?= $colaborador->atitude ?></td>
                                            <td><?= $colaborador->gapAtitude ?></td>
                                        <?php endswitch ?>

                                        <?php switch (true): case $colaborador->IDc === null: ?>
                                            <td class="text-muted">0</td>
                                            <td class="text-muted">0</td>
                                            <?php break; ?>
                                        <?php case $colaborador->IDc > $dimensao->IDc: ?>
                                            <td class="text-success"><strong><?= $colaborador->IDc ?></strong></td>
                                            <td class="text-success"><strong><?= $colaborador->gapIDc ?></strong></td>
                                            <?php break; ?>
                                        <?php case $colaborador->IDc < $dimensao->IDc: ?>
                                            <td class="text-danger"><strong><?= $colaborador->IDc ?></strong></td>
                                            <td class="text-danger"><strong><?= $colaborador->gapIDc ?></strong></td>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <td><?= $colaborador->IDc ?></td>
                                            <td><?= $colaborador->gapIDc ?></td>
                                        <?php endswitch ?>

                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                </div>
            </section>
            <!--</div>-->
        <?php endforeach; ?>
    <?php endforeach; ?>

</div>

<link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css'); ?>" rel="stylesheet">
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Relatório de Análise Comparativa: ' + "<?= $avaliacao->nome ?>";
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js'); ?>"></script>
<script src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    var demo2;
    var avaliados;
    var tables = new Object();

    <?php foreach ($cargos as $competencias): ?>
    <?php foreach ($competencias as $competencia): ?>
    <?php foreach ($competencia->dimensao as $k => $dimensao): ?>
    tables[<?= $dimensao->id ?>] = $('#table_' + '<?= $dimensao->id ?>').DataTable({
        'info': false,
        'processing': true, //Feature control the processing indicator.
        'serverSide': false, //Feature control DataTables' server-side processing mode.
        'searching': false,
        'lengthChange': false,
        'paging': false,
        'ordering': false,
        'paginate': false,
        'language': {
            'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
        },
        'columnDefs': [
            {
                'width': '100%',
                'targets': [0]
            },
            {
                'className': 'text-center text-nowrap',
                'targets': [1, 2, 3, 4, 5, 6]
            },
            {
                'render': function (data, type, row) {
                    if (data === null) {
                        data = '<span class="text-muted">0</span>';
                    } else if (row[1] > <?= $dimensao->nivel ?>) {
                        data = '<strong class="text-success">' + data + '</strong>';
                    } else if (row[1] < <?= $dimensao->nivel ?>) {
                        data = '<strong class="text-danger">' + data + '</strong>';
                    }
                    return data;
                },
                'targets': [1, 2]
            },
            {
                'render': function (data, type, row) {
                    if (data === null) {
                        data = '<span class="text-muted">0</span>';
                    } else if (row[3] > <?= $dimensao->atitude ?>) {
                        data = '<strong class="text-success">' + data + '</strong>';
                    } else if (row[3] < <?= $dimensao->atitude ?>) {
                        data = '<strong class="text-danger">' + data + '</strong>';
                    }
                    return data;
                },
                'targets': [3, 4]
            },
            {
                'render': function (data, type, row) {
                    if (data === null) {
                        data = '<span class="text-muted">0</span>';
                    } else if (row[5] > <?= $dimensao->IDc ?>) {
                        data = '<strong class="text-success">' + data + '</strong>';
                    } else if (row[5] < <?= $dimensao->IDc ?>) {
                        data = '<strong class="text-danger">' + data + '</strong>';
                    }
                    return data;
                },
                'targets': [5, 6]
            }
        ]
    });
    <?php endforeach; ?>
    <?php endforeach; ?>
    <?php endforeach; ?>

    //            google.charts.load("current", {packages: ["corechart"]});
    //            google.charts.setOnLoadCallback(drawChart);
    //            function drawChart() {
    //                var data = google.visualization.arrayToDataTable([
    //                    ["Element", "IDc", {role: "style"}],
    //                    ["ADRIANA EVA DOS SANTOS", 8.94, "#b07333"],
    //                    ["ANDREIA APARECIDA DE FREITAS SANTOS", 10.49, "silver"],
    //                    ["f1", 19.30, "gold"]
    //                ]);
    //
    //                var view = new google.visualization.DataView(data);
    //                view.setColumns([0, 1,
    //                    {calc: "stringify",
    //                        sourceColumn: 1,
    //                        type: "string",
    //                        role: "annotation"},
    //                    2]);
    //
    //                var options = {
    //                    title: "Nível de IDc por colaborador avaliado",
    //                    width: 600,
    //                    height: 200,
    //                    bar: {groupWidth: "55%"},
    //                    legend: {position: "none"},
    //                };
    //                var chart = new google.visualization.ColumnChart(document.getElementById("barchart_values"));
    //                chart.draw(view, options);
    //                document.getElementById('buu').setAttribute('src', chart.getImageURI());
    ////                document.getElementById('png').outerHTML = '<img src="' + chart.getImageURI() + '">Printable version</img>'
    //            }


    demo2 = $('#avaliados').bootstrapDualListbox({
        'nonSelectedListLabel': 'Colaboradores habilitados',
        'selectedListLabel': 'Colaboradores pesquisados',
        'moveOnSelect': false,
        'helperSelectNamePostfix': false,
        'selectorMinimalHeight': 182,
        'filterPlaceHolder': 'Filtrar',
        'infoText': false
    });

    $('#avaliados').on('change', function () {
        avaliados = $(this).val();
    });
    $('.filtro').on('change', function () {
        filtra_colaboradores();
    });
    $('#limpa_filtro').on('click', function () {
        $('.filtro').val('');
        filtra_colaboradores();
    });

    function filtra_colaboradores() {
        $.ajax({
            'url': '<?php echo site_url('avaliacao/avaliadoravaliados/ajax_avaliados/' . $avaliacao->id) ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': $('.filtro').serialize(),
            'success': function (json) {
                $('#avaliados').html(json).val(avaliados);
                demo2.bootstrapDualListbox('refresh', true);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function gerar_comparativo() {
        var form = $('#form').serialize();
        if (form.split('&').length < 2) {
            alert('Selecione ao menos um colaborador');
            $.each(tables, function (i) {
                tables[i].clear().draw();
            });
            $('html, body').animate({'scrollTop': 600}, 'swing');
            return false;
        }

        $.ajax({
            'url': '<?php echo site_url('avaliacao/relatorios/ajax_analiseComparativa') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': form,
            'beforeSend': function () {
                $('#gerar_comparativo').attr('disabled', true).text('Gerando comparativo...');
            },
            'success': function (json) {
                $('html, body').animate({'scrollTop': 600}, 'swing');
                if (json.length > 0) {
                    $(json).each(function (i, v) {
                        tables[v.id].clear().rows.add(v.dados).draw();
                    });
                } else {
                    $.each(tables, function (i) {
                        tables[i].clear().draw();
                    });
                }

                var search = '';
                var q = new Array();
                $(form.split('&')).each(function (i, v) {
                    if (i > 0) {
                        q[i] = v;
                    }
                });
                if (q.length > 0) {
                    search = '/q?' + q.join('&');
                }

                $('#pdf').prop('href', "<?= site_url('avaliacao/relatorios/pdfAnalise_comparativa/' . $this->uri->rsegment(3)); ?>" + search);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            },
            'complete': function () {
                $('#gerar_comparativo').attr('disabled', false).text('Gerar comparativo');
            }
        });
    }

</script>

</body>
</html>