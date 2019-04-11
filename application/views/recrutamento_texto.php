<?php
require_once "header.php";
?>
<style>
    .btn-success{
        background-color: #5cb85c;
        border-color: #4cae4c;
        color: #fff;
    }
    .btn-primary {
        background-color: #337ab7 !important;
        border-color: #2e6da4 !important;
        color: #fff;
    }
    .btn-info {
        color: #fff;
        background-color: #5bc0de;
        border-color: #46b8da;
    }
    .btn-warning {
        color: #fff;
        background-color: #f0ad4e;
        border-color: #eea236;
    }
    .btn-danger {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
    }
    .text-nowrap{
        white-space: nowrap;
    }   

    tr.group, tr.group:hover {
        background-color: #ddd !important;
    }
</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                    <li><a href="<?= site_url('recrutamento_modelos') ?>">Modelos de Teste de Seleção</a></li>
                    <li class="active">Texto dissertativo para teste de seleção - <?= $nome ?></li>
                </ol>
                <div class="row text-right">
                    <div class="col-md-12">
                        <button class="btn btn-default" onclick="limpar()"><i class="fa fa-times"></i> Limpar</button>
                        <button class="btn btn-primary" onclick="save()"><i class="fa fa-save"></i> Salvar</button>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div id="alert"></div>
                    <div class="col-xs-12">
                        <?php echo form_open('recrutamento_questoes/salvar_texto', 'data-aviso="alert" class="form-horizontal ajax-upload" id="form"'); ?>
                        <input type="hidden" value="<?= $id; ?>" name="id"/> 
                        <input type="hidden" value="<?= $id_modelo; ?>" id="id_modelo" name="id_modelo"/>
                        <input type="hidden" value="A" name="tipo_resposta"/>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="control-label"><strong>Instruções:</strong> digite ou cole o texto a ser interpretado na janela abaixo</label>
                                <?php if ($tipo === 'D'): ?>
                                    <textarea name="pergunta" id="pergunta" class="form-control" rows="16" placeholder="Insira o texto para o teste de digitação aqui"><?= $pergunta; ?></textarea>
                                <?php elseif ($tipo === 'I'): ?>
                                    <textarea name="pergunta" id="pergunta" class="form-control" rows="16" placeholder="Insira o texto para o teste de interpretação aqui"><?= $pergunta; ?></textarea>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- page end-->

    </section>
</section>
<!--main content end-->

<?php
require_once "end_js.php";
?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Texto dissertativo para teste de seleção - <?= $nome ?>';
    });
</script>

<script>
    function save() {
        $('#form').submit();
    }
    function limpar() {
        $('#pergunta').val('');
    }
</script>
<?php
require_once "end_html.php";
?>