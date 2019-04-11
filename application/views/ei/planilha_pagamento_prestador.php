<?php if ($is_pdf): ?>
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Planilha de pagamento de Prestador de Serviços</title>
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
        /*@page {
            margin: 40px 20px;
        }*/

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
<?php endif; ?>
<div class="container-fluid">

    <table id="table" class="table table-condensed">
        <thead>
        <tr>
            <td style="width: auto;">
                <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
            </td>
            <td style="width: 80%; vertical-align: top;">
                <p>
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </td>
            <?php if ($is_pdf == false): ?>
                <td nowrap>
                    <a id="pdf" class="btn btn-sm btn-danger"
                       href="<?= site_url('ei/apontamento/pdfPagamentoPrestador/?' . $query_string); ?>"
                       title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                </td>
            <?php endif; ?>
        </tr>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                <?php if ($is_pdf == false): ?>
                    <h3 class="text-center" style="font-weight: bold;">SOLICITAÇÃO DE PAGAMENTO DE PRESTADOR DE
                        SERVIÇO</h3>
                <?php else: ?>
                    <h2 class="text-center" style="font-weight: bold;">SOLICITAÇÃO DE PAGAMENTO DE PRESTADOR DE
                        SERVIÇO</h2>
                <?php endif; ?>
            </th>
        </tr>
        </thead>
    </table>

    <table width="100%">
        <tr>
            <td width="60%">
                <p>
                    <strong>Nome prestador de serviço:</strong> <?= implode(', ', array_filter([$prestador, $prestador_sub1, $prestador_sub2])); ?><br>
                    <strong>CNPJ:</strong> <?= $cnpj; ?><br>
                    <strong>Centro de custo:</strong> <?= $centroCusto; ?><br>
                    <strong>Agência:</strong> <?= $agencia; ?>&emsp;
                    <strong>Conta:</strong> <?= $conta; ?><br>
                    <strong>Banco:</strong> <?= $banco; ?><br>
                </p>
            </td>
            <td style="vertical-align: top; padding-left: 15px;">
                <p>
                    <strong>Solicitante:</strong> <?= $solicitante; ?><br>
                    <strong>Departamento:</strong> <?= $departamento; ?><br>
                    <strong>Mês de referência:</strong> <?= $mesAno; ?><br>
                </p>
            </td>
        </tr>
    </table>
    <br>

    <div>
        <table id="periodo" class="table table-condensed table-bordered">
            <thead>
            <tr>
                <th colspan="6" class="text-center">DESCRIÇÃO DOS SERVIÇOS PRESTADOS</th>
            </tr>
            <tr class="active">
                <th class="text-center">Período/dias</th>
                <th>Cliente/justificativa</th>
                <th>Período</th>
                <th class="text-center">Quantidade</th>
                <th class="text-center">Valor (R$)</th>
                <th class="text-center">Valor total (R$)</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($servicos as $servico): ?>
                <tr>
                    <td class="text-center"><?= $mesAno ?></td>
                    <td><?= $servico['escola'] ?></td>
                    <td><?= $servico['periodo'] ?></td>
                    <?php if ($is_pdf == false): ?>
                        <input type="hidden" name="id_totalizacao[]" value="<?= $servico['id'] ?>">
                        <td class="text-center horas" style="width: 100px;">
                            <input name="total_horas_faturadas[]" class="form-control hora text-center" type="text"
                                   value="<?= $servico['qtdeHoras'] ?>" placeholder="hh:mm" style="width: 100px;">
                        </td>
                        <td class="text-center" style="width: 100px;">
                            <input name="valor_pagamento[]" class="form-control valor" type="text"
                                   value="<?= $servico['valorCustoProfissional'] ?>" style="width: 100px;">
                        </td>
                        <td class="text-center" style="width: 100px;">
                            <input name="valor_total[]" class="form-control valor" type="text"
                                   value="<?= $servico['total'] ?>"
                                   style="width: 100px;">
                        </td>
                    <?php else: ?>
                        <td class="text-center"><?= $servico['qtdeHoras'] ?></td>
                        <td class="text-center"><?= $servico['valorCustoProfissional'] ?></td>
                        <td class="text-center"><?= $servico['total'] ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <?php if ($valorExtra1): ?>
                <tr>
                    <td class="text-center"><?= $mesAno ?></td>
                    <td colspan="3"><?= $justificativa1 ?></td>
                    <td class="text-center"><?= $valorExtra1 ?></td>
                    <td class="text-center"><?= $valorExtra1 ?></td>
                </tr>
            <?php endif; ?>
            <?php if ($valorExtra2): ?>
                <tr>
                    <td class="text-center"><?= $mesAno ?></td>
                    <td colspan="3"><?= $justificativa2 ?></td>
                    <td class="text-center"><?= $valorExtra2 ?></td>
                    <td class="text-center"><?= $valorExtra2 ?></td>
                </tr>
            <?php endif; ?>
            <tbody>
            <tr>
                <td colspan="5" class="text-right">Valor total a ser pago aos prestados >>></td>
                <td class="text-center"><?= $valorTotal ?></td>
            </tr>
            </tbody>
        </table>
    </div>

    <?php if ($is_pdf): ?>
        <br>
        <br>
        <br>
        <br>
        <table>
            <tr>
                <td style="width: 60%;"></td>
                <td class="text-center text-danger">
                    <hr id="assinatura" class="text-danger"
                        style="margin-bottom: 1px; height: 1px;">
                    <h4> Coordenação do Departamento </h4>
                    <br>
                    <br>
                    <p>
                    <h5 style="font-weight: bold;">São
                        Paulo, <?= strftime("%d de {$mesAtual} de %Y"); ?></h5>
                    </p>
                    <br>
                    <p>
                    <h4 class="text-danger"><?= $usuario->nome; ?></h4>
                    Programa de Apoio à Educação Inclusiva<br>
                    (11)2360-8900 - <?= $usuario->email; ?>
                    </p>
                </td>
            </tr>
        </table>
    <?php endif; ?>

</div>

<?php if ($is_pdf): ?>
</body>
</html>
<?php endif; ?>
