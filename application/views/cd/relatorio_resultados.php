<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de apontamento</title>
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
                    <img src="<?= base_url('imagens/usuarios/LOGOAME-TP.png') ?>" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="width: 100%; vertical-align: top;">
                    <p>
                        <span style="font-weight: bold;">Associação dos Amigos Metroviários dos Excepcionais - AME</span><br>
                        <span style="font-size: small;">Rua Serra de Botucatu, 1.197 - São Paulo, Brasil ─ CEP 03317-001 ─ Tel.: 2360-8900</span><br>
                        <span style="font-size: small;">Site: www.ame-sp.org.br ─ e-mail: ame@ame-sp.org.br</span>
                    </p>
                </td>
                <?php if ($is_pdf == false): ?>
                    <td nowrap>
                        <?php if ($modo == 'normal'): ?>
                            <a id="pdf" class="btn btn-sm btn-danger"
                               href="<?= site_url('cd/relatorios/pdfResultados/q?' . $query_string); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                            <!--<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>-->
                        <?php elseif ($modo == 'diretorias'): ?>
                            <a id="pdf" class="btn btn-sm btn-danger"
                               href="<?= site_url('cd/relatorios/pdfResultadosDiretorias/q?' . $query_string); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                        <?php else: ?>
                            <a id="pdf" class="btn btn-sm btn-danger"
                               href="<?= site_url('cd/relatorios/pdfResultadosConsolidados/q?' . $query_string); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                    <?php if ($is_pdf == false): ?>
                        <h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE ACOMPANHAMENTO
                            MENSAL <?= $modo == 'consolidado' ? ' CONSOLIDADO' : ($modo == 'diretorias' ? ' DE DIRETORIA' : 'INDIVIDUAL') ?>
                            DE <?= $ano ?></h3>
                    <?php else: ?>
                        <h4 class="text-center" style="font-weight: bold;">RELATÓRIO DE ACOMPANHAMENTO
                            MENSAL <?= $modo == 'consolidado' ? ' CONSOLIDADO' : (($modo == 'diretorias' ? ' DE DIRETORIA' : 'INDIVIDUAL')) ?>
                            DE <?= $ano ?></h4>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
        </table>

    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <?php if ($modo == 'normal' or $modo == 'diretorias'): ?>
        <div class="row">
            <?php if ($departamento): ?>
                <div class="col col-md-4">
                    <label>Departamento:</label> <?= $departamento; ?><br>
<!--                    <label>Total cuidadores:</label> --><?//= $total_alocados; ?>
                </div>
            <?php endif; ?>
            <?php if ($diretoria): ?>
                <div class="col col-md-4">
                    <label>Diretoria:</label> <?= $diretoria; ?><br>
