<?php
require_once "header.php";
?>
<style>
	/*    .modal, .modal-backdrop {
			overflow: auto;
			height: 100%;
		}
		#main-content .modal, .modal-backdrop {
			position: absolute;
		}
		#main-content .modal-backdrop {
			z-index: 1001;
		}
		.wrapper {
			overflow: auto;
			position:relative;
			height: 90%;
			min-height: 600px;
		}
		#main-content {
			height: 100%;
		}*/
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div style="color: #000;">
			<table class="table table-condensed pdi">
				<thead>
				<tr style='border-top: 5px solid #ddd;'>
					<th colspan="4">
						<div class="row">
							<div class="col-sm-12">
								<img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
									 style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
								<p class="text-left">
									<img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>"
										 align="left"
										 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
								</p>
								<?php if ($this->session->userdata('tipo') == 'empresa'): ?>
									<div style="float:right">
										<a class="btn btn-sm btn-info"
										   href="<?= site_url('jobDescriptorRelatorio/pdf/' . $this->uri->rsegment(3)); ?>"
										   title="Exportar PDF" target="_blank"><i
												class="glyphicon glyphicon-download-alt"></i>
											Exportar
											PDF</a>
										<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
												class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
										</button>
									</div>
								<?php else: ?>
									<div style="float:right">
										<a class="btn btn-sm btn-info"
										   href="<?= site_url('jobDescriptorRelatorio/pdfIndividual/' . $this->uri->rsegment(3)); ?>"
										   title="Exportar PDF" target="_blank"><i
												class="glyphicon glyphicon-download-alt"></i>
											Exportar
											PDF</a>
										<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
												class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
										</button>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</th>
				</tr>
				<tr>
					<th colspan="4" class="text-center">
						<?php if ($is_pdf == false): ?>
							<h2 class="text-center">DESCRITIVO DE CARGO-FUNÇÃO</h2>
						<?php else: ?>
							<h3 class="text-center">DESCRITIVO DE CARGO-FUNÇÃO</h3>
						<?php endif; ?>
					</th>
				</tr>
				</thead>
				<tbody>
				<tr style='border-top: 5px solid #ddd;'>
					<td>
						<div class="form-inline">
							<?php if (is_array($usuarios)): ?>
								<label class="control-label">Respondente(s)</label>
								<?php echo form_dropdown('id_usuario', $usuarios, '', 'class="form-control input-sm" autocomplete="off"'); ?>
							<?php else: ?>
								<strong>Respondente: </strong><?php echo $usuarios; ?>
							<?php endif; ?>
						</div>
					</td>
					<td colspan="3">
						<div class="row">
							<?php if ($this->session->userdata('tipo') == 'empresa'): ?>
								<div class="col-sm-12 text-right"> 
									<button type="button" class="btn btn-sm btn-success" id="btnCriarConsolidado"
											onclick="criar_consolidado();"<?= $id_consolidado ? 'style="display: none"' : '' ?>>
										<i class="glyphicon glyphicon-plus"></i> Criar consolidado
									</button>
									<button type="button" class="btn btn-sm btn-info" id="btnEditarConsolidado"
											onclick="editar_consolidado();"<?= $id_consolidado ? '' : 'style="display: none"' ?>>
										<i class="glyphicon glyphicon-pencil"></i> Editar consolidado
									</button>
									<button type="button" class="btn btn-sm btn-success" id="btnSalvarConsolidado"
											onclick="salvar_consolidado();" style="display: none;">
										<i class="glyphicon glyphicon-floppy-disk"></i> Salvar consolidado
									</button>
									<button type="button" class="btn btn-sm btn-default" id="btnCancelarConsolidado"
											onclick="cancelar_consolidado();" style="display: none">Cancelar
									</button>
									<button type="button" class="btn btn-sm btn-danger" id="btnLimparConsolidado"
											onclick="limpar_consolidado();"<?= $id_consolidado ? '' : 'style="display: none"' ?>>
										<i class="glyphicon glyphicon-trash"></i> Limpar consolidado
									</button>
								</div>
							<?php else: ?>
								<div class="col-sm-12 text-right">
									<button type="button" class="btn btn-sm btn-info" id="btnEditarConsolidado"
											onclick="editar_individual();"<?= $id_consolidado ? '' : 'style="display: none"' ?>>
										<i class="glyphicon glyphicon-pencil"></i> Editar descritivo
									</button>
									<button type="button" class="btn btn-sm btn-success" id="btnSalvarConsolidado"
											onclick="salvar_individual();" style="display: none;">
										<i class="glyphicon glyphicon-floppy-disk"></i> Salvar descritivo
									</button>
									<button type="button" class="btn btn-sm btn-default" id="btnCancelarConsolidado"
											onclick="cancelar_consolidado();" style="display: none">Cancelar
									</button>
								</div>
							<?php endif; ?>
						</div>
					</td>
				</tr>
				</tbody>
				<tr style='border-top: 5px solid #ddd;'>
					<td><strong>Cargo:</strong> <?= $jobDescriptor->cargo ?></td>
					<td><strong>Função:</strong> <?= $jobDescriptor->funcao ?></td>
					<td><strong>CBO:</strong> <?= $jobDescriptor->cbo ?></td>
					<td><strong>Versão:</strong> <?= $jobDescriptor->versao ?></td>
				</tr>
			</table>

			<br/>
			<div>
				<form action="#" id="form" class="form-horizontal" autocomplete="off">
					<input type="hidden" name="id_descritor" value="<?= $this->uri->rsegment(3, null); ?>">
					<?php foreach ($estruturas as $estrutura => $titulo): ?>
						<table id="<?php echo $estrutura; ?>" class="table table-striped table-bordered" cellspacing="0"
							   width="100%"
							   style="border-radius: 0 !important;">
							<thead>
							<tr class="success">
								<th style="font-size: 16px; font-weight: bold;"><?php echo $titulo; ?></th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td class="modo_individual" style="display: block;">
									<?php if (isset($respondentes[$estrutura])): ?>
										<?php foreach ($respondentes[$estrutura] as $id_usuario => $descritivo): ?>
											<div class="respondente respondente_<?= $id_usuario; ?>">
												<?php if (strlen($descritivo) > 0): ?>
													<p><?php echo nl2br($descritivo); ?></p>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
									<div class="respondente respondente_consolidado" style="display: none;">
										<p><?= nl2br($consolidado[$estrutura]); ?></p>
									</div>
								</td>
							</tr>
							<tr>
								<td class="modo_consolidado" style="display: none;">
                                    <textarea name="<?= $estrutura; ?>" rows="10"
											  class="form-control"><?=  nl2br($consolidado[$estrutura]); ?></textarea>
								</td>
							</tr>
							</tbody>
						</table>
					<?php endforeach; ?>
				</form>
			</div>

		</div>
	</section>
