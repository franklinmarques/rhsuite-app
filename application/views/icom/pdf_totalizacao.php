<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Gestão Comercial: Relatório de Totalização</title>
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
                <img src="<?= base_url('imagens/usuarios/' . $foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
            </td>
            <td style="vertical-align: top;">
                <p>
                    <img src="<?= base_url('imagens/usuarios/' . $foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </td>
        </tr>
    </table>
    <table id="totalizacao" class="table table-condensed table-condensed">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="3" style="padding-bottom: 12px;">
                <h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE TOTALIZAÇÃO - <?= $mes_ano; ?></h3>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
            <td>
                <h5><span style="font-weight: bold;">Departamento: </span><?= $depto ?></h5>
            </td>
            <td>
                <h5><span style="font-weight: bold;">Área: </span><?= $area ?></h5>
            </td>
            <td>
                <h5><span style="font-weight: bold;">Setor: </span><?= $setor ?></h5>
            </td>
        </tr>
        </tbody>
    </table>
    <table id="table" class="table table-bordered table-condensed" width="100%">
        <thead>
        <tr>
            <th rowspan="2" class="warning">Nome colaborador</th>
            <th colspan="3" class="text-center">Horarios</th>
            <th colspan="5" class="text-center">Saldo mensal</th>
        </tr>
        <tr>
            <th class="text-center">Entrada</th>
            <th class="text-center">Saída</th>
            <th class="text-center">Banco horas</th>
            <th class="text-center">Positivo</th>
            <th class="text-center">Negativo</th>
            <th class="text-center">Total</th>
            <th class="text-center">Desconto em folha</th>
            <th class="text-center">Hora extra</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($rows->data): ?>
            <?php foreach ($rows->data as $row): ?>
                <tr>
                    <?php foreach ($row as $k => $column): ?>
                        <td class="<?= $k > 0 ? 'text-center' : 'text-left'; ?>"><?= $column; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td class="text-center text-muted" colspan="4">Nenhum registro encontrado</td>
            </tr>
        <?php endif; ?>
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