<?php if ($is_pdf): ?>
	<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
<?php endif; ?>
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
			<?php if ($is_pdf == false): ?>
				<td nowrap>
					<a id="pdf_pagamento_prestador" class="btn btn-sm btn-info" target="_blank"
					   href="<?= site_url('ei/apontamento/pdfPagamentoPrestador/?' . $query_string); ?>"
					   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
				</td>
			<?php endif; ?>
		</tr>
		<tr style='border-top: 5px solid #ddd;'>
			<th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
				<?php if ($is_pdf == false): ?>
					<h3 class="text-center" style="font-weight: bold;">SOLICITAÇÃO DE PAGAMENTO DE PRESTADOR DE
						SERVIÇO</h3>
				<?php else: ?>
					<h2 class="text-center" style="font-weight: bold;">SOLICITAÇÃO DE PAGAMENTO DE PRESTADOR DE
						SERVIÇO</h2>
				<?php endif; ?>
			</th>
		</tr>
		</thead>
	</table>

	<table width="100%">
		<tr>
			<td width="50%">
				<p>
					<strong>Nome prestador de serviço:</strong> <span
						id="pagamento_prestador_nome"><?= $prestador; ?></span><br>
					<strong>CNPJ:</strong> <span id="pagamento_prestador_cnpj"><?= $cnpj; ?></span><br>
					<strong>Centro de custo:</strong> <span
						id="pagamento_prestador_centro_custo"><?= $centroCusto; ?></span><br>
					<strong>Agência:</strong> <span id="pagamento_prestador_agencia_bancaria"><?= $agencia; ?></span>&emsp;
					<strong>Conta:</strong> <span id="pagamento_prestador_conta_bancaria"><?= $conta; ?></span><br>
					<strong>Banco:</strong> <span id="pagamento_prestador_nome_banco"><?= $banco; ?></span><br>
				</p>
				<?php if ($is_pdf == false): ?>
					<div class="row">
						<div class="col-md-12">
							<div class="radio">
								<label>
									<input type="radio" name="tipo_pagamento"
										   value="1"<?= $tipo_pagamento === '1' ? ' checked' : ''; ?>>
									Valor pagto. início semestre
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="tipo_pagamento"
										   value="2"<?= $tipo_pagamento === '2' ? ' checked' : ''; ?>>
									Valor pagto. ajustado
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="tipo_pagamento"
										   value="3"<?= $tipo_pagamento === '3' ? ' checked' : ''; ?>>
									Valor pagto. da Ordem de Serviço
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<br>
							<button type="button" id="btnUsoHorasFaturadas"
									onclick="recuperar_horas_faturadas()"
									class="btn btn-info btn-sm">Usar horas de faturamento
							</button>
						</div>
					</div>
				<?php endif; ?>
			</td>
			<td style="vertical-align: top; padding-left: 15px;">
				<p>
					<strong>Solicitante:</strong> <?= $solicitante; ?><br>
					<strong>Departamento:</strong> <?= $departamento; ?><br>
					<strong>Mês de referência:</strong> <?= $mesAno; ?><br>
				</p>
				<?php if ($is_pdf == false): ?>
					<div class="row">
						<div class="col-md-12">
							<label class="control-label" style="font-weight: bold;">Funcionário(a)
								substituto(a):</label>
							<?php echo form_dropdown('id_alocado_sub', $substitutos_eventos, '', 'class="form-control input-sm" onchange="mostrar_dados_substituto(this);"'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label class="control-label" style="font-weight: bold;">Observações:</label>
							<textarea name="observacoes" id="observacoes_faturamento" class="form-control"
									  onchange="setPdfFaturamentoAttributes()" rows="3"><?= $observacoes; ?></textarea>
						</div>
					</div>
				<?php elseif (strlen($observacoes)): ?>
					<p><strong>Observações:</strong> <?= $observacoes; ?></p>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<br>

	<div>
		<table id="periodo" class="table table-condensed table-bordered">
			<thead>
			<tr>
				<th colspan="6" class="text-center">DESCRIÇÃO DOS SERVIÇOS PRESTADOS</th>
			</tr>
			<tr class="active">
				<th class="text-center">Período/dias</th>
				<th>Cliente/justificativa</th>
				<th>Período</th>
				<th class="text-center">Quantidade</th>
				<th class="text-center">Valor (R$)</th>
				<th class="text-center">Valor total (R$)</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($servicos as $servico): ?>
				<tr>
					<td class="text-center"><?= $mesAno ?></td>
					<td><?= $servico['escola'] ?></td>
					<td><?= $servico['periodo'] ?></td>
					<?php if ($is_pdf == false): ?>
						<input type="hidden" name="id_totalizacao[]" value="<?= $servico['id'] ?>">
						<td class="text-center horas" style="width: 100px;">
							<input name="total_horas_faturadas[]" class="form-control hora text-center" type="text"
								   value="<?= $servico['qtdeHoras'] ?>" placeholder="hh:mm" style="width: 100px;"
								   onchange="calcular_servicos(this);">
						</td>
						<td class="text-center" style="width: 100px;">
							<input name="valor_pagamento[]" class="form-control valor" type="text"
								   value="<?= $servico['valorCustoProfissional'] ?>" style="width: 100px;"
								   onchange="calcular_servicos(this);">
						</td>
						<td class="text-center" style="width: 100px;">
							<input name="valor_total[]" class="form-control valor" type="text"
								   value="<?= $servico['total'] ?>"
								   style="width: 100px;" onchange="calcular_servicos(this);">
						</td>
					<?php else: ?>
						<td class="text-center"><?= $servico['qtdeHoras'] ?></td>
						<td class="text-center"><?= $servico['valorCustoProfissional'] ?></td>
						<td class="text-center"><?= $servico['total'] ?></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			<?php if ($valorExtra1): ?>
				<tr>
					<td class="text-center"><?= $mesAno ?></td>
					<td colspan="3"><?= $justificativa1 ?></td>
					<td class="text-center"><?= $valorExtra1 ?></td>
					<td class="text-center"><?= $valorExtra1 ?></td>
				</tr>
			<?php endif; ?>
			<?php if ($valorExtra2): ?>
				<tr>
					<td class="text-center"><?= $mesAno ?></td>
					<td colspan="3"><?= $justificativa2 ?></td>
					<td class="text-center"><?= $valorExtra2 ?></td>
					<td class="text-center"><?= $valorExtra2 ?></td>
				</tr>
			<?php endif; ?>
			<tbody>
			<tr>
				<td colspan="5" class="text-right">Valor total a ser pago aos prestados >>></td>
				<td id="valor_total_geral" class="text-center"><?= $valorTotal ?></td>
			</tr>
			</tbody>
		</table>
	</div>

	<?php if ($is_pdf): ?>
		<br>
		<br>
		<br>
		<br>
		<table>
			<tr>
				<td style="width: 60%;"></td>
				<td class="text-center text-danger">
					<hr id="assinatura" class="text-danger"
						style="margin-bottom: 1px; height: 1px;">
					<h4> Coordenação do Departamento </h4>
					<br>
					<br>
					<p>
					<h5 style="font-weight: bold;">São
						Paulo, <?= strftime("%d de {$mesAtual} de %Y"); ?></h5>
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

<?php if ($is_pdf == false): ?>
	<script>
        $('#form_pagamento_prestador [name="tipo_pagamento"]').on('change', function () {
            var valor_pagamento = '';
            if (this.value === '1') {
                valor_pagamento = '<?= $pagamento_inicio_semestre; ?>';
            } else if (this.value === '2') {
                valor_pagamento = '<?= $pagamento_ajustado; ?>';
            } else if (this.value === '3') {
                valor_pagamento = '<?= $pagamento_ordem_servico; ?>';
            }

            $('#form_pagamento_prestador [name="valor_pagamento[]"]').val(valor_pagamento);

            calcular_servicos(this);
        });


        function mostrar_dados_substituto(elem) {
            var substituto = elem.value;
            var pdf = '<?= site_url('ei/apontamento/pdfPagamentoPrestador/?' . $query_string); ?>'
            if (substituto.length > 0) {
                $('#btnRecuperarPagamentoPrestador').prop('disabled', true);
                $('#pdf_pagamento_prestador').prop('href', pdf + '&alocado_bck=' + substituto);
            } else {
                $('#btnRecuperarPagamentoPrestador').prop('disabled', false);
                $('#pdf_pagamento_prestador').prop('href', pdf);
            }

            $.ajax({
                'url': '<?= site_url('ei/apontamento/planilhaPagamentoPrestadorSubstitutoEvento') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_horario': '<?= $id_horario; ?>',
                    'id_mes': '<?= $id_mes; ?>',
                    'substituto_semestre': '<?= $substituto; ?>',
                    'substituto': substituto
                },
                'beforeSend': function () {
                    $(elem).prop('disabled', true);
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }
                    $('#pagamento_prestador_nome').html(json.nome);
                    $('#pagamento_prestador_cnpj').html(json.cnpj);
                    $('#pagamento_prestador_centro_custo').html(json.centro_custo);
                    $('#pagamento_prestador_agencia_bancaria').html(json.agencia_bancaria);
                    $('#pagamento_prestador_conta_bancaria').html(json.conta_bancaria);
                    $('#pagamento_prestador_nome_banco').html(json.nome_banco);

                    $.each(json.servicos, function (i, value) {
                        // console.log(servico);
                        // $.each(servico, function (key, value) {
                        //     console.log(value);
                            $('#form_pagamento_prestador [name="id_totalizacao[]"]:eq(' + i + ')').val(value.id_totalizacao);
                            $('#form_pagamento_prestador [name="total_horas_faturadas[]"]:eq(' + i + ')').val(value.qtdeHoras);
                            $('#form_pagamento_prestador [name="valor_pagamento[]"]:eq(' + i + ')').val(value.valorCustoProfissional);
                            $('#form_pagamento_prestador [name="valor_total[]"]:eq(' + i + ')').val(value.total).trigger('change');
                            // alert(value.teste);
                        // });
                    });

                    // $.each(json.buu, function (i, val) {
                        // alert(val.data + ' | ' + val.desconto_sub1 + ' | ' + val.desconto_sub2);
                    // });
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert(textStatus + ' ' + jqXHR.status + ': ' + (jqXHR.status === 0 ? 'Disconnected' : errorThrown));
                    if (jqXHR.status === 401) {
                        window.close();
                    }
                },
                'complete': function () {
                    $(elem).prop('disabled', false);
                }
            });
        }


        function calcular_servicos(elem) {
            var valor_total = 0;
            $('#form_pagamento_prestador [name="id_totalizacao[]"').each(function (i) {
                var horas_faturadas = moment.duration($('#form_pagamento_prestador [name="total_horas_faturadas[]"]:eq(' + i + ')').val(), 'HHH:mm').asSeconds();
                var valor_hora = parseFloat($('#form_pagamento_prestador [name="valor_pagamento[]"]:eq(' + i + ')').val().replace('.', '').replace(',', '.'));
                var valor_faturado_alterado = parseFloat($('#form_pagamento_prestador [name="valor_total[]"]:eq(' + i + ')').val().replace('.', '').replace(',', '.'));

                if (elem.name === 'valor_total[]') {
                    var valor_faturado = (valor_faturado !== valor_faturado_alterado ? valor_faturado_alterado : valor_faturado);
                } else {
                    var valor_faturado = (horas_faturadas / 3600) * valor_hora;
                }

                if (isNaN(valor_faturado)) {
                    $('#form_pagamento_prestador [name="valor_total[]"]:eq(' + i + ')').val('');
                } else {
                    $('#form_pagamento_prestador [name="valor_total[]"]:eq(' + i + ')').val((Math.trunc(valor_faturado * 100) / 100).toLocaleString('pt-BR', {
                        'minimumFractionDigits': 2,
                        'maximumFractionDigits': 2
                    }));
                    valor_total += valor_faturado;
                }

            });

            if (isNaN(valor_total)) {
                $('#valor_total_geral').html('');
            } else {
                $('#valor_total_geral').html((Math.trunc(valor_total * 100) / 100).toLocaleString('pt-BR', {
                    'minimumFractionDigits': 2,
                    'maximumFractionDigits': 2
                }));
            }

        }

	</script>
<?php else: ?>
</body>
</html>
<?php endif; ?>
