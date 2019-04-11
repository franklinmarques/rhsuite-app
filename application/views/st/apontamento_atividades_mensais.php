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
                        <?php if ($modo == 'normal'): ?>
                            <a id="pdf" class="btn btn-sm btn-danger"
                               href="<?= site_url('apontamento_relatorios/pdfAtividades_mensais/q?' . $query_string); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                            <!--<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>-->
                        <?php else: ?>
                            <a id="pdf" class="btn btn-sm btn-danger"
                               href="<?= site_url('apontamento_relatorios/pdfAtividades_consolidados/q?' . $query_string); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                    <?php if ($is_pdf == false): ?>
                        <h3 class="text-center" style="font-weight: bold;">CONTROLE DE ATIVIDADES
                            MENSAIS<?= $modo == 'consolidado' ? ' CONSOLIDADO' : '' ?>
                            DE <?= $ano ?></h3>
                    <?php else: ?>
                        <h4 class="text-center" style="font-weight: bold;">CONTROLE DE ATIVIDADES
                            MENSAIS<?= $modo == 'consolidado' ? ' CONSOLIDADO' : '' ?>
                            DE <?= $ano ?></h4>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
        </table>

    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <?php if ($modo == 'normal'): ?>
        <div class="row">
            <?php if ($departamento): ?>
                <div class="col col-md-4">
                    <label>Departamento:</label> <?= $departamento; ?>
                </div>
            <?php endif; ?>
            <?php if ($area): ?>
                <div class="col col-md-4">
                    <label>Área:</label> <?= $area; ?>
                </div>
            <?php endif; ?>
            <?php if ($setor): ?>
                <div class="col col-md-4">
                    <label>Setor:</label> <?= $setor; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <br>

    <div>
        <table id="rh" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>RH - Quantidade de
                            colaboradores</strong></h4></th>
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
                <td>Contratuais</td>
                <?php foreach ($total_colaboradores_contratados as $colaborador_contratado): ?>
                    <td class="text-center"><?= $colaborador_contratado ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_colaboradores_contratados) ?></td>
            </tr>
            <tr>
                <td>Ativos</td>
                <?php foreach ($total_colaboradores_ativos as $colaborador_ativo): ?>
                    <td class="text-center"><?= $colaborador_ativo ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($total_colaboradores_ativos) ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="visitas_periodicas" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Visitas periódicas</strong></h4></th>
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
                <td>Projetadas</td>
                <?php foreach ($visitas_projetadas as $visitas_projetadas_mes): ?>
                    <td class="text-center"><?= $visitas_projetadas_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($visitas_projetadas) ?></td>
            </tr>
            <tr>
                <td>Realizadas</td>
                <?php foreach ($visitas_realizadas as $visitas_realizadas_mes): ?>
                    <td class="text-center"><?= $visitas_realizadas_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($visitas_realizadas) ?></td>
            </tr>
            <tr>
                <td>Diferença (%)</td>
                <?php foreach ($visitas_porcentagem as $visitas_porcentagem_mes): ?>
                    <td class="text-center"><?= $visitas_porcentagem_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= str_replace('.', ',', round(array_sum($visitas_porcentagem) / 12, 2)) ?></td>
            </tr>
            <tr>
                <td>Quantidade horas</td>
                <?php foreach ($visitas_total_horas as $visitas_total_horas_mes): ?>
                    <td class="text-center"><?= $visitas_total_horas_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($visitas_total_horas) ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="disponibilidade" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Balanço financeiro</strong></h4></th>
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
                <td>Quantidade glosas</td>
                <?php foreach ($balanco_glosas as $balanco_glosa): ?>
                    <td class="text-center"><?= $balanco_glosa ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($balanco_glosas) ?></td>
            </tr>
            <tr>
                <td>Valor glosa</td>
                <?php foreach ($balanco_valor_glosa as $balanco_valor_glosa_mes): ?>
                    <td class="text-center"><?= str_replace('.', ',', $balanco_valor_glosa_mes) ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= str_replace('.', ',', array_sum($balanco_valor_glosa)) ?></td>
            </tr>
            <tr>
                <td>Valor projetado</td>
                <?php foreach ($balanco_valor_projetado as $balanco_valor_projetado_mes): ?>
                    <td class="text-center"><?= str_replace('.', ',', $balanco_valor_projetado_mes) ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= str_replace('.', ',', array_sum($balanco_valor_projetado)) ?></td>
            </tr>
            <tr>
                <td>Diferença (%)</td>
                <?php foreach ($balanco_porcentagem as $balanco_porcentagem_mes): ?>
                    <td class="text-center"><?= $balanco_porcentagem_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= str_replace('.', ',', round(array_sum($balanco_porcentagem) / 12, 2)) ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="turnover" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Turnover</strong></h4></th>
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
                <td>Admissões</td>
                <?php foreach ($turnover_admissoes as $turnover_admissoes_mes): ?>
                    <td class="text-center"><?= $turnover_admissoes_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($turnover_admissoes) ?></td>
            </tr>
            <tr>
                <td>Demissões</td>
                <?php foreach ($turnover_demissoes as $turnover_demissoes_mes): ?>
                    <td class="text-center"><?= $turnover_demissoes_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($turnover_demissoes) ?></td>
            </tr>
            <tr>
                <td>Desligamentos</td>
                <?php foreach ($turnover_desligamentos as $turnover_desligamentos_mes): ?>
                    <td class="text-center"><?= $turnover_desligamentos_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($turnover_desligamentos) ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="atendimentos" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Atendimentos</strong></h4></th>
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
                <td>Total</td>
                <?php foreach ($atendimentos_total_mes as $atendimentos_mes): ?>
                    <td class="text-center"><?= $atendimentos_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($atendimentos_total_mes) ?></td>
            </tr>
            <tr>
                <td>Média diária</td>
                <?php foreach ($atendimentos_media_diaria as $atendimentos_dia): ?>
                    <td class="text-center"><?= $atendimentos_dia ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($atendimentos_media_diaria) ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="pendencias" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Pendências</strong></h4></th>
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
                <td>Total informada</td>
                <?php foreach ($pendencias_total_informada as $pendencias_total_informada_mes): ?>
                    <td class="text-center"><?= $pendencias_total_informada_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($pendencias_total_informada) ?></td>
            </tr>
            <tr>
                <td>Aguardando</td>
                <?php foreach ($pendencias_aguardando_tratativa as $pendencias_aguardando_tratativa_mes): ?>
                    <td class="text-center"><?= $pendencias_aguardando_tratativa_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($pendencias_aguardando_tratativa) ?></td>
            </tr>
            <tr>
                <td>Parcialmente respondidas</td>
                <?php foreach ($pendencias_parcialmente_resolvidas as $pendencias_parcialmente_resolvidas_mes): ?>
                    <td class="text-center"><?= $pendencias_parcialmente_resolvidas_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($pendencias_parcialmente_resolvidas) ?></td>
            </tr>
            <tr>
                <td>Total resolvida</td>
                <?php foreach ($pendencias_resolvidas as $pendencias_resolvidas_mes): ?>
                    <td class="text-center"><?= $pendencias_resolvidas_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($pendencias_resolvidas) ?></td>
            </tr>
            <tr>
                <td>N&ordm; de atendimentos x N&ordm; de pendências</td>
                <?php foreach ($pendencias_resolvidas_atendimentos as $pendencias_resolvidas_atendimentos_mes): ?>
                    <td class="text-center"><?= $pendencias_resolvidas_atendimentos_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($pendencias_resolvidas_atendimentos) ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="monitoria" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Monitoria</strong></h4></th>
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
                <td>Média Equipe</td>
                <?php foreach ($monitoria_media_equipe as $monitoria_media_equipe_mes): ?>
                    <td class="text-center"><?= $monitoria_media_equipe_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($monitoria_media_equipe) ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="indicadores_operacionais" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Indicadores operacionais</strong>
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
                <td>TMA</td>
                <?php foreach ($indicadores_operacionais_tma as $tma): ?>
                    <td class="text-center"><?= $tma ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= $total_indicadores_operacionais->tma ?></td>
            </tr>
            <tr>
                <td>TME</td>
                <?php foreach ($indicadores_operacionais_tme as $tme): ?>
                    <td class="text-center"><?= $tme ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= $total_indicadores_operacionais->tme ?></td>
            </tr>
            <tr>
                <td>Ociosidade</td>
                <?php foreach ($indicadores_operacionais_ociosidade as $ociosidade): ?>
                    <td class="text-center"><?= $ociosidade ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= $total_indicadores_operacionais->ociosidade ?></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table id="pesquisa_satisfacao" class="table table-condensed table-bordered">
            <thead>
            <tr class="success">
                <th colspan="<?= $total_meses; ?>" class="text-center"><h4><strong>Pesquisa de satisfação</strong>
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
            <tr class="success">
                <td colspan="<?= $total_meses; ?>" class="text-center">Avaliação do atendimento</td>
            </tr>
            <tr>
                <td>N&ordm; respostas</td>
                <?php foreach ($avaliacoes_atendimento as $atendimentos): ?>
                    <td class="text-center"><?= $atendimentos ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($avaliacoes_atendimento) ?></td>
            </tr>
            <tr>
                <td>Ótimo</td>
                <?php foreach ($avaliacoes_atendimento_otimos as $otimos): ?>
                    <td class="text-center"><?= $otimos ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($avaliacoes_atendimento_otimos) ?></td>
            </tr>
            <tr>
                <td>Bom</td>
                <?php foreach ($avaliacoes_atendimento_bons as $bons): ?>
                    <td class="text-center"><?= $bons ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($avaliacoes_atendimento_bons) ?></td>
            </tr>
            <tr>
                <td>Regular</td>
                <?php foreach ($avaliacoes_atendimento_regulares as $regulares): ?>
                    <td class="text-center"><?= $regulares ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($avaliacoes_atendimento_regulares) ?></td>
            </tr>
            <tr>
                <td>Ruim</td>
                <?php foreach ($avaliacoes_atendimento_ruins as $ruins): ?>
                    <td class="text-center"><?= $ruins ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($avaliacoes_atendimento_ruins) ?></td>
            </tr>
            <tr class="success">
                <td colspan="<?= $total_meses; ?>" class="text-center">Solicitação atendida</td>
            </tr>
            <tr>
                <td>N&ordm; respostas</td>
                <?php foreach ($solicitacoes as $solicitacoes_mes): ?>
                    <td class="text-center"><?= $solicitacoes_mes ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($solicitacoes) ?></td>
            </tr>
            <tr>
                <td>Sim</td>
                <?php foreach ($solicitacoes_atendidas as $atendidas): ?>
                    <td class="text-center"><?= $atendidas ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($solicitacoes_atendidas) ?></td>
            </tr>
            <tr>
                <td>Não</td>
                <?php foreach ($solicitacoes_nao_atendidas as $nao_atendidas): ?>
                    <td class="text-center"><?= $nao_atendidas ?></td>
                <?php endforeach; ?>
                <td class="text-center"><?= array_sum($solicitacoes_nao_atendidas) ?></td>
            </tr>
            </tbody>
        </table>
        <?php if (count(array_filter($observacoes)) > 0): ?>
            <br>
            <table id="observacoes" class="table table-condensed table-bordered">
                <thead>
                <tr class="success">
                    <th colspan="2" class="text-center">
                        <h4><strong>Observações</strong></h4>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($meses_completo as $k => $mes): ?>
                    <?php if (strlen($observacoes[$k]) > 0): ?>
                        <tr>
                            <td class="active" style="font-weight: bolder;"><?= $mes ?></td>
                            <td style="width: 85%;"><?= $observacoes[$k] ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>


    </div>

</div>
</body>
</html>