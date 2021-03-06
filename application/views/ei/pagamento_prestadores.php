<?php //if ($is_pdf == false): ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?= base_url("assets/images/favipn.ico"); ?>">
	<title>CORPORATE RH - LMS - Planilha de pagamento de Prestador de Serviços</title>
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
<?php //endif; ?>
<div class="container-fluid">

	<table id="table" class="table table-condensed">
		<thead>
		<tr>
			<td style="width: auto;">
				<img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
					 style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
			</td>
			<td style="width: 80%; vertical-align: top;">
				<p>
					<img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
						 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
				</p>
			</td>
			<td nowrap>
				<?php if ($is_pdf == false): ?>
					<a id="pdf" class="btn btn-sm btn-info"
					   href="<?= site_url('ei/relatorios/pdfPagamentoPrestadores/?' . $query_string); ?>"
					   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
				<?php endif; ?>
			</td>
		</tr>
		<tr style='border-top: 5px solid #ddd;'>
			<th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
				<?php if ($is_pdf == false): ?>
					<h3 class="text-center" style="font-weight: bold;">CONSOLIDADO DE PAGAMENTO DE PRESTADORES DE
						SERVIÇOS</h3>
				<?php else: ?>
					<h2 class="text-center" style="font-weight: bold;">CONSOLIDADO DE PAGAMENTO DE PRESTADORES DE
						SERVIÇOS</h2>
				<?php endif; ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td colspan="3"><h3 class="text-center"><?= ucfirst($nomeMes) . ' de ' . $ano; ?></h3></td>
		</tr>
		<tr>
			<td colspan="3"><h4 class="text-center">Supervisor(a): <?= $nomeSupervisor; ?></h4></td>
		</tr>
		<?php if ($is_pdf == false): ?>
			<tr>
				<td colspan="3">
					<div class="row">
						<label class="control-label col-sm-2 text-right">Filtrar por supervisor(a)</label>
						<div class="col-sm-4">
							<?php echo form_dropdown('supervisor', $supervisores, $supervisor, 'class="form-control input-sm" onchange="reload_page();"'); ?>
						</div>
						<label class="control-label col-sm-2 text-right">Filtrar por função</label>
						<div class="col-sm-4">
							<?php echo form_dropdown('funcao', $funcoes, $funcao, 'class="form-control input-sm" onchange="reload_page();"'); ?>
						</div>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<div>
		<table id="pagamento_prestadores" class="table table-condensed table-bordered">
			<thead>
			<tr class="active">
				<th>Nome prestador(a)</th>
				<th>Função</th>
				<th>CNPJ</th>
				<th>Unidade Ensino</th>
				<th class="text-nowrap">Liberação pagamento</th>
				<th>Observações</th>
				<th class="text-center">Horas<br>(<?= $total['horas'] ?>)</th>
				<th class="text-center text-nowrap">Valor (R$)<br>(<?= $total['valor'] ?>)</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($rows as $row): ?>
				<tr>
					<td><?= $row->cuidador ?></td>
					<td><?= $row->funcao ?></td>
					<td><?= $row->cnpj ?></td>
					<td><?= $row->escola ?></td>
					<td class="text-center"><?= $row->data_liberacao_pagto ?></td>
					<td><?= nl2br($row->observacoes) ?></td>
					<td class="text-center"><?= str_replace('.', ',', round($row->total_horas, 1)) ?></td>
					<td class="text-right"><?= $row->valor_total ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>

</div>

<?php //if ($is_pdf == false): ?>

<script>
	function reload_page() {
		var search = '<?= $query_string; ?>';
		var supervisor = $('[name="supervisor"]').val();
		var funcao = $('[name="funcao"]').val();
		search = search + '&supervisor=' + supervisor + '&funcao=' + funcao;
		window.location.href = '<?= site_url('ei/relatorios/pagamentoPrestadores'); ?>/q?' + search;
	}
</script>

</body>
</html>
<?php //endif; ?>
