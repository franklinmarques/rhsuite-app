<?php require_once APPPATH . 'views/header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('ei/apontamento') ?>">Apontamentos diários</a></li>
                        <li class="active">Gerenciar Alunos</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_aluno()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar aluno
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <a id="pdf" class="btn btn-sm btn-danger" style="float:right;"
                       href="<?= site_url('ei/alunos/pdf/'); ?>"
                       title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="control-label">Área/cliente</label>
                                            <?php echo form_dropdown('busca[diretoria]', $diretoria, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Unidade ensino</label>
                                            <?php echo form_dropdown('busca[escola]', $escola, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Status</label>
                                            <?php echo form_dropdown('busca[status]', $status, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Curso</label>
                                            <?php echo form_dropdown('busca[curso]', $curso, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label><br>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <button type="button" id="pesquisar" class="btn btn-sm btn-default">
                                                    Pesquisar
                                                </button>
                                                <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                    Limpar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table id="table" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Aluno(a)</th>
                            <th>Status aluno</th>
                            <th>Ações para aluno(a)</th>
                            <th>Curso(s)</th>
                            <th>Unidade</th>
                            <th>Status curso</th>
                            <!--                            <th>Clientes</th>-->
                            <!--                            <th>Unidade escolar</th>-->
                            <th>Ações para curso</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar aluno(a)</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div id="pesquisar_aluno">
                                        <div class="row form-group">
                                            <label class="control-label col-md-4 text-danger"><strong>Pesquisar se aluno
                                                    já é cadastrado</strong></label>
                                            <div class="col-md-5">
                                                <?php echo form_dropdown('', $alunos, '', 'class="combobox form-control"'); ?>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2"><strong>Nome aluno(a)</strong><span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-5">
                                            <input type="text" name="nome" class="form-control"
                                                   placeholder="Nome do aluno">
                                        </div>
                                        <label class="control-label col-md-1">Status</label>
                                        <div class="col col-md-3">
                                            <select class="form-control" name="status">
                                                <option value="A">Ativo</option>
                                                <option value="I">Inativo</option>
                                                <option value="N">Não frequentando</option>
                                                <option value="F">Afastado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Endereço</label>
                                        <div class="col-md-9">
                                            <input name="endereco" placeholder="Endereço" class="form-control"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Número</label>
                                        <div class="col-md-2">
                                            <input name="numero" class="form-control" type="number" min="0">
                                        </div>
                                        <label class="control-label col-md-1">Compl.</label>
                                        <div class="col-md-6">
                                            <input name="complemento" placeholder="Complemento" class="form-control"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Município</label>
                                        <div class="col-md-6">
                                            <input name="municipio" placeholder="Município" class="form-control"
                                                   type="text">
                                        </div>
                                        <label class="control-label col-md-1">CEP</label>
                                        <div class="col-md-2">
                                            <input name="cep" placeholder="CEP" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Telefone</label>
                                        <div class="col-md-2">
                                            <input name="telefone" placeholder="Telefone" class="form-control"
                                                   type="text">
                                        </div>
                                        <label class="control-label col-md-1">Contato</label>
                                        <div class="col-md-2">
                                            <input name="contato" placeholder="Tel. contato"
                                                   class="form-control" type="text">
                                        </div>
                                        <label class="control-label col-md-1">E-mail</label>
                                        <div class="col-md-3">
                                            <input name="email" placeholder="E-mail" class="form-control" type="text"
                                                   size="100">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Hipótese diagnóstica</label>
                                        <div class="col-md-9">
                                            <input name="hipotese_diagnostica" placeholder="Hipótese diagnóstica"
                                                   class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Responsável</label>
                                        <div class="col-md-9">
                                            <input name="nome_responsavel" placeholder="Nome do responsável"
                                                   class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Observações</label>
                                        <div class="col-md-9">
                                            <textarea name="observacoes" placeholder="Observações" class="form-control"
                                                      rows="1"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal_curso" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Editar curso</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form_curso" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="" name="id_aluno"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2"><strong>Área/cliente</strong></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('', $id_diretoria, '', 'id="id_diretoria" class="form-control"'); ?>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="control-label col-md-2"><strong>Município</strong></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('', $municipio, '', 'id="municipio" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2"><strong>Unidade ensino</strong><span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_escola', $id_escola, '', 'id="id_escola" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2"><strong>Curso</strong><span
                                                    class="text-danger"> *</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_curso', $cursos, '', 'id="id_curso" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Semestre inicial</label>
                                        <div class="col-md-2">
                                            <input name="semestre_inicial" placeholder="s/aaaa"
                                                   class="form-control text-center semestre" type="text">
                                        </div>
                                        <label class="control-label col-md-2">Semestre final</label>
                                        <div class="col-md-2">
                                            <input name="semestre_final" placeholder="s/aaaa"
                                                   class="form-control text-center semestre" type="text">
                                        </div>
                                        <label class="control-label col-md-1">Nota</label>
                                        <div class="col-md-2">
                                            <input name="nota_geral" class="form-control text-center" type="text"
                                                   readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Status</label>
                                        <div class="col-md-1">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="status_ativo" value="1">Ativo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveCurso" onclick="save_curso()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </section>

<?php require_once APPPATH . 'views/end_js.php'; ?>

    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-combobox/css/bootstrap-combobox.css"); ?>">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Alunos';
        });
    </script>

    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;

        $('[name="cep"]').mask('00000-000');
        $('.date').mask('00/00/0000');
        $('.semestre').mask('0/0000');

        $('.combobox').combobox();


        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 500,
                'lengthMenu': [[5, 10, 25, 50, 100, 500, 1000], [5, 10, 25, 50, 100, 500, 1000]],
                'ajax': {
                    'url': '<?php echo site_url('ei/alunos/ajax_list') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#busca').serialize();
                        d.id_escola = '<?= $this->uri->rsegment(3, '') ?>';
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '34%',
                        'targets': [0]
                    },

                    {
                        'width': '33%',
                        'targets': [3, 4]
                    },
                    {
                        'className': 'text-center',
                        'targets': [1]
                    },
                    {
                        'mRender': function (data) {
                            if (data === null) {
                                data = '<span class="text-muted">Nenhum curso encontrado</span>';
                            }
                            return data;
                        },
                        'targets': [3]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [2, -1]
                    },
                    {
                        'targets': [1, 2, -1, -2],
                        'orderable': false,
                        'searchable': false
                    }
                ],
                'rowsGroup': [0, 1, 2, -1, 3]
            });

        });


        $('#limpa_filtro').on('click', function () {
            var busca = unescape($('#busca').serialize());
            $.each(busca.split('&'), function (index, elem) {
                var vals = elem.split('=');
                $("[name='" + vals[0] + "']").val($("[name='" + vals[0] + "'] option:first").val());
            });
            atualizarFiltro();
        });

        /*$('#id_diretoria, #id_escola').on('click', function (e) {
            if ($(this).attr('readonly') === true) {
                console.log(1);
                e.preventDefault();
            }
        });*/

        $('.estrutura').on('change', function () {
            atualizar_estrutura();
        });
        $('#pesquisar').on('change', function () {
            atualizar_Filtro();
        });

        $('#id_diretoria, #municipio').on('change', function () {
            atualizar_escolas();
        });

        $('#id_escola').on('change', function () {
            atualizar_periodos();
        });


        function atualizarFiltro() {
            $.ajax({
                'url': '<?php echo site_url('ei/alunos/atualizar_filtro') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'success': function (json) {
                    $('[name="busca[escola]"]').html($(json.escola).html());
                    $('[name="busca[status]"]').html($(json.status).html());
                    $('[name="busca[curso]"]').html($(json.curso).html());
                    reload_table();
                }
            });
        }


        function atualizar_estrutura() {
            $.ajax({
                'url': '<?php echo site_url('ei/alunos/ajax_estrutura') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'depto': $('#depto').val(),
                    'area': $('#area').val(),
                    'setor': $('#setor').val()
                },
                'success': function (json) {
                    $('#area').html($(json.area).html());
                    $('#setor').html($(json.setor).html());
                }
            });
        }


        function atualizar_escolas() {
            $.ajax({
                'url': '<?php echo site_url('ei/alunos/atualizar_escolas') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_diretoria': $('#id_diretoria').val(),
                    'municipio': $('#municipio').val(),
                    'id_escola': $('#id_escola').val()
                },
                'success': function (json) {
                    $('#municipio').html($(json.municipios).html());
                    $('#form_curso [name="id_escola"]').html($(json.escolas).html());
                }
            });
        }


        function atualizar_periodos() {
            var id = $('#id_escola').val()

            if (id.length > 0) {
                $.ajax({
                    'url': '<?php echo site_url('ei/alunos/atualizar_periodos') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        $('#form_curso [name="id_curso"]').html($(json.cursos).html())
                        // $('#periodo_manha').prop('disabled', json.periodo_manha === '0');
                        // $('#periodo_tarde').prop('disabled', json.periodo_tarde === '0');
                        // $('#periodo_noite').prop('disabled', json.periodo_noite === '0');
                    }
                });
            } else {
                $('#periodo_manha, #periodo_tarde, #periodo_noite').prop('disabled', false);
            }
        }


        function add_aluno() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#pesquisar_aluno').show();
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar aluno');
            $('.combo_nivel1').hide();
        }


        function add_curso(id_aluno) {
            save_method = 'add';
            $('#form_curso')[0].reset();
            $('#form_curso input[type="hidden"]').val('');
            $('#form_curso input[name="id_aluno"]').val(id_aluno);

            $('#id_diretoria, #id_escola, #id_curso').attr('readonly', false);
            $('#id_diretoria option').prop('disabled', false);
            $('#id_escola option').prop('disabled', false);
            $('#id_curso option').prop('disabled', false);
            // $('#form_curso [name="semestre_inicial"]').prop('readonly', false);

            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_curso').modal('show');
            $('.modal-title').text('Adicionar curso');
            $('.combo_nivel1').hide();
        }


        function edit_aluno(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('ei/alunos/ajax_edit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('#id_diretoria').val(json.id_diretoria);
                    $('#id_escola').val(json.id_escola);
                    // $('#periodo_manha').prop('disabled', json.escola_manha === '0');
                    // $('#periodo_tarde').prop('disabled', json.escola_tarde === '0');
                    // $('#periodo_noite').prop('disabled', json.escola_noite === '0');
                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').is(':checkbox') === false) {
                            $('#form [name="' + key + '"]').val(value);
                        } else {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        }
                    });
                    // console.log($('#form [name="nome"]').combobox());
                    // $('#form [name="nome"]').combobox('value', json.nome);
                    // $('#form [name="nome"]').data('combobox').refresh();

                    $('#pesquisar_aluno').hide();
                    $('.modal-title').text('Editar aluno');
                    $('#modal_form').modal('show');

                }
            });
        }


        function edit_curso(id) {
            save_method = 'update';
            $('#form_curso')[0].reset();
            $('#form_curso input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('ei/alunos/ajax_editCurso') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $.each(json, function (key, value) {
                        $('#id_diretoria').val(json.id_diretoria);
                        if ($('#form_curso [name="' + key + '"]').is(':checkbox') === false) {
                            $('#form_curso [name="' + key + '"]').val(value);
                        } else {
                            $('#form_curso [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        }
                    });

                    $('#id_diretoria, #id_escola, #id_curso').attr('readonly', true);
                    $('#id_diretoria option:not(:selected)').prop('disabled', true);
                    $('#id_escola option:not(:selected)').prop('disabled', true);
                    $('#id_curso option:not(:selected)').prop('disabled', true);
                    // $('#form_curso [name="semestre_inicial"]').prop('readonly', true);

                    $('.modal-title').text('Editar curso');
                    $('#modal_curso').modal('show');
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function save() {
            var url;
            if (save_method === 'add') {
                url = '<?php echo site_url('ei/alunos/ajax_add') ?>';
            } else {
                url = '<?php echo site_url('ei/alunos/ajax_update') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSave').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function save_curso() {
            var url;
            if (save_method === 'add') {
                url = '<?php echo site_url('ei/alunos/ajax_addCurso') ?>';
            } else {
                url = '<?php echo site_url('ei/alunos/ajax_updateCurso') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form_curso').serialize(),
                'dataType': 'json',
                'beforeSend': function () {
                    $('#btnSaveCurso').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_curso').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSaveCurso').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_aluno(id) {
            if (confirm('Deseja remover o(a) aluno(a)?')) {
                $.ajax({
                    'url': '<?php echo site_url('ei/alunos/ajax_delete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        reload_table();
                    }
                });
            }
        }


        function delete_curso(id) {
            if (confirm('Deseja remover o curso?')) {
                $.ajax({
                    'url': '<?php echo site_url('ei/alunos/ajax_deleteCurso') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        reload_table();
                    }
                });
            }
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>
