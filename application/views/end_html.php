<!-- Modal session timeout-->
<div class="modal" id="session_timeout" tabindex="-1" data-backdrop="static" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sessão expirada</h4>
            </div>
            <div class="modal-body">
                <p>Clique no botão abaixo para retornar à tela de login.</p>
            </div>
            <div class="modal-footer">
                <a href="<?php echo site_url('home/sair'); ?>" class="btn btn-primary">Login</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    if ($.fn.dataTable !== undefined) {
        $.fn.dataTable.ext.errMode = function (settings, tn, msg) {
            if (settings.jqXHR.status !== 401 && settings.jqXHR.statusText !== 'expirado') {
                alert(msg);
            }
        };
    }
</script>


<!-- Modal ajuda -->
<div class='modal fade' id='modal-ajuda' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog' style="width: 80%; line-height: 70%;">
        <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title' style='text-align: center !important; font-weight: bolder;'>
                    Orientações de Utilização
                </h4>
            </div>
            <div class='modal-body' style="line-height: normal;">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#manual_ajuda" aria-controls="home" role="tab"
                                                              data-toggle="tab">Manual</a></li>
                    <!--
                    <li role="presentation"><a href="#video_ajuda" aria-controls="profile" role="tab" data-toggle="tab">VÃ­deo</a>
                    </li>
                    -->
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="manual_ajuda">
                        <object id="panel-manual" type="text/html" data="" width="100%" height="450px"></object>
                    </div>
                    <!--
                    <div role="tabpanel" class="tab-pane" id="video_ajuda">
                    <?php
                    if (is_file('PeoplenetCorpAjuda.mp4')) {
                        ?>
                                                                    <div align="center" class="embed-responsive embed-responsive-16by9"
                                                                         style="width: 100%; height: 400px; padding-top: 20px;">
                                                                            <video class="embed-responsive-item" controls="" style="width: 100%; height: 105%;">
                                                                                <source src='<?php //echo base_url('PeoplenetCorpAjuda.mp4');           ?>' type=video/mp4>
                                                                            </video>
                                                                    </div>
                        <?php
                    }
                    ?>
                    </div>
                    -->
                </div>
            </div>
            <div class='modal-footer' style="margin-top: 0;">
                <button type='button' class='btn btn-default' data-dismiss="modal" id='fechaModal'>
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#modal-ajuda').on('show.bs.modal', function (e) {
        if ($('#sidebar').hasClass('hide-left-bar') && $('#sidebar').css('margin-left') === '0px') {
            $('.sidebar-toggle-box:has(.fa-bars)').trigger('click');
        }
        if ($("#panel-manual").attr('data') === '') {
            var url = 'http://docs.google.com/gview?embedded=true&frameborder=0&url=<?= base_url('PeoplenetCorpAjuda.pdf'); ?>';
            $("#panel-manual").attr('data', url);
        }
    });
</script>
</body>
</html>