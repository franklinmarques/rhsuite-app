<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de financas</title>
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
                <td style="width: auto;">
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="width: 100%; vertical-align: top;">
                    <p>
                        <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                        <!--<span style="font-weight: bold;">Associação dos Amigos Metroviários dos Excepcionais - AME</span><br>
                        <span style="font-size: small;">Rua Serra de Botucatu, 1.197 - São Paulo, Brasil ─ CEP 03317-001 ─ Tel.: 2360-8900</span><br>
                        <span style="font-size: small;">Site: www.ame-sp.org.br ─ e-mail: ame@ame-sp.org.br</span>-->
                    </p>
                </td>
                <?php if ($is_pdf == false): ?>
                    <td nowrap>
                        <a id="pdf" class="btn btn-sm btn-danger"
                           href="<?= site_url('apontamento_financas/pdf/' . $query_string); ?>"
                           title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    </td>
                <?php endif; ?>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                    <?php if ($is_pdf == false): ?>
                        <h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE CONSOLIDAÇÃO FINANCEIRA</h3>
                        <?php if ($contrato): ?>
                            <h4 class="text-center" style="font-weight: bold;">CONTRATO Nº <?= $contrato->contrato ?>
                                ─ <?= $contrato->nome ?> ─ <?= $contrato->setor ?></h4>
                        <?php endif; ?>
                    <?php else: ?>
                        <h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE CONSOLIDAÇÃO FINANCEIRA</h3>
                        <?php if ($contrato): ?>
                            <h5 class="text-center" style="font-weight: bold;">CONTRATO Nº <?= $contrato->contrato ?>
                                ─ <?= $contrato->nome ?> ─ <?= $contrato->setor ?></h5>
                        <?php endif; ?>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
        </table>
        <?php if ($is_pdf == false): ?>
            <form id="busca" class="row form-inline">
                <input type="hidden" class="filtro" name="depto" value="<?= $depto ?>">
                <input type="hidden" class="filtro" name="area" value="<?= $area ?>">
                <input type="hidden" class="filtro" name="setor" value="<?= $setor ?>">
                <input type="hidden" class="filtro" name="cargo" value="<?= $cargo ?>">
                <input type="hidden" class="filtro" name="funcao" value="<?= $funcao ?>">
                <input type="hidden" name="mes" value="<?= $mes ?>">
                <input type="hidden" name="ano" value="<?= $ano ?>">
                <div class="col-md-4 form-group">&ensp;
                    <label for="exampleInputName2">Mês e ano inicial</label>
                    <?php echo form_dropdown('mes_inicial', $meses, '', 'class="form-control filtro"'); ?>
                    <input type="number" class="form-control filtro" name="ano_inicial" value="<?= $ano ?>"
                           placeholder="aaaa"
                           style="width: 120px;">
                </div>
                <div class="col-md-4 form-group">
                    <label for="exampleInputEmail2">Mês e ano final</label>
                    <?php echo form_dropdown('mes_final', $meses, '', 'class="form-control filtro"'); ?>
                    <input type="number" class="form-control filtro" name="ano_final" value="<?= $ano ?>"
                           placeholder="aaaa"
                           style="width: 120px;">
                </div>
                <div class="col-md-4 form-group">
                    <button type="button" id="pesquisar" class="btn btn-default"><i
                                class="glyphicon glyphicon-search"></i>
                        Pesquisar
                    </button>
                </div>
            </form>
            <br>
        <?php endif; ?>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <div>

        <!-- <div class="row">
            <div class="col-md-6">
                <table id="table_colaboradores" class="table datatable table-striped table-bordered table-condensed"
                       cellspacing="0"
                       width="100%">
                    <thead>
                    <tr class="success">
                        <th colspan="4" class="text-center">
                            <h3><strong>Quantidade de colaboradores (RH)</strong></h3>
                        </th>
                    </tr>
                    <tr class="active">
                        <th class="text-center">Mês/ano</th>
                        <th class="text-center">Contratual</th>
                        <th class="text-center">Ativos</th>
                        <th class="text-center">Férias</th>
                        <th class="text-center">Substitutos</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php //foreach ($rows as $row): ?>
                        <tr>
                            <td class="text-center"><?= $row->mes_ano ?></td>
                            <td class="text-center"><?= $row->usuarios ?></td>
                            <td class="text-center"><?php //echo $row->usuarios_ativos; ?></td>
                            <td class="text-center"><?= $row->usuarios_bck ?></td>
                            <td class="text-center"><?= $row->usuarios_sub ?></td>
                        </tr>
                    <?php //endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table id="table_tempo" class="table datatable table-striped table-bordered table-condensed" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr class="success">
                        <th colspan="4" class="text-center">
                            <h3><strong>Detalhamento de glosas</strong></h3>
                        </th>
                    </tr>
                    <tr class="active">
                        <th class="text-center" width="20%">Mês/ano</th>
                        <th class="text-center" width="20%">Minutos</th>
                        <th class="text-center" width="20%">Horas</th>
                        <th class="text-center">Minutos (diferença)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php //foreach ($rows as $row): ?>
                        <tr>
                            <td class="text-center"><?= $row->mes_ano ?></td>
                            <td class="text-center"><?= $row->total_minutos ?></td>
                            <td class="text-center"><?= $row->horas ?></td>
                            <td class="text-center"><?= $row->minutos ?></td>
                        </tr>
                    <?php //endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div> -->

        <table id="table_faltas" class="table datatable table-striped table-bordered table-condensed" cellspacing="0"
               width="100%">
            <thead>
            <tr class="success">
                <th colspan="11" class="text-center">
                    <h3><strong>Análise de Disponibilidade</strong></h3>
                </th>
            </tr>
            <tr class="active">
                <th class="text-center" style="vertical-align: middle;">Mês/ano</th>
                <th class="text-center" style="vertical-align: middle;">Dias úteis</th>
                <th class="text-center" style="vertical-align: middle;">Colaboradores potenciais</th>
                <th class="text-center" style="vertical-align: middle;">Colaboradores ativos</th>
                <th class="text-center" style="vertical-align: middle;">Total geral de faltas (dias)</th>
                <th class="text-center" style="vertical-align: middle;">Total dias cobertos (dias)</th>
                <th class="text-center" style="vertical-align: middle;">Total dias não cobertos dias + horas (dias)</th>
                <th class="text-center" style="vertical-align: middle;">Índice vacância sem cobertura (%)</th>
                <th class="text-center" style="vertical-align: middle;">Índice vacância com cobertura (%)</th>

                <th class="text-center">Glosas (dias)<br>Faltas em dias</th>
                <!-- <th class="text-center">Afastamentos<br>(acima de 3 dias)</th> -->
                <th class="text-center">Glosas (dias)<br>Faltas em minutos</th>
                <!-- <th class="text-center">Postos descobertos</th> -->
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="text-center"><?= $row->mes_ano ?></td>
                    <td class="text-center"><?= $row->dias_uteis ?></td>
                    <td class="text-center"><?= $row->qtde_alocados_potenciais ?></td>
                    <td class="text-center"><?= $row->usuarios_ativos ?></td>
                    <td class="text-center"><?= str_replace('.', ',', round($row->total_faltas, 2)) ?></td>
                    <td class="text-center"><?= str_replace('.', ',', round($row->qtde_dias_cobertos, 2)) ?></td>
                    <td class="text-center"><?= str_replace('.', ',', round($row->total_dias, 2)) ?></td>
                    <td class="text-center"><?= str_replace('.', ',', round($row->porcentagem_faltas, 2)) ?></td>
                    <td class="text-center"><?= str_replace('.', ',', round($row->indice_vacancia, 2)) ?></td>
                    <td class="text-center"><?= str_replace('.', ',', round($row->qtde_faltas, 2)) ?></td>
                    <!-- <td class="text-center"><?php //echo str_replace('.', ',', round($row->dias_ausentes, 2)); ?></td> -->
                    <td class="text-center"><?= str_replace('.', ',', round($row->horas_atraso, 2)) ?></td>
                    <!-- <td class="text-center"><?php //echo $row->posto_descoberto; ?></td> -->
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <table id="table_financas" class="table datatable table-striped table-bordered table-condensed" cellspacing="0"
               width="100%">
            <thead>
            <tr class="success">
                <th colspan="6" class="text-center">
                    <h3><strong>Análise Financeira</strong></h3>
                </th>
            </tr>
            <tr class="active">
                <th class="text-center">Mês/ano</th>
                <th class="text-center">Valor projetado (contrato) (R$)</th>
                <th class="text-center">Valor realizado (R$)</th>
                <th class="text-center">Valor glosa (real) (R$)</th>
                <th class="text-center">Perda de receita (%)</th>
                <th class="text-center">Receita líquida (%)</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="text-center"><?= $row->mes_ano ?></td>
                    <td class="text-center"><?= $row->valor_projetado ?></td>
                    <td class="text-center"><?= $row->valor_realizado ?></td>
                    <td class="text-center"><?= $row->valor_glosa ?></td>
                    <td class="text-center"><?= str_replace('.', ',', round($row->perda_receita, 4)) ?></td>
                    <td class="text-center"><?= str_replace('.', ',', round($frow->receita_liquida, 4)) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <table id="table_turnover" class="table datatable table-striped table-bordered table-condensed" cellspacing="0"
               width="100%">
            <thead>
            <tr class="success">
                <th colspan="6" class="text-center">
                    <h3><strong>Turnover</strong></h3>
                </th>
            </tr>
            <tr class="active">
                <th class="text-center">Admissões para reposição</th>
                <th class="text-center">Admissões aumento quadro</th>
                <th class="text-center">Desligamentos AME</th>
                <th class="text-center">Desligamentos colaboradores</th>
                <th class="text-center">Turnover mensal (%)</th>
                <th class="text-center">Índice de evasão (%)</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="text-center"><?= $row->turnover_reposicao ?></td>
                    <td class="text-center"><?= $row->turnover_aumento_quadro ?></td>
                    <td class="text-center"><?= $row->turnover_desligamento_empresa ?></td>
                    <td class="text-center"><?= $row->turnover_desligamento_colaborador ?></td>
                    <td class="text-center"><?= str_replace('.', ',', round($row->turnover_mensal, 2)) ?></td>
                    <td class="text-center"><?= str_replace('.', ',', round($frow->turnover_evasao, 2)) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>


        <pagebreak odd-header-name="myHeader"></pagebreak>

        <table id="chart">
            <tr>
                <td><?= $chart_valores ?><br></td>
            </tr>
            <tr>
                <td><?= $chart_vacancia ?><br></td>
            </tr>
            <!-- <tr>
                <td><?php //echo $chart_perdaReceita; ?><br></td>
            </tr>
            <tr>
                <td><?php //echo  $chart_glosaDias; ?><br></td>
            </tr>
            <tr>
                <td><?php //echo  $chart_glosaMinutos; ?><br></td>
            </tr> -->
        </table>


    </div>

</div>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
</body>
</html>