<?php
require_once "header.php";
?>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-search"></i> Gerenciar documentos - Colaborador
                            <?php
                            if ($this->session->userdata('tipo') == 'empresa') {
                                ?>
                                <a class="btn btn-success btn-sm"
                                   style="float:right;border-radius: 20px !important; margin-top: -0.5%;"
                                   href="<?php echo site_url('documento/colaborador/novo/' . $this->uri->rsegment(4)); ?>"><i
                                            class="fa fa-plus"></i> Adicionar</a>
                                <?php
                            }
                            ?>
                        </header>
                        <div class="panel-body">
                            <?php echo form_open('documento/getDocumentoColaborador/' . $this->uri->rsegment(4), 'data-html="html-documento" class="form-horizontal" style="margin-top: 15px;" id="busca-funcionarios"'); ?>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>

                                <div class="col-xs-9 col-sm-6 col-lg-7 controls">
                                    <input type="text" name="busca" placeholder="Buscar..."
                                           class="form-control input-sm"/>
                                </div>
                                <div class="col-xs-1 col-sm-3 col-lg-3">
                                    <button type="submit" class="btn btn-primary btn-sm"><i
                                                class="glyphicon glyphicon-search"></i></button>
                                </div>
                            </div>
                            <?php echo form_close('<div class="box-content" id="html-documento"></div>'); ?>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

    <!-- Modal -->
    <div class='modal fade' id='modalVisualizar' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
         aria-hidden='true'>
        <div class='modal-dialog' style="width: 90%;">
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' style='text-align: center !important; font-weight: bolder;'>
                        Documento - Visualização
                    </h4>
                </div>
                <div class='modal-body' style="line-height: normal;" id="conteudoDocumento">
                </div>
                <div class='modal-footer' style="margin-top: 0;">
                    <button type='button' class='btn btn-default' data-dismiss="modal" id='fechaModal'>
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php
require_once "end_js.php";
?>
    <!-- Js -->
    <script src="<?php echo base_url('assets/js/jquery.fileDownload-master/src/Scripts/jquery.fileDownload.js'); ?>"></script>

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar funcionários';
        });

        $('#busca-funcionarios').submit(function () {
            ajax_post($(this).attr('action'), $(this).serialize(), $('#' + $(this).data('html')));
            return false;
        }).submit();

        function excluiArquivo(id) {
            if (confirm('Deseja realmente excluir esse arquivo?')) {
                $.ajax({
                    url: '<?= site_url('documento/excluir/'); ?>/' + id,
                    type: 'GET',
                    success: function (data) {
                        if (data === 'success') {
                            location.reload(true);
                        }
                    }
                });
            }
        }

        function visualizaDocumento(id) {
            $.ajax({
                type: "GET",
                url: '<?= site_url('documento/visualizar'); ?>/' + id,
                data: '',
                dataType: 'json',
                success: function (data) {
                    $('#conteudoDocumento').html(data);
                    $('#modalVisualizar').modal('show');
                }
            });
        }

        function baixar_documento(id) {
            $.fileDownload('<?= site_url('documento/download') ?>/', {
//            preparingMessageHtml: "Preparando o arquivo solicitado, aguarde...",
//            failMessageHtml: "Erro ao baixar o arquivo, tente novamente.",
                httpMethod: "POST",
                data: {id: id}
            });
        }
    </script>
<?php
require_once "end_html.php";
?>