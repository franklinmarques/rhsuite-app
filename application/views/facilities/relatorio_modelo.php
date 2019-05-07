<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de Vistoria</title>
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
        <table id="table" class="table table-condensed" style="margin-bottom: 5px;">
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
                <td nowrap>
                    <?php if ($is_pdf == false): ?>
                        <a id="pdf" class="btn btn-sm btn-info"
                           href="<?= site_url('facilities/modelos/pdf/' . $query_string); ?>"
                           title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                        <!--<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                    class="glyphicon glyphicon-arrow-left"></i> Voltar
                        </button>-->
                    <?php endif; ?>
                </td>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="3" style="text-align: center;">
                    <h3 class="text-center" style="font-weight: bold;">PROGRAMA DE VISTORIA/MANUTENÇÃO PERIÓDICA</h3>
                </th>
            </tr>
            </thead>
        </table>
        <?php if ($is_pdf == false): ?>
            <div class="row">
                <div class="col col-md-6">
                    <h5><strong>Identificação do plano:</strong> <?= $nomeVistoria; ?></h5>
                </div>
                <div class="col col-md-6">
                    <h5><strong>Mês/ano da vistoria:</strong> <?= date('m/Y'); ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col col-md-6">
                    <h5><strong>Empresa:</strong> <?= $empresaFacilities; ?></h5>
                </div>
            </div>
        <?php else: ?>
            <p>
            <h5><span style="font-weight: bold;">Identificação do plano:</span> <?= $nomeVistoria; ?></h5>
            <h5><span style="font-weight: bold;">Mês/ano da vistoria:</span> <?= date('m/Y'); ?></h5>
            <h5><span style="font-weight: bold;">Empresa:</span> <?= $empresaFacilities; ?></h5>
            </p>
        <?php endif; ?>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <br>

    <?php if (empty($vistorias)): ?>
        <table id="no_itens" class="table table-bordered table-condensed">
            <thead>
            <tr class="active">
                <th rowspan="2">Ativo/facility</th>
                <th rowspan="2">Item</th>
                <th colspan="3" class="text-center">Vistoria/manutenção realizada</th>
                <th colspan="2" class="text-center">Apresenta problemas</th>
                <th rowspan="2" class="text-center">Problema/solicitação</th>
                <th rowspan="2" class="text-center text-nowrap">O. S.</th>
                <th rowspan="2" class="text-center">Observações</th>
                <th colspan="2" class="text-center">Realização</th>
            </tr>
            <tr class="active">
                <th class="text-center">Sim</th>
                <th class="text-center">Não</th>
                <th class="text-center">Não se aplica</th>
                <th class="text-center">Sim</th>
                <th class="text-center">Não</th>
                <th class="text-center">Data</th>
                <th class="text-center">CAT.</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="12" class="text-center text-muted">Nenhum item encontrado.</td>
            </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <?php foreach ($vistorias as $vistoria): ?>
        <table class="table table-bordered table-condensed itens">
            <thead>
            <tr class="success">
                <th colspan="12">
                    <h4>
                        <strong>Unidade:</strong> <span
                                style="font-weight: normal;"><?= $vistoria['nome']->unidade; ?></span>&emsp;
                        <strong>Andar:</strong> <span
                                style="font-weight: normal;"><?= $vistoria['nome']->andar; ?></span>&emsp;
                        <strong>Sala:</strong> <span
                                style="font-weight: normal;"><?= $vistoria['nome']->sala; ?></span>
                    </h4>
                </th>
            </tr>
            <tr class="active">
                <th rowspan="2">Ativo/facility</th>
                <th rowspan="2">Item</th>
                <th colspan="3" class="text-center">Vistoria/manutenção realizada</th>
                <th colspan="2" class="text-center">Apresenta problemas</th>
                <th rowspan="2" class="text-center">Problema/solicitação</th>
                <th rowspan="2" class="text-center text-nowrap">O. S.</th>
                <th rowspan="2" class="text-center">Observações</th>
                <th colspan="2" class="text-center">Realização</th>
            </tr>
            <tr class="active">
                <th class="text-center">Sim</th>
                <th class="text-center">Não</th>
                <th class="text-center">Não se aplica</th>
                <th class="text-center">Sim</th>
                <th class="text-center">Não</th>
                <th class="text-center">Data</th>
                <th class="text-center">CAT.</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($vistoria['subitens'] as $subitem): ?>
                <tr class="<?= $subitem->tipo; ?>">
                    <td><?= $subitem->item; ?></td>
                    <td><?= $subitem->subitem; ?></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center"></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>

</div>

</body>
</html>