<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="ThemeBucket">
	<link rel="shortcut icon" href="<?= base_url("assets/images/favipn.ico"); ?>">

	<title>Rhsuite - Ferramentas Para RH</title>

	<!--Core CSS -->
	<link href="<?= base_url('assets/bs3/css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/css/bootstrap-reset.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet"/>

	<!-- Custom styles for this template -->
	<link href="<?= base_url('assets/css/style.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('assets/css/style-responsive.css'); ?>" rel="stylesheet"/>

	<!--Core js-->
	<script src="<?= base_url('assets/js/jquery.js'); ?>"></script>
	<script src="<?= base_url('assets/bs3/js/bootstrap.min.js'); ?>"></script>
	<!-- Just for debugging purposes. Don't actually copy this line! -->
	<!--[if lt IE 9]>
    <script src="<?= base_url('assets/js/ie8-responsive-file-warning.js'); ?>"></script>
    <![endif]-->
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<?php if (!empty($imagem_fundo)): ?>
		<style>
			.login-page {
				background-image: url(<?= '../imagens/usuarios/' . $imagem_fundo ?>);
			}
		</style>
	<?php endif; ?>

	<style>
		.background-video {
			position: fixed;
			right: 0;
			bottom: 0;
			min-width: 100%;
			min-height: 100%;
			width: auto;
			height: auto;
			z-index: -1000;
			background: url(<?= '../imagens/usuarios/' . $imagem_fundo ?>) no-repeat;
			background-size: cover;
		}

		.form-control-static {
			padding-top: 0;
		}

		.login-page form button {
			margin-top: 0;
			margin-bottom: 10px;
		}

		.login-page form input[type="text"], .login-page form input[type="textarea"], .login-page form .btn {
			padding: 9px 6px !important;
			height: auto !important;
			font-size: 14px;
		}
	</style>
</head>

<body class="login-page">

<video autoplay loop poster="<?= base_url('imagens/usuarios/' . $imagem_fundo) ?>" class="background-video">
	<source src="<?= base_url('videos/usuarios/' . $video_fundo) ?>" type="video/mp4">
</video>

<div id="cookie" class="text-danger text-center" style="background-color: #ffe; display: none;">
	Este site usa Cookies! Habilite o uso de cookies em seu navegador para o correto funcionamento do site.
</div>
<div class="container">
	<?php
	if ($logoempresa) {
		$logo = base_url('imagens/usuarios/' . $logo);
		$hr = '<hr style="margin-top:10px; margin-bottom:10px;"/>';
	} else {
		$logo = base_url('assets/img/Llogo-rhsuite.jpg');
		$cabecalho = '';
		$hr = '';
	}
	?>
	<div style="width: 100%; max-width: 400px; margin: 0 auto;">
		<div align="center">
			<img src="<?php echo $logo; ?>" style="width: auto; max-height: 100px; margin-bottom: 3%;">
			<h4 style="color: #111343; text-shadow: 1px 1px 1px rgba(255,255,255,0.5);">
				<strong><?php echo $cabecalho; ?></strong></h4>
		</div>
	</div>
	<div class="login-wrapper">
		<!-- BEGIN alert -->
		<div id="alert" style="margin: 10px auto;"></div>
		<!-- END alert -->
		<!-- BEGIN Login Form -->
		<?php echo form_open('login/registrarApontamento', 'data-aviso="alert" id="form_apontamento_hora" class="ajax-simple" autocomplete="off"'); ?>
		<input type="hidden" name="token" value="<?= $token; ?>">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h4>Apontamento de horas</h4>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">Usuário:</label>
					<div class="col-sm-10">
						<p class="form-control-static"><?= $nome; ?></p>
					</div>
				</div>
				<div class="form-group">
					<div class="radio">
						<label>
							<input type="radio" name="turno_evento" value="E" checked> Início de atividades
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="turno_evento" value="S"> Término de atividades
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="modo_cadastramento" value="M">
							Apontamento manual
						</label>
					</div>
				</div>
				<div class="row manual" style="display: none;">
					<div class="col-sm-7">
						<label class="control-label">Data</label>
						<input type="text" name="data" placeholder="dd/mm/aaaa" class="form-control text-center date">
					</div>
					<div class="col-sm-5">
						<label class="control-label">Hora</label>
						<input type="text" name="hora" placeholder="hh:mm" class="form-control text-center time">
					</div>
				</div>
				<div class="form-group manual" style="display: none;">
					<label class="control-label">Justificativa</label>
					<textarea name="justificativa" class="form-control" rows="5"></textarea>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<a href="<?= site_url($login); ?>" class="btn btn-primary  btn-block"><i
								class="fa fa-long-arrow-left"></i> Voltar ao portal</a>
					</div>
					<div class="col-sm-6">
						<button type="submit" class="btn btn-primary btn-block">Apontar evento</button>
					</div>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>

		<a class="btn btn-primary form-control" style="box-shadow: 1px 2px 4px rgba(0, 0, 0, .15);"
		   href="vagas">
			Consultar vagas | Cadastrar currículo
		</a>
		<br>
		<?php if ($visualizacao_pilula_conhecimento): ?>
			<button type="button" class="btn btn-primary form-control"
					style="margin-top: 3px; box-shadow: 1px 2px 4px rgba(0, 0, 0, .15);"
					data-toggle="modal" data-target="#modal_pilulas_conhecimento">
				Pílulas de Conhecimento
			</button>
		<?php endif; ?>

	</div>


	<div id="modal_apontamento_horas" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">Evento registrado com sucesso</h3>
				</div>
				<div class="modal-body">
					<div class="row form-group">
						<label class="col-sm-4 control-label"><strong>Colaborador(a):</strong></label>
						<div class="col-sm-8">
							<p id="modal_apontamento_horas_usuario" class="form-control-static"></p>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-4 control-label">
							<strong><span id="modal_apontamento_horas_turno"></span> das atividades:</strong>
						</label>
						<div class="col-sm-8">
							<p id="modal_apontamento_horas_data_hora" class="form-control-static"></p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="modal_pilulas_conhecimento" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" style="float:right;" class="btn btn-default" data-dismiss="modal">Fechar
					</button>
					<h4 class="modal-title text-primary">
						<strong>Programa de Formação Continuada - Pílulas de Conhecimento</strong>
					</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="area_conhecimento" class="col-sm-3 text-primary control-label"><strong>Área
									de conhecimento</strong></label>
							<div class="col-sm-4">
								<?php echo form_dropdown('area_conhecimento', $area_conhecimento, '', 'id="area_conhecimento" class="form-control" autocomplete="off"'); ?>
							</div>
							<label for="tema" class="col-sm-1 text-primary control-label"><strong>Tema</strong></label>
							<div class="col-sm-4">
								<?php echo form_dropdown('tema', $tema, '', 'id="tema" class="form-control" autocomplete="off"'); ?>
							</div>
						</div>

						<div class="row form-group">
							<div class="col-xs-12">
								<div id="conteudo" class="embed-responsive embed-responsive-16by9"></div>
							</div>
						</div>
					</form>
					<div class="form-group">
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

