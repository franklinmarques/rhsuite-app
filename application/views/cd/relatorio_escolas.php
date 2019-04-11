<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de apontamento</title>
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
                    </p>
                </td>
                <?php if ($is_pdf == false): ?>
                    <td nowrap>
                        <a id="pdf" class="btn btn-sm btn-danger"
                           href="<?= site_url('cd/relatorios/pdfEscolas/' . $query_string); ?>"
                           title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                        <!--<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>-->
                    </td>
                <?php endif; ?>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                    <?php if ($is_pdf == false): ?>
                        <h3 class="text-center" style="font-weight: bold;">REGISTRO DE OCORRÊNCIAS NO MÊS
                            DE <?= strtoupper($mes_nome) ?> DE <?= $ano ?></h3>
                        <?php if ($contrato): ?>
                            <h4 class="text-center" style="font-weight: bold;">CONTRATO Nº <?= $contrato->contrato ?>
                                ─ <?= $contrato->nome ?> ─ <?= $contrato->setor ?></h4>
                        <?php endif; ?>
                    <?php else: ?>
                        <h4 class="text-center" style="font-weight: bold;">REGISTRO DE OCORRÊNCIAS NO MÊS
                            DE <?= strtoupper($mes_nome) ?> DE <?= $ano ?></h4>
                        <?php if ($contrato): ?>
                            <h5 class="text-center" style="font-weight: bold;">CONTRATO Nº <?= $contrato->contrato ?>
                                ─ <?= $contrato->nome ?> ─ <?= $contrato->setor ?></h5>
                        <?php endif; ?>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
        </table>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <div class="row">
        <?php if ($departamento): ?>
            <div class="col col-md-4">
                <label>Departamento:</label> <?= $departamento; ?>
            </div>
        <?php endif; ?>
        <?php if ($diretoria): ?>
            <div class="col col-md-4">
                <label>Diretoria:</label> <?= $diretoria; ?>
            </div>
        <?php endif; ?>
        <?php if ($supervisor): ?>
            <div class="col col-md-4">
                <label>Supervisor(a):</label> <?= $supervisor; ?>
            </div>
        <?php endif; ?>
    </div>
    <br>

    <div>
        <table id="escolas" class="table table-bordered table-condensed">
            <thead>
            <tr class="success">
                <th colspan="<?= ($postos ? 3 : 0) + $dias + 6 ?>" class="text-center"><h3><strong>Medição de
                            Escolas</strong></h3></th>
            </tr>
            <tr class="active">
                <th rowspan="2" class="text-center text-nowrap" style="vertical-align: middle;">Escola</th>
                <th rowspan="2" class="text-center text-nowrap" style="vertical-align: middle;">Município</th>
                <th rowspan="2" class="text-center text-nowrap" style="vertical-align: middle;">P</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">Funcionário(a)</th>
                <!-- <th rowspan="2" class="text-center" style="vertical-align: middle;">N&ordm;</th> -->
                <th colspan="<?= $dias ?>" class="text-center">Dias cobertos por cuidadores substitutos</th>
            </tr>
            <tr class="active">
                <?php for ($i = 1; $i <= $dias; $i++): ?>
                    <th class="text-center"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                <?php endfor; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($apontamentos as $apontamento): ?>
                <tr>
                    <td><?= $apontamento->escola ?></td>
                    <td><?= $apontamento->municipio ?></td>
                    <td><?= $apontamento->turno ?></td>
                    <?php if ($apontamento->nome): ?>
                        <td><?= $apontamento->nome ?></td>
                    <?php elseif ($apontamento->remanejado): ?>
                        <td class="text-center text-danger">Remanejado</td>
                    <?php else: ?>
                        <td class="text-center text-danger">A contratar</td>
                    <?php endif; ?>
                    <!-- <td><?php //echo $apontamento->numero; ?></td> -->
                    <?php for ($i = 1; $i <= $dias; $i++): ?>
                        <?php $dia = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                        <td class="text-center"><?php eval('echo $apontamento->sub_' . $dia . ' ?? $apontamento->dia_' . $dia . ';'); ?></td>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <table id="legenda" class="table table-condensed">
            <thead>
            <tr class="active">
                <th colspan="6" class="text-center"><h4><strong>Legenda de cuidadores substitutos</strong></h4></th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 1; ?>
            <?php foreach ($legendas as $legenda => $nomeFuncionario): ?>
                <?php if ($count % 3 == 1): ?>
                    <tr>
                <?php endif; ?>
                <td class="text-right" width="auto"><strong><?= $legenda ?? '' ?>:</strong></td>
                <td><?= $nomeFuncionario ?? '' ?></td>
                <?php if ($count % 3 == 0): ?>
                    </tr>
                <?php endif; ?>
                <?php $count++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (count($observacoes) > 0): ?>
            <pagebreak odd-header-name="myHeader"></pagebreak>
            <table id="observacoes" class="table table-bordered table-condensed">
                <thead>
                <tr class="success">
                    <th colspan="7" class="text-center"><h3><strong>Observações do mês</strong></h3></th>
                </tr>
                <tr class="active">
                    <th class="text-center">Legenda</th>
                    <th class="text-center" nowrap>Tipo de evento</th>
                    <?php foreach ($observacoes['semanas'] as $semana): ?>
                        <th class="text-center" nowrap>
                            <?php if ($semana['data_ini'] != $semana['data_fim']): ?>
                                Dias <?= $semana['data_ini'] ?> a <?= $semana['data_fim'] ?>
                            <?php else: ?>
                                Dia <?= $semana['data_ini'] ?>
                            <?php endif; ?>
                        </th>
                    <?php endforeach; ?>
                    <?php unset($observacoes['semanas']); ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($observacoes as $k => $observacao): ?>
                    <tr>
                        <td class="text-center"><?= $k ?></td>
                        <td><?= $observacao['status'] ?></td>
                        <td>
                            <?php foreach ($observacao['semana1'] as $semana1): ?>
                                <p><?php foreach ($semana1['observacoes'] as $observacao1): ?>
                                        <?php if (!empty($observacao1['nome'])) : ?>
                                            (Dia <?= implode(',', $observacao1['dias']) ?>)&ensp;
                                            <?= $semana1['nome'] ?> -
                                            <?= $observacao1['nome'] ?>
                                        <?php endif; ?><br>
                                    <?php endforeach; ?></p>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php foreach ($observacao['semana2'] as $semana2): ?>
                                <p><?php foreach ($semana2['observacoes'] as $observacao2): ?>
                                        <?php if (!empty($observacao2['nome'])) : ?>
                                            (Dia <?= implode(',', $observacao2['dias']) ?>)&ensp;
                                            <?= $semana2['nome'] ?> -
                                            <?= $observacao2['nome'] ?>
                                        <?php endif; ?><br>
                                    <?php endforeach; ?></p>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php foreach ($observacao['semana3'] as $semana3): ?>
                                <p><?php foreach ($semana3['observacoes'] as $observacao3): ?>
                                        <?php if (!empty($observacao3['nome'])) : ?>
                                            (Dia <?= implode(',', $observacao3['dias']) ?>)&ensp;
                                            <?= $semana3['nome'] ?> -
                                            <?= $observacao3['nome'] ?>
                                        <?php endif; ?><br>
                                    <?php endforeach; ?></p>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php foreach ($observacao['semana4'] as $semana4): ?>
                                <p><?php foreach ($semana4['observacoes'] as $observacao4): ?>
                                        <?php if (!empty($observacao4['nome'])) : ?>
                                            (Dia <?= implode(',', $observacao4['dias']) ?>)&ensp;
                                            <?= $semana4['nome'] ?> -
                                            <?= $observacao4['nome'] ?>
                                        <?php endif; ?><br>
                                    <?php endforeach; ?></p>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php foreach ($observacao['semana5'] as $semana5): ?>
                                <p><?php foreach ($semana5['observacoes'] as $observacao5): ?>
                                        <?php if (!empty($observacao5['nome'])) : ?>
                                            (Dia <?= implode(',', $observacao5['dias']) ?>)&ensp;
                                            <?= $semana5['nome'] ?> -
                                            <?= $observacao5['nome'] ?>
                                        <?php endif; ?><br>
                                    <?php endforeach; ?></p>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>
<script>
    $('[name="calculo_totalizacao"]').on('change', function () {
        var queryStr = '<?= $query_string ?>';
        var arrQuery = {};
        $.each(queryStr.split('&'), function (i, v) {
            var q = v.split('=');
            arrQuery[q[0]] = q[1];
        });
        if (this.value === '2') {
            $('.totalizacao_1').hide();
            $('.totalizacao_2').show();
            arrQuery['calculo_totalizacao'] = '2'
        } else {
            $('.totalizacao_1').show();
            $('.totalizacao_2').hide();
            arrQuery['calculo_totalizacao'] = '1'
        }

        var search = [];
        $.each(arrQuery, function (i, v) {
            search.push(i + '=' + v);
        });
        $('#pdf').prop('href', "<?= site_url('cd/relatorios/pdfEscolas'); ?>/" + search.join('&'));
    });
</script>
</body>
</html>