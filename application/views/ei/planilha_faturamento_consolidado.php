<?php if ($is_pdf): ?>
	<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CORPORATE RH - LMS - Planilha de faturamento consolidado</title>
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
					<a id="pdf" class="btn btn-sm btn-info" target="_blank"
					   href="<?= site_url('ei/apontamento/pdfTotalizacaoConsolidada/?' . $query_string); ?>"
					   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
				</td>
			<?php endif; ?>
		</tr>
		<tr style='border-top: 5px solid #ddd;'>
			<th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
				<?php if ($is_pdf == false): ?>
					<h3 class="text-center" style="font-weight: bold;">PLANILHA DE FATURAMENTO CONSOLIDADO</h3>
				<?php else: ?>
					<h2 class="text-center" style="font-weight: bold;">PLANILHA DE FATURAMENTO CONSOLIDADO</h2>
				<?php endif; ?>
			</th>
		</tr>
		</thead>
	</table>


	<table width="100%">
		<tr>
			<td colspan="2">
				<p>
					Solicitamos ao departamento Administrativo-Financeiro promover a emissão de nota fiscal e
					faturamento conforme
					dados abaixo.
				</p>
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top; padding-right: 10px;" width="50%">
				<p>
					<strong>Cliente:</strong> <?= $diretoria; ?><br>
					<strong>Contrato(s):</strong> <?= $contratos; ?><br>
					<strong>Ordens de Serviço:</strong> <?= $ordensServico; ?><br>
					<strong>Mês/ano de referência:</strong> <?= $mesAno; ?><br>
					<!--        <strong>Total de escolas:</strong> --><? //= $totalEscolas; ?><!--<br>-->
					<!--        <strong>Total de alunos:</strong> --><? //= $totalAlunos; ?><!--<br>-->
					<!--        <strong>Total de profissionais:</strong> --><? //= $totalProfissionais; ?>
				</p>
			</td>
			<td style="vertical-align: top; padding-left: 10px;" width="50%">
				<?php if ($is_pdf == false): ?>
					<input type="hidden" name="id_medicao_mensal" value="<?= $id_medicao_mensal ?>">
					<div class="row">
						<div class="col-md-12">
							<label class="control-label" style="font-weight: bold;">Observações:</label>
							<textarea id="observacoes" class="form-control" rows="4"><?= $observacoes; ?></textarea>
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
				<th class="text-center">Tipo de profissional</th>
				<th class="text-center">Valor hora (R$)</th>
				<th class="text-center">Qtde. total horas</th>
				<th class="text-center">Valor a ser faturado (R$)</th>
			</tr>
			</thead>
			<tbody>
			<?php if ($is_pdf == false): ?>
				<?php foreach ($alocados as $alocado): ?>
					<input type="hidden" name="id[]" value="<?= $alocado->id ?>">
					<input type="hidden" name="id_alocacao[]" value="<?= $alocado->id_alocacao ?>">
					<input type="hidden" name="cargo[]" value="<?= $alocado->cargo ?>">
					<input type="hidden" name="funcao[]" value="<?= $alocado->funcao ?>">
					<tr>
						<td><?= $alocado->funcao ?></td>
						<td class="text-center" style="width: 130px;">
							<input name="valor_hora[]" class="form-control valor" type="text"
								   value="<?= $alocado->valor_hora ?>" style="width: 130px;">
						</td>
						<td class="text-center" style="width: 130px;">
							<input name="total_horas[]" class="form-control hora text-center" type="text"
								   value="<?= $alocado->total_horas_mes ?: secToTime($alocado->total_segundos_mes, false); ?>"
								   placeholder="hh:mm"
								   style="width: 130px;">
						</td>
						<td class="text-center" style="width: 170px;">
							<input name="valor_faturado[]" class="form-control valor" type="text"
								   value="<?= $alocado->valor_faturado ?>" style="width: 170px;">
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<?php foreach ($alocados as $alocado): ?>
					<tr>
						<td><?= $alocado->funcao ?></td>
						<td class="text-center"><?= $alocado->valor_hora ?></td>
						<td class="text-center"><?= $alocado->total_horas_mes ?: secToTime($alocado->total_segundos_mes, false); ?></td>
						<td class="text-center"><?= $alocado->valor_faturado ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
			<tfoot>
			<tr class="active">
				<th colspan="2">Totalização</th>
				<th class="text-center"><?= $total_horas ?></th>
				<th class="text-center"><?= $valor_faturado ?></th>
			</tr>
			</tfoot>
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
						Paulo, <?= utf8_encode(strftime("%d de {$mesAtual} de %Y")) ?></h5>
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
