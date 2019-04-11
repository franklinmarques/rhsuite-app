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
                <li>
                    <a href="<?php echo site_url('ead/treinamento_cliente'); ?>">
                        <i class="fa fa-graduation-cap"></i>
                        <span>Treinamentos</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- sidebar menu end-->

    </div>
</aside>

<!--sidebar end-->