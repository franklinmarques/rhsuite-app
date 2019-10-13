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
		white-space: nowrap;
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
	<table class="table table-condensed requisicao">
		<thead>
		<tr style='border-top: 5px solid #ddd;'>
			<td class="text-right">
				<?php if ($is_pdf == false): ?>
					<a class="btn btn-sm btn-danger"
					   href="<?= site_url('requisicaoPessoal/pdf/' . $this->uri->rsegment(3)); ?>"
					   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
					<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
							class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
					</button>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th colspan="3">
				<?php if ($is_pdf == false): ?>
					<h2 class="text-center">REQUISIÇÃO DE PESSOAL</h2>
				<?php else: ?>
					<h3 class="text-center">REQUISIÇÃO DE PESSOAL</h3>
				<?php endif; ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr style='border-top: 5px solid #ddd;'>
			<td><strong>N&ordm; requisição:</strong> <?= $row->id ?></td>
			<td><strong>Tipo de vaga:</strong> <?= $row->tipo_vaga ?></td>
			<td><strong>Data da requisição:</strong> <?= $row->data_abertura ?></td>
		</tr>
		<tr>
			<td colspan="3"><strong>Nome da requisição:</strong> <?= $row->numero ?></td>
		</tr>
		<tr>
			<td colspan="3"><strong>Depto/área/setor:</strong> <?= $row->estrutura ?></td>
		</tr>
		<tr>
			<td colspan="3"><strong>Requisitante:</strong> <?= $row->requisitante ?></td>
		</tr>
		</tbody>
	</table>

	<br/>

	<table class="table table-condensed dados">
		<thead>
		<tr>
			<th colspan="2">Dados do contrato e centro de custo</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="active">N&ordm; do contrato:</td>
			<td><?= $row->numero_contrato; ?></td>
		</tr>
		<tr>
			<td class="active">Regime de contratação:</td>
			<td><?= $row->regime_contratacao; ?></td>
		</tr>
		<tr>
			<td class="active">Centro de custo:</td>
			<td><?= $row->centro_custo; ?></td>
		</tr>
		</tbody>
	</table>

	<table id="datas_inportantes" class="table table-condensed dados">
		<thead>
		<tr>
			<th colspan="2">Datas importantes</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="active">Data abertura RP:</td>
			<td><?= $row->data_abertura; ?></td>
		</tr>
		<tr>
			<td class="active">Data início processo recrutamento/seleção:</td>
			<td><?= $row->data_processo_seletivo; ?></td>
		</tr>
		<tr>
			<td class="active">Data fechamento RP:</td>
			<td><?= $row->data_fechamento; ?></td>
		</tr>
		<tr>
			<td class="active">Data suspensão RP:</td>
			<td><?= $row->data_suspensao; ?></td>
		</tr>
		<tr>
			<td class="active">Data cancelamento RP:</td>
			<td><?= $row->data_cancelamento; ?></td>
		</tr>
		</tbody>
	</table>

	<?php if ($row->candidatos_aprovados): ?>
		<table id="candidatos_aprovados" class="table table-condensed dados">
			<thead>
			<tr>
				<th>Candidatos contratados</th>
				<th>Data de admissão</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($row->candidatos_aprovados as $candidato_aprovado): ?>
				<tr>
					<td class="active"><?= $candidato_aprovado->nome; ?>:</td>
					<td><?= $candidato_aprovado->data_admissao; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>

	<table class="table table-condensed dados">
		<thead>
		<tr>
			<th colspan="2">Dados da vaga</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="active">Tipo de vaga:</td>
			<td><?= $row->requisicao_confidencial; ?></td>
		</tr>
		<tr>
			<td class="active">Cargo:</td>
			<td><?= $row->cargo; ?></td>
		</tr>
		<tr>
			<td class="active">Função:</td>
			<td><?= $row->funcao; ?></td>
		</tr>
		<tr>
			<td class="active">Quantidade de vagas:</td>
			<td><?= $row->numero_vagas; ?></td>
		</tr>
		<tr>
			<td class="active">Justificativa da contratação:</td>
			<td><?= $row->justificativa_contratacao; ?></td>
		</tr>
		<?php if ($row->justificativa_contratacao == 'Substituição'): ?>
			<tr>
				<td class="active">Colaborador substituto:</td>
				<td><?= nl2br($row->colaborador_substituto); ?></td>
			</tr>
		<?php endif; ?>
		<?php if ($row->possui_indicacao): ?>
			<tr>
				<td class="active">Colaboradores indicados:</td>
				<td><?= nl2br($row->colaboradores_indicados); ?></td>
			</tr>
			<tr>
				<td class="active">Responsável pela indicação:</td>
				<td><?= nl2br($row->indicador_responsavel); ?></td>
			</tr>
		<?php endif; ?>
		<?php if ($row->possui_indicacao and $row->id_depto === '5'): ?>
			<tr>
				<td class="active">Nome do pai:</td>
				<td><?= $row->nome_pai; ?></td>
			</tr>
			<tr>
				<td class="active">Nome da mãe:</td>
				<td><?= $row->nome_mae; ?></td>
			</tr>
			<tr>
				<td class="active">Data de nascimento:</td>
				<td><?= $row->data_nascimento; ?></td>
			</tr>
			<tr>
				<td class="active">RG:</td>
				<td><?= $row->rg; ?></td>
			</tr>
			<tr>
				<td class="active">Data de emissão RG:</td>
				<td><?= $row->rg_data_emissao; ?></td>
			</tr>
			<tr>
				<td class="active">Órgão Emissor RG:</td>
				<td><?= $row->rg_orgao_emissor; ?></td>
			</tr>
			<tr>
				<td class="active">CPF:</td>
				<td><?= $row->cpf; ?></td>
			</tr>
			<tr>
				<td class="active">PIS:</td>
				<td><?= $row->pis; ?></td>
			</tr>
			<tr>
				<td class="active">Informações de departamento:</td>
				<td><?= nl2br($row->departamento_informacoes); ?></td>
			</tr>
		<?php endif; ?>
		<tr>
			<td class="active">Benefícios:</td>
			<td><?= $row->beneficios; ?></td>
		</tr>
		<tr>
			<td class="active">Remuneração mensal:</td>
			<td><?= $row->remuneracao_mensal; ?></td>
		</tr>
		<tr>
			<td class="active">Horário de trabalho:</td>
			<td><?= $row->horario_trabalho; ?></td>
		</tr>
		<tr>
			<td class="active">Previsão de início:</td>
			<td><?= $row->previsao_inicio; ?></td>
		</tr>
		<tr>
			<td class="active">Local de trabalho:</td>
			<td><?= nl2br($row->local_trabalho); ?></td>
		</tr>
		<tr>
			<td class="active">Exames necessários:</td>
			<td><?= $row->exames_necessarios; ?></td>
		</tr>
		<tr>
			<td class="active">Perfil geral:</td>
			<td><?= nl2br($row->perfil_geral); ?></td>
		</tr>
		<tr>
			<td class="active">Competências técnicas necessárias:</td>
			<td><?= nl2br($row->competencias_tecnicas); ?></td>
		</tr>
		<tr>
			<td class="active">Competências comportamentais necessárias:</td>
			<td><?= nl2br($row->competencias_comportamentais); ?></td>
		</tr>
		<tr>
			<td class="active">Atividaes e responsabilidades associadas ao cargo-função:</td>
			<td><?= nl2br($row->atividades_associadas); ?></td>
		</tr>
		<tr>
			<td class="active">Observações:</td>
			<td><?= nl2br($row->observacoes); ?></td>
		</tr>
		</tbody>
	</table>


</div>
</body>
</html>
