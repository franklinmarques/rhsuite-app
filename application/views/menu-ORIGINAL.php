<!--sidebar start-->
<aside>
<div id="sidebar" class="nav-collapse">
<!-- sidebar menu start-->
<div class="leftside-navigation">
<ul class="sidebar-menu" id="nav-accordion">
<li>
    <a href="<?php echo base_url('home'); ?>" <?php if (in_array($this->uri->segment(2), array(''))) echo 'class="active"'; ?>>
        <i class="fa fa-home"></i>
        <span>Início</span>
    </a>
</li>
<li>
    <a href="javascript:modalAjuda();">
        <i class="fa fa-question-circle"></i>
        <span>Ajuda</span>
    </a>
</li>
<?php if (in_array($this->session->userdata('tipo'), array('administrador'))) { ?>
    <li class="sub-menu">
        <a href="javascript:;" <?php if (in_array($this->uri->segment(2), array('biblioteca', 'novabiblioteca'))) echo 'class="active"'; ?>>
            <i class="fa fa-book"></i>
            <span>Biblioteca</span>
        </a>
        <ul class="sub">
            <li <?php if (in_array($this->uri->segment(2), array('novabiblioteca'))) echo 'class="active"'; ?>><a
                    href="<?php echo base_url('home/novabiblioteca'); ?>">Adicionar</a></li>
            <li <?php if (in_array($this->uri->segment(2), array('biblioteca'))) echo 'class="active"'; ?>><a
                    href="<?php echo base_url('home/biblioteca'); ?>">Gerenciar</a></li>
        </ul>
    </li>
    <li class="sub-menu">
        <a href="javascript:;" <?php if (in_array($this->uri->segment(2), array('empresas', 'cursosempresa', 'novocursoempresa', 'novaempresa', 'editarempresa'))) echo 'class="active"'; ?>>
            <i class="fa fa-institution"></i>
            <span>Empresas</span>
        </a>
        <ul class="sub">
            <li <?php if (in_array($this->uri->segment(2), array('novaempresa'))) echo 'class="active"'; ?>><a
                    href="<?php echo base_url('home/novaempresa'); ?>">Adicionar</a></li>
            <li <?php if (in_array($this->uri->segment(2), array('empresas', 'cursosempresa', 'novocursoempresa', 'editarempresa'))) echo 'class="active"'; ?>>
                <a href="<?php echo base_url('home/empresas'); ?>">Gerenciar</a></li>
        </ul>
    </li>
<?php } ?>

<?php if (in_array($this->session->userdata('tipo'), array('empresa'))) { ?>
    <li class="sub-menu">
        <a href="javascript:;" <?php if (in_array($this->uri->segment(2), array('funcionarios', 'cursosfuncionario', 'novocursofuncionario', 'novofuncionario', 'editarfuncionario'))) echo 'class="active"'; ?>>
            <i class="fa fa-users"></i>
            <span>Funcionários</span>
        </a>
        <ul class="sub">
            <li <?php if (in_array($this->uri->segment(2), array('novofuncionario'))) echo 'class="active"'; ?>><a
                    href="<?php echo base_url('home/novofuncionario'); ?>">Adicionar</a></li>
            <li <?php if (in_array($this->uri->segment(2), array('funcionarios', 'cursosfuncionario', 'novocursofuncionario', 'editarfuncionario'))) echo 'class="active"'; ?>>
                <a href="<?php echo base_url('home/funcionarios'); ?>">Gerenciar</a></li>
            <li <?php if (in_array($this->uri->segment(2), array('importarfuncionario'))) echo 'class="active"'; ?>><a
                    href="<?php echo base_url('funcionario/importarFuncionario'); ?>">Importar</a></li>
        </ul>
    </li>
<?php } ?>

