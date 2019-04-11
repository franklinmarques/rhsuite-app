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
                           href="<?= site_url('cd/relatorios/pdfInsumos/' . $query_string); ?>"
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
        <table id="insumos" class="table table-bordered table-condensed">
            <thead>
            <tr class="success">
                <th colspan="<?= count($titulos) + 2; ?>" class="text-center"><h3><strong>Insumos do mês</strong></h3>
                </th>
            </tr>
            <tr class="active">
                <th>Escola</th>
                <th>Aluno(a)</th>
                <?php foreach ($titulos as $titulo): ?>
                    <th class="text-center" style="font-size: x-small;"><?= $titulo; ?></th>
                <?php endforeach; ?>
                <!--<th>Total</th>-->
            </tr>
            </thead>
            <tbody>
            <?php $id_escola = 0; ?>
            <?php foreach ($insumos as $insumo): ?>
                <tr>
                    <?php if ($id_escola !== $insumo['id_escola']): ?>
                        <td rowspan="<?= max($insumo['total_alunos'], 1) ?>"><?= $insumo['escola']; ?></td>
                        <?php $id_escola = $insumo['id_escola']; ?>
                    <?php endif; ?>

                    <td><?= $insumo['aluno']; ?></td>

                    <?php foreach ($titulos as $k => $titulo): ?>
                        <td class="text-center"><?= $insumo[$k]; ?></td>
                    <?php endforeach; ?>

                    <!--<td class="text-center" style="font-weight: bold;"><?php //echo $insumo['total']; ?></td>-->
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>
</body>
</html>