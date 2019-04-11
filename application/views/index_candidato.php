<?php
require_once "header.php";
?>
    <style>
        .thumbnail {
            border: 1px transparent;
            border-radius: 15px;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->

            <div class="jumbotron" style="padding: 15px; color: #051A38; background-color: #FFF;">
                <?php if ($this->agent->is_mobile()): ?>
                    <p style="font-size: small; font-weight: 600; text-indent: 20px;">
                        Prezado colega,
                    </p>
                    <p style="font-size: small; text-align: justify; font-weight: 600; text-indent: 20px;">
                        É um grande prazer tê-lo em nosso processo seletivo. Utilize os botões abaixo para acessar as
                        categorias de testes relacionados a você.
                    </p>
                    <p style="font-size: small; font-weight: 600; text-indent: 20px;">
                        Boa sorte!
                    </p>
                <?php else: ?>
                    <p style="font-size: medium; font-weight: 600; text-indent: 20px;">
                        Prezado colega,
                    </p>
                    <p style="font-size: medium; text-align: justify; font-weight: 600; text-indent: 20px;">
                        É um grande prazer tê-lo em nosso processo seletivo. Utilize os botões abaixo para acessar as
                        categorias de testes relacionados a você.
                    </p>
                    <p style="font-size: medium; font-weight: 600; text-indent: 20px;">
                        Boa sorte!
                    </p>
                <?php endif; ?>
                <hr style="margin-top: 5px; margin-bottom: 5px;">
                <br>
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-md-4 text-center">
                            <a href="<?= site_url('recrutamento/testes/matematica') ?>" class="thumbnail">
                                <img src="<?= base_url('assets/images/PROCESSO_SELETIVO_Matemática.png'); ?>">
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-4 text-center">
                            <a href="<?= site_url('recrutamento/testes/perfil-personalidade') ?>" class="thumbnail">
                                <img src="<?= base_url('assets/images/PROCESSO_SELETIVO_Perfil-Personalidade.png'); ?>">
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-4 text-center">
                            <a href="<?= site_url('recrutamento/testes/raciocinio-logico') ?>" class="thumbnail">
                                <img src="<?= base_url('assets/images/PROCESSO_SELETIVO_Raciocínio_Lógico.png'); ?>">
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-4 text-center">
                            <a href="<?= site_url('recrutamento/testes/portugues') ?>" class="thumbnail">
                                <img src="<?= base_url('assets/images/PROCESSO_SELETIVO_Português.png'); ?>">
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-4 text-center">
                            <a href="<?= site_url('recrutamento/testes/digitacao') ?>" class="thumbnail">
                                <img src="<?= base_url('assets/images/PROCESSO_SELETIVO_Digitação.png'); ?>">
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-4 text-center">
                            <a href="<?= site_url('recrutamento/testes/interpretacao') ?>" class="thumbnail">
                                <img src="<?= base_url('assets/images/PROCESSO_SELETIVO_Interpretação_de_Textos.png'); ?>">
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <!--<div class="col-xs-12 col-md-4 col-md-offset-2 text-center">
                            <a href="<? /*= site_url('recrutamento/testes/lideranca') */ ?>" class="thumbnail">
                                <img src="<? /*= base_url('assets/images/PROCESSO_SELETIVO_Liderança.png'); */ ?>">
                            </a>
                        </div>-->
                        <div class="col-xs-12 col-md-4 col-md-offset-4 text-center">
                            <a href="<?= site_url('recrutamento/testes/entrevista') ?>" class="thumbnail">
                                <img src="<?= base_url('assets/images/PROCESSO_SELETIVO_Entrevista.png'); ?>">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- page end-->
        </section>
    </section>
    <!--main content end-->


    <!-- Date input -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Home';
        });
    </script>
<?php
require_once "end_js.php";
require_once "end_html.php";
?>