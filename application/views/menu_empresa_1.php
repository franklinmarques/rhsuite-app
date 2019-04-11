<?php
$uri = $this->uri->rsegment(2);
$toggleMenu = $uri === 'acessarcurso' ? 'hide-left-bar' : '';
include_once(APPPATH . 'controllers/menu.php');
$menu =& load_class('Menu', 'controllers');
print_r($menu);
?>

<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse <?= $toggleMenu ?>">

        <!-- sidebar menu start-->

        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">

                <?php foreach ($menu as $opcoes): ?>
                <li class="<?= $opcoes->submenu ? 'sub-menu' : ''; ?>">
                    <a href="<?= site_url($opcoes->url); ?>"
                       class="<?= (in_array($this->uri->rsegment(2), $opcoes->pages) ? 'active' : ''); ?>">
                        <i class="<?= $opcoes->icon; ?>"></i>
                        <span><?= $opcoes->name; ?></span>
                    </a>
                    <?php foreach ($opcoes->submenu as $submenu): ?>
                        <li style="<?= $submenu->separator ? 'border-bottom: solid 1px rgba(255,255,255,0.2)' : ''; ?>"
                            class="<?php echo(in_array($this->uri->rsegment(2), $submenu->pages) ? 'active' : ''); ?>">
                            <a href="<?php echo site_url($submenu->url); ?>"><?= $submenu->name; ?></a>
                        </li>
                    <?php endforeach; ?>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>

        <!-- sidebar menu end-->

    </div>
</aside>

<!--sidebar end-->