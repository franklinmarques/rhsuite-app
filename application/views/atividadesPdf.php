<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de Atividades</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body style="color: #000;">
<div class="container-fluid">
    <table>
        <tr>
            <td>
                <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 92px; max-width:154px; vertical-align: middle; padding: 0 10px 5px 0;">
            </td>
            <td style="vertical-align: top;">
                <p>
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </td>
        </tr>
    </table>
    <table id="atividades" class="table table-condensed table-condensed">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="3" style="padding-bottom: 12px;">
                <h2 class="text-center" style="font-weight: bold;">RELATORIO DE ATIVIDADES PENDENTES</h2>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr class="success" style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
            <td style="padding: 4px 0px;">
                <h5><span style="font-weight: bold;">Data atual: </span><?= date('d/m/Y') ?></h5>
            </td>
            <td style="padding: 4px 0px;">
                <h5><span style="font-weight: bold;">Usuário: </span><?= $usuario->nome ?></h5>
            </td>
            <td style="padding: 4px 0px;">
                <h5><span style="font-weight: bold;">Depto/área/setor: </span><?= $usuario->estrutura ?></h5>
            </td>
        </tr>
        </tbody>
    </table>
    <table id="table" class="table table-bordered table-condensed">
        <thead>
        <tr class='active'>
            <th class="text-center">Atv.</th>
            <th class="text-center">Colaborador</th>
            <th class="text-center">PR</th>
            <th class="text-center">ST</th>
            <th class="text-center">Atividades</th>
            <th class="text-center">Cadastro</th>
            <th class="text-center">Limite</th>
            <th class="text-center">Fechamento</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr class="<?= $row->id_mae ? 'active' : ''; ?>">
                <td style="background-color: #f5f5f5"><?= $row->id ?></td>
                <td><?= $row->nome ?></td>
                <td><?= $row->prioridade ?></td>
                <td><?= $row->status ?></td>
                <td><?= $row->atividade . (strlen($row->observacoes) ? '<br><br><p style="margin: 8px 0 0 15px;">Obs.: ' . $row->observacoes . '</p>' : '') ?></td>
                <td class="text-center"><?= $row->data_cadastro ?></td>
                <td class="text-center"><?= $row->data_limite ?></td>
                <td class="text-center"><?= $row->data_fechamento ?></td>
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
        table = $('#table').DataTable({});

    });
</script>
</body>
</html>