<!--header start-->
<header class="header fixed-top clearfix" style="z-index: 1040;">
    <!--logo start-->
    <div class="brand">
        <img src="<?php echo base_url('imagens/usuarios/' . $this->session->userdata('logomarca')); ?>"
             style="height: auto; width: auto; max-height: 78px; max-width: 239px; vertical-align: middle;">
    </div>
    <!--logo end-->

    <?php if (!in_array($this->session->userdata('tipo'), ['cliente', 'candidato_externo'])): ?>
        <div class="nav notify-row" id="top_menu">
            <!--  notification start -->
            <ul class="nav top-menu">
                <li id="header_notification_bar" class="dropdown">
                    <a data-toggle="dropdown" class="sidebar-toggle-box" href="javascript:void(0)"
                       title="Mostrar/ocultar menu">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>
                <li id="header_maximize_bar" class="dropdown"
                    style="display: <?= $this->uri->rsegment(2) == 'acessarcurso' ? 'block' : 'none' ?>;">
                    <a data-toggle="dropdown" class="sidebar-toggle-box" href="javascript:void(0)" title="Tela cheia">
                        <i class="glyphicons glyphicons-fullscreen"></i>
                    </a>
                </li>
                <!-- inbox dropdown start-->
                <?php if ($this->session->userdata('tipo') !== 'candidato') : ?>
                    <li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#" title="Ver mensagens novas">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge bg-important" id="total_msg">0</span>
                        </a>
                        <ul class="dropdown-menu extended inbox" id="inbox">

                        </ul>
                    </li>
                <?php endif; ?>
                <!-- inbox dropdown end -->
            </ul>
            <!--  notification end -->
        </div>
    <?php endif; ?>
    <div class="top-nav clearfix">
        <!--search & user info start-->
        <?php if ($this->session->userdata('tipo') !== 'candidato_externo') : ?>
            <ul class="nav top-menu">
                <!-- user login dropdown start-->
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;"
                       title="<?php echo $this->session->userdata('nome'); ?>" style="padding:3px 0;">
                        <img src="<?php //echo base_url('imagens/usuarios/' . $this->session->userdata('foto')); ?>">
                        <span class="username"><?php echo $this->session->userdata('nome'); ?></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu extended logout">
                        <li>
                            <?php if ($this->session->userdata('tipo') === 'cliente') : ?>
                                <a href="#" onclick="edit_perfil();">
                                    <i class="fa fa-user-circle-o"></i> Meu perfil
                                </a>
                            <?php elseif ($this->session->userdata('tipo') === 'candidato_externo') : ?>
                                <a href="<?php echo site_url('candidatoVagas/editarPerfil/' . $this->session->userdata('id')); ?>">
                                    <i class="fa fa-user-circle-o"></i> Meu perfil
                                </a>
                            <?php elseif ($this->session->userdata('tipo') === 'candidato'): ?>
                                <a href="<?php echo site_url('recrutamento_candidatos/perfil/' . $this->session->userdata('id')); ?>">
                                    <i class="fa fa-user-circle-o"></i> Meu perfil
                                </a>
                            <?php else: ?>
                                <a href="<?php echo site_url('home/meuperfil'); ?>">
                                    <i class="fa fa-user-circle-o"></i> Meu perfil
                                </a>
                            <?php endif; ?>
                        </li>
                        <li>
                            <!--<a href="<?php //echo site_url('contato/novaMensagem');   ?>">-->
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#modal-contato">
                                <i class="fa fa-envelope-o"></i> Fale conosco
                            </a>
                        </li>
                        <!--<li>
                            <a href="javascript:void(0);"  data-toggle="modal" data-target="#modal-sobre">
                                <i class="fa fa-info-circle"></i> Sobre
                            </a>
                        </li>-->
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo site_url('home/sair'); ?>">
                                <i class="fa fa-power-off"></i> Desconectar
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- user login dropdown end -->
            </ul>
        <?php endif; ?>
        <!--search & user info end-->
    </div>
</header>
<!--header end-->

<div class="modal fade" id="modal-contato" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <div class="modal-title row">
                    <div class="col-sm-12">
                        <img src="<?= base_url('assets/img/logorhsuite.jpg') ?>" title="logo">
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <p class="text-center">Para contatar o administrador da plataforma, por gentileza enviar e-mail para: <a
                            href="mailto:contato@rhsuite.com.br">contato@rhsuite.com.br</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal" id="modal-sobre" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <img class="modal-title" src="<?= base_url('assets/img/logorhsuite.jpg') ?>" title="logo">
            </div>
            <div class="modal-body">
                <p>&copy; 2017 Rhsuite - Ferramentas Para RH</p>
                <p>Versão: 2.35</p>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-primary" onclick="javascript:alert('Você já possui a versão atual.')">Verificar atualização</button>-->
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $('.glyphicons-fullscreen').on('click', function () {
        if (getFullscreen()) {
            exitFullscreen();
        } else {
            var element = document.body;
//            var element = document.getElementById("main-content").firstElementChild;
            launchIntoFullscreen(element);
        }
    });

    function getFullscreen() {
        var element = document.body;
        return element.fullscreenEnabled || element.mozFullScreenEnabled || element.webkitFullscreenEnabled;
    }

    function launchIntoFullscreen(element) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
        sessionStorage.setItem('fullscreen', true);
    }

    function exitFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
        alert(1);
    }
</script>
<style>
    section.wrapper:-moz-full-screen {
        background-color: #fff;
        overflow: auto;
    }

    section.wrapper:-webkit-full-screen {
        background-color: #fff;
        /*overflow: auto;*/
    }

    section.wrapper:-ms-full-screen {
        background-color: #fff;
        overflow: auto;
    }

    section.wrapper:full-screen,
    section.wrapper:fullscreen {
        background-color: #fff;
        overflow: auto;
    }
</style>