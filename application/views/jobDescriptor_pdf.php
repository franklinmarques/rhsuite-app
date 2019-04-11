<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Descritivos de Cargo-Função</title>
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
<style>
    table tr td:first-child {
        white-space: nowrap;
    }
</style>
<body style="color: #000;">
<div class="container-fluid">
    <div style="color: #000;">
        <htmlpageheader name="myHeader">
            <table>
                <tr>
                    <td>
                        <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                             style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                    </td>
                    <td style="vertical-align: top;">
                        <p>
                            <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                                 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                        </p>
                    </td>
                </tr>
            </table>
            <table class="descritivos table table-condensed">
                <thead>
                <tr style='border-top: 3px solid #ddd;'>
                    <th colspan="4" style="padding: 8px 0px; text-align: center;">
                        <h3 style="font-weight: bold;">DESCRITIVO DE CARGO-FUNÇÃO</h3>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd;'>
                    <td style="padding: 8px; 0px;">
                        <span style="font-weight: bold;">Cargo:</span> <?= $jobDescriptor->cargo ?>
                    </td>
                    <td style="padding: 8px; 0px;">
                        <span style="font-weight: bold;">Função:</span> <?= $jobDescriptor->funcao ?>
                    </td>
                    <td style="padding: 8px; 0px;">
                        <span style="font-weight: bold;">CBO:</span> <?= $jobDescriptor->cbo ?>
                    </td>
                    <td style="padding: 8px; 0px;">
                        <span style="font-weight: bold;">Versão:</span> <?= $jobDescriptor->versao ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </htmlpageheader>
        <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>
        <div>
            <?php foreach ($estruturas as $estrutura => $titulo): ?>
                <table id="<?php echo $estrutura; ?>" class="table table-striped table-bordered respondentes"
                       cellspacing="0"
                       width="100%"
                       style="border-radius: 0 !important;">
                    <thead>
                    <tr class="success">
                        <th style="font-size: 16px; font-weight: bold;"><?php echo $titulo; ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?= nl2br($consolidado[$estrutura]); ?></td>
                    </tr>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </div>

    </div>

</body>
</html>
