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

                <li>
                    <a href="<?php echo site_url('atividades_scheduler'); ?>">
                        <i class="fa fa-address-book"></i>
                        <span>Scheduler - Atividades</span>
                    </a>
                </li>

                <?php
                $this->db->select('depto, nivel_acesso');
                $this->db->where('id', $this->session->userdata('id'));
                $usuariox = $this->db->get('usuarios')->row();
                if (in_array($usuariox->nivel_acesso, array(0, 1, 4, 7, 8, 9, 10, 19)) or $usuariox->depto == 'Gestão de Pessoas'): ?>
                    <li class="sub-menu">
                        <a href="javascript:;" class="active">
                            <i class="fa fa-user-plus"></i>
                            <span>Gestão Processos Seletivos</span>
                        </a>
                        <ul class="sub">
                            <li><a href="<?php echo site_url('requisicaoPessoal'); ?>">Gerenciar Requisições Pessoal</a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="sub-menu">
                    <a href="javascript:void(0);">
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

                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-graduation-cap"></i>
                        <span>Gestão de Treinamentos</span>
                    </a>
                    <ul class="sub">
                        <li<?php (in_array($this->uri->rsegment(2), array('')) ? ' class="active"' : ''); ?>>
                            <a href="<?php echo site_url('ead/treinamento'); ?>">Meus treinamentos</a>
                        </li>
                        <?php if ($this->agent->is_mobile() == false): ?>
                            <li<?php (in_array($this->uri->rsegment(2), array('disponiveis')) ? ' class="active"' : ''); ?>>
                                <a href="<?php echo site_url('ead/cursos/disponiveis'); ?>">Treinamentos disponíveis</a>
                            </li>
                            <?php /*if ($this->session->userdata('nivel') != '4'): */ ?><!--
                                <li<? /*= (in_array($this->uri->rsegment(2), array('status')) ? ' class="active"' : ''); */ ?>>
                                    <a href="<?php /*echo site_url('ead/funcionarios'); */ ?>">Gerenciar alocação
                                        treinamentos</a>
                                </li>
                            --><?php /*endif; */ ?>
                        <?php endif; ?>
                    </ul>
                </li>
                <!--<li<? /*= (in_array($this->uri->rsegment(2), array('entrada')) ? ' class="active"' : ''); */ ?>>
                    <a href="<?php /*echo site_url('email/entrada'); */ ?>">
                        <i class="fa fa-envelope"></i>
                        <span>Mensagens</span>
                    </a>
                </li>-->

                <li<?= (in_array($this->uri->rsegment(2), array('jobDescriptorRespondente')) ? ' class="active"' : ''); ?>>
                    <a href="<?php echo site_url('jobDescriptorRespondente'); ?>">
                        <i class="glyphicons glyphicons-nameplate"> </i>
                        <span>Job Descriptor</span>
                    </a>
                </li>

                <li class="sub-menu">
                    <a href="javascript:void(0);"<?= (in_array($this->uri->rsegment(2), array('home', 'funcionarios', 'cursosfuncionario', 'novocursofuncionario', 'novofuncionario', 'editarfuncionario')) ? ' class="active"' : ''); ?>>
                        <i class="glyphicons glyphicons-charts"> </i>
                        <span>Ferramentas de Assessment</span>
                    </a>
                    <ul class="sub">
                        <li>
                            <a href="<?php echo site_url('pesquisa_lifo'); ?>">Personalidade - LIFO</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('pesquisaQuati'); ?>">Personalidade - Jung</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('pesquisa_eneagrama'); ?>">Personalidade - Eneagrama</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('home/manutencao'); ?>">Potencial - NineBox</a>
                        </li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-institution"></i>
                        <span>Gestão de Desempenho</span>
                    </a>
                    <ul class="sub">
                        <li>
                            <a href="<?php echo site_url('avaliacaoexp_avaliador/periodo'); ?>">Avaliação Período
                                Experiência</a>
                        </li>
                        <?php if (in_array($this->session->userdata('nivel'), array(7, 8, 9, 10, 11))): ?>
                            <li>
                                <a href="<?php echo site_url('avaliacaoexp_avaliados/status/2'); ?>">Status Avaliações
                                    Experiência</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="sub">
                        <li>
                            <a href="<?php echo site_url('avaliacaoexp_avaliador/avaliacoesPeriodicas'); ?>">Avaliações
                                Periódicas</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('avaliacaoexp_avaliador/desempenho'); ?>">Avaliações de
                                Desempenho</a>
                        </li>
                        <?php if (in_array($this->session->userdata('nivel'), array(7, 8, 9, 10, 11))): ?>
                            <li style="border-bottom: solid 1px rgba(255,255,255,0.2);">
                                <a href="<?php echo site_url('avaliacaoexp_avaliados/status/1'); ?>">Status Avaliações
                                    Periódicas</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="sub">
                        <li>
                            <a href="<?php echo site_url('competencias/avaliador'); ?>">Avaliação Por Competências</a>
                        </li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-institution"></i>
                        <span>Gestão de Pesquisas</span>
                    </a>
                    <ul class="sub">
                        <li>
                            <a href="<?php echo site_url('pesquisa_clima'); ?>">Clima Organizacional</a>
                        </li>
                        <!--<li>
                            <a href="<?php /*echo site_url('pesquisa_modelos/personalidade'); */ ?>">Personalidade -
                                Eneagrama</a>
                        </li>-->
                        <li>
                            <a href="<?php echo site_url('pesquisa_perfil'); ?>">Perfil Profissional - Pessoal</a>
                        </li>
                    </ul>
                </li>

                <?php if (in_array($this->session->userdata('nivel'), array(7, 8, 9, 10, 17, 18))): ?>
                    <li class="sub-menu">
                        <a href="javascript:;">
                            <i class="fa fa-institution"></i>
                            <span>Gestão de Facilities</span>
                        </a>
                        <ul class="sub">
                            <?php if ($this->session->userdata('nivel') == 17): ?>
                                <li>
                                    <a href="<?php echo site_url('facilities/empresas'); ?>">Itens de
                                        Vistoria/Manutenção</a>
                                </li>
                            <?php endif; ?>
                            <?php if (in_array($this->session->userdata('nivel'), array(7, 8, 17, 18))): ?>
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
                                    <a href="<?php echo site_url('facilities/manutencoes'); ?>">Gerenciar
                                        Manutenções</a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->session->userdata('nivel') == 17): ?>
                                <li>
                                    <a href="<?php echo site_url('facilities/contasMensais'); ?>">Contas Mensais
                                        Facilities</a>
                                </li>
                            <?php endif; ?>
                            <?php if (in_array($this->session->userdata('nivel'), array(7, 8, 17, 18))): ?>
                                <li>
                                    <a href="<?php echo site_url('facilities/fornecedoresPrestadores'); ?>">Gerenciar
                                        fornecedores</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="<?php echo site_url('facilities/ordensServico'); ?>">
                        <i class="fa fa-sticky-note-o"></i>
                        <span>Gerenciar Ordens de Serviço</span>
                    </a>
                </li>

                <!-- coordenador, gerente, diretor e presidente -->
                <?php if (in_array($this->session->userdata('nivel'), [7, 8, 9, 18])): ?>
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
                    <?php if (isset($hash_acesso['ST'])): ?>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-history"></i>
                                <span>Gestão Operacional ST</span>
                            </a>
                            <ul class="sub">
                                <?php /*if (in_array(401, $hash_acesso['ST'])): */ ?><!--
                                    <li>
                                        <a href="<?php /*echo site_url('manutencao'); */ ?>">Gestão de Contratos</a>
                                    </li>
                                --><?php /*endif; */ ?>
                                <?php if (in_array(410, $hash_acesso['ST'])): ?>
                                    <li>
                                        <a href="<?php echo site_url('apontamento'); ?>">Gerenciar apontamentos</a>
                                    </li>
                                    <!--<li><a href="<?php /*echo site_url('requisicaoPessoal/st'); */ ?>">Requisição de
                                            pessoal</a>
                                    </li>-->
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($hash_acesso['CD'])): ?>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-history"></i>
                                <span>Gestão Operacional CD</span>
                            </a>
                            <ul class="sub">
                                <?php if (in_array(610, $hash_acesso['CD'])): ?>
                                    <li>
                                        <a href="<?php echo site_url('cd/apontamento'); ?>">Gerenciar apontamentos</a>
                                    </li>
                                    <!--<li><a href="<?php /*echo site_url('requisicaoPessoal/cd'); */ ?>">Requisição de
                                            pessoal</a>
                                    </li>-->
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($hash_acesso['EI'])): ?>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-history"></i>
                                <span>Gestão Operacional EI</span>
                            </a>
                            <ul class="sub">
                                <?php if (in_array(710, $hash_acesso['EI'])): ?>
                                    <li>
                                        <a href="<?php echo site_url('ei/apontamento'); ?>">Gerenciar apontamentos</a>
                                    </li>
                                    <li><a href="<?php echo site_url('requisicaoPessoal/ei'); ?>">Requisição de
                                            pessoal</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($hash_acesso['PAPD'])): ?>
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
                                        <a href="<?php echo site_url('papd/atividades_deficiencias'); ?>">Gerenciar
                                            Atividades/Deficiências</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array(503, $hash_acesso['PAPD'])): ?>
                                    <li<?php echo($this->uri->ruri_string() == 'papd/relatorios/medicao_mensal' ? ' class="active"' : ''); ?>>
                                        <a href="<?php echo site_url('papd/relatorios/medicao_mensal'); ?>">Relatório de
                                            medição
                                            (individual)</a>
                                    </li>
                                    <li<?php echo($this->uri->ruri_string() == 'papd/relatorios/medicao_consolidada' ? ' class="active"' : ''); ?>>
                                        <a href="<?php echo site_url('papd/relatorios/medicao_consolidada'); ?>">Relatório
                                            de medição
                                            (equipe)</a>
                                    </li>
                                    <li<?php echo($this->uri->ruri_string() == 'papd/relatorios/medicao_anual' ? ' class="active"' : ''); ?>>
                                        <a href="<?php echo site_url('papd/relatorios/medicao_anual'); ?>">Relatório de
                                            medição
                                            (consolidado)</a>
                                    </li>
                                <?php endif; ?>
                                <li><a href="<?php echo site_url('requisicaoPessoal/papd'); ?>">Gerenciar Requisição de
                                        Pessoal</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($hash_acesso['ICOM'])): ?>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento', 'sessoes')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-history"></i>
                                <span>Gestão Operacional Libras</span>
                            </a>
                            <?php if (in_array(801, $hash_acesso['ICOM'])): ?>
                                <ul class="sub">
                                    <li><a href="<?php echo site_url('icom/sessoes'); ?>">Gerenciar eventos</a></li>
                                </ul>
                            <?php endif; ?>
                        </li>
                        <li class="sub-menu">
                            <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('apontamento')) ? ' class="active"' : ''); ?>>
                                <i class="fa fa-history"></i>
                                <span>Gestão Operacional ICOM</span>
                            </a>
                            <ul class="sub">
                                <li><a href="<?php echo site_url('icom/apontamento'); ?>">Gerenciar apontamentos</a>
                                </li>
                                <li><a href="<?php echo site_url('manutencao'); ?>">Requisição de pessoal</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>



                <?php if (in_array($this->session->userdata('nivel'), array(7, 8, 18))): ?>
                    <li class="sub-menu">
                        <a href="javascript:;"<?php echo(in_array($this->uri->rsegment(1), array('backup', 'log')) ? ' class="active"' : ''); ?>>
                            <i class="fa fa-server"></i>
                            <span>Gestão da Plataforma</span>
                        </a>
                        <ul class="sub">
                            <!--                            <li>-->
                            <!--                                <a href="-->
                            <?php //echo site_url('backup'); ?><!--">Backup/Restore de DBase</a>-->
                            <!--                            </li>-->
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