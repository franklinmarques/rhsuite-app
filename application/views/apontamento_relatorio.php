<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CORPORATE RH - LMS - Controle de Frequência Individual</title>
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
						<button onclick="fechar_mes()" class="btn btn-sm btn-success" id="fechar_mes"
								title="Fechar mês"><i class="glyphicon glyphicon-saved"></i> Fechar mês
						</button>
						<a id="pdf" class="btn btn-sm btn-info"
						   href="<?= site_url('apontamento_relatorios/pdf/' . $query_string); ?>"
						   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
						<!--<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>-->
					</td>
				<?php endif; ?>
			</tr>
			<tr style='border-top: 5px solid #ddd;'>
				<th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
					<?php if ($is_pdf == false): ?>
						<h3 class="text-center" style="font-weight: bold;">REGISTRO DE OCORRÊNCIAS NO MÊS
							DE <?= mb_strtoupper($mes_nome) ?> DE <?= $ano ?></h3>
						<?php if ($contrato): ?>
							<h4 class="text-center" style="font-weight: bold;">CONTRATO Nº <?= $contrato->contrato ?>
								─ <?= $contrato->nome ?> ─ <?= $contrato->setor ?></h4>
						<?php endif; ?>
					<?php else: ?>
						<h4 class="text-center" style="font-weight: bold;">REGISTRO DE OCORRÊNCIAS NO MÊS
							DE <?= mb_strtoupper($mes_nome) ?> DE <?= $ano ?></h4>
						<?php if ($contrato): ?>
							<h5 class="text-center" style="font-weight: bold;">CONTRATO Nº <?= $contrato->contrato ?>
								─ <?= $contrato->nome ?> ─ <?= $contrato->setor ?></h5>
						<?php endif; ?>
					<?php endif; ?>
				</th>
			</tr>
			</thead>
		</table>
	</htmlpageheader>
	<sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

	<div>
		<table id="apontamento" class="table table-bordered table-condensed">
			<thead>
			<tr class="success">
				<th colspan="<?= ($postos ? 3 : 0) + count($dias) + 6 ?>" class="text-center"><h3><strong>Apontamentos
							Diários</strong></h3></th>
			</tr>
			<tr class="active">
				<th rowspan="2" style="vertical-align: middle;">Colaborador(a)</th>
				<?php if ($postos): ?>
					<th rowspan="2" class="text-center text-nowrap" style="vertical-align: middle;">Matrícula</th>
					<th rowspan="2" class="text-center text-nowrap" style="vertical-align: middle;">Login</th>
					<th rowspan="2" class="text-center" style="vertical-align: middle;">Horário trabalho</th>
				<?php endif; ?>
				<th rowspan="2" class="text-center" style="vertical-align: middle;">Cargo</th>
				<th colspan="<?= count($dias) ?>" class="text-center">Dias/horas</th>
				<th colspan="2" class="text-center" nowrap>Faltas/atrasos</th>
			</tr>
			<tr class="active">
				<?php foreach ($dias as $dia): ?>
					<th class="text-center"><?= $dia ?></th>
				<?php endforeach; ?>
				<th class="text-center">Dias</th>
				<th class="text-center">Horas</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($apontamentos as $apontamento): ?>
				<tr>
					<td><?= $apontamento->nome ?></td>
					<?php if ($postos): ?>
						<td><?= $apontamento->matricula ?></td>
						<td><?= $apontamento->login ?></td>
						<td><?= $apontamento->horario_trabalho ?></td>
					<?php endif; ?>
					<td><?= $apontamento->nome_cargo ?></td>

					<?php foreach ($dias as $dia): ?>
						<td class="text-center"><?php eval('echo $apontamento->dia_' . $dia . ';'); ?></td>
					<?php endforeach; ?>
					<td class="text-center"><?= $apontamento->total_faltas ?></td>
					<td class="text-center"><?= $apontamento->total_atrasos ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<pagebreak odd-header-name="myHeader"></pagebreak>
		<table id="totalizacao" class="table table-bordered table-condensed">
			<thead>
			<tr class="success">
				<th colspan="14" class="text-center"><h3><strong>Totalização</strong></h3></th>
			</tr>
			<tr class="success">
				<th colspan="14" class="text-center">
					<?php if ($is_pdf == false): ?>
						<label class="radio-inline">
							<input type="radio" name="calculo_totalizacao" autocomplete="off"
								   value="1"<?= $calculo_totalizacao == '2' ? '' : ' checked' ?>> Cálculo por dia/hora
						</label>
						<label class="radio-inline">
							<input type="radio" name="calculo_totalizacao" autocomplete="off"
								   value="2"<?= $calculo_totalizacao == '2' ? ' checked' : '' ?>> Cálculo por percentual
						</label>
					<?php else: ?>
						<?= $calculo_totalizacao == '2' ? 'Cálculo por percentual' : 'Cálculo por dia/hora' ?>
					<?php endif; ?>
				</th>
			</tr>
			<tr class="active">
				<th rowspan="2" style="vertical-align: middle;">Colaborador(a)</th>
				<th colspan="4" class="text-center">Faltas/atrasos</th>
				<?php if ($is_pdf == false): ?>
					<th colspan="6"
						class="text-center totalizacao_1"<?= $calculo_totalizacao === '2' ? ' style="display: none;"' : '' ?>>
						Valores: R$ <?= number_format($reajuste->total_liquido, 2, ',', '.') ?>
					</th>
					<th colspan="6"
						class="text-center totalizacao_2"<?= $calculo_totalizacao === '2' ? '' : ' style="display: none;"' ?>>
						Valores: R$ <?= number_format($reajuste->total_liquido_2, 2, ',', '.') ?>
					</th>
				<?php else: ?>
					<th colspan="6" class="text-center">
						Valores:
						R$ <?= number_format($calculo_totalizacao === '2' ? $reajuste->total_liquido_2 : $reajuste->total_liquido, 2, ',', '.') ?>
					</th>
				<?php endif; ?>
			</tr>
			<tr class="active">
				<th class="text-center">Dias</th>
				<?php if ($is_pdf == false): ?>
					<th class="text-center totalizacao_1"<?= $calculo_totalizacao === '2' ? ' style="display: none;"' : '' ?>>
						%
					</th>
					<th class="text-center totalizacao_2"<?= $calculo_totalizacao === '2' ? '' : ' style="display: none;"' ?>>
						%
					</th>
					<th class="text-center">Horas</th>
					<th class="text-center totalizacao_1"<?= $calculo_totalizacao === '2' ? ' style="display: none;"' : '' ?>>
						%
					</th>
					<th class="text-center totalizacao_2"<?= $calculo_totalizacao === '2' ? '' : ' style="display: none;"' ?>>
						%
					</th>
				<?php else: ?>
					<th class="text-center">%</th>
					<th class="text-center">Horas</th>
					<th class="text-center">%</th>
				<?php endif; ?>
				<th class="text-center">Posto</th>
				<th class="text-center">Conversor dia</th>
				<th class="text-center">Glosa dia</th>
				<th class="text-center">Conversor hora</th>
				<th class="text-center">Glosa hora</th>
				<?php if ($is_pdf == false): ?>
					<th class="text-center totalizacao_1"<?= $calculo_totalizacao === '2' ? ' style="display: none;"' : '' ?>>
						Total
					</th>
					<th class="text-center totalizacao_2"<?= $calculo_totalizacao === '2' ? '' : ' style="display: none;"' ?>>
						Total
					</th>
				<?php else: ?>
					<th class="text-center">Total</th>
				<?php endif; ?>
			</tr>
			</thead>
			<tbody>
			<?php if ($is_pdf == false): ?>
				<?php foreach ($totalizacoes as $totalizacao): ?>
					<tr>
						<td><?= $totalizacao->nome ?></td>
						<td class="text-right"><?= $totalizacao->dias_faltas ?></td>
						<td class="text-center totalizacao_1"<?= $calculo_totalizacao === '2' ? ' style="display: none;"' : '' ?>><?= str_replace('.', ',', $totalizacao->perc_dias_faltas ? round($totalizacao->perc_dias_faltas, 2) : $totalizacao->perc_dias_faltas) ?></td>
						<td class="text-center totalizacao_2"<?= $calculo_totalizacao === '2' ? '' : ' style="display: none;"' ?>><?= str_replace('.', ',', $totalizacao->perc_dias_faltas ? round($totalizacao->perc_dias_faltas, 2) : $totalizacao->perc_dias_faltas) ?></td>
						<td class="text-center"><?= $totalizacao->horas_atraso ?></td>
						<td class="text-center totalizacao_1"<?= $calculo_totalizacao === '2' ? ' style="display: none;"' : '' ?>><?= str_replace('.', ',', $totalizacao->perc_horas_atraso ? round($totalizacao->perc_horas_atraso, 2) : $totalizacao->perc_horas_atraso) ?></td>
						<td class="text-center totalizacao_2"<?= $calculo_totalizacao === '2' ? '' : ' style="display: none;"' ?>><?= str_replace('.', ',', $totalizacao->perc_horas_atraso ? round($totalizacao->perc_horas_atraso, 2) : $totalizacao->perc_horas_atraso) ?></td>
						<td class="text-center"><?= $totalizacao->valor_posto ?></td>
						<td class="text-center"><?= $totalizacao->valor_dia ?></td>
						<td class="text-center"><?= $totalizacao->glosa_dia ?></td>
						<td class="text-center"><?= $totalizacao->valor_hora ?></td>
						<td class="text-center"><?= $totalizacao->glosa_hora ?></td>
						<td class="text-center totalizacao_1"<?= $calculo_totalizacao === '2' ? ' style="display: none;"' : '' ?>><?= $totalizacao->valor_total ?></td>
						<td class="text-center totalizacao_2"<?= $calculo_totalizacao === '2' ? '' : ' style="display: none;"' ?>><?= $totalizacao->valor_total_2 ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<?php foreach ($totalizacoes as $totalizacao): ?>
					<tr>
						<td><?= $totalizacao->nome ?></td>
						<td class="text-right"><?= $totalizacao->dias_faltas ?></td>
						<td class="text-center">
							<?php if ($calculo_totalizacao === '2'): ?>
								<?php //echo str_replace('.', ',', $totalizacao->perc_dias_faltas ? floor($totalizacao->perc_dias_faltas) : $totalizacao->perc_dias_faltas); ?>
								<?= str_replace('.', ',', $totalizacao->perc_dias_faltas ? round($totalizacao->perc_dias_faltas, 2) : $totalizacao->perc_dias_faltas) ?>
							<?php else: ?>
								<?= str_replace('.', ',', $totalizacao->perc_dias_faltas ? round($totalizacao->perc_dias_faltas, 2) : $totalizacao->perc_dias_faltas) ?>
							<?php endif; ?>
						</td>
						<td class="text-center"><?= $totalizacao->horas_atraso ?></td>
						<td class="text-center">
							<?php if ($calculo_totalizacao === '2'): ?>
								<?php //echo str_replace('.', ',', $totalizacao->perc_horas_atraso ? floor($totalizacao->perc_horas_atraso) : $totalizacao->perc_horas_atraso); ?>
								<?= str_replace('.', ',', $totalizacao->perc_horas_atraso ? round($totalizacao->perc_horas_atraso, 2) : $totalizacao->perc_horas_atraso) ?>
							<?php else: ?>
								<?= str_replace('.', ',', $totalizacao->perc_horas_atraso ? round($totalizacao->perc_horas_atraso, 2) : $totalizacao->perc_horas_atraso) ?>
							<?php endif; ?>
						</td>
						<td class="text-center"><?= $totalizacao->valor_posto ?></td>
						<td class="text-center"><?= $totalizacao->valor_dia ?></td>
						<td class="text-center"><?= $totalizacao->glosa_dia ?></td>
						<td class="text-center"><?= $totalizacao->valor_hora ?></td>
						<td class="text-center"><?= $totalizacao->glosa_hora ?></td>
						<td class="text-center">
							<?= $calculo_totalizacao === '2' ? $totalizacao->valor_total_2 : $totalizacao->valor_total ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
		<?php if ($contrato): ?>
			<pagebreak></pagebreak>
			<?php if ($postos): ?>
				<table id="servicos" class="table table-bordered table-condensed">
					<thead>
					<tr class="success">
						<th colspan="2" class="text-center"><h3><strong>Serviços</strong></h3></th>
					</tr>
					</thead>
					<tbody>
					<?php if (isset($servicos->nao_compartilhados) and count($servicos->compartilhados) > 0): ?>
						<tr class="active">
							<td colspan="2"><strong>Serviços compartilhados</strong></td>
						</tr>
						<?php foreach ($servicos->compartilhados as $compartilhado): ?>
							<tr>
								<td><?= $compartilhado->descricao ?></td>
								<td class="text-center"><?= number_format($compartilhado->valor, 2, ',', '.') ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					<tr>
						<td><strong>VALOR TOTAL DOS SERVIÇOS COMPARTILHADOS (R$)</strong></td>
						<td class="text-center"><strong><?= number_format($servicos->total ?? null, 2, ',', '.') ?></strong>
						</td>
					</tr>
					<?php if (isset($servicos->nao_compartilhados) and count($servicos->nao_compartilhados) > 0): ?>
						<!-- <tr class="active">
							<td colspan="2"><strong>Serviços não compartilhados</strong></td>
						</tr> -->
						<?php foreach ($servicos->nao_compartilhados as $nao_compartilhado): ?>
							<tr>
								<td><?= $nao_compartilhado->descricao ?></td>
								<td class="text-center"><?= number_format($nao_compartilhado->valor, 2, ',', '.') ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<div style="float: left; width: 64%;">
				<table id="reajuste" class="table table-bordered table-condensed">
					<thead>
					<tr class="success">
						<th colspan="3" class="text-center"><h3><strong>Fechamento mensal</strong></h3></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td colspan="2"><strong>VALOR CONTRATUAL PACTUADO (R$)</strong></td>
						<td class="text-center">
							<strong><?= number_format($reajuste->valor_contratual, 2, ',', '.') ?></strong>
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><strong>VALOR MENSAL APURADO (R$)</strong></td>
						<td class="text-center">
                            <span
								class="totalizacao_1"<?= $calculo_totalizacao === '1' ? '' : ' style="display: none;"'; ?>>
                                <strong><?= number_format($reajuste->total_liquido, 2, ',', '.') ?></strong>
                            </span>
							<span
								class="totalizacao_2"<?= $calculo_totalizacao === '2' ? '' : ' style="display: none;"'; ?>>
                                <strong><?= number_format($reajuste->total_liquido_2, 2, ',', '.') ?></strong>
                            </span>
						</td>
					</tr>
					<?php if ($area == 'Ipesp' and isset($servicos->total)): ?>
						<tr>
							<td colspan="2">SERVIÇOS COMPARTILHADOS</td>
							<td class="text-center"><?= number_format($servicos->total, 2, ',', '.') ?></td>
						</tr>
					<?php else: ?>
						<?php foreach ($reajuste->indices as $indice): ?>
							<tr>
								<td>REAJUSTE <?= $indice->data_reajuste ?></td>
								<td class="text-center"><?= str_replace('.', ',', $indice->valor_indice); ?></td>
								<td class="text-center">
                                <span
									class="totalizacao_1"<?= $calculo_totalizacao === '1' ? '' : ' style="display: none;"'; ?>>
                                    <?= number_format($indice->valor_reajuste, 2, ',', '.'); ?>
                                </span>
									<span
										class="totalizacao_2"<?= $calculo_totalizacao === '2' ? '' : ' style="display: none;"'; ?>>
                                    <?= number_format($indice->valor_reajuste_2, 2, ',', '.'); ?>
                                </span>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					<tr>
						<td colspan="2"><strong>VALOR NOTA FISCAL (R$)</strong></td>
						<td class="text-center">
                            <span
								class="totalizacao_1"<?= $calculo_totalizacao === '1' ? '' : ' style="display: none;"'; ?>>
                                <strong><?= number_format($reajuste->valor_total, 2, ',', '.') ?></strong>
                            </span>
							<span
								class="totalizacao_2"<?= $calculo_totalizacao === '2' ? '' : ' style="display: none;"'; ?>>
                                <strong><?= number_format($reajuste->valor_total_2, 2, ',', '.') ?></strong>
                            </span>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div style="float: right; width: 36%;" id="gestor">
				<div class="well text-center" style="margin-left: 30px;">
					<p>
						<strong>São Paulo, <?= utf8_encode(strftime('%d de %B de %Y')) ?></strong>
					</p>
					<br>
					<?php if ($contrato->nome_usuario): ?>
						<h4><?= $contrato->nome_usuario ?></h4>
					<?php endif; ?>
					<?php if ($contrato->depto_usuario): ?>
						<p><?= $contrato->depto_usuario ?></p>
					<?php endif; ?>
					<?php if ($contrato->telefone): ?>
						<p>Tel.: <?= str_replace('/', ' / ', $contrato->telefone) ?></p>
					<?php endif; ?>
					<?php if ($contrato->email): ?>
						<p>E-mail: <a href="mailto:<?= $contrato->email ?>"><u><?= $contrato->email ?></u></a></p>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if (count($observacoes) > 0 or !empty($alocacao_observacoes)): ?>
			<pagebreak></pagebreak>
			<table id="observacoes" class="table table-bordered table-condensed">
				<thead>
				<tr class="success">
					<th colspan="5" class="text-center"><h3><strong>Observações do mês</strong></h3></th>
				</tr>
				<?php if ($alocacao_observacoes): ?>
					<tr>
						<td colspan="5"><?= $alocacao_observacoes ?></td>
					</tr>
				<?php endif; ?>
				<?php if (count($observacoes) > 0): ?>
					<tr class="active">
						<th>Colaborador(a)</th>
						<th>Tipo de evento</th>
						<th class="text-center">Data início</th>
						<th class="text-center">Data término</th>
						<th>Colaborador(a) backup</th>
					</tr>
				<?php endif; ?>
				</thead>
				<tbody>

				<?php foreach ($observacoes as $observacao): ?>
					<tr>
						<td><?= $observacao->nome ?></td>
						<td><?= $observacao->evento ?></td>
						<td class="text-center"><?= $observacao->data_inicio ?></td>
						<td class="text-center"><?= $observacao->data_termino ?></td>
						<td><?= $observacao->nome_bck ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
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
