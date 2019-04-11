<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de Medição Mensal Consolidado</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        @page {
            margin: 40px 20px;
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
    <table id="medicao" class="table table-condensed table-condensed">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="2" style="padding-bottom: 12px;">
                <h3 class="text-center" style="font-weight: bold;">PROGRAMA DE APOIO À PESSOA COM DEFICIÊNCIA<br>RELATORIO
                    DE MEDIÇÃO MENSAL CONSOLIDADO</h3>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
            <td colspan="2" style="padding: 4px 0px;">
                <h5><span style="font-weight: bold;">Data atual: </span><?= date('d/m/Y') ?></h5>
            </td>
        </tr>
        <tr style='border-bottom: 5px solid #ddd;'>
            <td style="padding: 4px 0px;">
                <h5>
                    <span style="font-weight: bold;">Período de medição: </span><?= $medicao->data_inicio . ' a ' . $medicao->data_termino ?>
                </h5>
            </td>
            <td style="padding: 4px 0px;" class="text-right">
                <h5><span style="font-weight: bold;">VALOR TOTAL (R$): </span><?= $medicao->total ?></h5>
            </td>
        </tr>
        </tbody>
    </table>
    <table id="table" class="table table-bordered table-condensed">
        <thead>
        <tr class='active'>
            <th class="text-center" width="60%">Funcionário(a)</th>
            <th class="text-center">Número de atendimentos</th>
            <th class="text-center">Receita gerada (R$)</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= $row->profissional ?></td>
                <td class="text-center"><?= $row->qtde_atendimentos ?></td>
                <td class="text-right"><?= $row->valor ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <!--</div>-->
</div>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

<script>

    var table;

    $(document).ready(function () {
        //datatables
        table = $('#table').DataTable();
    });
</script>
</body>
</html>