<?php if (in_array($this->session->userdata('tipo'), array('administrador', 'empresa'))) { ?>
    <li class="sub-menu">
        <a href="javascript:;" <?php if (in_array($this->uri->segment(2), array('cursos', 'paginascurso', 'novapaginacurso', 'editarpaginacurso', 'novocurso', 'editarcurso'))) echo 'class="active"'; ?>>
            <i class="fa fa-graduation-cap"></i>
            <span>Treinamentos</span>
        </a>
        <ul class="sub">
            <li <?php if (in_array($this->uri->segment(2), array('novocurso'))) echo 'class="active"'; ?>><a
                    href="<?php echo base_url('home/novocurso'); ?>">Adicionar</a></li>
            <li <?php if (in_array($this->uri->segment(2), array('cursos', 'paginascurso', 'novapaginacurso', 'editarpaginacurso', 'editarcurso'))) echo 'class="active"'; ?>>
                <a href="<?php echo base_url('home/cursos'); ?>">Gerenciar</a></li>
        </ul>
    </li>

    <li <?php if (in_array($this->uri->segment(2), array('entrada'))) echo 'class="active"'; ?>>
        <a href="<?php echo base_url('email/entrada') ?>">
            <i class="fa fa-envelope"></i>
            <span>Mensagens</span>
        </a>
    </li>
<?php } ?>