<!--                    <label>Total alunos:</label> --><?//= $total_matriculados; ?>
                </div>
            <?php endif; ?>
            <?php if ($supervisor): ?>
                <?php if (is_array($supervisor)): ?>
                    <div class="col col-md-4">
                        <?php foreach ($supervisor as $k => $nome): ?>
                            <label<?= $k > 0 ? ' style="visibility: hidden;"' : '' ?>>Supervisores(as):</label> <?= $nome; ?>
                            <br>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="col col-md-4">
                        <label>Supervisor(a):</label> <?= $supervisor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <br>

    <div>
        <table id="recursos_alocados" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Recursos alocados</strong></h4></th>
            </tr>
            </thead>
            <tbody>
            <tr class="active" style="font-weight: bolder;">
                <td>Meses</td>
                <?php foreach ($meses as $mes): ?>
                    <td class="text-center"><?= $mes ?></td>
                <?php endforeach; ?>
                <td class="text-center">Totalização</td>
            </tr>
            <tr class="success">
                <td colspan="<?= $total_meses; ?>" class="text-center">Quantidade de cuidadores</td>
            </tr>
            <tr>
                <td>Contratados</td>
                <?php foreach ($total_cuidadores as $cuidador_contratado): ?>
                    <td class="text-center"><?= $cuidador_contratado ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_cuidadores) ?></td>
            </tr>
            <tr>
                <td>Cobrados</td>
                <?php foreach ($total_cuidadores_cobrados as $cuidador_cobrado): ?>
                    <td class="text-center"><?= $cuidador_cobrado ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_cuidadores_cobrados) ?></td>
            </tr>
            <tr>
                <td>Ativos</td>
                <?php foreach ($total_cuidadores_ativos as $cuidador_ativo): ?>
                    <td class="text-center"><?= $cuidador_ativo ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_cuidadores_ativos) ?></td>
            </tr>
            <tr>
                <td>Afastados</td>
                <?php foreach ($total_cuidadores_afastados as $cuidador_afastado): ?>
                    <td class="text-center"><?= $cuidador_afastado ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_cuidadores_afastados) ?></td>
            </tr>


            <tr class="success">
                <td colspan="<?= $total_meses; ?>" class="text-center">Quantidade de supervisores</td>
            </tr>
            <tr>
                <td>Contratados</td>
                <?php foreach ($total_supervisores as $supervisor_contratado): ?>
                    <td class="text-center"><?= $supervisor_contratado ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_supervisores) ?></td>
            </tr>
            <tr>
                <td>Cobrados</td>
                <?php foreach ($total_supervisores_cobrados as $supervisor_cobrado): ?>
                    <td class="text-center"><?= $supervisor_cobrado ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_supervisores_cobrados) ?></td>
            </tr>
            <tr>
                <td>Ativos</td>
                <?php foreach ($total_supervisores_ativos as $supervisor_ativo): ?>
                    <td class="text-center"><?= $supervisor_ativo ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_supervisores_ativos) ?></td>
            </tr>
            <tr>
                <td>Afastados</td>
                <?php foreach ($total_supervisores_afastados as $supervisor_afastado): ?>
                    <td class="text-center"><?= $supervisor_afastado ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_supervisores_afastados) ?></td>
            </tr>


            <tr class="success">
                <td colspan="<?= $total_meses; ?>" class="text-center">Quantidade de escolas e alunos</td>
            </tr>
            <tr>
                <td>Escolas</td>
                <?php foreach ($total_escolas as $escola): ?>
                    <td class="text-center"><?= $escola ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_escolas) ?></td>
            </tr>
            <tr>
                <td>Alunos</td>
                <?php foreach ($total_alunos as $aluno): ?>
                    <td class="text-center"><?= $aluno ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_alunos) ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="faltas" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Faltas</strong></h4></th>
            </tr>
            </thead>
            <tbody>
            <tr class="active" style="font-weight: bolder;">
                <td>Meses</td>
                <?php foreach ($meses as $mes): ?>
                    <td class="text-center"><?= $mes ?></td>
                <?php endforeach; ?>
                <td class="text-center">Totalização</td>
            </tr>
            <tr>
                <td>Faltas com atestado</td>
                <?php foreach ($total_faltas_justificadas as $falta_justificada): ?>
                    <td class="text-center"><?= $falta_justificada ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_faltas_justificadas) ?></td>
            </tr>
            <tr>
                <td>Faltas sem atestado</td>
                <?php foreach ($total_faltas as $falta): ?>
                    <td class="text-center"><?= $falta ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_faltas) ?></td>
            </tr>
            <tr>
                <td>Índice de disponibilidade x Faltas c/ atestado</td>
                <?php foreach ($total_faltas_justificadas as $falta_justificada): ?>
                    <td class="text-center"><?= $falta_justificada ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_faltas_justificadas) ?></td>
            </tr>
            <tr>
                <td>Índice de disponibilidade x Faltas s/ atestado</td>
                <?php foreach ($total_faltas as $falta): ?>
                    <td class="text-center"><?= $falta ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_faltas) ?></td>
            </tr>
            <tr>
                <td>Índice de disponibilidade x Faltas totais</td>
                <?php foreach ($total_faltas_justificadas as $falta_justificada): ?>
                    <td class="text-center"><?= $falta_justificada ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_faltas_justificadas) ?></td>
            </tr>
            </tbody>
        </table>
        <br><table id="intercorrencias" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Intercorrências</strong></h4></th>
            </tr>
            </thead>
            <tbody>
            <tr class="active" style="font-weight: bolder;">
                <td>Meses</td>
                <?php foreach ($meses as $mes): ?>
                    <td class="text-center"><?= $mes ?></td>
                <?php endforeach; ?>
                <td class="text-center">Totalização</td>
            </tr>
            <tr>
                <td>Intercorrências Diretoria Ensino</td>
                <?php foreach ($intercorrencias_diretoria as $intercorrencia_diretoria): ?>
                    <td class="text-center"><?= $intercorrencia_diretoria ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($intercorrencias_diretoria) ?></td>
            </tr>
            <tr>
                <td>Intercorrências cuidador</td>
                <?php foreach ($intercorrencias_cuidador as $intercorrencia_cuidador): ?>
                    <td class="text-center"><?= $intercorrencia_cuidador ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($intercorrencias_cuidador) ?></td>
            </tr>
            <tr>
                <td>Intercorrências alunos</td>
                <?php foreach ($intercorrencias_alunos as $intercorrencia_alunos): ?>
                    <td class="text-center"><?= $intercorrencia_alunos ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($intercorrencias_alunos) ?></td>
            </tr>
            <tr>
                <td>Acidentes de trabalho</td>
                <?php foreach ($acidentes_trabalho as $acidente_trabalho): ?>
                    <td class="text-center"><?= $acidente_trabalho ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($acidentes_trabalho) ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="movimentacoes" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Movimentações de pessoal</strong>
                    </h4></th>
            </tr>
            </thead>
            <tbody>
            <tr class="active" style="font-weight: bolder;">
                <td>Meses</td>
                <?php foreach ($meses as $mes): ?>
                    <td class="text-center"><?= $mes ?></td>
                <?php endforeach; ?>
                <td class="text-center">Totalização</td>
            </tr>
            <tr>
                <td>Contratações por reposição de quadro</td>
                <?php foreach ($turnover_substituicao as $substituicao): ?>
                    <td class="text-center"><?= $substituicao ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($turnover_substituicao) ?></td>
            </tr>
            <tr>
                <td>Contratações por aumento de quadro</td>
                <?php foreach ($turnover_aumento_quadro as $aumento_quadro): ?>
                    <td class="text-center"><?= $aumento_quadro ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($turnover_aumento_quadro) ?></td>
            </tr>
            <tr>
                <td>Desligamento pela AME</td>
                <?php foreach ($turnover_desligamento_empresa as $desligamento_empresa): ?>
                    <td class="text-center"><?= $desligamento_empresa ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($turnover_desligamento_empresa) ?></td>
            </tr>
            <tr>
                <td>Desligamento solicitado por colaboradores</td>
                <?php foreach ($turnover_desligamento_solicitacao as $desligamento_solicitacao): ?>
                    <td class="text-center"><?= $desligamento_solicitacao ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($turnover_desligamento_solicitacao) ?></td>
            </tr>
            <tr>
                <td>Turnover (saídas + entradas) / Quadra ativa</td>
                <?php foreach ($turnover_desligamento_solicitacao as $desligamento_solicitacao): ?>
                    <td class="text-center"><?= $desligamento_solicitacao ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($turnover_desligamento_solicitacao) ?></td>
            </tr>
            <tr>
                <td>Índice evasão (desligamentos solicitados/quadros)</td>
                <?php foreach ($turnover_desligamento_solicitacao as $desligamento_solicitacao): ?>
                    <td class="text-center"><?= $desligamento_solicitacao ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($turnover_desligamento_solicitacao) ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="faturamento" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Faturamento</strong></h4></th>
            </tr>
            </thead>
            <tbody>
            <tr class="active" style="font-weight: bolder;">
                <td>Meses</td>
                <?php foreach ($meses as $mes): ?>
                    <td class="text-center"><?= $mes ?></td>
                <?php endforeach; ?>
                <td class="text-center">Totalização
                    (<?= round(array_sum($faturamentos_realizados) * 100 / max(array_sum($faturamentos_projetados), 1), 2); ?>
                    %)
                </td>
            </tr>
            <tr>
                <td>Faturamento projetado (R$)</td>
                <?php foreach ($faturamentos_projetados as $faturamento_projetado): ?>
                    <td class="text-center"><?= strlen($faturamento_projetado) > 0 ? number_format($faturamento_projetado, 2, ',', '.') : '' ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= number_format(array_sum($faturamentos_projetados), 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Faturamento realizado (R$)</td>
                <?php foreach ($faturamentos_realizados as $faturamento_realizado): ?>
                    <td class="text-center"><?= strlen($faturamento_realizado) > 0 ? number_format($faturamento_realizado, 2, ',', '.') : '' ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= number_format(array_sum($faturamentos_realizados), 2, ',', '.') ?></td>
            </tr>
            <?php if ($dias_letivos): ?>
                <tr>
                    <td>Quantidade de dias úteis trabalhados</td>
                    <?php foreach ($dias_letivos as $dia_letivo): ?>
                        <td class="text-center"><?= $dia_letivo ?></td>
                    <?php endforeach; ?>
                    <td class="text-center"><?= array_sum($dias_letivos) ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>


    </div>

</div>
</body>
</html>