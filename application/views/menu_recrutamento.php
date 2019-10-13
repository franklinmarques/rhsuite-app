<?php
$uri = $this->uri->rsegment(2);
$toggleMenu = $uri === 'acessarcurso' ? 'hide-left-bar' : '';
?>

<!--sidebar start-->
<aside>
	<div id="sidebar" class="nav-collapse <?= $toggleMenu ?>">

		<!-- sidebar menu start-->

		<div class="leftside-navigation">
			<ul class="sidebar-menu" id="nav-accordion">
				<li>
					<a href="<?= site_url('home'); ?>"
					   class="<?= (in_array($this->uri->rsegment(2), array('')) ? 'active' : ''); ?>">
						<i class="fa fa-home"></i>
						<span>Início</span>
					</a>
				</li>

				<li>
					<a href="<?php echo site_url('atividades'); ?>">
						<i class="fa fa-calendar"></i>
						<span>Lista de Pendências</span>
					</a>
				</li>

				<li>
					<a href="<?php echo site_url('atividades_scheduler'); ?>">
						<i class="fa fa-address-book"></i>
						<span>Scheduler - Atividades</span>
					</a>
				</li>

				<?php if (in_array($this->session->userdata('tipo'), array('selecionador'))) : ?>
					<?php
					$this->db->select('depto, nivel_acesso');
					$this->db->where('id', $this->session->userdata('id'));
					$usuariox = $this->db->get('usuarios')->row();
					?>
					<!--<li>
						<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-ajuda">
							<i class="fa fa-question-circle"></i>
							<span>Ajuda</span>
						</a>
					</li>-->
					<li class="sub-menu">
						<a href="javascript:;" class="active">
							<i class="fa fa-user-plus"></i>
							<span>Gestão Processos Seletivos</span>
						</a>
						<?php if ($this->session->userdata('tipo') == 'selecionador'): ?>

							<ul class="sub">
								<li><a href="<?php echo site_url('recrutamento_modelos'); ?>">Modelos de Testes
										Online</a>
								</li>
								<li><a href="<?php echo site_url('requisicaoPessoal_emails'); ?>">E-mails - De Apoio</a>
								</li>
								<li><a href="<?php echo site_url('recrutamento_candidatos'); ?>">Banco de Candidatos</a>
								<li><a href="<?php echo site_url('requisicaoPessoal'); ?>">Gerenciar Requisições
										Pessoal</a>
								<li><a href="<?php echo site_url('gestaoDeVagas'); ?>">Gerenciar Vagas Publicadas</a>
								</li>
								<li><a href="<?php echo site_url('vagas'); ?>" target="_blank">Visualizar Vagas
										Publicadas</a></li>
								<li><a href="<?php echo site_url('requisicaoPessoal_fontes'); ?>">Gerenciar
										fontes/aprovadores</a>
								</li>
								<li><a href="<?php echo site_url('requisicaoPessoal_candidatos'); ?>">Relatório de
										Gestão</a>
								</li>
								<li><a href="<?php echo site_url('requisicaoPessoal_estagios'); ?>">Texto e-mails
										apoio</a>
								</li>
							</ul>

						<?php else: ?>


							<ul class="sub">
								<li><a href="<?php echo site_url('recrutamento_modelos'); ?>">Modelos de Testes de
										Online</a></li>
								<!--                            <li><a href="-->
								<?php //echo site_url('recrutamento'); ?><!--">Processos Seletivos Online</a></li>-->
								<li style="border-bottom: solid 1px rgba(255,255,255,0.2);"><a
										href="<?php echo site_url('recrutamento_candidatos'); ?>">Banco de
										Candidatos</a></li>
								<?php if ($usuariox->depto != 'Gestão de Pessoas'): ?>
									<li><a href="<?php echo site_url('recrutamentoPresencial_cargos'); ?>">Processos
											Seletivos</a>
									</li>
								<?php endif; ?>
								<li><a href="<?php echo site_url('requisicaoPessoal'); ?>">Gerenciar Requisições
										Pessoal</a>
								</li>
								<li><a href="<?php echo site_url('requisicaoPessoal_fontes'); ?>">Gerenciar
										fontes/aprovadores</a>
								</li>
							</ul>

						<?php endif; ?>
					</li>
					<!--<li<? /*= (in_array($this->uri->rsegment(2), array('entrada')) ? ' class="active"' : ''); */ ?>>
                        <a href="<?php /*echo site_url('email/entrada'); */ ?>">
                            <i class="fa fa-envelope"></i>
                            <span>Mensagens Internas</span>
                        </a>
                    </li>-->
				<?php elseif (in_array($this->session->userdata('tipo'), array('candidato'))) : ?>
					<li class="sub-menu">
						<a href="javascript:;" class="active">
							<i class="fa fa-graduation-cap"></i>
							<span>Testes de Seleção</span>
						</a>
						<ul class="sub">
							<li<?= (in_array($this->uri->rsegment(2), array('matematica')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('recrutamento/testes/matematica'); ?>">
									<i class="glyphicons glyphicons-calculator"></i>
									<span>Testes de Matemática</span>
								</a>
							</li>
							<li<?= (in_array($this->uri->rsegment(2), array('raciocinio-logico')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('recrutamento/testes/raciocinio-logico'); ?>">
									<i class="fa fa-puzzle-piece"></i>
									<span>Testes de Raciocínio Lógico</span>
								</a>
							</li>
							<li<?= (in_array($this->uri->rsegment(2), array('portugues')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('recrutamento/testes/portugues'); ?>">
									<i class="fa fa-language"></i>
									<span>Testes de Português</span>
								</a>
							</li>
							<li<?= (in_array($this->uri->rsegment(2), array('lideranca')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('recrutamento/testes/lideranca'); ?>">
									<i class="glyphicons glyphicons-bullhorn"></i>
									<span>Testes de Liderança</span>
								</a>
							</li>
							<li<?= (in_array($this->uri->rsegment(2), array('perfil-personalidade')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('recrutamento/testes/perfil-personalidade'); ?>">
									<i class="glyphicons glyphicons-tie"></i>
									<span>Testes de Perfil-Personalidade</span>
								</a>
							</li>
							<li<?= (in_array($this->uri->rsegment(2), array('digitacao')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('recrutamento/testes/digitacao'); ?>">
									<i class="fa fa-keyboard-o"></i>
									<span>Testes de Digitação</span>
								</a>
							</li>
							<!--<li<? /*= (in_array($this->uri->rsegment(2), array('interpretacao')) ? ' class="active"' : ''); */ ?>>
                                <a href="<?php /*echo site_url('recrutamento/testes/interpretacao'); */ ?>">
                                    <i class="fa fa-lightbulb-o"></i>
                                    <span>Testes de Interpretação</span>
                                </a>
                            </li>-->
							<li<?= (in_array($this->uri->rsegment(2), array('entrevista')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('recrutamento/testes/entrevista'); ?>">
									<i class="glyphicons glyphicons-nameplate"></i>
									<span>Entrevista por Competências</span>
								</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>

				<li>
					<a href="<?php echo site_url('facilities/ordensServico'); ?>">
						<i class="fa fa-sticky-note-o"></i>
						<span>Gerenciar Ordens de Serviço</span>
					</a>
				</li>

			</ul>
		</div>

		<!-- sidebar menu end-->

	</div>
</aside>

<!--sidebar end-->
