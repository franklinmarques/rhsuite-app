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
                       class="<?= (in_array($this->uri->rsegment(2), array('', 'home')) ? 'active' : ''); ?>">
                        <i class="fa fa-home"></i>
                        <span>Início</span>
                    </a>
                </li>
                <!--<li>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modal-ajuda">
                        <i class="fa fa-question-circle"></i>
                        <span>Ajuda</span>
                    </a>
                </li>-->
                <li>
                    <a href="<?php echo site_url('atividades'); ?>">
                        <i class="fa fa-calendar"></i>
                        <span>Lista de Pendências</span>
                    </a>
                </li>

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

                <li class="sub-menu">
                    <a href="javascript:;"<?= (in_array($this->uri->rsegment(2), array('cursos', 'paginascurso', 'novapaginacurso', 'editarpaginacurso', 'novocurso', 'editarcurso')) ? ' class="active"' : ''); ?>>
                        <i class="fa fa-graduation-cap"></i>
                        <span>Programas de Capacitação</span>
                    </a>
                    <ul class="sub">
                        <li<?= (in_array($this->uri->rsegment(2), array('novocurso')) ? ' class="active"' : ''); ?>>
                            <a href="<?php echo site_url('ead/cursos/novo'); ?>">Adicionar treinamento</a>
                        </li>
                        <li<?= (in_array($this->uri->rsegment(2), array('cursos', 'paginascurso', 'novapaginacurso', 'editarpaginacurso', 'editarcurso')) ? ' class="active"' : ''); ?>>
                            <a href="<?php echo site_url('ead/cursos'); ?>">Gerenciar treinamentos</a>
                        </li>
                        <li<?= (in_array($this->uri->rsegment(2), array('status')) ? ' class="active"' : ''); ?>>
                            <a href="<?php echo site_url('ead/funcionarios'); ?>">Gerenciar alocação treinamentos</a>
                        </li>
                    </ul>
                </li>

                <!--<li<? /*= (in_array($this->uri->rsegment(2), array('entrada')) ? ' class="active"' : ''); */ ?>>
                    <a href="<?php /*echo site_url('email/entrada'); */ ?>">
                        <i class="fa fa-envelope"></i>
                        <span>Mensagens Internas</span>
                    </a>
                </li>-->

                <li class="sub-menu">
                    <a href="javascript:;"<?= (in_array($this->uri->rsegment(1), array('documento')) ? ' class="active"' : ''); ?>>
                        <i class="fa fa-files-o"></i>
                        <span>Gestão de Documentos</span>
                    </a>
                    <ul class="sub">
                        <li>
                            <a href="javascript: void(0);">
                                <i class="fa fa-list"></i>
                                Tipos
                            </a>
                            <ul class="menu">
                                <li>
                                    <a href="<?php echo site_url('tipo/novo'); ?>">Adicionar</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('tipo/gerenciar'); ?>">Gerenciar</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!--<li class="sub-menu">
                    <a href="javascript:;"<? /*= (in_array($this->uri->rsegment(2), array()) ? ' class="active"' : ''); */ ?>>
                        <i class="fa fa-street-view"></i>
                        <span>Captação de Colaboradores</span>
                    </a>
                    <ul class="sub">
                        <li>
                            <a href="<?php /*echo site_url('manutencao'); */ ?>">Período de experiência</a>
                        </li>
                        <li>
                            <a href="<?php /*echo site_url('manutencao'); */ ?>">Prog. Integração de Colaboradores</a>
                        </li>
                        <li>
                            <a href="<?php /*echo site_url('manutencao'); */ ?>">Processo de desligamento</a>
                        </li>
                        <li>
                            <a href="<?php /*echo site_url('manutencao'); */ ?>">Recrutamento e seleção</a>
                        </li>
                    </ul>
                </li>-->

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

                    <li class="sub-menu">
                        <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-history"></i>
                            <span>Gestão Operacional CD</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('cd/apontamento'); ?>">Apontamentos diários</a></li>
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
                            <li><a href="<?php echo site_url('papd/relatorios/medicao_mensal'); ?>">Relatório
                                    (individual)</a>
                            <li><a href="<?php echo site_url('papd/relatorios/medicao_consolidada'); ?>">Relatório
                                    (consolidado)</a>
                            <li><a href="<?php echo site_url('requisicaoPessoal/papd'); ?>">Gerenciar Requisição de
                                    Pessoal</a></li>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="sub-menu">
                    <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('backup', 'log')) ? ' class="active"' : ''); ?>>
                        <i class="fa fa-server"></i>
                        <span>Gestão da Plataforma</span>
                    </a>
                    <ul class="sub">
                        <li><a href="<?php echo site_url('gestaoProcessos'); ?>">Gestão de Processos</a></li>
<!--                        <li><a href="--><?php //echo site_url('backup'); ?><!--">Backup/Restore de DBase</a></li>-->
                        <li><a href="<?php echo site_url('log_usuarios'); ?>">Log de usuários</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- sidebar menu end-->

    </div>
</aside>

<!--sidebar end-->