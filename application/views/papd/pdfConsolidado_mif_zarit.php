<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório Consolidado MIF-ZARIT</title>
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

        tr.group, tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
</head>
<body style="color: #000;">
<div class="container-fluid">
    <table id="table">
        <thead>
        <tr>
            <th>
                <img src="<?= base_url($foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
            </th>
            <th style="vertical-align: top;">
                <p>
                    <img src="<?= base_url($foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 3px solid #ddd;'>
            <td colspan="2" style="padding-bottom: 8px; text-align: center;">
                <h3 style="font-weight: bold;">CONSOLIDADO MIF-ZARIT</h3>
            </td>
        </tr>
        </tbody>
    </table>
    <table id="table_consolidado_mif_zarit" class="table table-bordered table-condensed"
           cellspacing="0" width="100%">
        <thead>
        <tr class="active">
            <th rowspan="2">Paciente</th>
            <th colspan="2" class="text-center ano"><?= $ano1; ?></th>
            <th colspan="2" class="text-center ano"><?= $ano2; ?></th>
            <th colspan="2" class="text-center ano"><?= $ano3; ?></th>
            <th colspan="2" class="text-center ano"><?= $ano4; ?></th>
            <th colspan="2" class="text-center ano"><?= $ano5; ?></th>
        </tr>
        <tr class="active">
            <th class="text-center">MIF</th>
            <th class="text-center">ZARIT</th>
            <th class="text-center">MIF</th>
            <th class="text-center">ZARIT</th>
            <th class="text-center">MIF</th>
            <th class="text-center">ZARIT</th>
            <th class="text-center">MIF</th>
            <th class="text-center">ZARIT</th>
            <th class="text-center">MIF</th>
            <th class="text-center">ZARIT</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= $row->paciente; ?></td>
                <td class="text-center"><?= isset($mif[0]) ? $row->{$mif[0]} : null; ?></td>
                <td class="text-center"><?= isset($zarit[0]) ? $row->{$zarit[0]} : null; ?></td>
                <td class="text-center"><?= isset($mif[1]) ? $row->{$mif[1]} : null; ?></td>
                <td class="text-center"><?= isset($zarit[1]) ? $row->{$zarit[1]} : null; ?></td>
                <td class="text-center"><?= isset($mif[2]) ? $row->{$mif[2]} : null; ?></td>
                <td class="text-center"><?= isset($zarit[2]) ? $row->{$zarit[2]} : null; ?></td>
                <td class="text-center"><?= isset($mif[3]) ? $row->{$mif[3]} : null; ?></td>
                <td class="text-center"><?= isset($zarit[3]) ? $row->{$zarit[3]} : null; ?></td>
                <td class="text-center"><?= isset($mif[4]) ? $row->{$mif[4]} : null; ?></td>
                <td class="text-center"><?= isset($zarit[4]) ? $row->{$zarit[4]} : null; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>