<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de Alocação de Backups</title>
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
        <table id="backup" class="table table-condensed">
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
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="2" style="padding-bottom: 8px; text-align: center;">
                    <h4 class="text-center" style="font-weight: bold;">RELATÓRIO DE ALOCAÇÃO DE BACKUPS MÊS
                        DE <?= mb_strtoupper($mes_nome) ?> DE <?= $ano ?></h4>
                    <?php if ($contrato): ?>
                        <?php if ($setor): ?>
                            <h5 class="text-center" style="font-weight: bold;">CONTRATO
                                Nº <?= $contrato->contrato ?>
                                ─ <?= $contrato->nome ?> ─ <?= $contrato->setor ?></h5>
                        <?php else: ?>
                            <h5 class="text-center" style="font-weight: bold;"><?= $contrato->nome ?></h5>
                        <?php endif; ?>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
        </table>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>
    <br>
    <div>
        <table id="table" class="table table-bordered table-condensed" width="100%">
            <thead>
            <tr class="success">
                <th colspan="6" class="text-center"><h3><strong>Alocação de Backups</strong></h3></th>
            </tr>
            <tr class="active">
                <th class="text-center">Dia</th>
                <th class="text-center text-nowrap">Unidade</th>
                <th class="text-center">Evento</th>
                <th class="text-center">Glosa</th>
                <th class="text-center text-nowrap">Principal</th>
                <th class="text-center text-nowrap">Backup</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="text-center"><?= $row->dia ?></td>
                    <td><?= $row->setor ?></td>
                    <td class="text-center"><?= $row->status ?></td>
                    <td class="text-center">
                        <?php if (in_array($row->status, array('FJ', 'FN', 'PD', 'PI', 'FR'))): ?>
                            <?= $row->qtde_dias ?>
                        <?php elseif (in_array($row->status, array('AJ', 'AN', 'SJ', 'SN'))): ?>
                            <?= $row->hora_atraso ?>
                        <?php elseif ($row->status == 'AE'): ?>
                            <?= $row->apontamento ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $row->nome ?></td>
                    <td><?= $row->nome_bck ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>