<?php if (!in_array($this->session->userdata('tipo'), array('funcionario'))) { ?>

    <li class="sub-menu">
        <a href="javascript:;" <?php if (in_array($this->uri->segment(1), array('documento'))) echo 'class="active"'; ?>>
            <i class="fa fa-files-o"></i>
            <span>Documentos</span>
        </a>
        <ul class="sub">
            <?php
            if (in_array($this->session->userdata('tipo'), array('empresa'))) {
                ?>
                <li>
                    <a href="javascript: void(0);" <?php if (in_array($this->uri->segment(1), array('documento')) && in_array($this->uri->segment(2), array('organizacao'))) echo 'class="active"'; ?>>
                        <i class="fa fa-institution"></i>
                        Da organização
                    </a>
                    <ul class="menu">
                        <li>
                            <a href="<?php echo base_url('documento/organizacao'); ?>" <?php if (in_array($this->uri->segment(1), array('documento')) && in_array($this->uri->segment(2), array('organizacao')) && in_array($this->uri->segment(3), array('novo', ''))) echo 'class="active"'; ?>>
                                Adicionar
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('documento/organizacao/gerenciar'); ?>" <?php if (in_array($this->uri->segment(1), array('documento')) && in_array($this->uri->segment(2), array('organizacao')) && in_array($this->uri->segment(3), array('gerenciar'))) echo 'class="active"'; ?>>
                                Gerenciar
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
                            <a href="<?php echo base_url('tipo/novo'); ?>">
                                Adicionar
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('tipo/gerenciar'); ?>">
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
        <a href="javascript:void(0);"<?php if (in_array($this->uri->segment(1), array('documento'))) echo 'class="active"'; ?>>
            <i class="fa fa-files-o"></i>
            <span>Documentos</span>
        </a>
        <ul class="sub">
            <li>
                <a href="<?php echo base_url('documento/organizacao/gerenciar'); ?>" <?php if (in_array($this->uri->segment(1), array('documento')) && in_array($this->uri->segment(2), array('organizacao')) && in_array($this->uri->segment(3), array('gerenciar'))) echo 'class="active"'; ?>>
                    Da organização
                </a>
            </li>
            <li>
                <a href="<?php echo base_url('documento/colaborador/gerenciar/' . $this->session->userdata('id')); ?>" <?php if (in_array($this->uri->segment(1), array('documento')) && in_array($this->uri->segment(2), array('colaborador')) && in_array($this->uri->segment(3), array('gerenciar'))) echo 'class="active"'; ?>>
                    Meus Documentos
                </a>
            </li>
        </ul>
    </li>


<?php
}
if (in_array($this->session->userdata('tipo'), array('administrador', 'empresa'))) {
    ?>
    <!--li class="sub-menu">
        <a href="javascript:;" <?php if (in_array($this->uri->segment(2), array())) echo 'class="active"'; ?>>
            <i class="fa fa-street-view"></i>
            <span>Captação de Colaboradores</span>
        </a>
        <ul class="sub">
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Período de Experiência</a></li>
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Prog. Integração de Colaboradores</a></li>
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Processo de Desligamento</a></li>
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Recrutamento e Seleção</a></li>
        </ul>
    </li-->
<?php } ?>

<?php if (!in_array($this->session->userdata('tipo'), array('administrador', 'funcionario'))) { ?>
    <li class="sub-menu">
        <a href="javascript:;">
            <i class="fa fa-institution"></i>
            <span>Avaliação de Desempenho</span>
        </a>
        <ul class="sub">
            <li><a href="<?php echo base_url('home/paginaCargos'); ?>">Mapeamento de Competencias</a></li>
            <li><a href="<?php echo base_url('home/paginaAvaliacao'); ?>">Gerenciar Avaliações</a></li>
			
            
        </ul>

        <!--ul class="sub">
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Gestão de Clima Organizacional</a></li>
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Avaliação de Desempenho</a></li>
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Processo de Coaching</a></li>
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Gestão de Talentos</a></li>
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Consultoria Interna de RH</a></li>
        </ul-->
    </li>


    <!--li class="sub-menu">
        <a href="javascript:;">
            <i class="fa fa-list-alt"></i>
            <span>Organograma</span>
        </a>
        <ul class="sub">
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Adicionar</a></li>
            <li><a href="<?php echo base_url('home/manutencao'); ?>">Gerenciar</a>
            </li>
        </ul>
    </li-->
<?php
}
if (in_array($this->session->userdata('tipo'), array('funcionario'))) {
    ?>
    <li class="sub-menu">
        <a href="javascript:;" <?php if (in_array($this->uri->segment(2), array('meuscursos', 'solicitarcursos'))) echo 'class="active"'; ?>>
            <i class="fa fa-graduation-cap"></i>
            <span>Treinamentos</span>
        </a>
        <ul class="sub">
            <li <?php if (in_array($this->uri->segment(2), array('meuscursos'))) echo 'class="active"'; ?>><a
                    href="<?php echo base_url('home/meuscursos'); ?>">Meus Treinamentos</a></li>
            <li <?php if (in_array($this->uri->segment(2), array('solicitarcursos'))) echo 'class="active"'; ?>><a
                    href="<?php echo base_url('home/solicitarcursos'); ?>">Treinamentos Disponíveis</a></li>
        </ul>
    </li>

    <li <?php if (in_array($this->uri->segment(2), array('entrada'))) echo 'class="active"'; ?>>
        <a href="<?php echo base_url('email/entrada') ?>">
            <i class="fa fa-envelope"></i>
            <span>Mensagens</span>
        </a>
    </li>
<?php } 

if (in_array($this->session->userdata('tipo'), array('funcionario'))) { 
?>
	<li class="sub-menu">
        <a href="javascript:;">
            <i class="fa fa-institution"></i>
            <span>Avaliação de Desempenho</span>
        </a>
        <ul class="sub">
            <li><a href="<?php echo base_url('home/paginaAvaliador'); ?>">Realizar Avaliação</a></li>
         </ul>
	</li>
<?php 
}
if (in_array($this->uri->segment(2), array('acessarcurso'))) { ?>
    <li class="sub-menu" id="menu-curso">
        <a href="javascript:;" <?php if (in_array($this->uri->segment(2), array('acessarcurso'))) echo 'class="active"'; ?>>
            <i class="fa fa-book"></i>
            <span><?php echo $curso->curso; ?></span>
        </a>
        <ul class="sub">
            <?php
            $p = 0;
            foreach ($paginas->result() as $rowpagina) {
                ?>
                <li <?php if (in_array($p, array($this->uri->segment(4)))) echo 'class="active"'; ?>>
                    <a href="<?php echo base_url('home/acessarcurso/' . $this->uri->segment(3) . '/' . $p); ?>">
                        <?php echo $rowpagina->titulo; ?>
                    </a>
                </li>
                <?php
                $p++;
            }
            ?>
        </ul>
    </li>
<?php }
if (in_array($this->session->userdata('tipo'), array('administrador'))) {
?>
<li class="sub-menu">
    <a href="<?= base_url('backup');?>" <?php if (in_array($this->uri->segment(1), array('backup'))) echo 'class="active"'; ?>>
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