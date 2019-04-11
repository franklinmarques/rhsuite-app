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
                    <a href="<?= site_url('home'); ?>" class="<?= (in_array($this->uri->rsegment(2), array('')) ? 'active' : ''); ?>">
                        <i class="fa fa-home"></i>
                        <span>Início</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modal-ajuda">
                        <i class="fa fa-question-circle"></i>
                        <span>Ajuda</span>
                    </a>
                </li>
                <?php if (in_array($this->session->userdata('tipo'), array('administrador'))) : ?>
                    <li class="sub-menu">
                        <a href="javascript:;" class="<?= (in_array($this->uri->rsegment(2), array('biblioteca', 'novabiblioteca')) ? 'active' : ''); ?>">
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
                        <a href="javascript:;" class="<?= (in_array($this->uri->rsegment(2), array('empresas', 'cursosempresa', 'novocursoempresa', 'novaempresa', 'editarempresa')) ? 'active' : ''); ?>">
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
                                <a href="<?php echo site_url('funcionario/importarFuncionario'); ?>">Importar funcionários</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (in_array($this->session->userdata('tipo'), array('administrador', 'empresa'))) : ?>
                    <li class="sub-menu">
                        <a href="javascript:;"<?= (in_array($this->uri->rsegment(2), array('cursos', 'paginascurso', 'novapaginacurso', 'editarpaginacurso', 'novocurso', 'editarcurso')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-graduation-cap"></i>
                            <span>Programas de Capacitação</span>
                        </a>
                        <ul class="sub">
                            <li<?= (in_array($this->uri->rsegment(2), array('novocurso')) ? ' class="active"' : ''); ?>><a
                                    href="<?php echo site_url('home/novocurso'); ?>">Adicionar treinamento</a></li>
                            <li<?= (in_array($this->uri->rsegment(2), array('cursos', 'paginascurso', 'novapaginacurso', 'editarpaginacurso', 'editarcurso')) ? ' class="active"' : ''); ?>>
                                <a href="<?php echo site_url('home/cursos'); ?>">Gerenciar treinamentos</a></li>
                        </ul>
                    </li>
                    <li<?= (in_array($this->uri->rsegment(2), array('entrada')) ? ' class="active"' : ''); ?>>
                        <a href="<?php echo site_url('email/entrada'); ?>">
                            <i class="fa fa-envelope"></i>
                            <span>Mensagens internas</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (!in_array($this->session->userdata('tipo'), array('funcionario'))) { ?>
                    <li class="sub-menu">
                        <a href="javascript:;"<?= (in_array($this->uri->rsegment(1), array('documento')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-files-o"></i>
                            <span>Políticas | Documentos</span>
                        </a>
                        <ul class="sub">
                            <?php
                            if (in_array($this->session->userdata('tipo'), array('empresa'))) {
                                ?>
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
                                <?php
                            }
                            if (in_array($this->session->userdata('tipo'), array('administrador'))) {
                                ?>
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
                                <?php
                            }
                            ?>
                        </ul>
                    </li>

                    <?php
                }
                if (in_array($this->session->userdata('tipo'), array('funcionario'))) {
                    ?>
                    <li class="sub-menu">
                        <a href="javascript:void(0);"<?= (in_array($this->uri->rsegment(1), array('documento')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-files-o"></i>
                            <span>Documentos</span>
                        </a>
                        <ul class="sub">
                            <li>
                                <a href="<?php echo site_url('documento/organizacao/gerenciar'); ?>"<?= (in_array($this->uri->rsegment(1), array('documento')) && in_array($this->uri->rsegment(2), array('organizacao')) && in_array($this->uri->rsegment(3), array('gerenciar')) ? ' class="active"' : ''); ?>>
                                    Da Organização
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('documento/colaborador/gerenciar/' . $this->session->userdata('id')); ?>"<?= (in_array($this->uri->rsegment(1), array('documento')) && in_array($this->uri->rsegment(2), array('colaborador')) && in_array($this->uri->rsegment(3), array('gerenciar')) ? ' class="active"' : ''); ?>>
                                    Meus documentos
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php
                }

                if (in_array($this->session->userdata('tipo'), array('administrador', 'empresa'))) {
                    ?>
                    <!--<li class="sub-menu">
                            <a href="javascript:;"<?= (in_array($this->uri->rsegment(2), array()) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-street-view"></i>
                                <span>Captação de Colaboradores</span>
                            </a>
                            <ul class="sub">
                                <li><a href="<?php echo site_url('manutencao'); ?>">Período de experiência</a></li>
                                <li><a href="<?php echo site_url('manutencao'); ?>">Prog. Integração de Colaboradores</a></li>
                                <li><a href="<?php echo site_url('manutencao'); ?>">Processo de desligamento</a></li>
                                <li><a href="<?php echo site_url('manutencao'); ?>">Recrutamento e seleção</a></li>
                            </ul>
                    </li>-->
                <?php } ?>

                <?php if (!in_array($this->session->userdata('tipo'), array('administrador', 'funcionario'))) { ?>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-institution"></i>
                            <!-- span>Avaliação de Desempenho</span> -->
                            <span>Sistema de Avaliações</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('home/paginaCargos'); ?>">Mapeamento de competências</a></li>
                            <li><a href="<?php echo site_url('home/paginaAvaliacao'); ?>">Aval. desempenho p/ competências</a></li>
                            <li><a href="<?php echo site_url('home/funcionarios'); ?>">Aval. des. período de experiência</a></li>
                            <li><a href="<?php echo site_url('manutencao'); ?>">Aval. desempenho por resultados</a></li>
                            <li><a href="<?php echo site_url('home/funcionarios'); ?>">PDIs - Planos Desenv. Individuais</a></li>
                            <li><a href="<?php echo site_url('manutencao'); ?>">Gestão do clima organizacional</a></li>
                        </ul>
                        <!--ul class="sub">
                                <li><a href="<?php echo site_url('home/paginaAvaliacao'); ?>">Gerenciar avaliações</a></li>
                                <li><a href="<?php echo site_url('manutencao'); ?>">Gestão de clima organizacional</a></li>
                                <li><a href="<?php echo site_url('manutencao'); ?>">Avaliação de desempenho</a></li>
                                <li><a href="<?php echo site_url('manutencao'); ?>">Processo de coaching</a></li>
                                <li><a href="<?php echo site_url('manutencao'); ?>">Gestão de talentos</a></li>
                                <li><a href="<?php echo site_url('manutencao'); ?>">Consultoria interna de RH</a></li>
                            </ul-->
                    </li>
                    <!--<li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-list-alt"></i>
                            <span>Organograma</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('manutencao'); ?>">Adicionar</a></li>
                            <li><a href="<?php echo site_url('manutencao'); ?>">Gerenciar</a></li>
                        </ul>
                    </li>-->
                    <?php
                }
                if (in_array($this->session->userdata('tipo'), array('funcionario'))) {
                    ?>
                    <li class="sub-menu">
                        <a href="javascript:;"<?= (in_array($this->uri->rsegment(2), array('meuscursos', 'solicitarcursos')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-graduation-cap"></i>
                            <span>Treinamentos</span>
                        </a>
                        <ul class="sub">
                            <li<?= (in_array($this->uri->rsegment(2), array('meuscursos')) ? ' class="active"' : ''); ?>><a
                                    href="<?php echo site_url('home/meuscursos'); ?>">Meus treinamentos</a></li>
                            <li<?= (in_array($this->uri->rsegment(2), array('solicitarcursos')) ? ' class="active"' : ''); ?>><a
                                    href="<?php echo site_url('home/solicitarcursos'); ?>">Treinamentos disponíveis</a></li>
                        </ul>
                    </li>
                    <li<?= (in_array($this->uri->rsegment(2), array('entrada')) ? ' class="active"' : ''); ?>>
                        <a href="<?php echo site_url('email/entrada'); ?>">
                            <i class="fa fa-envelope"></i>
                            <span>Mensagens</span>
                        </a>
                    </li>
                    <?php
                }

                if (in_array($this->session->userdata('tipo'), array('funcionario'))) {
                    ?>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-institution"></i>
                            <span>Avaliação de desempenho</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('home/paginaAvaliador'); ?>">Realizar avaliação</a></li>
                        </ul>
                    </li>
                    <?php
                }
                if (in_array($this->uri->rsegment(2), array('acessarcurso'))) {
                    ?>
                    <li class="sub-menu" id="menu-curso">
                        <a href="javascript:;"<?= (in_array($this->uri->rsegment(2), array('acessarcurso')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-book"></i>
                            <span><?php echo $curso->curso; ?></span>
                        </a>
                        <ul class="sub">
                            <?php
                            $p = 0;
                            foreach ($paginas->result() as $rowpagina) {
                                ?>
                                <li<?= (in_array($p, array($this->uri->rsegment(4))) ? ' class="active"' : ''); ?>>
                                    <a href="<?php echo site_url('home/acessarcurso/' . $this->uri->rsegment(3) . '/' . $p); ?>">
                                        <?php echo $rowpagina->titulo; ?>
                                    </a>
                                </li>
                                <?php
                                $p++;
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                if (in_array($this->session->userdata('tipo'), array('administrador'))) {
                    ?>
                    <li class="sub-menu">
                        <a href="<?= site_url('backup'); ?>"<?= ($this->uri->rsegment(1) == 'backup' ? ' class="active"' : ''); ?>>
                            <i class="fa fa-download"></i>
                            <span>Backup</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <!-- sidebar menu end-->

    </div>
</aside>

<!--sidebar end-->