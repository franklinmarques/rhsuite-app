<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?= base_url("assets/images/favipn.ico"); ?>">
	<title>CORPORATE RH - LMS - Solicitação de Pagamento</title>
	<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

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
						<button onclick="validar_pagamento();" class="btn btn-sm btn-success" id="validar"
								title="Validar"><i class="glyphicon glyphicon-saved"></i> Validar
						</button>
						<a id="pdf" class="btn btn-sm btn-info"
						   href="<?= site_url('icom/sessoes/imprimirSolicitacaoPagamento/q?' . $query_string); ?>"
						   title="Exportar PDF"><i class="glyphicon glyphicon-print"></i> Imprimir</a>
						<button class="btn btn-sm btn-default" onclick="javascript:window.close()"><i
								class="glyphicon glyphicon-remove"></i> Fechar
						</button>
					</td>
				<?php endif; ?>
			</tr>
			<tr style='border-top: 5px solid #ddd; border-bottom: 5px solid #ddd;'>
				<th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
					<h3 class="text-center" style="font-weight: bold;">SOLICITAÇÃO DE PAGAMENTO - INTÉRPRETE DE
						LIBRAS</h3>
				</th>
			</tr>
			</thead>
		</table>
		<table class="table table-condensed" width="100%">
			<tr style='border-bottom: 1px solid #ddd;'>
				<td>
					<span style="font-weight: bold;">Solicitante: </span><?= $profissional->nome_cliente ?>
				</td>
				<td>
					<span style="font-weight: bold;">CC: </span><?= $profissional->centro_custo ?>
				</td>
				<td>
					<span
						style="font-weight: bold;">Departamento: </span><?= $profissional->nome_depto_prestador_servico ?>
				</td>
			</tr>
			<tr style='border-bottom: 1px solid #ddd;'>
				<td>
					<span
						style="font-weight: bold;">Nome do intérprete: </span><?= $profissional->nome_profissional_alocado ?>
				</td>
				<td>
					<span style="font-weight: bold;">CNPJ: </span><?= $profissional->cnpj ?>
				</td>
				<td>
					<?php if ($is_pdf == false): ?>
						<form class="form-inline">
							<div class="form-group">
								<label for="nota_fiscal">N&ordm; Nota Fiscal:</label>
								<input type="text" class="form-control" id="nota_fiscal"
									   value="<?= $profissional->nota_fiscal ?>">
							</div>
						</form>
					<?php else: ?>
						<span
							style="font-weight: bold;">N&ordm; Nota Fiscal: </span><?= $profissional->nota_fiscal ?>
					<?php endif; ?>
				</td>
			</tr>
			<tr style='border-bottom: 5px solid #ddd;'>
				<td>
					<span
						style="font-weight: bold;">Banco: </span><?= $profissional->nome_banco ?>
				</td>
				<td>
					<span style="font-weight: bold;">Agência: </span><?= $profissional->agencia_bancaria ?>
				</td>
				<td>
					<span style="font-weight: bold;">N&ordm; Conta: </span><?= $profissional->conta_bancaria ?>
				</td>
			</tr>
		</table>
	</htmlpageheader>
	<sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>


	<br>

	<div>
		<table id="pagamento" class="table table-bordered table-condensed">
			<thead>
			<tr class="success">
				<th colspan="6" class="text-center"><h3><strong>DESCRIÇÃO DOS SERVIÇOS PRESTADOS</strong></h3></th>
			</tr>
			<tr class="active">
				<th>N&ordm;</th>
				<th nowrap>Data evento</th>
				<th width="40%">Cliente</th>
				<th nowrap>Qtde. horas</th>
				<th nowrap>Valor unitário (R$)</th>
				<th nowrap>Valor total (R$)</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($clientes as $k => $cliente): ?>
				<tr>
					<td class="text-right"><?= $k + 1; ?></td>
					<td class="text-center"><?= $cliente->data_evento; ?></td>
					<td><?= $cliente->nome_cliente; ?></td>
					<td class="text-right"><?= $cliente->qtde_horas; ?></td>
					<td class="text-right"><?= $cliente->valor_unitario; ?></td>
					<td class="text-right"><?= $cliente->valor_total; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
			<tr class="active">
				<th colspan="5" class=" text-center">VALOR TOTAL A SER PAGO (R$)</th>
				<th class="text-right"><?= number_format($profissional->valor_total, 2, ',', '.'); ?></th>
			</tr>
			</tfoot>
		</table>

		<br>
		<br>
		<br>
		<table class="table table-condensed">
			<tbody>
			<tr>
				<td style="border: 0; text-align: center; width: 50%;"><br><br>&nbsp;</td>
				<td style="border: 0; text-align: center;">
					<h4>São Paulo, <?= $data_atual; ?><h4>
				</td>
			</tr>
			<tr>
				<td style="border: 0; text-align: center; width: 50%;">Solicitado por:<br><br>&nbsp;</td>
				<td style="border: 0; text-align: center;">_____________________________________________________________<br><?= $profissional->nome_cliente ?>
				</td>
			</tr>
			<tr>
				<td style="border: 0; text-align: center; width: 50%;">Autorizado por:<br><br>&nbsp;</td>
				<td style="border: 0; text-align: center;">
					<?php if ($is_pdf == false): ?>
						<input type="text" class="form-control" id="assinatura" value="<?= $profissional->assinatura ?>"
							   placeholder="Assinatura">
					<?php else: ?>
						<p><?= $profissional->assinatura ?></p>
					<?php endif; ?>
				</td>
			</tr>
			</tbody>
		</table>

	</div>


</div>

<script>
	var query_string = '<?= $query_string ?>';
	var id_profissional_alocado = '<?= $profissional->id; ?>';
	var id_pagamento = '<?= $profissional->id_pagamento; ?>';
	var mes_referencia = '<?= $profissional->mes_referencia; ?>';
	var ano_referencia = '<?= $profissional->ano_referencia; ?>';
	var total_sessoes = '<?= count($clientes); ?>';
	var valor_total = '<?= $profissional->valor_total; ?>';

	function validar_pagamento() {
		$.ajax({
			'url': '<?php echo site_url('icom/sessoes/salvarPagamento') ?>',
			'type': 'POST',
			'data': {
				'id': id_pagamento,
				'id_profissional_alocado': id_profissional_alocado,
				'mes_referencia': mes_referencia,
				'ano_referencia': ano_referencia,
				'total_sessoes': total_sessoes,
				'valor_total': valor_total,
				'nota_fiscal': $('#nota_fiscal').val(),
				'assinatura': $('#assinatura').val()
			},
			'dataType': 'json',
			'beforeSend': function () {
				$('#validar').prop('disabled', true).html('<i class="glyphicon glyphicon-saved"></i> Validando...');
			},
			'success': function (json) {
				if (json.status === true) {
					alert('Solicitação de pagamento validada com sucesso!');
				} else {
					alert(json.status);
				}
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Não foi possível validar a solicitação de pagamento.');
			},
			'complete': function () {
				$('#validar').prop('disabled', false).html('<i class="glyphicon glyphicon-saved"></i> Validar');
			}
		});
	}
</script>
</body>
</html>
