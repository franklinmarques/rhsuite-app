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
                <!-- <li>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modal-ajuda">
                        <i class="fa fa-question-circle"></i>
                        <span>Ajuda</span>
                    </a>
                </li> -->
                <li>
                    <a href="<?php echo site_url('atividades'); ?>">
                        <i class="fa fa-calendar"></i>
                        <span>Lista de Pendências</span>
                    </a>
                </li>

                <?php if (in_array($this->session->userdata('tipo'), array('administrador'))) : ?>
                    <li class="sub-menu">
                        <a href="javascript:;"
                           class="<?= (in_array($this->uri->rsegment(2), array('biblioteca', 'novabiblioteca')) ? 'active' : ''); ?>">
                            <i class="fa fa-book"></i>
                            <span>Biblioteca</span>
                        </a>
                        <ul class="sub">
                            <li class="<?= (in_array($this->uri->rsegment(2), array('novabiblioteca')) ? 'active' : ''); ?>">
                                <a href="<?php echo site_url('home/novabiblioteca'); ?>">Adicionar</a>
                            </li>
                            <li class="<?= (in_array($this->uri->rsegment(2), array('biblioteca')) ? 'active' : ''); ?>">
                                <a href="<?php echo site_url('home/biblioteca'); ?>">Gerenciar</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;"
                           class="<?= (in_array($this->uri->rsegment(2), array('empresas', 'cursosempresa', 'novocursoempresa', 'novaempresa', 'editarempresa')) ? 'active' : ''); ?>">
                            <i class="fa fa-institution"></i>
                            <span>Empresas</span>
                        </a>
                        <ul class="sub">
                            <li class="<?= (in_array($this->uri->rsegment(2), array('novaempresa')) ? 'active' : ''); ?>">
                                <a href="<?php echo site_url('home/novaempresa'); ?>">Adicionar</a></li>
                            <li class="<?= (in_array($this->uri->rsegment(2), array('empresas', 'cursosempresa', 'novocursoempresa', 'editarempresa')) ? 'active' : ''); ?>">
                                <a href="<?php echo site_url('home/empresas'); ?>">Gerenciar</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (in_array($this->session->userdata('tipo'), array('empresa'))) : ?>
                    <li class="sub-menu">
                        <a href="javascript:void(0);"<?= (in_array($this->uri->rsegment(2), array('home', 'funcionarios', 'cursosfuncionario', 'novocursofuncionario', 'novofuncionario', 'editarfuncionario')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-users"></i>
                            <span>Recursos Humanos</span>
                        </a>
                        <ul class="sub">
                            <li class="<?= (in_array($this->uri->rsegment(2), array('novofuncionario')) ? 'active' : ''); ?>">
                                <a href="<?php echo site_url('home/novofuncionario'); ?>">Adicionar funcionário</a></li>
                            <li class="<?= (in_array($this->uri->rsegment(2), array('funcionarios', 'cursosfuncionario', 'novocursofuncionario', 'editarfuncionario')) ? 'active' : ''); ?>">
                                <a href="<?php echo site_url('home/funcionarios'); ?>">Gerenciar funcionários</a></li>
                            <li class="<?= (in_array($this->uri->rsegment(2), array('importarfuncionario')) ? 'active' : ''); ?>">
                                <a href="<?php echo site_url('funcionario/importarFuncionario'); ?>">Importar
                                    funcionários</a></li>

                            <li class="<?php echo(in_array($this->uri->rsegment(2), array('estruturas')) ? 'active' : ''); ?>">
                                <a href="<?php echo site_url('home/estruturas'); ?>">Gerenciar Estrutura
                                    Organizacional</a></li>
                            <li class="<?php echo(in_array($this->uri->rsegment(2), array('cargo_funcao')) ? 'active' : ''); ?>">
                                <a href="<?php echo site_url('home/cargo_funcao'); ?>">Gerenciar Cargos/Funções</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (in_array($this->session->userdata('tipo'), array('empresa'))) : ?>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-user-plus"></i>
                            <span>Processos Seletivos</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('recrutamento_modelos'); ?>">Modelos para Teste de
                                    Seleção</a></li>
                            <li><a href="<?php echo site_url('recrutamento'); ?>">Gestão de Processos Seletivos</a></li>
                            <li><a href="<?php echo site_url('recrutamento_candidatos'); ?>">Gerenciar Candidatos</a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-user-times"></i>
                            <span>Processos de Desligamento</span>
                        </a>
                        <ul class="sub">
                            <li ><a href="<?php //echo site_url('manutencao'); ?>">Modelos Entrevistas Desligamento</a></li>
                            <li ><a href="<?php //echo site_url('manutencao'); ?>">Entrevistas de Desligamentos</a></li>
                        </ul>
                    </li> -->
                <?php endif; ?>

                <?php if (in_array($this->session->userdata('tipo'), array('administrador', 'empresa'))) : ?>
                    <li class="sub-menu">
                        <a href="javascript:;"<?= (in_array($this->uri->rsegment(2), array('cursos', 'paginascurso', 'novapaginacurso', 'editarpaginacurso', 'novocurso', 'editarcurso')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-graduation-cap"></i>
                            <span>Programas de Capacitação</span>
                        </a>
                        <ul class="sub">
                            <li<?= (in_array($this->uri->rsegment(2), array('novocurso')) ? ' class="active"' : ''); ?>>
                                <!--<a href="<?php //echo site_url('home/novocurso');                                                     ?>">Adicionar treinamento</a></li>-->
                                <a href="<?php echo site_url('ead/cursos/novo'); ?>">Adicionar treinamento</a></li>
                            <li<?= (in_array($this->uri->rsegment(2), array('cursos', 'paginascurso', 'novapaginacurso', 'editarpaginacurso', 'editarcurso')) ? ' class="active"' : ''); ?>>
                                <!--<a href="<?php //echo site_url('home/cursos');                                                     ?>">Gerenciar treinamentos</a></li>-->
                                <a href="<?php echo site_url('ead/cursos'); ?>">Gerenciar treinamentos</a></li>
                            <li<?= (in_array($this->uri->rsegment(2), array('status')) ? ' class="active"' : ''); ?>>
                                <!--<a href="<?php //echo site_url('home/cursos');                                                     ?>">Gerenciar treinamentos</a></li>-->
                                <a href="<?php echo site_url('ead/funcionarios'); ?>">Gerenciar alocação
                                    treinamentos</a>
                            </li>
                        </ul>
                    </li>
                    <li<?= (in_array($this->uri->rsegment(2), array('entrada')) ? ' class="active"' : ''); ?>>
                        <a href="<?php echo site_url('email/entrada'); ?>">
                            <i class="fa fa-envelope"></i>
                            <span>Mensagens Internas</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (in_array($this->session->userdata('tipo'), array('empresa', 'administrador'))): ?>
                    <li class="sub-menu">
                        <a href="javascript:;"<?= (in_array($this->uri->rsegment(1), array('documento')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-files-o"></i>
                            <span>Gestão de Documentos</span>
                        </a>
                        <ul class="sub">
                            <?php if (in_array($this->session->userdata('tipo'), array('administrador'))): ?>
                                <li>
                                    <a href="javascript: void(0);">
                                        <i class="fa fa-list"></i>
                                        Tipos
                                    </a>
                                    <ul class="menu">
                                        <li>
                                            <a href="<?php echo site_url('tipo/novo'); ?>">
                                                Adicionar
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('tipo/gerenciar'); ?>">
                                                Gerenciar
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a href="javascript: void(0);"<?= (in_array($this->uri->rsegment(1), array('documento')) && in_array($this->uri->rsegment(2), array('organizacao')) ? ' class="active"' : ''); ?>>
                                        <i class="fa fa-institution"></i>
                                        Docs. corporativos
                                    </a>
                                    <ul class="menu">
                                        <li>
                                            <a href="<?php echo site_url('documento/organizacao'); ?>"<?= (in_array($this->uri->rsegment(1), array('documento')) && in_array($this->uri->rsegment(2), array('organizacao')) && in_array($this->uri->rsegment(3), array('novo', '')) ? ' class="active"' : ''); ?>>
                                                Adicionar documentos
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('documento/organizacao/gerenciar'); ?>"<?= (in_array($this->uri->rsegment(1), array('documento')) && in_array($this->uri->rsegment(2), array('organizacao')) && in_array($this->uri->rsegment(3), array('gerenciar')) ? ' class="active"' : ''); ?>>
                                                Gerenciar documentos
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (in_array($this->session->userdata('tipo'), array('funcionario'))): ?>
                    <li class="sub-menu">
                        <a href="javascript:void(0);" class="active">
                            <i class="fa fa-files-o"></i>
                            <span>Gestão de Documentos</span>
                        </a>
                        <ul class="sub">
                            <li>
                                <a href="<?php echo site_url('documento/organizacao/gerenciar'); ?>"<?= (in_array($this->uri->rsegment(1), array('documento')) && in_array($this->uri->rsegment(2), array('organizacao')) && in_array($this->uri->rsegment(3), array('gerenciar')) ? ' class="active"' : ''); ?>>
                                    Documentos Organizacionais
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('documento/colaborador/gerenciar/' . $this->session->userdata('id')); ?>"<?= (in_array($this->uri->rsegment(1), array('documento')) && in_array($this->uri->rsegment(2), array('colaborador')) && in_array($this->uri->rsegment(3), array('gerenciar')) ? ' class="active"' : ''); ?>>
                                    Meus Documentos
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (in_array($this->session->userdata('tipo'), array('administrador', 'empresa'))): ?>
                    <!--<li class="sub-menu">
                                                <a href="javascript:;"<?= (in_array($this->uri->rsegment(2), array()) ? ' class="active"' : ''); ?>>
                                                    <i class="fa fa-street-view"></i>
                                                    <span>Captação de Colaboradores</span>
                                                </a>
                                                <ul class="sub">
                                                    <li><a href="<?php //echo site_url('manutencao');                                                                 ?>">Período de experiência</a></li>
                                                    <li><a href="<?php //echo site_url('manutencao');                                                                 ?>">Prog. Integração de Colaboradores</a></li>
                                                    <li><a href="<?php //echo site_url('manutencao');                                                                 ?>">Processo de desligamento</a></li>
                                                    <li><a href="<?php //echo site_url('manutencao');                                                                 ?>">Recrutamento e seleção</a></li>
                                                </ul>
                                        </li>-->
                <?php endif; ?>

                <?php if (!in_array($this->session->userdata('tipo'), array('administrador', 'funcionario'))): ?>
                    <li class="sub-menu">
                        <a href="javascript:;" class="active">
                            <i class="fa fa-institution"></i>
                            <!-- span>Avaliação de Desempenho</span> -->
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
                            <li><a href="<?php echo site_url('avaliacaoexp_avaliados/status/2'); ?>">Status Avaliações
                                    Experiência</a></li>
                            <li><a href="<?php echo site_url('avaliacaoexp'); ?>">Avaliações Periódicas Desempenho</a>
                            </li>
                            <li style="border-bottom: solid 1px rgba(255,255,255,0.2);"><a
                                        href="<?php echo site_url('avaliacaoexp_avaliados/status/1'); ?>">Status
                                    Avaliações Periódicas</a></li>
                            <li style="border-bottom: solid 1px rgba(255,255,255,0.2);"><a
                                        href="<?php echo site_url('home/funcionarios'); ?>">PDIs - Planos Desenv.
                                    Individuais</a></li>
                        </ul>
                        <!--ul class="sub">
                                <li><a href="<?php //echo site_url('home/paginaAvaliacao');                           ?>">Gerenciar avaliações</a></li>
                                <li><a href="<?php //echo site_url('manutencao');                           ?>">Gestão de clima organizacional</a></li>
                                <li><a href="<?php //echo site_url('manutencao');                           ?>">Avaliação de desempenho</a></li>
                                <li><a href="<?php //echo site_url('manutencao');                           ?>">Processo de coaching</a></li>
                                <li><a href="<?php //echo site_url('manutencao');                           ?>">Gestão de talentos</a></li>
                                <li><a href="<?php //echo site_url('manutencao');                           ?>">Consultoria interna de RH</a></li>
                            </ul-->
                    </li>
                    <!--<li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-list-alt"></i>
                            <span>Organograma</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php //echo site_url('manutencao');                           ?>">Adicionar</a></li>
                            <li><a href="<?php //echo site_url('manutencao');                           ?>">Gerenciar</a></li>
                        </ul>
                    </li>-->
                <?php endif; ?>

                <?php if (!in_array($this->session->userdata('tipo'), array('administrador', 'funcionario'))): ?>
                    <li class="sub-menu">
                        <a href="javascript:;"<?= (in_array($this->uri->rsegment(1), array('pesquisa', 'pesquisa_modelos')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-list-ol"></i>
                            <!-- span>Avaliação de Desempenho</span> -->
                            <span>Gestão de Pesquisas</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('pesquisa/clima'); ?>">Pesquisa de Clima Organizacional</a>
                            </li>
                            <li><a href="<?php echo site_url('pesquisa/personalidade'); ?>">Pesquisa de
                                    Personalidade</a>
                            </li>
                            <li><a href="<?php echo site_url('pesquisa/perfil'); ?>">Pesquisa de Perfil Profissional</a>
                            </li>
                            <li><a href="<?php echo site_url('pesquisa_modelos'); ?>">Modelos de Pesquisa</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (in_array($this->session->userdata('tipo'), array('funcionario'))): ?>
                    <li class="sub-menu">
                        <a href="javascript:;" class="active">
                            <i class="fa fa-graduation-cap"></i>
                            <span>Gestão de Treinamentos</span>
                        </a>
                        <ul class="sub">
                            <li<?php (in_array($this->uri->rsegment(2), array('')) ? ' class="active"' : ''); ?>>
                                <a href="<?php echo site_url('ead/treinamento'); ?>">Meus treinamentos</a>
                            </li>
                        </ul>
                    </li>
                    <li<?= (in_array($this->uri->rsegment(2), array('entrada')) ? ' class="active"' : ''); ?>>
                        <a href="<?php echo site_url('email/entrada'); ?>">
                            <i class="fa fa-envelope"></i>
                            <span>Mensagens</span>
                        </a>
                    </li>
                    <li class="sub-menu">
                        <a href="javascript:;" class="active">
                            <i class="fa fa-institution"></i>
                            <span>Gestão de Desempenho</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('avaliacaoexp_avaliador/periodo'); ?>">Avaliação Período
                                    Experiência</a></li>
                            <?php if (in_array($this->session->userdata('nivel'), array(7, 8, 9, 10, 11))): ?>
                                <li><a href="<?php echo site_url('avaliacaoexp_avaliados/status/2'); ?>">Status
                                        Avaliações Experiência</a></li>
                            <?php endif; ?>
                        </ul>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('avaliacaoexp_avaliador/desempenho'); ?>">Avaliações
                                    Periódicas Desempenho</a></li>
                            <?php if (in_array($this->session->userdata('nivel'), array(7, 8, 9, 10, 11))): ?>
                                <li style="border-bottom: solid 1px rgba(255,255,255,0.2);"><a
                                            href="<?php echo site_url('avaliacaoexp_avaliados/status/1'); ?>">Status
                                        Avaliações Periódicas</a></li>
                            <?php endif; ?>
                        </ul>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('competencias/avaliador'); ?>">Avaliação Por
                                    Competências</a></li>
                        </ul>
                    </li>

                    <li class="sub-menu">
                        <a href="javascript:;" class="active">
                            <i class="fa fa-institution"></i>
                            <!-- span>Avaliação de Desempenho</span> -->
                            <span>Gestão de Pesquisas</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('pesquisa_clima'); ?>">Clima Organizacional</a>
                            </li>
                            <li><a href="<?php echo site_url('pesquisa_modelos/personalidade'); ?>">Personalidade -
                                    Eneagrama</a>
                            </li>
                            <li><a href="<?php echo site_url('pesquisa_perfil'); ?>">Perfil Profissional - Pessoal</a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (in_array($this->uri->rsegment(2), array('acessarcurso'))): ?>
                    <li class="sub-menu" id="menu-curso">
                        <a href="javascript:;"<?= (in_array($this->uri->rsegment(2), array('acessarcurso')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-book"></i>
                            <span><?php echo $curso->curso; ?></span>
                        </a>
                        <ul class="sub">
                            <?php foreach ($paginas->result() as $p => $rowpagina): ?>
                                <li<?= (in_array($p, array($this->uri->rsegment(4))) ? ' class="active"' : ''); ?>>
                                    <a target="_blank"
                                       href="<?php echo site_url('home/acessarcurso/' . $this->uri->rsegment(3) . '/' . $p); ?>">
                                        <?php echo $rowpagina->titulo; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php $hash_acesso = $this->session->userdata('hash_acesso'); ?>
                <?php if ($this->session->userdata('empresa') == 78): ?>
                    <?php if (in_array($this->session->userdata('tipo'), array('empresa', 'administrador'))): ?>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-history"></i>
                                <span>Gestão Operacional ST</span>
                            </a>
                            <ul class="sub">
                                <li<?php echo(in_array($this->uri->uri_string(), array('apontamento', 'apontamento_eventos', 'apontamento_contratos', 'apontamento_postos')) ? ' class="active"' : ''); ?>>
                                    <a href="<?php echo site_url('apontamento'); ?>">Apontamentos diários</a>
                                </li>
                            </ul>
                        </li>
                    <?php elseif (isset($hash_acesso['ST'])): ?>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-history"></i>
                                <span>Gestão Operacional ST</span>
                            </a>
                            <ul class="sub">
                                <?php if (in_array(401, $hash_acesso['ST'])): ?>
                                    <!--<li><a href="<?php // echo site_url('manutencao'); ?>">Gestão de Contratos</a></li>-->
                                <?php endif; ?>
                                <?php if (in_array(410, $hash_acesso['ST'])): ?>
                                    <li><a href="<?php echo site_url('apontamento'); ?>">Apontamentos diários</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (in_array($this->session->userdata('tipo'), array('empresa', 'administrador'))): ?>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-history"></i>
                                <span>Gestão Operacional CD</span>
                            </a>
                            <ul class="sub">
                                <li><a href="<?php echo site_url('cd/apontamento'); ?>">Apontamentos diários</a></li>
                            </ul>
                        </li>
                    <?php elseif (isset($hash_acesso['CD'])): ?>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-history"></i>
                                <span>Gestão Operacional CD</span>
                            </a>
                            <ul class="sub">
                                <?php if (in_array(610, $hash_acesso['CD'])): ?>
                                    <li><a href="<?php echo site_url('cd/apontamento'); ?>">Apontamentos diários</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (in_array($this->session->userdata('tipo'), array('empresa', 'administrador'))): ?>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-hospital-o"></i>
                                <span>Gestão Operacional PAPD</span>
                            </a>
                            <ul class="sub">
                                <li><a href="<?php echo site_url('papd/pacientes'); ?>">Gerenciar Pacientes</a></li>
                                <li><a href="<?php echo site_url('papd/atividades_deficiencias'); ?>">Gestão
                                        Atividades/Deficiências</a></li>
                                <li><a href="<?php echo site_url('papd/relatorios/medicao_mensal'); ?>">Relatórios</a>
                                </li>
                            </ul>
                        </li>
                    <?php elseif (isset($hash_acesso['PAPD'])): ?>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-hospital-o"></i>
                                <span>Gestão Operacional PAPD</span>
                            </a>
                            <ul class="sub">
                                <?php if (in_array(510, $hash_acesso['PAPD'])): ?>
                                    <li<?php echo($this->uri->ruri_string() == 'papd/atendimento' ? ' class="active"' : ''); ?>>
                                        <a href="<?php echo site_url('papd/atendimento'); ?>">Gerenciar Meus
                                            Atendimentos</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array(501, $hash_acesso['PAPD'])): ?>
                                    <li<?php echo($this->uri->ruri_string() == 'papd/pacientes' ? ' class="active"' : ''); ?>>
                                        <a href="<?php echo site_url('papd/pacientes'); ?>">Gerenciar Pacientes</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array(502, $hash_acesso['PAPD'])): ?>
                                    <li<?php echo($this->uri->ruri_string() == 'papd/atividades_deficiencias' ? ' class="active"' : ''); ?>>
                                        <a href="<?php echo site_url('papd/atividades_deficiencias'); ?>">Gestão
                                            Atividades/Deficiências</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array(503, $hash_acesso['PAPD'])): ?>
                                    <li<?php echo($this->uri->ruri_string() == 'papd/relatorios/medicao_mensal' ? ' class="active"' : ''); ?>>
                                        <a href="<?php echo site_url('papd/relatorios/medicao_mensal'); ?>">Relatórios</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (in_array($this->session->userdata('tipo'), array('administrador', 'empresa'))): ?>
                    <li class="sub-menu">
                        <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('backup', 'log')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-server"></i>
                            <span>Gestão da Plataforma</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('backup'); ?>">Backup/Restore de DBase</a></li>
                            <li><a href="<?php echo site_url('log_usuarios'); ?>">Log de usuários</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- sidebar menu end-->

    </div>
</aside>

<!--sidebar end-->