<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CORPORATE RH - LMS - Relatório de Fechamento Mensal</title>
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
					<img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
						 style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
				</th>
				<th style="width: 100%; vertical-align: top;">
					<p>
						<img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
							 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
					</p>
				</th>
				<?php if ($is_pdf == false): ?>
					<td nowrap>
						<a id="pdf" class="btn btn-sm btn-info"
						   href="<?= site_url('apontamento_relatorios/imprimirFechamentoMensal/q?' . $query_string); ?>"
						   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
					</td>
				<?php endif; ?>
			</tr>
			<tr style='border-top: 5px solid #ddd;'>
				<th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
					<?php if ($is_pdf == false): ?>
						<h3 class="text-center" style="font-weight: bold;">
							RELATÓRIO DE FECHAMENTO MENSAL<br>
							DIGITAÇÃO DO CARTÃO PASSE ESCOLAR<br>
							<?= $contrato ? 'Contrato: ' . $contrato : ''; ?><br>
							Período: <?= $data_inicio; ?> a <?= $data_termino; ?>
						</h3>
					<?php else: ?>
						<h4 class="text-center" style="font-weight: bold;">
							RELATÓRIO DE FECHAMENTO MENSAL<br>
							DIGITAÇÃO DO CARTÃO PASSE ESCOLAR<br>
							<?= $contrato ? 'Contrato: ' . $contrato : ''; ?><br>
							Período: <?= $data_inicio; ?> a <?= $data_termino; ?>
						</h4>
					<?php endif; ?>
				</th>
			</tr>
			</thead>
		</table>
	</htmlpageheader>
	<sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

	<div>
		<table id="fechamento_mensal" class="table table-bordered table-condensed">
			<thead>
			<tr class="active">
				<th rowspan="2" style="vertical-align: middle;">Colaborador(a)</th>
				<th colspan="2" class="text-center" style="vertical-align: middle;">Quantidade</th>
			</tr>
			<tr>
				<th class="text-center">Requisições</th>
				<th class="text-center">Processamentos</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($rows as $row): ?>
				<tr>
					<td><?= $row->nome; ?></td>
					<td class="text-center"><?= $row->qtde_req; ?></td>
					<td class="text-center"><?= $row->qtde_rev; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
			<tr>
				<th>Subtotal</th>
				<th class="text-center"><?= $subtotal_req; ?></th>
				<th class="text-center"><?= $subtotal_rev; ?></th>
			</tr>
			<tr>
				<th>Total geral</th>
				<th colspan="2" class="text-center"><?= $total; ?></th>
			</tr>
			<tr>
				<th>Valor unitário (R$)</th>
				<th colspan="2" class="text-center"><?= $valor_unitario; ?></th>
			</tr>
			<tr>
				<th>Valor faturamento (R$)</th>
				<th colspan="2" class="text-center"><?= $valor_faturamento; ?></th>
			</tr>
			</tfoot>
		</table>
	</div>

</div>


<script>
    var query_string = '<?= $query_string ?>';

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
