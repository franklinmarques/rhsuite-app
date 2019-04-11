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
                           href="<?= site_url('ei/relatorios/pdfMedicao/' . $query_string); ?>"
                           title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                        <!--<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>-->
                    </td>
                <?php endif; ?>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                    <?php if ($is_pdf == false): ?>
                        <h3 class="text-center" style="font-weight: bold;">RELATÓRIO CONSOLIDADO - EDUCAÇÃO
                            INCLUSIVA<br><?= mb_strtoupper($mes_nome) ?> DE <?= $ano ?></h3>
                    <?php else: ?>
                        <h4 class="text-center" style="font-weight: bold;">ELATÓRIO CONSOLIDADO - EDUCAÇÃO
                            INCLUSIVA<br><?= mb_strtoupper($mes_nome) ?> DE <?= $ano ?></h4>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
        </table>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <div>
        <table id="quantitativo_recursos_humanos" class="table table-bordered table-condensed">
            <thead>
            <tr class="success">
                <th colspan="4" class="text-center"><h3><strong>Quantitativo de Recursos Humanos</strong></h3></th>
            </tr>
            <tr class="active">
                <th>Colaborador(a)</th>
                <th>Qtde. pessoas</th>
                <th>Qtde horas projetadas</th>
                <th>Qtde horas realizadas</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Quantidade de Escolas</td>
                <th><input name="total_pessoas[]" type="text" class="form-control text-center total"
                           value="<?= $alocacao->total_escolas; ?>"></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>Quantidade de Alunos</td>
                <th><input name="total_pessoas[]" type="text" class="form-control text-center total"
                           value="<?= $alocacao->total_alunos; ?>"></th>
                <th></th>
                <th></th>
            </tr>
            <?php foreach ($funcoes as $funcao): ?>
                <tr>
                    <td><?= $funcao->nome; ?></td>
                    <th><input name="total_pessoas[]" value="<?= $funcao->total_pessoas; ?>" type="text"
                               class="form-control text-center total"></th>
                    <th><input name="total_horas_projetadas[]" value="<?= $funcao->total_horas_projetadas; ?>"
                               type="text" class="form-control text-center horas"></th>
                    <th><input name="total_horas_realizadas[]" value="<?= $funcao->total_horas_realizadas; ?>"
                               type="text" class="form-control text-center horas"></th>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <pagebreak odd-header-name="myHeader"></pagebreak>
        <table id="balanco_financeiro" class="table table-bordered table-condensed">
            <thead>
            <tr class="success">
                <th colspan="6" class="text-center"><h3><strong>Balanço Financeiro</strong></h3></th>
            </tr>
            <tr class="active">
                <th>Colaborador(a)</th>
                <th>Receita Projetada</th>
                <th>Receita Efetuada</th>
                <th>Pagamentos Efetuados</th>
                <th>Resultado (R$)</th>
                <th>Resultado (%)</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($funcoes as $funcao): ?>
                <tr>
                    <td><?= $funcao->nome; ?></td>
                    <th><input name="receita_projetada[]" value="<?= $funcao->receita_projetada; ?>" type="text"
                               class="form-control text-center valor"></th>
                    <th><input name="receita_efetuada[]" value="<?= $funcao->receita_efetuada; ?>" type="text"
                               class="form-control text-center valor"></th>
                    <th><input name="pagamentos_efetuados[]" value="<?= $funcao->pagamentos_efetuados; ?>" type="text"
                               class="form-control text-center valor"></th>
                    <th><input name="resultado[]" type="text" value="<?= $funcao->resultado; ?>"
                               class="form-control text-center valor"></th>
                    <th><input name="resultado_percentual[]" value="<?= $funcao->resultado_percentual; ?>" type="text"
                               class="form-control text-center porcentagem"></th>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

<script>
    var query_string = '<?= $query_string ?>';

    $('.horas').mask('###00:00', {'reverse': true});
    $('.valor').mask('##.###.##0,00', {'reverse': true});
    $('.porcentagem').mask('000, 0', {'reverse': true});
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
        var mes_ano = moment('<?= $ano; ?>-<?= $mes; ?>-01').subtract(1, 'month');
        var search = 'mes=' + mes_ano.format('MM') + '&ano=' + mes_ano.format('YYYY');
        window.location.href = '<?= site_url('ei/relatorios/medicao'); ?>/q?' + search;
    }

    function mes_seguinte() {
        var mes_ano = moment('<?= $ano; ?>-<?= $mes; ?>-01').add(1, 'month');
        var search = 'mes=' + mes_ano.format('MM') + '&ano=' + mes_ano.format('YYYY');
        window.location.href = '<?= site_url('ei/relatorios/medicao'); ?>/q?' + search;
    }

    function fechar_mes() {
        $('#fechar_mes').prop('disabled', true);

        $.ajax({
            url: "<?php echo site_url('apontamento_totalizacao/fecharMes') ?>",
            type: "POST",
            data: query_string.replace('q?', ''),
            dataType: "JSON",
            success: function (data) {
                if (data.status === true) {
                    alert('Mês fechado com sucesso!');
                } else {
                    alert(data.status);
                }

                $('#fechar_mes').prop('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Não foi possível fechar o mês');
                $('#fechar_mes').prop('disabled', false);
            }
        });
    }
</script>
</body>
</html>