<?php
require_once APPPATH . 'controllers/GestaoProcessos.php';

$processo = GestaoProcessos::getProcesso($url ?? []);
?>

<?php if ($processo): ?>
	<span class="tools pull-left">
        <a href="#" class="fa fa-question-circle" data-toggle="modal" data-target="#modal_processos"
		   style="margin-right: 10px; margin-left: 0 !important;"></a>
    </span>

	<div id="modal_processos" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="btn btn-default" data-dismiss="modal" style="float:right;">Fechar
					</button>
					<h5 class="modal-title text-primary"><strong>Para orientações utilize as documentações das abas
							abaixo.</strong></h5>
				</div>
				<div class="modal-body">
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active">
							<a href="#orientacoes_gerais" aria-controls="orientacoes_gerais" role="tab"
							   data-toggle="tab"><strong>Orientações gerais</strong>
							</a>
						</li>
						<?php if ($processo->arquivo_processo_1): ?>
							<li role="presentation">
								<a href="#arquivo_processo_1" aria-controls="arquivo_processo_1" role="tab"
								   data-toggle="tab">
									<strong><?php echo($processo->nome_processo_1 ?? 'Processo 1'); ?></strong>
								</a>
							</li>
						<?php endif; ?>
						<?php if ($processo->arquivo_processo_2): ?>
							<li role="presentation">
								<a href="#arquivo_processo_2" aria-controls="arquivo_processo_2" role="tab"
								   data-toggle="tab">
									<strong><?php echo($processo->nome_processo_2 ?? 'Processo 2'); ?></strong>
								</a>
							</li>
						<?php endif; ?>
						<?php if ($processo->arquivo_documentacao_1): ?>
							<li role="presentation">
								<a href="#arquivo_documentacao_1" aria-controls="arquivo_documentacao_1" role="tab"
								   data-toggle="tab">
									<strong><?php echo($processo->nome_documentacao_1 ?? 'Documentação 1'); ?></strong>
								</a>
							</li>
						<?php endif; ?>
						<?php if ($processo->arquivo_documentacao_2): ?>
							<li role="presentation">
								<a href="#arquivo_documentacao_2" aria-controls="arquivo_documentacao_2" role="tab"
								   data-toggle="tab">
									<strong><?php echo($processo->nome_documentacao_2 ?? 'Documentação 2'); ?></strong>
								</a>
							</li>
						<?php endif; ?>
					</ul>

					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="orientacoes_gerais">
							<div class="row">
								<div class="col-xs-12">
									<br>
									<p style="text-indent: 30px;">
										<?php echo nl2br($processo->orientacoes_gerais); ?>
									</p>
								</div>
							</div>
						</div>
						<?php if ($processo->arquivo_processo_1): ?>
							<div role="tabpanel" class="tab-pane" id="arquivo_processo_1">
								<br>
								<div class="row">
									<div class="col-xs-12">
										<iframe
											src="https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/pdf/' . convert_accented_characters($processo->arquivo_processo_1)); ?>"
											style="width:100%; height:600px; margin:0;" frameborder="0"></iframe>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<?php if ($processo->arquivo_processo_2): ?>
							<div role="tabpanel" class="tab-pane" id="arquivo_processo_2">
								<br>
								<div class="row">
									<div class="col-xs-12">
										<iframe
											src="https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/pdf/' . convert_accented_characters($processo->arquivo_processo_2)); ?>"
											style="width:100%; height:600px; margin:0;" frameborder="0"></iframe>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<?php if ($processo->arquivo_documentacao_1): ?>
							<div role="tabpanel" class="tab-pane" id="arquivo_documentacao_1">
								<br>
								<div class="row">
									<div class="col-xs-12">
										<iframe
											src="https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/pdf/' . convert_accented_characters($processo->arquivo_documentacao_1)); ?>"
											style="width:100%; height:600px; margin:0;" frameborder="0"></iframe>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<?php if ($processo->arquivo_documentacao_2): ?>
							<div role="tabpanel" class="tab-pane" id="arquivo_documentacao_2">
								<br>
								<div class="row">
									<div class="col-xs-12">
										<iframe
											src="https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/pdf/' . convert_accented_characters($processo->arquivo_documentacao_2)); ?>"
											style="width:100%; height:600px; margin:0;" frameborder="0"></iframe>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>

<?php else: ?>
	<span class="tools pull-left">
        <a href="#" class="fa fa-question-circle disabled" style="margin-right: 10px; margin-left: 0 !important;"></a>
    </span>
<?php endif; ?>
