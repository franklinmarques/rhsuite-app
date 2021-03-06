<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CORPORATE RH - LMS - Relatório de Fechamento Mensal</title>
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
				<img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
					 style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
			</td>
			<td style="vertical-align: top;">
				<p>
					<img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
						 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
				</p>
			</td>
		</tr>
	</table>
	<table id="table" class="table table-condensed table-condensed">
		<thead>
		<tr style='border-top: 5px solid #ddd;'>
			<th colspan="2" style="text-align: center; padding-bottom: 12px;">
				<h4 style="font-weight: bold;">
					RELATÓRIO DE FECHAMENTO MENSAL<br>
					PASSE ESCOLAR<?= $contrato ? ' | Contrato: ' . $contrato : ''; ?><br>
					<?= $setor; ?> | CNPJ: <?= $cnpj; ?><br>
					Período: <?= $data_inicio; ?> a <?= $data_termino; ?>
				</h4>
			</th>
		</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<table id="fechamento_mensal" class="table table-bordered table-condensed" width="50%">
		<?php if ($mostrarColaboradores): ?>
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
		<?php else: ?>
			<tr>
				<th class="active text-center">Quantidade</th>
				<th class="active text-center">Valor unitário (R$)</th>
				<th class="active text-center">Valor total (R$)</th>
			</tr>
			<tr>
				<td class="text-center"><?= $total; ?></td>
				<td class="text-center"><?= $valor_unitario; ?></td>
				<td class="text-center"><?= $valor_faturamento; ?></td>
			</tr>
		<?php endif; ?>
	</table>

	<?php if ($mostrarColaboradores == false): ?>
	<br>
		<table class="table table-bordered table-condensed">
			<tr>
				<th class="active text-center">Valor da nota fiscal (R$)</th>
				<td class="text-center"><?= $valor_faturamento; ?></td>
			</tr>
		</table>
	<?php endif; ?>

	<div style="float: right; width: 45%;" id="gestor">
		<br>
		<div class="well text-center" style="margin-left: 30px;">
			<p>
				<strong>São Paulo, <?= utf8_encode(strftime('%d de %B de %Y')) ?></strong>
			</p>
			<br>
			<?php if (!empty($coordenador->nome_usuario)): ?>
				<h4><?= $coordenador->nome_usuario ?></h4>
			<?php endif; ?>
			<?php if (!empty($coordenador->depto_usuario)): ?>
				<p><?= $coordenador->depto_usuario ?></p>
			<?php endif; ?>
			<?php if (!empty($coordenador->telefone)): ?>
				<p>Tel.: <?= str_replace('/', ' / ', $coordenador->telefone) ?></p>
			<?php endif; ?>
			<?php if (!empty($coordenador->email)): ?>
				<p>E-mail: <a href="mailto:<?= $coordenador->email ?>"><u><?= $coordenador->email ?></u></a></p>
			<?php endif; ?>
		</div>
	</div>
	<!--</div>-->
</div>

</body>
</html>
