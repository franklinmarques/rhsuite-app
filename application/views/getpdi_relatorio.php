<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório PDI</title>
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
                    <!--<span style="font-weight: bold;">Associação dos Amigos Metroviários dos Excepcionais - AME</span><br>
                            <span style="font-size: small;">Rua Serra de Botucatu, 1.197 - São Paulo, Brasil ─ CEP 03317-001 ─ Tel.: 2360-8900</span><br>
                            <span style="font-size: small;">Site: www.ame-sp.org.br ─ e-mail: ame@ame-sp.org.br</span>-->
                </p>
            </td>
        </tr>
    </table>
    <table class="table table-condensed pdi">
        <thead>
        <tr>
            <th colspan="2">
                <?php if ($is_pdf == false): ?>
                    <h1 class="text-center">PDI - PLANO DE DESENVOLVIMENTO INDIVIDUAL</h1>
                <?php else: ?>
                    <h3 class="text-center">PDI - PLANO DE DESENVOLVIMENTO INDIVIDUAL</h3>
                <?php endif; ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd;'>
            <td colspan="2">
                <?php if ($is_pdf == false): ?>
                    <h5><strong>PDI: </strong><?= $dadosPDI->nome ?></h5>
                    <h5><strong>Período de
                            desenvolvimento: </strong><span<?= ($dadosPDI->data_valida == 'ok' ? '' : ' class="text-danger"') ?>><?= $dadosPDI->data_inicio . ' a ' . $dadosPDI->data_termino ?></span>
                    </h5>
                    <h5><strong>Data atual: </strong><?= $dadosPDI->data_atual ?></h5>
                <?php else: ?>
                    <h6><strong>PDI: </strong><?= $dadosPDI->nome ?></h6>
                    <h6><strong>Período de
                            desenvolvimento: </strong><span<?= ($dadosPDI->data_valida == 'ok' ? '' : ' class="text-danger"') ?>><?= $dadosPDI->data_inicio . ' a ' . $dadosPDI->data_termino ?></span>
                    </h6>
                    <h6><strong>Data atual: </strong><?= $dadosPDI->data_atual ?></h6>
                <?php endif; ?>
            </td>
        </tr>
        <tr style='border-top: 5px solid #ddd;'>
            <td style="font-weight: bold; width: 120px;">Colaborador</td>
            <td><?= $dadosPDI->colaborador ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Função</td>
            <td><?= $dadosPDI->funcao ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Depto/área/setor</td>
            <td><?= $dadosPDI->depto ?></td>
        </tr>
        </tbody>
    </table>

    <br/>
    <!--<div class="table-responsive">-->

    <?php foreach ($itensPDI as $itemPDI): ?>
        <table class="desenvolvimento table-bordered table table-condensed">
            <thead>
            <tr class="success">
                <th>Competência/item a desenvolver</th>
                <th class="text-center">Data início</th>
                <th class="text-center">Data término</th>
                <th class="text-center">Status</th>
            </tr>
            <tr class="active">
                <td><?= $itemPDI->competencia ?></td>
                <td class="text-center"><?= $itemPDI->data_inicio ?></td>
                <td class="text-center"><?= $itemPDI->data_termino ?></td>
                <?php switch ($itemPDI->status): case 'A': ?>
                    <td class="text-center warning text-warning"><strong>Atrasado</strong></td>
                    <?php break; ?>
                <?php case 'E': ?>
                    <td class="text-center info text-primary"><strong>Em andamento</strong></td>
                    <?php break; ?>
                <?php case 'F': ?>
                    <td class="text-center success text-success"><strong>Finalizado</strong></td>
                    <?php break; ?>
                <?php case 'C': ?>
                    <td class="text-center danger text-danger"><strong>Cancelado</strong></td>
                    <?php break; ?>
                <?php default : ?>
                    <td class="text-center">Não iniciado</td>
                <?php endswitch; ?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="font-weight: bold; white-space: nowrap; vertical-align: top;">Ações para desenvolvimento</td>
                <td colspan="3"><?= htmlentities($itemPDI->descricao); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; white-space: nowrap; vertical-align: top;">Resultados esperados</td>
                <td colspan="3"><?= $itemPDI->expectativa ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; white-space: nowrap; vertical-align: top;">Resultados alcançados</td>
                <td colspan="3"><?= $itemPDI->resultado ?></td>
            </tr>
            </tbody>
        </table>
    <?php endforeach; ?>

    <!--</div>-->

</div>
</body>
</html>