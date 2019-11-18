<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Controle de Frequência Individual</title>
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
                        <button onclick="mes_anterior()" class="btn btn-sm btn-info" id="mes_anterior"
                                title="Mês anterior"><i class="glyphicon glyphicon-arrow-left"></i> Mês anterior
                        </button>
                        <button onclick="mes_seguinte()" class="btn btn-sm btn-info" id="mes_seguinte"
                                title="Mês seguinte"><i class="glyphicon glyphicon-arrow-right"></i> Mês seguinte
                        </button>
                        <button onclick="fechar_mes()" class="btn btn-sm btn-success" id="fechar_mes"
                                title="Fechar mês"><i class="glyphicon glyphicon-saved"></i> Fechar mês
                        </button>
                        <button onclick="salvar()" class="btn btn-sm btn-success" id="salvar" title="Salvar">Salvar
                        </button>
                        <a id="pdf" class="btn btn-sm btn-info"
                           href="<?= site_url('ei/relatorios/pdfMedicao/q?' . $query_string); ?>"
                           title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                        <!--<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>-->
                    </td>
                <?php endif; ?>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                    <?php if ($is_pdf == false): ?>
                        <h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE MEDIÇÃO MENSAL - EDUCAÇÃO
                            INCLUSIVA<br><?= mb_strtoupper($mes_nome) ?> DE <?= $ano ?></h3>
                    <?php else: ?>
                        <h2 class="text-center" style="font-weight: bold;">RELATÓRIO DE MEDIÇÃO MENSAL - EDUCAÇÃO
                            INCLUSIVA<br><?= mb_strtoupper($mes_nome) ?> DE <?= $ano ?></h2>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
        </table>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <div>
        <?php if ($is_pdf == false): ?>
        <form action="#" id="form" class="form-horizontal" autocomplete="off">
            <?php endif; ?>

            <table id="quantitativo_recursos_humanos" class="table medicao table-bordered table-condensed">
                <thead>
                <tr class="success">
                    <th colspan="4" class="text-center"><h3><strong>Quantitativo de Recursos Humanos</strong></h3></th>
                </tr>
                <tr class="active">
                    <th>Colaborador(a)</th>
                    <th class="text-center">Quantidades</th>
                    <th class="text-center">Qtde horas projetadas</th>
                    <th class="text-center">Qtde horas realizadas</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Quantidade de Escolas</td>
                    <?php if ($is_pdf): ?>
                        <td class="text-center"><?= $alocacao->total_escolas; ?></td>
                    <?php else: ?>
                        <th><input name="total_escolas[]" type="text" class="form-control text-center total"
                                   value="<?= $alocacao->total_escolas; ?>" autocomplete="off"></th>
                    <?php endif; ?>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Quantidade de Alunos</td>
                    <?php if ($is_pdf): ?>
                        <td class="text-center"><?= $alocacao->total_alunos; ?></td>
                    <?php else: ?>
                        <th><input name="total_alunos[]" type="text" class="form-control text-center total"
                                   value="<?= $alocacao->total_alunos; ?>" autocomplete="off"></th>
                    <?php endif; ?>
                    <td></td>
                    <td></td>
                </tr>
                <?php foreach ($funcoes as $funcao): ?>
                    <tr>
                        <td><?= $funcao->nome; ?></td>
                        <?php if ($is_pdf): ?>
                            <td class="text-center"><?= $funcao->total_pessoas; ?></td>
                            <td class="text-center"><?= secToTime($funcao->total_secs_projetados, false); ?></td>
                            <td class="text-center"><?= $funcao->total_horas_mes ? $funcao->total_horas_mes : secToTime($funcao->total_secs_realizados, false); ?></td>
                        <?php else: ?>
                            <th><input name="total_cuidadores[]" value="<?= $funcao->total_pessoas; ?>" type="text"
                                       class="form-control text-center total" autocomplete="off"></th>
                            <th><input name="total_horas_projetadas[]"
                                       value="<?= secToTime($funcao->total_secs_projetados, false); ?>"
                                       type="text" class="form-control text-center horas" autocomplete="off"></th>
                            <th><input name="total_horas_realizadas[]"
                                       value="<?= $funcao->total_horas_mes ? $funcao->total_horas_mes : secToTime($funcao->total_secs_realizados, false); ?>"
                                       type="text" class="form-control text-center horas" autocomplete="off"></th>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <pagebreak odd-header-name="myHeader"></pagebreak>
            <table id="balanco_financeiro" class="table medicao table-bordered table-condensed">
                <thead>
                <tr class="success">
                    <th colspan="6" class="text-center"><h3><strong>Balanço Financeiro</strong></h3></th>
                </tr>
                <tr class="active">
                    <th>Colaborador(a)</th>
                    <th class="text-center">Receita Projetada</th>
                    <th class="text-center">Receita Efetuada</th>
                    <th class="text-center">Pagamentos Efetuados</th>
                    <th class="text-center">Resultado (R$)</th>
                    <th class="text-center">Resultado (%)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($funcoes as $funcao): ?>
                    <tr>
                        <td><?= $funcao->nome; ?></td>
                        <?php if ($is_pdf): ?>
                            <td class="text-center"><?= $funcao->receita_projetada; ?></td>
                            <td class="text-center"><?= $funcao->receita_efetuada; ?></td>
                            <td class="text-center"><?= $funcao->pagamentos_efetuados; ?></td>
                            <td class="text-center"><?= $funcao->resultado; ?></td>
                            <td class="text-center"><?= $funcao->resultado_percentual; ?></td>
                        <?php else: ?>
                            <th><input name="receita_projetada[]"
                                       value="<?= number_format($funcao->valor_hora * ($funcao->total_secs_projetados / 3600), 2, ',', '.'); ?>"
                                       type="text"
                                       class="form-control text-center valor" autocomplete="off"></th>
                            <th><input name="receita_efetuada[]" value="<?= $funcao->receita_efetuada; ?>" type="text"
                                       class="form-control text-center valor" autocomplete="off"></th>
                            <th><input name="pagamentos_efetuados[]"
                                       value="<?= number_format($funcao->pagamentos_efetuados, 2, ',', '.'); ?>"
                                       type="text"
                                       class="form-control text-center valor" autocomplete="off"></th>
                            <th><input name="resultado[]" type="text" value="<?= $funcao->resultado; ?>"
                                       class="form-control text-center valor" autocomplete="off"></th>
                            <th><input name="resultado_percentual[]"
                                       value="<?= number_format($funcao->pagamentos_efetuados / max($funcao->valor_hora * ($funcao->total_secs_projetados / 3600), 1) * 100, 1, ',', ''); ?>"
                                       type="text"
                                       class="form-control text-center porcentagem" autocomplete="off"></th>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>


            <?php if ($is_pdf == false): ?>
        </form>
    <?php endif; ?>

    </div>


