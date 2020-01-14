<?php
$uri = $this->uri->rsegment(2);
$toggleMenu = $uri === 'acessarcurso' ? 'hide-left-bar' : '';
$hash_acesso = $this->session->userdata('hash_acesso');
?>

<!--sidebar start-->
<aside>
	<div id="sidebar" class="nav-collapse <?= $toggleMenu ?>">

		<!-- sidebar menu start-->

		<div class="leftside-navigation">
			<ul class="sidebar-menu" id="nav-accordion">
				<li>
					<a href="<?= site_url('home'); ?>"
					   class="<?= (in_array($this->uri->rsegment(2), array('', 'home')) ? 'active' : ''); ?>">
						<i class="fa fa-home"></i>
						<span>Início</span>
					</a>
				</li>

				<?php if (isset($hash_acesso['PD'])): ?>
					<li>
						<a href="<?php echo site_url('atividades'); ?>">
							<i class="fa fa-calendar"></i>
							<span>Lista de Pendências | To Do</span>
						</a>
					</li>
				<?php endif; ?>

				<li>
					<a href="<?php echo site_url('atividades_scheduler'); ?>">
						<i class="fa fa-address-book"></i>
						<span>Scheduler - Atividades</span>
					</a>
				</li>

				<li class="sub-menu">
					<a href="javascript:void(0);"
					   <?= (in_array($this->uri->rsegment(2), array('estruturas', 'cargo_funcao')) ? ' class="active"' : ''); ?>>
						<i class="fa fa-industry"> </i>
						<span>Estrutura Organizacional</span>
					</a>
					<ul class="sub">
						<li class="<?php echo(in_array($this->uri->rsegment(2), array('estruturas')) ? 'active' : ''); ?>">
							<a href="<?php echo site_url('estruturas'); ?>">Gerenciar Estruturas</a>
						</li>
						<li class="<?php echo(in_array($this->uri->rsegment(2), array('cargo_funcao')) ? 'active' : ''); ?>">
							<a href="<?php echo site_url('cargo_funcao'); ?>">Gerenciar Cargos/Funções</a>
						</li>
					</ul>
				</li>

				<?php if (isset($hash_acesso['JD'])): ?>
					<li>
						<a href="<?php echo site_url('jobDescriptor'); ?>"<?php echo($this->uri->rsegment(2) == 'jobDescriptor' ? 'class="active"' : ''); ?>>
							<i class="glyphicon glyphicon-briefcase"> </i>
							<span>Job Descriptor</span>
						</a>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['GP'])): ?>
					<li class="sub-menu">
						<a href="javascript:void(0);"
						   <?= (in_array($this->uri->rsegment(2), array('home', 'funcionarios', 'cursosfuncionario', 'novocursofuncionario', 'novofuncionario', 'editarfuncionario')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-users"></i>
							<span>Gestão de Pessoas</span>
						</a>
						<ul class="sub">
							<li class="<?= (in_array($this->uri->rsegment(2), array('novofuncionario')) ? 'active' : ''); ?>">
								<a href="<?php echo site_url('funcionario/novo'); ?>">Adicionar colaborador (CLT/PJ)</a>
							</li>
							<li class="<?= (in_array($this->uri->rsegment(2), array('funcionarios', 'cursosfuncionario', 'novocursofuncionario', 'editarfuncionario')) ? 'active' : ''); ?>">
								<a href="<?php echo site_url('home/funcionarios1'); ?>">Gerenciar colaboradores
									(CLT/PJ)</a>
							</li>
							<li class="<?= (in_array($this->uri->rsegment(2), array('importarfuncionario')) ? 'active' : ''); ?>">
								<a href="<?php echo site_url('funcionario/importarFuncionario'); ?>">Importar
									colaboradores (CLT)</a>
							</li>
							<li style="border-bottom: solid 1px rgba(255,255,255,0.2);">
								<a href="<?php echo site_url('gestaoDePessoal'); ?>">Relatórios de Gestão GP</a>
							</li>

							<li>
								<a href="<?php echo site_url('examePeriodico'); ?>">Relatório de Exames Periódicos</a>
							</li>
							<li>
								<a href="<?php echo site_url('usuarioAfastamento'); ?>">Relatório de Afastamentos</a>
							</li>
							<li>
								<a href="<?php echo site_url('usuarioDemissao'); ?>">Relatório de Demissões</a>
							</li>
							<li>
								<a href="<?php echo site_url('funcionario/aniversariantes'); ?>">Lista de
									Aniversariantes</a>
							</li>
							<li <?= (in_array($this->uri->rsegment(2), array('status')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('ead/funcionarios'); ?>">Gerenciar alocação
									treinamentos</a>
							</li>
							<li><a href="<?php echo site_url('avaliacaoexp_avaliados/status/2'); ?>">Status Avaliações
									Experiência</a></li>
							<li style="border-bottom: solid 1px rgba(255,255,255,0.2);"><a
									href="<?php echo site_url('avaliacaoexp_avaliados/status/1'); ?>">Status
									Avaliações
									Periódicas</a></li>

							<li style="border-bottom: solid 1px rgba(255,255,255,0.2);"><a
									href="<?php echo site_url('home/funcionarios'); ?>">PDIs - Planos Desenv.
									Individuais</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['PS'])): ?>
					<?php
					$this->db->select('depto, nivel_acesso');
					$this->db->where('id', $this->session->userdata('id'));
					$usuariox = $this->db->get('usuarios')->row();
					if (in_array($usuariox->nivel_acesso, array(0, 1, 4, 7, 8, 9)) or $usuariox->depto == 'Gestão de Pessoas'): ?>
						<li class="sub-menu">
							<a href="javascript:;">
								<i class="fa fa-user-plus"></i>
								<span>Gestão Processos Seletivos</span>
							</a>
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
						</li>
					<?php endif; ?>
				<?php endif; ?>

				<li class="sub-menu">
					<a href="javascript:;"
					   <?= (in_array($this->uri->rsegment(2), array('faltasAtrasos')) ? ' class="active"' : ''); ?>>
						<i class="fa fa-sticky-note-o"></i>
						<span>Gestão Frequências/Eventos</span>
					</a>
					<ul class="sub">
						<li <?= (in_array($this->uri->rsegment(2), array('faltasAtrasos')) ? ' class="active"' : ''); ?>>
							<a href="<?php echo site_url('faltasAtrasos'); ?>">Apontamento Manual</a></li>
						<li <?= (in_array($this->uri->rsegment(2), array('controleFrequencias')) ? ' class="active"' : ''); ?>>
							<a href="<?php echo site_url('controleFrequencias'); ?>">Apontamento Web</a></li>
					</ul>
				</li>

				<?php if (isset($hash_acesso['PC'])): ?>
					<li class="sub-menu">
						<a href="javascript:;"
						   <?= (in_array($this->uri->rsegment(2), array('cursos', 'paginascurso', 'novapaginacurso', 'editarpaginacurso', 'novocurso', 'editarcurso')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-graduation-cap"></i>
							<span>Programas de Capacitação</span>
						</a>
						<ul class="sub">
							<li <?= (in_array($this->uri->rsegment(2), array('novocurso')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('ead/cursos/novo'); ?>">Adicionar treinamento</a></li>
							<li <?= (in_array($this->uri->rsegment(2), array('cursos', 'paginascurso', 'novapaginacurso', 'editarpaginacurso', 'editarcurso')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('ead/cursos'); ?>">Gerenciar treinamentos</a></li>
							<li <?= (in_array($this->uri->rsegment(2), array('cursos_usuarios', 'cursos_usuarios/novo', 'cursos_usuarios/editar')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('ead/clientes/'); ?>">Gerenciar Treinamentos Clientes</a>
							</li>
							<li <?= (in_array($this->uri->rsegment(2), array('pilulasConhecimento')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('ead/pilulasConhecimento/'); ?>">Gerenciar Pílulas
									Conhecimento</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['DO'])): ?>
					<li class="sub-menu">
						<a href="javascript:;"
						   <?= (in_array($this->uri->rsegment(1), array('documento')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-files-o"></i>
							<span>Gestão de Documentos</span>
						</a>
						<ul class="sub">
							<li>
								<a href="javascript: void(0);"
								   <?= (in_array($this->uri->rsegment(1), array('documento')) && in_array($this->uri->rsegment(2), array('organizacao')) ? ' class="active"' : ''); ?>>
									<i class="fa fa-institution"></i>
									Docs. corporativos
								</a>
								<ul class="menu">
									<li>
										<a href="<?php echo site_url('documento/organizacao'); ?>"
										   <?= (in_array($this->uri->rsegment(1), array('documento')) && in_array($this->uri->rsegment(2), array('organizacao')) && in_array($this->uri->rsegment(3), array('novo', '')) ? ' class="active"' : ''); ?>>
											Adicionar documentos
										</a>
									</li>
									<li>
										<a href="<?php echo site_url('documento/organizacao/gerenciar'); ?>"
										   <?= (in_array($this->uri->rsegment(1), array('documento')) && in_array($this->uri->rsegment(2), array('organizacao')) && in_array($this->uri->rsegment(3), array('gerenciar')) ? ' class="active"' : ''); ?>>
											Gerenciar documentos
										</a>
									</li>
									<li class="divider"></li>
								</ul>
							</li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['AS'])): ?>
					<li class="sub-menu">
						<a href="javascript:void(0);"
						   <?= (in_array($this->uri->rsegment(2), array('home', 'funcionarios', 'cursosfuncionario', 'novocursofuncionario', 'novofuncionario', 'editarfuncionario')) ? ' class="active"' : ''); ?>>
							<i class="glyphicons glyphicons-charts"> </i>
							<span>Ferramentas de Assessment</span>
						</a>
						<ul class="sub">
							<li>
								<a href="<?php echo site_url('pesquisa_modelos'); ?>">Modelos de Pesquisa/Assessment</a>
							</li>
							<li>
								<a href="<?php echo site_url('pesquisa/eneagrama'); ?>">Personalidade - Eneagrama</a>
							</li>
							<li>
								<a href="<?php echo site_url('pesquisa/quati'); ?>">Personalidade - Jung</a>
							</li>
							<li>
								<a href="<?php echo site_url('pesquisa/lifo'); ?>">Personalidade - Estilos LIFO</a>
							</li>
							<li>
								<a href="<?php echo site_url('home/manutencao'); ?>">Potencial - NineBox</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['DE'])): ?>
					<li class="sub-menu">
						<a href="javascript:;">
							<i class="glyphicons glyphicons-stats"> </i>
							<span>Gestão de Desempenho</span>
						</a>
						<ul class="sub">
							<li><a href="<?php echo site_url('competencias/cargos'); ?>">Mapeamento de Competências</a>
							</li>
							<li style="border-bottom: solid 1px rgba(255,255,255,0.2);"><a
									href="<?php echo site_url('competencias/avaliacao'); ?>">Avaliações por
									Competências</a></li>
							<li><a href="<?php echo site_url('avaliacaoexp_modelos'); ?>">Modelos de Avaliações</a></li>
							<li><a href="<?php echo site_url('home/funcionarios'); ?>">Avaliações Período
									Experiência</a></li>
							<li><a href="<?php echo site_url('avaliacaoexp'); ?>">Avaliações Periódicas Desempenho</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['PE'])): ?>
					<li class="sub-menu">
						<a href="javascript:;"
						   <?= (in_array($this->uri->rsegment(1), array('pesquisa', 'pesquisa_modelos')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-list-ol"></i>
							<span>Gestão de Pesquisas</span>
						</a>
						<ul class="sub">
							<li>
								<a href="<?php echo site_url('pesquisa/clima'); ?>">Pesquisa de Clima Organizacional</a>
							</li>
							<li>
								<a href="<?php echo site_url('pesquisa/perfil'); ?>">Pesquisa de Perfil Profissional</a>
							</li>
							<li>
								<a href="<?php echo site_url('pesquisa_modelos'); ?>">Modelos de Pesquisa/Assessment</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['FA'])): ?>
					<li class="sub-menu">
						<a href="javascript:;">
							<i class="fa fa-institution"></i>
							<span>Gestão de Facilities</span>
						</a>
						<ul class="sub">
							<li>
								<a href="<?php echo site_url('facilities/empresas'); ?>">Itens de
									Vistoria/Manutenção</a>
							</li>
							<li>
								<a href="<?php echo site_url('facilities/estruturas'); ?>">Cadastro Estrutural</a>
							</li>
							<li>
								<a href="<?php echo site_url('facilities/modelos'); ?>">Modelos de
									Vistorias/Manutenções</a>
							</li>
							<li>
								<a href="<?php echo site_url('facilities/vistorias'); ?>">Gerenciar Vistorias</a>
							</li>
							<li>
								<a href="<?php echo site_url('facilities/manutencoes'); ?>">Gerenciar Manutenções</a>
							</li>
							<li>
								<a href="<?php echo site_url('facilities/contasMensais'); ?>">Contas Mensais
									Facilities</a>
							</li>
							<li>
								<a href="<?php echo site_url('facilities/fornecedoresPrestadores'); ?>">Gerenciar
									fornecedores</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['OS'])): ?>
					<li>
						<a href="<?php echo site_url('facilities/ordensServico'); ?>">
							<i class="fa fa-sticky-note-o"></i>
							<span>Gerenciar Ordens de Serviço</span>
						</a>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['GC'])): ?>
					<li class="sub-menu">
						<a href="javascript:;">
							<i class="fa fa-institution"></i>
							<span>Gestão Comercial</span>
						</a>
						<ul class="sub">
							<li>
								<a href="<?php echo site_url('icom/produtos'); ?>">Gerenciar produtos</a>
							</li>
							<li>
								<a href="<?php echo site_url('icom/clientes'); ?>">Gerenciar clientes</a>
							</li>
							<li>
								<a href="<?php echo site_url('icom/propostas'); ?>">Gerenciar propostas</a>
							</li>
							<li>
								<a href="<?php echo site_url('icom/contratos'); ?>">Gerenciar contratos</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['RG'])): ?>
					<li>
						<a href="<?php echo site_url('relatoriosGestao'); ?>">
							<i class="glyphicons glyphicons-list"> </i>
							<span>Relatórios de Gestão</span>
						</a>
					</li>
				<?php endif; ?>

				<li>
					<a href="<?php echo site_url('pj/colaboradores'); ?>">
						<i class="fa fa-user-plus"> </i>
						<span>Gestão de PJs</span>
					</a>
				</li>

				<?php if (in_array($this->uri->rsegment(2), array('acessarcurso'))): ?>
					<li class="sub-menu" id="menu-curso">
						<a href="javascript:;"
						   <?= (in_array($this->uri->rsegment(2), array('acessarcurso')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-book"></i>
							<span><?php echo $curso->curso; ?></span>
						</a>
						<ul class="sub">
							<?php foreach ($paginas->result() as $p => $rowpagina): ?>
								<li <?= (in_array($p, array($this->uri->rsegment(4))) ? ' class="active"' : ''); ?>>
									<a target="_blank"
									   href="<?php echo site_url('home/acessarcurso/' . $this->uri->rsegment(3) . '/' . $p); ?>">
										<?php echo $rowpagina->titulo; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endif; ?>

				<?php if ($this->session->userdata('empresa') == 78): ?>
					<li class="sub-menu">
						<a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-history"></i>
							<span>Gestão Operacional ST</span>
						</a>
						<ul class="sub">
							<li<?php echo(in_array($this->uri->uri_string(), array('apontamento', 'apontamento_eventos', 'apontamento_contratos', 'apontamento_postos')) ? ' class="active"' : ''); ?>>
								<a href="<?php echo site_url('apontamento'); ?>">Gerenciar apontamentos</a>
							</li>
							<li><a href="<?php echo site_url('requisicaoPessoal/st'); ?>">Requisição de pessoal</a>
							</li>
						</ul>
					</li>

					<li class="sub-menu">
						<a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-history"></i>
							<span>Gestão Operacional CD</span>
						</a>
						<ul class="sub">
							<li><a href="<?php echo site_url('cd/apontamento'); ?>">Gerenciar apontamentos</a></li>
							<li><a href="<?php echo site_url('requisicaoPessoal/cd'); ?>">Requisição de pessoal</a>
							</li>
						</ul>
					</li>

					<li class="sub-menu">
						<a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-history"></i>
							<span>Gestão Operacional EI</span>
						</a>
						<ul class="sub">
							<li><a href="<?php echo site_url('ei/apontamento'); ?>">Gerenciar apontamentos</a></li>
							<li><a href="<?php echo site_url('requisicaoPessoal/ei'); ?>">Requisição de pessoal</a>
							</li>
						</ul>
					</li>

					<li class="sub-menu">
						<a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-hospital-o"></i>
							<span>Gestão Operacional PAPD</span>
						</a>
						<ul class="sub">
							<li><a href="<?php echo site_url('papd/pacientes'); ?>">Gerenciar Pacientes</a></li>
							<li><a href="<?php echo site_url('papd/atividades_deficiencias'); ?>">Gerenciar
									Atividades/Deficiências</a></li>
							<li><a href="<?php echo site_url('papd/relatorios/medicao_mensal'); ?>">Relatório de
									medição
									(individual)</a>
							<li><a href="<?php echo site_url('papd/relatorios/medicao_consolidada'); ?>">Relatório
									de
									medição
									(equipe)</a>
							</li>
							<li><a href="<?php echo site_url('papd/relatorios/medicao_anual'); ?>">Relatório de
									medição
									(consolidado)</a>
							</li>
							<li><a href="<?php echo site_url('requisicaoPessoal/papd'); ?>">Gerenciar Requisição de
									Pessoal</a></li>
						</ul>
					</li>

					<li class="sub-menu">
						<a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-history"></i>
							<span>Gestão Operacional CDH</span>
						</a>
						<ul class="sub">
							<li><a href="<?php echo site_url('manutencao'); ?>">Gerenciar apontamentos</a></li>
							<li><a href="<?php echo site_url('manutencao'); ?>">Requisição de pessoal</a></li>
						</ul>
					</li>

					<li class="sub-menu">
						<a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-history"></i>
							<span>Gestão Operacional Libras</span>
						</a>
						<ul class="sub">
							<li><a href="<?php echo site_url('icom/sessoes'); ?>">Gerenciar eventos</a></li>
						</ul>
					</li>

					<li class="sub-menu">
						<a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-history"></i>
							<span>Gestão Operacional ICOM</span>
						</a>
						<ul class="sub">
							<li><a href="<?php echo site_url('icom/apontamento'); ?>">Gerenciar apontamentos</a></li>
							<li><a href="<?php echo site_url('manutencao'); ?>">Requisição de pessoal</a></li>
						</ul>
					</li>

					<!--<li class="sub-menu">
                        <a href="javascript:;"<?php /*echo(in_array($this->uri->rsegment(1), ['apontamento']) ? ' class="active"' : ''); */ ?>>
                            <i class="fa fa-history"></i>
                            <span>Gestão Operacional EMTU</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php /*echo site_url('emtu/apontamento'); */ ?>">Gerenciar apontamentos</a></li>
                            <li><a href="<?php /*echo site_url('manutencao'); */ ?>">Requisição de pessoal</a></li>
                        </ul>
                    </li>-->

					<li class="sub-menu">
						<a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-history"></i>
							<span>Gestão Operacional ADM-FIN</span>
						</a>
						<ul class="sub">
							<li><a href="<?php echo site_url('manutencao'); ?>">Gerenciar apontamentos</a></li>
							<li><a href="<?php echo site_url('manutencao'); ?>">Requisição de pessoal</a></li>
						</ul>
					</li>

					<li class="sub-menu">
						<a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-history"></i>
							<span>Gestão Operacional GExec</span>
						</a>
						<ul class="sub">
							<li><a href="<?php echo site_url('manutencao'); ?>">Gerenciar apontamentos</a></li>
							<li><a href="<?php echo site_url('manutencao'); ?>">Requisição de pessoal</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<?php if (isset($hash_acesso['PL'])): ?>
					<li class="sub-menu">
						<a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('backup', 'log')) ? ' class="active"' : ''); ?>>
							<i class="fa fa-server"></i>
							<span>Gestão da Plataforma</span>
						</a>
						<ul class="sub">
							<li class="<?php $this->uri->rsegment(1) == 'gestaoProcessos' ? 'active' : ''; ?>">
								<a href="<?php echo site_url('gestaoProcessos'); ?>">Gestão de Processos</a>
							</li>
							<!--<li>
                                <a href="<?php /*echo site_url('backup'); */ ?>">Backup/Restore de DBase</a>
                            </li>-->
							<li>
								<a href="<?php echo site_url('log_usuarios'); ?>">Log de usuários</a>
							</li>
						</ul>
					</li>
				<?php endif; ?>
			</ul>
		</div>

		<!-- sidebar menu end-->

	</div>
</aside>

<!--sidebar end-->
