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

    <table id="cuidadores" class="table table-condensed">
        <thead>
        <tr>
            <td style="width: auto;">
                <img src="<?= $empresa->foto ?>" align="left"
                     style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
            </td>
            <td style="width: 100%; vertical-align: top;">
                <p>
                    <img src="<?= $empresa->foto_descricao ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </td>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
            <td colspan="2" style="padding-bottom: 8px; text-align: center;">
                <h4 class="text-center" style="font-weight: bold;">RELATÓRIO DE RELAÇÃO DE ESCOLAS</h4>
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <table id="table" class="table datatable table-striped table-bordered table-condensed"
               cellspacing="0"
               width="100%">
            <thead>
            <tr class="active">
                <th style="vertical-align: middle;">Município - Escola</th>
                <th style="vertical-align: middle;">Cuidador(es)</th>
                <th class="text-center" style="vertical-align: middle;">Vale-transporte</th>
                <th class="text-center" style="vertical-align: middle;">Período</th>
                <th style="vertical-align: middle;">Aluno(a)</th>
                <th>Hipótese diagnóstica</th>
            </tr>
            </thead>
            <tbody>
            <?php $escolas = ''; ?>
            <?php $turno = ''; ?>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= $row->municipio_escola; ?></td>
                    <td><?= $row->cuidador; ?></td>
                    <td class="text-center"><?= $row->vale_transporte; ?></td>
                    <td class="text-center"><?= $row->turno; ?></td>
                    <td><?= $row->aluno; ?></td>
                    <td><?= $row->hipotese_diagnostica; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<script>

    var table;

    $(document).ready(function () {
        //datatables
        table = $('#table').DataTable();
    });
</script>
</body>
</html>