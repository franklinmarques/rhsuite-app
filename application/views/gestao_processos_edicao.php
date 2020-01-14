<?php require_once 'header.php'; ?>

<!--main content start-->
<section id="main-content">
	<section class="wrapper">

		<!-- page start-->
		<div class="row">
			<div class="col-md-12">
				<div id="alert"></div>
				<section class="panel">
					<header class="panel-heading">
						<i class="fa fa-reorder"></i> Edição de Gestão de Processo
					</header>
					<div class="panel-body">
						<?php echo form_open_multipart('gestaoProcessos/alterar', 'data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
						<input type="hidden" value="<?= $id; ?>" name="id"/>
						<input type="hidden" value="<?= $id_empresa; ?>" name="id_empresa"/>

						<div class="row">
							<div class="col-xs-12 text-right">
								<button type="submit" name="submit" class="btn btn-success"><i
										class="fa fa-save"></i> Salvar
								</button>
								<button type="button" class="btn btn-default" onclick="javascript:history.back()"><i
										class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
								</button>
							</div>
						</div>

						<div class="form-body">
							<div class="form-group">
								<label class="control-label col-sm-2">URL página</label>
								<div class="col-sm-10">
									<?php echo form_dropdown('url_pagina', $urlPaginas, $url_pagina, 'class="form-control"'); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">Orientacoes gerais</label>
								<div class="col-sm-10">
                                            <textarea name="orientacoes_gerais" class="form-control"
													  rows="5"><?= $orientacoes_gerais; ?></textarea>
								</div>
							</div>
							<fieldset>
								<legend><small>Processo 1</small></legend>
								<div class="row form-group">
									<label class="control-label col-md-1 text-nowrap">Nome</label>
									<div class="col-md-3">
										<input type="text" name="nome_processo_1" class="form-control"
											   placeholder="Padrão: Processo 1" value="<?= $nome_processo_1; ?>">
									</div>
									<label class="control-label col-md-1 text-nowrap">Arquvo</label>
									<div class="col-md-7">
										<div id="arquivo_processo_1"
											 class="fileinput fileinput-<?= $arquivo_processo_1 ? 'exists' : 'new'; ?> input-group"
											 data-provides="fileinput">
											<div class="form-control" data-trigger="fileinput">
												<i class="glyphicon glyphicon-file fileinput-exists"></i>
												<span
													class="fileinput-preview fileinput-filename"><?= $arquivo_processo_1; ?></span>
											</div>
											<div class="input-group-addon btn btn-default btn-file" name="buu">
												<span class="fileinput-new">Selecionar arquivo</span>
												<span class="fileinput-exists">Alterar</span>
												<input type="file" accept=".pdf"/>
											</div>
											<a href="#" class="input-group-addon btn btn-default fileinput-exists"
											   data-dismiss="fileinput">Limpar</a>
										</div>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend><small>Processo 2</small></legend>
								<div class="row form-group">
									<label class="control-label col-md-1 text-nowrap">Nome</label>
									<div class="col-md-3">
										<input type="text" name="nome_processo_2" class="form-control"
											   placeholder="Padrão: Processo 2" value="<?= $nome_processo_2; ?>">
									</div>
									<label class="control-label col-md-1 text-nowrap">Arquivo</label>
									<div class="col-md-7">
										<div id="arquivo_processo_2"
											 class="fileinput fileinput-<?= $arquivo_processo_2 ? 'exists' : 'new'; ?> input-group"
											 data-provides="fileinput">
											<div class="form-control" data-trigger="fileinput">
												<i class="glyphicon glyphicon-file fileinput-exists"></i>
												<span
													class="fileinput-preview fileinput-filename"><?= $arquivo_processo_2; ?></span>
											</div>
											<div class="input-group-addon btn btn-default btn-file">
												<span class="fileinput-new">Selecionar arquivo</span>
												<span class="fileinput-exists">Alterar</span>
												<input type="file" accept=".pdf"/>
											</div>
											<a href="#" class="input-group-addon btn btn-default fileinput-exists"
											   data-dismiss="fileinput">Limpar</a>
										</div>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend><small>Documentação 1</small></legend>
								<div class="row form-group">
									<label class="control-label col-md-1 text-nowrap">Nome</label>
									<div class="col-md-3">
										<input type="text" name="nome_documentacao_1" class="form-control"
											   placeholder="Padrão: Documentação 1"
											   value="<?= $nome_documentacao_1; ?>">
									</div>
									<label class="control-label col-md-1">Arquivo</label>
									<div class="col-md-7">
										<div id="arquivo_documentacao_1"
											 class="fileinput fileinput-<?= $arquivo_documentacao_1 ? 'exists' : 'new'; ?> input-group"
											 data-provides="fileinput">
											<div class="form-control" data-trigger="fileinput">
												<i class="glyphicon glyphicon-file fileinput-exists"></i>
												<span
													class="fileinput-preview fileinput-filename"><?= $arquivo_documentacao_1; ?></span>
											</div>
											<div class="input-group-addon btn btn-default btn-file">
												<span class="fileinput-new">Selecionar arquivo</span>
												<span class="fileinput-exists">Alterar</span>
												<input type="file" accept=".pdf"/>
											</div>
											<a href="#" class="input-group-addon btn btn-default fileinput-exists"
											   data-dismiss="fileinput">Limpar</a>
										</div>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend><small>Documentação 2</small></legend>
								<div class="row form-group">
									<label class="control-label col-md-1 text-nowrap">Nome</label>
									<div class="col-md-3">
										<input type="text" name="nome_documentacao_2" class="form-control"
											   placeholder="Padrão: Documentação 2"
											   value="<?= $nome_documentacao_2; ?>">
									</div>
									<label class="control-label col-md-1 text-nowrap">Arquivo</label>
									<div class="col-md-7">
										<div id="arquivo_documentacao_2"
											 class="fileinput fileinput-<?= $arquivo_documentacao_2 ? 'exists' : 'new'; ?> input-group"
											 data-provides="fileinput">
											<div class="form-control" data-trigger="fileinput">
												<i class="glyphicon glyphicon-file fileinput-exists"></i>
												<span
													class="fileinput-preview fileinput-filename"><?= $arquivo_documentacao_2; ?></span>
											</div>
											<div class="input-group-addon btn btn-default btn-file">
												<span class="fileinput-new">Selecionar arquivo</span>
												<span class="fileinput-exists">Alterar</span>
												<input type="file" accept=".pdf"/>
											</div>
											<a href="#" class="input-group-addon btn btn-default fileinput-exists"
											   data-dismiss="fileinput">Limpar</a>
										</div>
									</div>
								</div>
							</fieldset>

						</div>

						<div class="row">
							<div class="col-xs-12 text-right">
								<button type="submit" name="submit" class="btn btn-success"><i
										class="fa fa-save"></i> Salvar
								</button>
								<button type="button" class="btn btn-default" onclick="javascript:history.back()"><i
										class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
								</button>
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
				</section>
			</div>
		</div>
		<!-- page end-->
	</section>
</section>
<!--main content end-->

<?php require_once 'end_js.php'; ?>

<!-- Css -->
<link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

<!-- Js -->
<script>
	$(document).ready(function () {
		document.title = 'CORPORATE RH - LMS - Edição de Gestão de Processo';
	});
</script>

<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>

<script>
	$(document).ready(function () {
		$('#arquivo_processo_1').fileinput({'name': 'arquivo_processo_1'}).find('[type="hidden"]').val('<?= $arquivo_processo_1; ?>');
		$('#arquivo_processo_1 .fileinput-preview').html('<?= $arquivo_processo_1; ?>');

		$('#arquivo_processo_2').fileinput({'name': 'arquivo_processo_2'}).find('[type="hidden"]').val('<?= $arquivo_processo_2; ?>');
		$('#arquivo_processo_2 .fileinput-preview').html('<?= $arquivo_processo_2; ?>');

		$('#arquivo_documentacao_1').fileinput({'name': 'arquivo_documentacao_1'}).find('[type="hidden"]').val('<?= $arquivo_documentacao_1; ?>');
		$('#arquivo_documentacao_1 .fileinput-preview').html('<?= $arquivo_documentacao_1; ?>');

		$('#arquivo_documentacao_2').fileinput({'name': 'arquivo_documentacao_2'}).find('[type="hidden"]').val('<?= $arquivo_documentacao_2; ?>');
		$('#arquivo_documentacao_2 .fileinput-preview').html('<?= $arquivo_documentacao_2; ?>');
	});
</script>
<?php require_once 'end_html.php'; ?>
