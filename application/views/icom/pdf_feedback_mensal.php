<?php if ($is_pdf): ?>
	<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CORPORATE RH - LMS - Relatório de Feedback Mensal</title>
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
					<a id="pdf_faturamento" class="btn btn-sm btn-info" target="_blank"
					   href="<?= site_url('icom/apontamento/pdfFeedbackMensal/?' . $query_string); ?>"
					   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
				</td>
			<?php endif; ?>
		</tr>
		<tr style='border-top: 5px solid #ddd;'>
			<th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
				<?php if ($is_pdf == false): ?>
					<h2 class="text-center" style="font-weight: bold;">Folha de Controle e Recebimento de Feedback</h2>
					<h3 class="text-center" style="font-weight: bold;"><?= ucfirst($nomeMes); ?>
						de <?= $ano; ?></h3>
				<?php else: ?>
					<h1 class="text-center" style="font-weight: bold;">Folha de Controle e Recebimento de Feedback</h1>
					<h2 class="text-center" style="font-weight: bold;"><?= ucfirst($nomeMes); ?>
						de <?= $ano; ?></h2>
				<?php endif; ?>
			</th>
		</tr>
		</thead>
	</table>

	<br>
	<div>
		<table id="feedback" class="table table-condensed table-bordered">
			<thead>
			<tr class="active">
				<th class="text-center">Colaborador</th>
				<th class="text-center">Horário</th>
				<th class="text-center">Regime contratação</th>
				<th class="text-center">Colaborador orientador</th>
				<th class="text-center">Data feedback</th>
				<th class="text-center">Assinatura orientador</th>
				<th class="text-center">Data feedback</th>
				<th class="text-center">Assinatura orientado</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($feedbacks as $feedback): ?>
				<tr>
					<td><?= $feedback->nome_usuario ?></td>
					<td class="text-center"><?= $feedback->horario ?></td>
					<td class="text-center"><?= $feedback->categoria ?></td>
					<td><?= $feedback->nome_usuario_orientador ?></td>
					<td class="text-center"><?= $feedback->data ?></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>

</div>

<?php if ($is_pdf): ?>
</body>
</html>
<?php endif; ?>
