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
                            <i class="fa fa-search"></i> Gerenciar colaboradores CLT
                        </header>
                        <div class="panel-body">
                            <?php echo form_open('home/getfuncionarios', 'data-html="html-funcionarios" class="form-horizontal" style="margin-top: 15px;" id="busca-funcionarios"'); ?>
                            <div class="form-group">
                                <div class="row">
                                    <!--<label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>-->
                                    <div class="col-sm-6 col-sm-offset-2 controls">
                                        <p><label class="control-label"></label></p>
                                        <input type="text" name="busca" placeholder="Buscar..."
                                               class="form-control input-sm"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <p><label></label></p>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <button type="submit" class="btn btn-sm btn-primary"><i
                                                        class="glyphicon glyphicon-search"></i></button>
                                            <button type="reset" class="btn btn-sm btn-default">Limpar filtros</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <p><label></label></p>
                                        <a id="pdf" class="btn btn-sm btn-danger"
                                           href="<?= site_url('funcionario/pdf/'); ?>" title="Exportar PDF"><i
                                                    class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3 col-lg-2">
                                        <label class="control-label">Filtrar status PDI</label>
                                        <select name="pdi" class="form-control input-sm filtro">
                                            <option value="">Todos</option>
                                            <option value="N">Não iniciados</option>
                                            <option value="A">Atrasados</option>
                                            <option value="E">Em andamento</option>
                                            <option value="C">Completos</option>
                                            <option value="X">Cancelados</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-lg-2">
                                        <label class="control-label">Filtrar status vínculo</label>
                                        <select name="status" class="form-control input-sm filtro">
                                            <option value="">Todos</option>
                                            <option value="1">Ativos</option>
                                            <option value="2">Inativos</option>
                                            <option value="3">Em experiência</option>
                                            <option value="4">Em desligamento</option>
                                            <option value="5">Desligado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por departamento</label>
                                        <?php echo form_dropdown('depto', $depto, '', 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Filtrar por área</label>
                                        <?php echo form_dropdown('area', $area, '', 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por setor</label>
                                        <?php echo form_dropdown('setor', $setor, '', 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por cargo</label>
                                        <?php echo form_dropdown('cargo', $cargo, '', 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por função</label>
                                        <?php echo form_dropdown('funcao', $funcao, '', 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">Filtrar por contrato</label>
                                        <?php echo form_dropdown('contrato', $contrato, '', 'class="form-control input-sm filtro"'); ?>
                                    </div>
                                </div>
                                <hr>
                                <?php echo form_close('<div class="box-content" id="html-funcionarios"></div>'); ?>
                            </div>
                    </section>
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
            document.title = 'CORPORATE RH - LMS - Gerenciar funcionários';
            setPdf_atributes();
        });

        $('select.filtro').on('change', function () {
            $('#busca-funcionarios').submit();
        });

        $('#busca-funcionarios button[type="reset"]').on('click', function () {
            setTimeout(function () {
                $('#busca-funcionarios').submit();
            });
        });

        $('#busca-funcionarios').submit(function () {
            ajax_post($(this).attr('action'), $(this).serialize(), $('#' + $(this).data('html')));
            atualizarFiltro(this);
            setPdf_atributes();
            return false;
        }).submit();

        function atualizarFiltro(el) {
            $.ajax({
                url: "<?php echo site_url('home/atualizar_filtro/') ?>",
                type: "POST",
                dataType: "JSON",
                data: $(el).serialize(),
                success: function (data) {
                    $('[name="area"]').html($(data.area).html());
                    $('[name="setor"]').html($(data.setor).html());
                    $('[name="cargo"]').html($(data.cargo).html());
                    $('[name="funcao"]').html($(data.funcao).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function setPdf_atributes() {
            var search = '';
            var q = new Array();

            $('.filtro').each(function (i, v) {
                if (v.value.length > 0) {
                    q[i] = v.name + "=" + v.value;
                }
            });

            q = q.filter(function (v) {
                return v.length > 0;
            });
            if (q.length > 0) {
                search = '/q?' + q.join('&');
            }

            $('#pdf').prop('href', "<?= site_url('funcionario/pdf/'); ?>" + search);
        }
    </script>

<?php
require_once "end_html.php";
?>