</div>

<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

<script>
    var query_string = '<?= $query_string ?>';

    $('.horas').mask('###00:00', {'reverse': true});
    $('.valor').mask('##.###.##0,00', {'reverse': true});
    $('.porcentagem').mask('000,0', {'reverse': true});
    $('.total').mask('00000000000');

    $('[name="calculo_totalizacao"]').on('change', function () {
        var queryStr = query_string;
        var arrQuery = {};
        $.each(queryStr.split('&'), function (i, v) {
            var q = v.split('=');
            arrQuery[q[0]] = q[1];
        });
        if (this.value === '2') {
            $('.totalizacao_1').hide();
            $('.totalizacao_2').show();
            arrQuery['calculo_totalizacao'] = '2';
        } else {
            $('.totalizacao_1').show();
            $('.totalizacao_2').hide();
            arrQuery['calculo_totalizacao'] = '1';
        }

        var search = [];
        $.each(arrQuery, function (i, v) {
            search.push(i + '=' + v);
        });
        query_string = search.join('&');
        $('#pdf').prop('href', "<?= site_url('apontamento_relatorios/pdf'); ?>/" + query_string);
    });

    function mes_anterior() {
        var semestre = parseInt('<?= $semestre ?>');
        var mes_ano = moment('<?= $ano; ?>-<?= $mes; ?>-01');

        if (mes_ano.format('MM') === '07' && semestre === 2) {
            semestre = 1;
        } else {
            mes_ano = mes_ano.subtract(1, 'month');
            semestre = Math.ceil(mes_ano.format('Q') / 2);
        }

        var search = 'mes=' + mes_ano.format('MM') + '&ano=' + mes_ano.format('YYYY') + '&semestre=' + semestre;
        window.location.href = '<?= site_url('ei/relatorios/medicao'); ?>/q?' + search;
    }

    function mes_seguinte() {
        var semestre = parseInt('<?= $semestre ?>');
        var mes_ano = moment('<?= $ano; ?>-<?= $mes; ?>-01');

        if (mes_ano.format('MM') === '06') {
            mes_ano = mes_ano.add(1, 'month');
        } else if (mes_ano.format('MM') === '07' && semestre === 1) {
            semestre = 2;
        } else {
            mes_ano = mes_ano.add(1, 'month');
            semestre = Math.ceil(mes_ano.format('Q') / 2);
        }

        var search = 'mes=' + mes_ano.format('MM') + '&ano=' + mes_ano.format('YYYY') + '&semestre=' + semestre;
        window.location.href = '<?= site_url('ei/relatorios/medicao'); ?>/q?' + search;
    }

    function fechar_mes() {
        $.ajax({
            'url': '<?php echo site_url('ei/relatorios/ajaxSaveMedicao') ?>',
            'type': 'POST',
            'data': $('form').serialize(),
            'dataType': 'json',
            'beforeSend': function () {
                $('#fechar_mes').prop('disabled', true);
            },
            'success': function (json) {
                if (json.status === true) {
                    alert('Mês fechado com sucesso!');
                } else {
                    alert(json.status);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Não foi possível fechar o mês');
            },
            'complete': function () {
                $('#fechar_mes').prop('disabled', false);
            }
        });
    }
</script>
</body>
</html>
