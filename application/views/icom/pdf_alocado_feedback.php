<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CORPORATE RH - LMS - Relatório de Feedback Intérpretes ICOM</title>
	<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

	<!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
	<!--WARNING: Respond.js doesn't work if you view the page via file://-->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style>
		@page {
			margin: 40px 20px;
		}
	</style>
</head>
<body style="color: #000;">
<div class="container-fluid">
	<table>
		<tr>
			<td>
				<img src="<?= base_url('imagens/usuarios/' . $foto) ?>" align="left"
					 style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
			</td>
			<td style="vertical-align: top;">
				<p>
					<img src="<?= base_url('imagens/usuarios/' . $foto_descricao) ?>" align="left"
						 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
				</p>
			</td>
		</tr>
	</table>
	<table id="feedback" class="table table-condensed table-condensed">
		<thead>
		<tr style='border-top: 5px solid #ddd;'>
			<th style="padding-bottom: 12px;">
				<h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE FEEDBACK</h3>
				<h3 class="text-center" style="font-weight: bold;">INTÉRPRETES ICOM</h3>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
			<td style="padding: 0px;">
				<h5><strong>Nome do orientado: </strong><span id="depto"><?= $nome_usuario_orientado ?></span></h5>
			</td>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td style="padding: 0px;">
				<h5><strong>Nome do orientador: </strong><span id="area"><?= $nome_usuario_orientador ?></span></h5>
			</td>
		</tr>
		<tr style='border-bottom: 1px solid #ddd;'>
			<td style="padding: 0px;">
				<h5><strong>Data do feedback: </strong><span id="setor"><?= $data ?></span></h5>
			</td>
		</tr>
		</tbody>
	</table>
	<table id="table" class="table table-bordered table-condensed" width="100%">
		<thead>
		<tr class='active'>
			<th class="text-center text-nowrap">Feedback repassado + Plano de ações de melhoria</th>
			<th class="text-center text-nowrap">Resultado do feedback/Plano de ações</th>
		</tr>
		</thead>
		<tbody>
		<?php if ($id): ?>
			<tr>
				<td width="auto"><?= nl2br($descricao); ?></td>
				<td width="50%"><?= nl2br($resultado); ?></td>
			</tr>
		<?php else: ?>
			<tr>
				<td class="text-center text-muted" colspan="2">Nenhum registro encontrado</td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
	<!--</div>-->
</div>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

<script>

    var table;

    $(document).ready(function () {
        //datatables
        table = $('#table').DataTable();
    });
</script>
</body>
</html>
