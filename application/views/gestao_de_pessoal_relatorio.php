<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Consolidado de Gestão de Pessoas</title>
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
        <table id="table" class="table table-condensed">
            <thead>
            <tr>
                <th style="width: auto;">
                    <img src="<?= base_url($foto) ?>" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </th>
                <th style="width: 100%; vertical-align: top;">
                    <p>
                        <img src="<?= base_url($foto_descricao) ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </th>
                <th>
                    <?php if ($is_pdf == false): ?>
                        <a href="<?= site_url('gestaoDePessoal/pdf/q?&ano=' . $ano); ?>" class="btn btn-info"><i
                                    class="glyphicon glyphicon-print"></i> Imprimir</a>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="3" style='border-bottom: 5px solid #ddd;'>
                    <?php if ($is_pdf == false): ?>
                        <h2 class="text-center"><strong>CONSOLIDADO DE GESTÃO DE PESSOAS - <?= $ano; ?></strong>
                        </h2>
                    <?php else: ?>
                        <h3 class="text-center"><strong>CONSOLIDADO DE GESTÃO DE PESSOAS - <?= $ano; ?></strong>
                        </h3>
                    <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <div>
        <h3 style="color: #111343;"><strong>Consolidado de Quadro de Colaboradores</strong></h3>
        <table id="table_quadro_colaboradores" class="table table_gestao table-bordered table-condensed"
               cellspacing="0" width="100%">
            <thead>
            <tr class="active">
                <th>Departamento (unidade de negócios)</th>
                <th class="meses_quadro_colaboradores">Jan</th>
                <th class="meses_quadro_colaboradores">Fev</th>
                <th class="meses_quadro_colaboradores">Mar</th>
                <th class="meses_quadro_colaboradores">Abr</th>
                <th class="meses_quadro_colaboradores">Mai</th>
                <th class="meses_quadro_colaboradores">Jun</th>
                <th class="meses_quadro_colaboradores">Jul</th>
                <th class="meses_quadro_colaboradores">Ago</th>
                <th class="meses_quadro_colaboradores">Set</th>
                <th class="meses_quadro_colaboradores">Out</th>
                <th class="meses_quadro_colaboradores">Nov</th>
                <th class="meses_quadro_colaboradores">Dez</th>
                <th>Média anual</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($quadroColaboradores)): ?>
                <tr>
                    <td colspan="14" class="text-center">Nenhum registro encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($quadroColaboradores as $row): ?>
                    <tr>
                        <?php for ($i = 0; $i < 14; $i++): ?>
                            <td><?= $row[$i]; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <hr>
        <h3 style="color: #111343;"><strong>Consolidado de Requisições de Pessoal</strong></h3>
        <table id="table_requisicoes_pessoal" class="table table_gestao table-bordered table-condensed"
               cellspacing="0" width="100%">
            <thead>
            <tr class="active">
                <th rowspan="2" style="vertical-align: middle;">Mês</th>
                <th colspan="2" class="text-center">Abertas</th>
                <th colspan="2" class="text-center">Fechadas</th>
                <th colspan="2" class="text-center">Suspensas</th>
                <th class="text-center">Canceladas</th>
            </tr>
            <tr class="active">
                <th class="text-center">RPs</th>
                <th class="text-center">Vagas</th>
                <th class="text-center">RPs</th>
                <th class="text-center">Vagas</th>
                <th class="text-center">RPs</th>
                <th class="text-center">Vagas</th>
                <th class="text-center">RPs</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($requisicoesPessoal)): ?>
                <tr>
                    <td colspan="8" class="text-center">Nenhum registro encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($requisicoesPessoal as $row): ?>
                    <tr>
                        <?php for ($i = 0; $i < 8; $i++): ?>
                            <td><?= $row[$i]; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr class="active">
                <th>Total</th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            </tfoot>
        </table>


        <hr>
        <h3 style="color: #111343;"><strong>Consolidado de Movimentação de Pessoal</strong></h3>
        <table id="table_turnover" class="table table_gestao table-bordered table-condensed" cellspacing="0"
               width="100%">
            <thead>
            <tr class="active">
                <th>Indicadores</th>
                <th class="meses_turnover">Jan</th>
                <th class="meses_turnover">Fev</th>
                <th class="meses_turnover">Mar</th>
                <th class="meses_turnover">Abr</th>
                <th class="meses_turnover">Mai</th>
                <th class="meses_turnover">Jun</th>
                <th class="meses_turnover">Jul</th>
                <th class="meses_turnover">Ago</th>
                <th class="meses_turnover">Set</th>
                <th class="meses_turnover">Out</th>
                <th class="meses_turnover">Nov</th>
                <th class="meses_turnover">Dez</th>
                <th>Média anual</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($turnover)): ?>
                <tr>
                    <td colspan="14" class="text-center">Nenhum registro encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($turnover as $row): ?>
                    <tr>
                        <?php for ($i = 0; $i < 14; $i++): ?>
                            <td><?= $row[$i]; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>


        <hr>
        <h3 style="color: #111343;"><strong>Consolidado do Quadro de Afastados</strong></h3>
        <table id="table_afastamentos" class="table table_gestao table-bordered table-condensed" cellspacing="0"
               width="100%">
            <thead>
            <tr class="active">
                <th>Indicadores</th>
                <th class="meses_afastamentos">Jan</th>
                <th class="meses_afastamentos">Fev</th>
                <th class="meses_afastamentos">Mar</th>
                <th class="meses_afastamentos">Abr</th>
                <th class="meses_afastamentos">Mai</th>
                <th class="meses_afastamentos">Jun</th>
                <th class="meses_afastamentos">Jul</th>
                <th class="meses_afastamentos">Ago</th>
                <th class="meses_afastamentos">Set</th>
                <th class="meses_afastamentos">Out</th>
                <th class="meses_afastamentos">Nov</th>
                <th class="meses_afastamentos">Dez</th>
                <th>Média anual</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($afastamentos)): ?>
                <tr>
                    <td colspan="14" class="text-center">Nenhum registro encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($afastamentos as $row): ?>
                    <tr>
                        <?php for ($i = 0; $i < 14; $i++): ?>
                            <td><?= $row[$i]; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>


        <hr>
        <h3 style="color: #111343;"><strong>Consolidado de Faltas e Atrasos</strong></h3>
        <table id="table_faltas_atrasos" class="table table_gestao table-bordered table-condensed"
               cellspacing="0" width="100%">
            <thead>
            <tr class="active">
                <th rowspan="2" class="text-center">Departamento (unidade de negócios)</th>
                <th colspan="2" class="text-center">Jan</th>
                <th colspan="2" class="text-center">Fev</th>
                <th colspan="2" class="text-center">Mar</th>
                <th colspan="2" class="text-center">Abr</th>
                <th colspan="2" class="text-center">Mai</th>
                <th colspan="2" class="text-center">Jun</th>
                <th colspan="2" class="text-center">Jul</th>
                <th colspan="2" class="text-center">Ago</th>
                <th colspan="2" class="text-center">Set</th>
                <th colspan="2" class="text-center">Out</th>
                <th colspan="2" class="text-center">Nov</th>
                <th colspan="2" class="text-center">Dez</th>
                <th colspan="2" class="text-center">Média anual</th>
            </tr>
            <tr class="active">
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th class="meses_faltas">F</th>
                <th class="meses_atrasos">A</th>
                <th>F</th>
                <th>A</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($faltasAtrasos)): ?>
                <tr>
                    <td colspan="27" class="text-center">Nenhum registro encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($faltasAtrasos as $row): ?>
                    <tr>
                        <?php for ($i = 0; $i < 27; $i++): ?>
                            <td><?= $row[$i]; ?></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>


<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>


<script>

</script>
</body>
</html>