</section>
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - DESCRITIVO DE CARGO-FUNÇÃO';
    });


</script>


<?php require_once "end_js.php"; ?>

<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/ckeditor/adapters/jquery.js'); ?>"></script>

<script>
    $('[name="id_usuario"]').on('change', function () {
        if (this.value.length > 0) {
            $('.respondente').hide();
            $('.respondente_' + this.value).show();
        } else {
            $('.respondente').show();
            $('.respondente_consolidado').hide();
        }
    });

    $("#form textarea").ckeditor({
        'height': '100',
        'toolbar': [
            ['Source', 'Bold', 'Italic', 'Underline', '-', 'Undo', 'Redo', '-',
                'Outdent', 'Indent', 'NumberedList', 'BulletedList', '-',
                'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-',
                'Link', 'Smiley', 'TextColor', 'BGColor', '-', 'Font', 'FontSize'
            ]
        ],
        'removePlugins': 'elementspath'
    });

    function criar_consolidado() {
        $.ajax({
            'url': '<?php echo site_url('jobDescriptorRespondente/ajaxAddConsolidado') ?>',
            'type': "POST",
            'data': {
                'id_descritor': '<?= $this->uri->rsegment(3, 0); ?>'
            },
            'dataType': "json",
            'success': function (json) {
                if (json.status) //if success close modal and reload ajax table
                {
                    $('#btnEditarConsolidado, #btnLimparConsolidado').show();
                    $('#btnCriarConsolidado, #btnSalvarConsolidado, #btnCancelarConsolidado').hide();
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }

    function editar_individual() {
        $.ajax({
            'url': '<?php echo site_url('jobDescriptorRelatorio/editarIndividual') ?>',
            'type': "POST",
            'data': {
                'id_descritor': '<?= $this->uri->rsegment(3, 0); ?>'
            },
            'dataType': "json",
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                } else {
                    $.each(json, function (key, value) {
                        $('#form [name="' + key + '"]').val(value);
                    });
                    $('#btnSalvarConsolidado, #btnCancelarConsolidado, .modo_consolidado').show();
                    $('#btnCriarConsolidado, #btnEditarConsolidado, #btnLimparConsolidado, .modo_individual').hide();
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }

    function editar_consolidado() {
        $.ajax({
            'url': '<?php echo site_url('jobDescriptorRespondente/ajaxEditConsolidado') ?>',
            'type': "POST",
            'data': {
                'id_descritor': '<?= $this->uri->rsegment(3, 0); ?>'
            },
            'dataType': "json",
            'success': function (json) {
                if (json.erro) {
                    alert(json.erro);
                } else {
                    $.each(json, function (key, value) {
                        $('#form [name="' + key + '"]').val(value);
                    });
                    $('#btnSalvarConsolidado, #btnCancelarConsolidado, .modo_consolidado').show();
                    $('#btnCriarConsolidado, #btnEditarConsolidado, #btnLimparConsolidado, .modo_individual').hide();
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }

    function salvar_individual() {
        $.ajax({
            'url': '<?php echo site_url('jobDescriptorRelatorio/salvarIndividual') ?>',
            'type': "POST",
            'data': $('#form').serialize(),
            'dataType': "json",
            'success': function (json) {
                if (json.status) //if success close modal and reload ajax table
                {
                    $.each($('#form').serializeArray(), function (i, elem) {
                        $('table#' + elem.name + ' tbody tr td.modo_individual p').html(elem.value);
                    });
                    $('#btnEditarConsolidado, #btnLimparConsolidado, .modo_individual').show();
                    $('#btnCriarConsolidado, #btnSalvarConsolidado, #btnCancelarConsolidado, .modo_consolidado').hide();

                    // $.each('.respondente_consolidado');
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }

    function salvar_consolidado() {
        $.ajax({
            'url': '<?php echo site_url('jobDescriptorRespondente/ajaxUpdateConsolidado') ?>',
            'type': "POST",
            'data': $('#form').serialize(),
            'dataType': "json",
            'success': function (json) {
                if (json.status) //if success close modal and reload ajax table
                {
                    $.each($('#form').serializeArray(), function (i, elem) {
                        $('table#' + elem.name + ' tbody tr td.modo_individual div.respondente_consolidado').html(elem.value);
                    });
                    $('#btnEditarConsolidado, #btnLimparConsolidado, .modo_individual').show();
                    $('#btnCriarConsolidado, #btnSalvarConsolidado, #btnCancelarConsolidado, .modo_consolidado').hide();

                    // $.each('.respondente_consolidado');
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }

    function cancelar_consolidado() {
        $('#btnEditarConsolidado, #btnLimparConsolidado, .modo_individual').show();
        $('#btnCriarConsolidado, #btnSalvarConsolidado, #btnCancelarConsolidado, .modo_consolidado').hide();
    }

    function limpar_consolidado() {
        $.ajax({
            'url': '<?php echo site_url('jobDescriptorRespondente/ajaxDeleteConsolidado') ?>',
            'type': "POST",
            'data': {
                'id_descritor': '<?= $this->uri->rsegment(3, 0); ?>'
            },
            'dataType': "json",
            'success': function (json) {
                if (json.status) //if success close modal and reload ajax table
                {
                    $('#btnEditarConsolidado, #btnSalvarConsolidado, #btnCancelarConsolidado, #btnLimparConsolidado, .modo_consolidado').hide();
                    $('#btnCriarConsolidado, .modo_individual').show();
                    $('.respondente_consolidado p').html('');
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }

</script>
<?php require_once "end_html.php"; ?>
