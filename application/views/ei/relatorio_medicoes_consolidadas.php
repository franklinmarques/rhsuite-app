<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?= base_url("assets/images/favipn.ico"); ?>">
	<title>CORPORATE RH - LMS - Relatório de Medições Consolidadas</title>
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
				<td colspan="2" style="vertical-align: top;">
					<img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
						 style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
					<p>
						<img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
							 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
					</p>
				</td>
			</tr>
			<tr style='border-top: 5px solid #ddd;'>
				<th colspan="2" style="padding-bottom: 8px; text-align: center;">
					<?php if ($is_pdf == false): ?>
						<h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE MEDIÇÕES
							CONSOLIDADAS<br><?= mb_strtoupper($alocacao->diretoria) ?></h3>
					<?php else: ?>
						<h2 class="text-center" style="font-weight: bold;">RELATÓRIO DE MEDIÇÕES
							CONSOLIDADAS<br><?= mb_strtoupper($alocacao->diretoria) ?></h2>
					<?php endif; ?>
				</th>
			</tr>
			<tr>
				<?php if ($is_pdf): ?>
					<td colspan="2" style="border-tpo:2px;">
						<h4>Ano: <?= $ano; ?></h4>
					</td>
				<?php else: ?>
					<td width="100%" nowrap>
						<div class="form-inline">
							<label class="control-label">Ano:</label>
							<input type="text" class="form-control text-center ano" value="<?= $ano; ?>">
						</div>
					</td>
					<td class="text-right">
						<a id="pdf" class="btn btn-sm btn-info"
						   href="<?= site_url('ei/relatorios/pdfMedicoesConsolidadas/q?' . $query_string); ?>"
						   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
					</td>
				<?php endif; ?>
			</tr>
			</thead>
		</table>
	</htmlpageheader>
	<sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

	<div>
		<table class="table medicoes_consolidadas table-bordered table-condensed">
			<thead>
			<tr class="success">
				<th colspan="15" class="text-center"><h3><strong>Quantitativo de Recursos Humanos</strong></h3></th>
			</tr>
			<tr class="active">
				<th rowspan="2">Função</th>
				<th class="text-center" colspan="7">1&ordm; semestre</th>
				<th class="text-center" colspan="6">2&ordm; semestre</th>
			</tr>
			<tr class="active">
				<th class="text-center">Jan</th>
				<th class="text-center">Fev</th>
				<th class="text-center">Mar</th>
				<th class="text-center">Abr</th>
				<th class="text-center">Mai</th>
				<th class="text-center">Jun</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Ago</th>
				<th class="text-center">Set</th>
				<th class="text-center">Out</th>
				<th class="text-center">Nov</th>
				<th class="text-center">Dez</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>Total de Escolas</td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes1 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes2 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes3 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes4 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes5 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes6 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes7_1 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes7_2 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes8 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes9 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes10 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes11 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_escolas_mes12 ?? 0; ?></td>
			</tr>
			<tr>
				<td>Total de Alunos</td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes1 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes2 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes3 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes4 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes5 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes6 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes7_1 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes7_2 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes8 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes9 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes10 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes11 ?? 0; ?></td>
				<td class="text-center"><?php echo $alocacao->total_alunos_mes12 ?? 0; ?></td>
			</tr>
			<?php foreach ($funcoes as $funcao): ?>
				<tr>
					<td><?php echo $funcao->nome; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes1 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes2 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes3 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes4 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes5 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes6 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes7_1 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes7_2 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes8 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes9 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes10 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes11 ?? 0; ?></td>
					<td class="text-center"><?php echo $funcao->total_pessoas_mes12 ?? 0; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<br>
		<table class="table medicoes_consolidadas table-bordered table-condensed">
			<thead>
			<tr class="success">
				<th colspan="16" class="text-center"><h3><strong>Quantitativo de Horas</strong></h3></th>
			</tr>
			<tr class="active">
				<th rowspan="2">Função</th>
				<th class="text-center" rowspan="2">Total alocados</th>
				<th class="text-center" rowspan="2">Total utilizados</th>
				<th class="text-center" colspan="7">1&ordm; semestre</th>
				<th class="text-center" colspan="6">2&ordm; semestre</th>
			</tr>
			<tr class="active">
				<th class="text-center">Jan</th>
				<th class="text-center">Fev</th>
				<th class="text-center">Mar</th>
				<th class="text-center">Abr</th>
				<th class="text-center">Mai</th>
				<th class="text-center">Jun</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Ago</th>
				<th class="text-center">Set</th>
				<th class="text-center">Out</th>
				<th class="text-center">Nov</th>
				<th class="text-center">Dez</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($funcoes as $funcao): ?>
				<tr>
					<td><?php echo $funcao->nome; ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_horas_alocadas * 3600, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_horas_utilizadas, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes1 ?? $funcao->total_secs_realizados_mes1 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes2 ?? $funcao->total_secs_realizados_mes2 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes3 ?? $funcao->total_secs_realizados_mes3 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes4 ?? $funcao->total_secs_realizados_mes4 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes5 ?? $funcao->total_secs_realizados_mes5 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes6 ?? $funcao->total_secs_realizados_mes6 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes7_1 ?? $funcao->total_secs_realizados_mes7_1 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes7_2 ?? $funcao->total_secs_realizados_mes7_2 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes8 ?? $funcao->total_secs_realizados_mes8 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes9 ?? $funcao->total_secs_realizados_mes9 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes10 ?? $funcao->total_secs_realizados_mes10 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes11 ?? $funcao->total_secs_realizados_mes11 ?? null, false); ?></td>
					<td class="text-center"><?php echo secToTime($funcao->total_segundos_mes12 ?? $funcao->total_secs_realizados_mes12 ?? null, false); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<br>
		<table class="table medicoes_consolidadas table-bordered table-condensed">
			<thead>
			<tr class="success">
				<th colspan="16" class="text-center"><h3><strong>Balanço Financeiro - Receita (R$)</strong></h3></th>
			</tr>
			<tr class="active">
				<th rowspan="2">Função</th>
				<th class="text-center" rowspan="2">Valor total</th>
				<th class="text-center" colspan="7">1&ordm; semestre</th>
				<th class="text-center" colspan="6">2&ordm; semestre</th>
			</tr>
			<tr class="active">
				<th class="text-center">Jan</th>
				<th class="text-center">Fev</th>
				<th class="text-center">Mar</th>
				<th class="text-center">Abr</th>
				<th class="text-center">Mai</th>
				<th class="text-center">Jun</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Ago</th>
				<th class="text-center">Set</th>
				<th class="text-center">Out</th>
				<th class="text-center">Nov</th>
				<th class="text-center">Dez</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($funcoes as $funcao): ?>
				<tr>
					<td><?php echo $funcao->nome; ?></td>
					<td class="text-right"><?php echo number_format($funcao->total_receita, 2, ',', '.'); ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes1) ? number_format($funcao->receita_efetuada_mes1, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes2) ? number_format($funcao->receita_efetuada_mes2, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes3) ? number_format($funcao->receita_efetuada_mes3, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes4) ? number_format($funcao->receita_efetuada_mes4, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes5) ? number_format($funcao->receita_efetuada_mes5, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes6) ? number_format($funcao->receita_efetuada_mes6, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes7_1) ? number_format($funcao->receita_efetuada_mes7_1, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes7_2) ? number_format($funcao->receita_efetuada_mes7_2, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes8) ? number_format($funcao->receita_efetuada_mes8, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes9) ? number_format($funcao->receita_efetuada_mes9, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes10) ? number_format($funcao->receita_efetuada_mes10, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes11) ? number_format($funcao->receita_efetuada_mes11, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->receita_efetuada_mes12) ? number_format($funcao->receita_efetuada_mes12, 2, ',', '.') : null; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<br>
		<table class="table medicoes_consolidadas table-bordered table-condensed">
			<thead>
			<tr class="success">
				<th colspan="16" class="text-center"><h3><strong>Balanço Financeiro - Pagamentos (R$)</strong></h3></th>
			</tr>
			<tr class="active">
				<th rowspan="2">Função</th>
				<th class="text-center" rowspan="2">Valor total</th>
				<th class="text-center" colspan="7">1&ordm; semestre</th>
				<th class="text-center" colspan="6">2&ordm; semestre</th>
			</tr>
			<tr class="active">
				<th class="text-center">Jan</th>
				<th class="text-center">Fev</th>
				<th class="text-center">Mar</th>
				<th class="text-center">Abr</th>
				<th class="text-center">Mai</th>
				<th class="text-center">Jun</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Ago</th>
				<th class="text-center">Set</th>
				<th class="text-center">Out</th>
				<th class="text-center">Nov</th>
				<th class="text-center">Dez</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($funcoes as $funcao): ?>
				<tr>
					<td><?php echo $funcao->nome; ?></td>
					<td class="text-right"><?php echo number_format($funcao->total_pagamentos, 2, ',', '.'); ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes1) ? number_format($funcao->pagamentos_efetuados_mes1, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes2) ? number_format($funcao->pagamentos_efetuados_mes2, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes3) ? number_format($funcao->pagamentos_efetuados_mes3, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes4) ? number_format($funcao->pagamentos_efetuados_mes4, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes5) ? number_format($funcao->pagamentos_efetuados_mes5, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes6) ? number_format($funcao->pagamentos_efetuados_mes6, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes7_1) ? number_format($funcao->pagamentos_efetuados_mes7_1, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes7_2) ? number_format($funcao->pagamentos_efetuados_mes7_2, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes8) ? number_format($funcao->pagamentos_efetuados_mes8, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes9) ? number_format($funcao->pagamentos_efetuados_mes9, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes10) ? number_format($funcao->pagamentos_efetuados_mes10, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes11) ? number_format($funcao->pagamentos_efetuados_mes11, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->pagamentos_efetuados_mes12) ? number_format($funcao->pagamentos_efetuados_mes12, 2, ',', '.') : null; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<br>
		<table class="table medicoes_consolidadas table-bordered table-condensed">
			<thead>
			<tr class="success">
				<th colspan="16" class="text-center"><h3><strong>Balanço Financeiro - Resultado (R$)</strong></h3></th>
			</tr>
			<tr class="active">
				<th rowspan="2">Função</th>
				<th class="text-center" rowspan="2">Valor total</th>
				<th class="text-center" colspan="7">1&ordm; semestre</th>
				<th class="text-center" colspan="6">2&ordm; semestre</th>
			</tr>
			<tr class="active">
				<th class="text-center">Jan</th>
				<th class="text-center">Fev</th>
				<th class="text-center">Mar</th>
				<th class="text-center">Abr</th>
				<th class="text-center">Mai</th>
				<th class="text-center">Jun</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Ago</th>
				<th class="text-center">Set</th>
				<th class="text-center">Out</th>
				<th class="text-center">Nov</th>
				<th class="text-center">Dez</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($funcoes as $funcao): ?>
				<tr>
					<td><?php echo $funcao->nome; ?></td>
					<td class="text-right"><?php echo number_format($funcao->total_resultado, 2, ',', '.'); ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes1) ? number_format($funcao->resultado_mes1, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes2) ? number_format($funcao->resultado_mes2, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes3) ? number_format($funcao->resultado_mes3, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes4) ? number_format($funcao->resultado_mes4, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes5) ? number_format($funcao->resultado_mes5, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes6) ? number_format($funcao->resultado_mes6, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes7_1) ? number_format($funcao->resultado_mes7_1, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes7_2) ? number_format($funcao->resultado_mes7_2, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes8) ? number_format($funcao->resultado_mes8, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes9) ? number_format($funcao->resultado_mes9, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes10) ? number_format($funcao->resultado_mes10, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes11) ? number_format($funcao->resultado_mes11, 2, ',', '.') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_mes12) ? number_format($funcao->resultado_mes12, 2, ',', '.') : null; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<br>
		<table class="table medicoes_consolidadas table-bordered table-condensed">
			<thead>
			<tr class="success">
				<th colspan="16" class="text-center"><h3><strong>Balanço Financeiro - Resultado (%)</strong></h3>
				</th>
			</tr>
			<tr class="active">
				<th rowspan="2">Função</th>
				<th class="text-center" rowspan="2">Média percentual</th>
				<th class="text-center" colspan="7">1&ordm; semestre</th>
				<th class="text-center" colspan="6">2&ordm; semestre</th>
			</tr>
			<tr class="active">
				<th class="text-center">Jan</th>
				<th class="text-center">Fev</th>
				<th class="text-center">Mar</th>
				<th class="text-center">Abr</th>
				<th class="text-center">Mai</th>
				<th class="text-center">Jun</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Jul</th>
				<th class="text-center">Ago</th>
				<th class="text-center">Set</th>
				<th class="text-center">Out</th>
				<th class="text-center">Nov</th>
				<th class="text-center">Dez</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($funcoes as $funcao): ?>
				<tr>
					<td><?php echo $funcao->nome; ?></td>
					<td class="text-right"><?php echo number_format($funcao->total_resultado_percentual, 1, ',', '.'); ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes1) ? number_format($funcao->resultado_percentual_mes1, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes2) ? number_format($funcao->resultado_percentual_mes2, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes3) ? number_format($funcao->resultado_percentual_mes3, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes4) ? number_format($funcao->resultado_percentual_mes4, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes5) ? number_format($funcao->resultado_percentual_mes5, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes6) ? number_format($funcao->resultado_percentual_mes6, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes7_1) ? number_format($funcao->resultado_percentual_mes7_1, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes7_2) ? number_format($funcao->resultado_percentual_mes7_2, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes8) ? number_format($funcao->resultado_percentual_mes8, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes9) ? number_format($funcao->resultado_percentual_mes9, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes10) ? number_format($funcao->resultado_percentual_mes10, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes11) ? number_format($funcao->resultado_percentual_mes11, 1, ',', '') : null; ?></td>
					<td class="text-right"><?php echo isset($funcao->resultado_percentual_mes12) ? number_format($funcao->resultado_percentual_mes12, 1, ',', '') : null; ?></td>
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

	$('.ano').mask('0000');

	$('input.ano').on('change', function () {
		var depto = '<?= $depto; ?>';
		var diretoria = '<?= $diretoria; ?>';
		var search = 'depto=' + depto + '&diretoria=' + diretoria + '&ano=' + this.value;
		window.location.href = '<?= site_url('ei/relatorios/medicoesConsolidadas'); ?>/q?' + search;
	});
</script>
</body>
</html>
