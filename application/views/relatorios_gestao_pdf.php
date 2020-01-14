<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CORPORATE RH - LMS - Requisição de Pessoal</title>
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
</head>
<style>
	table tr td:first-child {
		max-width: 100%;
		/*white-space: nowrap;*/
	}

	th.active h3 {
		margin-top: 2px;
		margin-bottom: 2px;
	}
</style>
<body style="color: #000;">
<div class="container-fluid">
	<table>
		<tr>
			<td>
				<img src="<?= base_url($foto) ?>" align="left"
					 style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
			</td>
			<td style="vertical-align: top;">
				<p>
					<img src="<?= base_url($foto_descricao) ?>" align="left"
						 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
				</p>
			</td>
		</tr>
	</table>
	<table class="table table-condensed gestao">
		<thead>
		<tr style='border-top: 5px solid #ddd;'>
			<td class="text-right" colspan="3">
				<?php if ($is_pdf == false): ?>
					<a class="btn btn-sm btn-info"
					   href="<?= site_url('relatoriosGestao/pdf/q?id=' . $id); ?>"
					   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
					<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Fechar</button>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th colspan="3">
				<?php if ($is_pdf == false): ?>
					<h2 class="text-center"><strong>RELATÓRIO DE GESTÃO</strong></h2>
				<?php else: ?>
					<h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE GESTÃO</h3>
				<?php endif; ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr style='border-top: 5px solid #ddd;'>
			<td><strong>Departamento:</strong> <?= $depto ?></td>
			<td><strong>Área:</strong> <?= $area ?></td>
			<td><strong>Setor:</strong> <?= $setor ?></td>
		</tr>
		<tr style='border-top: 5px solid #ddd;'>
			<td colspan="2"><strong>Gestor responsável:</strong> <?= $usuario ?></td>
			<td><strong>Mês e ano de referência:</strong> <?= $mes_referencia . '/' . $ano_referencia ?></td>
		</tr>
		</tbody>
	</table>

	<br/>

	<table class="table table-condensed dados">
		<tbody>
		<tr>
			<th class="active"><h3><strong>Indicadores</strong></h3></th>
		</tr>
		<tr>
			<td><p><?= nl2br($indicadores); ?></p><br></td>
		</tr>
		<tr>
			<th class="active"><h3><strong>Riscos/oportunidades</strong></h3></th>
		</tr>
		<tr>
			<td><p><?= nl2br($riscos_oportunidades); ?></p><br></td>
		</tr>
		<tr>
			<th class="active"><h3><strong>Ocorrências</strong></h3></th>
		</tr>
		<tr>
			<td><p><?= nl2br($ocorrencias); ?></p><br></td>
		</tr>
		<tr>
			<th class="active"><h3><strong>Necessidades/investimentos</strong></h3></th>
		</tr>
		<tr>
			<td><p><?= nl2br($necessidades_investimentos); ?></p></td>
		</tr>
		</tbody>
	</table>

</div>
</body>
</html>
