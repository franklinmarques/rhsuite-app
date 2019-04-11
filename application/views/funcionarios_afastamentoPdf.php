<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório Recrutamento</title>
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
    <table>
        <tr>
            <td>
                <img src="<?= base_url($foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
            </td>
            <td style="vertical-align: top;">
                <p>
                    <img src="<?= base_url($foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </td>
        </tr>
    </table>
    <table class="afastamento table table-condensed">
        <tr style='border-top: 3px solid #ddd;'>
            <th colspan="3" style="padding-top: 8px; text-align: center;">
                <h3 style="font-weight: bold;">RELATÓRIO DE AFASTAMENTOS</h3>
            </th>
        </tr>
    </table>

    <!--<div class="table-responsive">-->
    <table id="table" class="funcionarios table table-bordered table-condensed">
        <thead>
        <tr>
            <th>Funcionário</th>
            <th class="text-center">Data afastamento</th>
            <th>Motivo do afastamento</th>
            <th class="text-center">Data perícia médica</th>
            <th class="text-center">Data limite do benefício</th>
            <th class="text-center">Data do retorno ao trabalho</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($funcionarios as $funcionario): ?>
            <tr>
                <td><?= $funcionario->nome ?></td>
                <td class="text-center"><?= $funcionario->data_afastamento ?></td>
                <td><?= $funcionario->motivo_afastamento ?></td>
                <td class="text-center"><?= $funcionario->data_pericia_medica ?></td>
                <td class="text-center"><?= $funcionario->data_limite_beneficio ?></td>
                <td class="text-center"><?= $funcionario->data_retorno ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <!--</div>-->
</div>
</body>
</html>