</div>
<footer class="footer">
	<p style="text-align: center; color: rgb(21,24,96); text-shadow: -1px -1px 0 rgba(255,255,255,0.51);">Copyright
		&copy;
		PeopleNet In
		Education<br>
		<a href="mailto:contato@rhsuite.com.br" style="color: #151860;">contato@rhsuite.com.br</a> | <a
			href="mailto:contato@multirh.com.br" style="color: #151860;">contato@multirh.com.br</a>
	</p>
</footer>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="<?php echo base_url("assets/js/ajax/ajax.simple.js"); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>
	var tmpcookie = new Date();
	chkcookie = (tmpcookie.getTime() + '');
	document.cookie = "chkcookie=" + chkcookie + "; path=/";

	if (document.cookie.indexOf(chkcookie, 0) < 0) {
		$('#cookie').show();
	} else {
		$('#cookie').hide();
	}

	$('.date').mask('00/00/0000');
	$('.time').mask('00:00');

	$(document).ajaxComplete(function (event, jqXHR) {
		var retorno = jqXHR.responseJSON.retorno;
		if (retorno !== undefined && retorno === 1) {
			var data = jqXHR.responseJSON.data;
			$('#modal_apontamento_horas_usuario').html(data.usuario);
			$('#modal_apontamento_horas_turno').html(data.turno);
			$('#modal_apontamento_horas_data_hora').html(data.data_hora);
			$('#modal_apontamento_horas').modal('show');
		}
	});

	$('#modal_apontamento_horas').on('hide.bs.modal', function () {
		location.href = "<?php echo site_url($login); ?>";
	});


	$('#form_apontamento_hora [name="modo_cadastramento"]').on('change', function () {
		if (this.checked) {
			$('.manual').show();
		} else {
			$('.manual input, .manual textarea').val('');
			$('.manual').hide();
		}
	});

	$('#area_conhecimento').on('change', function () {
		var tema = $('#tema').val();
		$.ajax({
			'url': "<?php echo site_url('login/filtrarTemas') ?>",
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'area_conhecimento': this.value,
				'tema': tema
			},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
				} else {
					$('#tema').html($(json.tema).html());

					if ($('#tema').val() !== tema) {
						$('#tema').trigger('change');
					}
				}
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Erro ao montar a estrutura');
			}
		});
	});


	$('#tema').on('change', function () {
		$.ajax({
			'url': "<?php echo site_url('login/mostrarPilulaConhecimento') ?>",
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'tema': this.value
			},
			'success': function (json) {
				if (json.erro) {
					alert(json.erro);
				} else if (json.conteudo) {
					$('#conteudo').html($(json.conteudo).html());
				} else {
					$('#conteudo').html('');
				}
			},
			'error': function (jqXHR, textStatus, errorThrown) {
				alert('Erro ao montar a estrutura');
			}
		});
	});


</script>

</body>
</html>
