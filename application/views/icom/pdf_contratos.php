<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Gestão Comercial: Mapa de Contratos</title>
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
    <table id="contratos" class="table table-condensed table-condensed">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="3" style="padding-bottom: 12px;">
                <h3 class="text-center" style="font-weight: bold;">MAPA DE CONTRATOS</h3>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
            <td colspan="3" style="padding: 4px 0px;">
                <h5><span style="font-weight: bold;">Mês/ano: </span><?= $mes_ano ?></h5>
            </td>
        </tr>
        <tr style='border-bottom: 5px solid #ddd;'>
            <td style="padding: 0px;">
                <h5><strong>Departamento: </strong><span id="depto"><?= $depto ?></span></h5>
            </td>
            <td style="padding: 0px;">
                <h5><strong>Áera: </strong><span id="area"><?= $area ?></span></h5>
            </td>
            <td style="padding: 0px;">
                <h5><strong>Setor: </strong><span id="setor"><?= $setor ?></span></h5>
            </td>
        </tr>
        </tbody>
    </table>
    <table id="table" class="table table-bordered table-condensed" width="100%">
        <thead>
        <tr class='active'>
            <th class="text-nowrap">ID Contrato</th>
            <th>Cliente</th>
            <th class="text-center">Status</th>
            <th class="text-center">Vencimento</th>
            <th>Contato</th>
            <th>Telefone</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($rows): ?>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td width="auto"><?= $row->codigo; ?></td>
                    <td width="30%"><?= $row->nome_cliente; ?></td>
                    <td width="auto" class="text-center"><?= $row->status; ?></td>
                    <td width="auto" class="text-center"><?= $row->data_vencimento; ?></td>
                    <td width="30%"><?= $row->contato_principal; ?></td>
                    <td width="20%"><?= $row->telefone_contato_principal; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td class="text-center text-muted" colspan="6">Nenhum registro encontrado</td>
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