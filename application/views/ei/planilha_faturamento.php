<?php if ($is_pdf): ?>
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Planilha de faturamento</title>
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
            <td style="width: 100%; vertical-align: top;">
                <p>
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </td>
            <?php if ($is_pdf == false): ?>
                <td nowrap>
                    <a id="pdf_faturamento" class="btn btn-sm btn-info"
                       href="<?= site_url('ei/apontamento/pdfTotalizacao/?' . $query_string); ?>"
                       title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                </td>
            <?php endif; ?>
        </tr>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                <?php if ($is_pdf == false): ?>
                    <h2 class="text-center" style="font-weight: bold;">Planilha de Faturamento</h2>
                    <h3 class="text-center" style="font-weight: bold;"><?= ucfirst($mesFaturamento); ?>
                        de <?= $anoFaturamento; ?></h3>
                <?php else: ?>
                    <h1 class="text-center" style="font-weight: bold;">Planilha de Faturamento</h1>
                    <h2 class="text-center" style="font-weight: bold;"><?= ucfirst($mesFaturamento); ?>
                        de <?= $anoFaturamento; ?></h2>
                <?php endif; ?>
            </th>
        </tr>
        </thead>
    </table>

    <p>
        Prestação de serviço contínuo de apoio à educação inclusiva, com fornecimento de mão-de-obra aos atendidos
        pelo Centro Paulo Souza, conforme dados abaixo.
    </p>
    <p><strong>Contrato(s):</strong> <?= $contrato; ?><br>
        <strong>Unidade escolar:</strong> <?= $escola; ?><br>
        <strong>Ordem(ns) de Execução de Serviço:</strong> <?= $ordemServico; ?></p>
    <p><strong>Alunos atendidos:</strong> <?= $alunos; ?><br>
        <strong>Profissionais:</strong> <?= $profissional; ?></p>
    <p><strong>Período:</strong> <?= $nomePeriodo; ?></p>
    <table>
        <tr>
            <th style="vertical-align: top;">Horário(s) de trabalho:&nbsp;</th>
            <td>
                <p>
                    <?php foreach ($diasSemana as $k => $diaSemana): ?>
                        <?= implode('', $diaSemana); ?><?= $k < count($diasSemana) - 1 ? ';<br>' : ''; ?>
                    <?php endforeach; ?>
                </p>
            </td>
            <td style="vertical-align: top;">
                <?php if ($is_pdf == false): ?>
                    <div class="row">
                        <label class="control-label col-md-7" style="font-weight: bold;">Data de impressão:</label>
                        <div class="col-md-5">
                            <input id="data_impressao_faturamento" type="text" value="<?= date('d/m/Y'); ?>"
                                   class="form-control text-center data" onchange="setPdfFaturamentoAttributes()">
                        </div>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <br>

    <div>
        <table id="periodo" class="table table-condensed table-bordered">
            <thead>
            <tr class="active">
                <th class="text-center">Mes/ano</th>
                <th class="text-center">Função</th>
                <th class="text-center">Período</th>
                <th class="text-center">Dias</th>
                <th class="text-center">Horas</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($is_pdf == false): ?>
                <?php foreach ($faturamentos as $faturamento): ?>
                    <tr>
                        <td class="text-center"><?= $mesAno ?></td>
                        <input type="hidden" value="<?= $faturamento['id'] ?>" name="id_totalizacao[]">
                        <input type="hidden" value="<?= $faturamento['id_alocado'] ?>" name="id_alocado[]">
                        <input type="hidden" value="<?= $faturamento['periodo'] ?>" name="periodo[]">
                        <td class="text-center" style="width: 60%;"><?= $faturamento['funcao'] ?></td>
                        <td class="text-center"><?= $faturamento['nome_periodo'] ?></td>
                        <td class="text-center" style="width: 60px;"><input name="total_dias[]" class="form-control"
                                                                            type="text"
                                                                            value="<?= $faturamento['dias'] ?>"
                                                                            style="width: 50px;">
                        </td>
                        <td class="text-center" style="width: 110px;"><input name="total_horas[]" type="text"
                                                                             class="form-control hora text-center"
                                                                             value="<?= $faturamento['horas'] ?>"
                                                                             placeholder="hh:mm" style="width: 100px;">
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($faturamentos as $faturamento): ?>
                    <tr>
                        <td class="text-center"><?= $mesAno ?></td>
                        <td class="text-center"><?= $faturamento['funcao'] ?></td>
                        <td class="text-center"><?= $faturamento['nome_periodo'] ?></td>
                        <td class="text-center"><?= $faturamento['dias'] ?></td>
                        <td class="text-center"><?= $faturamento['horas'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($is_pdf): ?>
        <br>
        <br>
        <br>
        <table>
            <tr>
                <td style="width: 60%;"></td>
                <td class="text-center text-danger">
                    <p>
                    <h5 style="font-weight: bold;">São
                        Paulo, <?= $diaAtual; ?> de <?= $mesAtual; ?> de <?= $anoAtual; ?></h5>
                    </p>
                    <br>
                    <p>
                    <h5 class="text-danger"><?= $supervisor->nome ?></h5>
                    <?= $supervisor->funcao ?><br>
                    <?= $supervisor->email ?><br>
                    Programa de Apoio à Educação Inclusiva<br>
                    Tel.: (11)2360-8900
                    </p>
                </td>
            </tr>
        </table>
    <?php endif; ?>

</div>

<?php if ($is_pdf == false): ?>
    <script>
        function setPdfFaturamentoAttributes() {
            var search = '';
            var data_atual = $('#data_impressao_faturamento').val();
            if (moment(data_atual, 'DD/MM/YYYY').isValid()) {
                search = '&data_atual=' + data_atual;
            }
            $('#pdf_faturamento').prop('href', '<?= site_url('ei/apontamento/pdfTotalizacao/?' . $query_string); ?>' + search);
        }
    </script>
<?php else: ?>
</body>
</html>
<?php endif; ?>
