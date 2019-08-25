<?php require_once APPPATH . 'views/header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('cd/apontamento') ?>">Apontamentos diários</a></li>
                        <li class="active">Gerenciar Alunos</li>
                    </ol>
                    <button class="btn btn-success" onclick="add_aluno()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar aluno
                    </button>
                    <a class="btn btn-success" href="<?= site_url('cd/alunos/importar') ?>"><i
                                class="glyphicon glyphicon-import"></i>
                        Importar alunos
                    </a>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="control-label">Departamento</label>
                                            <?php echo form_dropdown('busca[depto]', $depto, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Diretoria de ensino/Prefeitura</label>
                                            <?php echo form_dropdown('busca[diretoria]', $diretoria, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Unidade de ensino</label>
                                            <?php echo form_dropdown('busca[escola]', $escola, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="control-label">Supervisor</label>
                                            <?php echo form_dropdown('busca[supervisor]', $supervisor, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Períodos</label><br>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="busca[periodo_manha]" value="1"
                                                       onchange="atualizarFiltro()"> Manhã
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="busca[periodo_tarde]" value="1"
                                                       onchange="atualizarFiltro()"> Tarde
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="busca[periodo_noite]" value="1"
                                                       onchange="atualizarFiltro()"> Noite
                                            </label>
                                        </div>
                                        <div class="col-md-4">
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
                            <th>Diretoria de ensino</th>
                            <th>Unidade escolar</th>
                            <th>Aluno</th>
                            <th>Período(s)</th>
                            <th>Status</th>
                            <th>Hipótese diagnóstica</th>
                            <th>Ações</th>
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
                            <h3 class="modal-title">Editar aluno</h3>
                        </div>
                        <div class="modal-body form">
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome aluno</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Nome do aluno" class="form-control"
                                                   type="text">
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
                                        <div class="col-md-9">
                                            <input name="municipio" placeholder="Município" class="form-control"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">CEP</label>
                                        <div class="col-md-3">
                                            <input name="cep" placeholder="CEP" class="form-control" type="text">
                                        </div>
                                        <label class="control-label col-md-2">Telefone</label>
                                        <div class="col-md-3">
                                            <input name="telefone" placeholder="Telefone" class="form-control"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Contato</label>
                                        <div class="col-md-9">
                                            <input name="contato" placeholder="Tel. contato"
                                                   class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">E-mail</label>
                                        <div class="col-md-9">
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
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Diretoria de ensino</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('', $id_diretoria, '', 'id="id_diretoria" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Unidade escolar</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_escola', $id_escola, '', 'id="id_escola" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Status</label>
                                        <div class="col col-lg-4">
                                            <select class="form-control" name="status">
                                                <option value="A">Ativo</option>
                                                <option value="I">Inativo</option>
                                                <option value="N">Não frequentando</option>
                                                <option value="F">Afastado</option>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2">Data matrícula</label>
                                        <div class="col-md-3">
                                            <input name="data_matricula" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Data afastamento</label>
                                        <div class="col-md-3">
                                            <input name="data_afastamento" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date"
                                                   type="text">
                                        </div>
                                        <label class="control-label col-md-3">Data desligamento</label>
                                        <div class="col-md-3">
                                            <input name="data_desligamento" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date"
                                                   type="text">
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Período(s)</label>
                                        <div class="col col-lg-4">
                                            <label class="checkbox-inline">
                                                <input id="periodo_manha" name="periodo_manha" value="1"
                                                       type="checkbox"> Manhã
                                            </label>
                                            <label class="checkbox-inline">
                                                <input id="periodo_tarde" name="periodo_tarde" value="1"
                                                       type="checkbox"> Tarde
                                            </label>
                                            <label class="checkbox-inline">
                                                <input id="periodo_noite" name="periodo_noite" value="1"
                                                       type="checkbox"> Noite
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </section>

<?php require_once APPPATH . 'views/end_js.php'; ?>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Alunos';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;

        $(document).ready(function () {

            $('[name="cep"]').mask('00000-000');
            $('.date').mask('00/00/0000');

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': 500,
                'lengthMenu': [[5, 10, 25, 50, 100, 500, 1000], [5, 10, 25, 50, 100, 500, 1000]],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('cd/alunos/ajax_list') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#busca').serialize();
                        d.id_escola = '<?= $this->uri->rsegment(3, '') ?>';
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '25%',
                        'targets': [0, 1, 2, 5]
                    },
                    {
                        'className': 'text-center',
                        'targets': [4]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ]
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


        $('.estrutura').on('change', function () {
            atualizar_estrutura();
        });

        $('#pesquisar').on('change', function () {
            atualizar_Filtro();
        });

        $('#id_diretoria').on('change', function () {
            atualizar_escolas();
        });

        $('#id_escola').on('change', function () {
            atualizar_periodos();
        });


        function atualizarFiltro() {
            $.ajax({
                'url': '<?php echo site_url('cd/alunos/atualizar_filtro') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': $('#busca').serialize(),
                'success': function (json) {
                    $('[name="busca[diretoria]"]').html($(json.diretoria).html());
                    $('[name="busca[escola]').html($(json.escola).html());
                    $('[name="busca[supervisor]').html($(json.supervisor).html());
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function atualizar_estrutura() {
            $.ajax({
                'url': '<?php echo site_url('cd/alunos/ajax_estrutura') ?>',
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
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function atualizar_escolas() {
            $.ajax({
                'url': '<?php echo site_url('cd/alunos/atualizar_escolas') ?>',
                'type': 'POST',
                'dataType': 'html',
                'data': {
                    'id_diretoria': $('#id_diretoria').val(),
                    'id_escola': $('#id_escola').val()
                },
                'success': function (json) {
                    $('#id_escola').html($(json).html());
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function atualizar_periodos() {
            var id = $('#id_escola').val()

            if (id.length > 0) {
                $.ajax({
                    'url': '<?php echo site_url('cd/alunos/atualizar_periodos') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {
                        'id': id
                    },
                    'success': function (json) {
                        // $('#periodo_manha').prop('disabled', json.periodo_manha === '0');
                        // $('#periodo_tarde').prop('disabled', json.periodo_tarde === '0');
                        // $('#periodo_noite').prop('disabled', json.periodo_noite === '0');
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
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
            $('[name="tipo"] option').prop('disabled', false);
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar novo contrato');
            $('.combo_nivel1').hide();
        }


        function edit_aluno(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('#form input[type="hidden"]').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('cd/alunos/ajax_edit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('#id_diretoria').val(json.id_diretoria);
                    // $('#periodo_manha').prop('disabled', json.escola_manha === '0');
                    // $('#periodo_tarde').prop('disabled', json.escola_tarde === '0');
                    // $('#periodo_noite').prop('disabled', json.escola_noite === '0');
                    $.each(json, function (key, value) {
                        if ($('[name="' + key + '"]').is(':checkbox') === false) {
                            $('[name="' + key + '"]').val(value);
                        } else {
                            $('[name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        }
                    });

                    $('#modal_form').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function save() {
            var url;
            if (save_method === 'add') {
                url = '<?php echo site_url('cd/alunos/ajax_add') ?>';
            } else {
                url = '<?php echo site_url('cd/alunos/ajax_update') ?>';
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
                'error': function (jqXHR, textStatus, errorThrown) {
                    if (textStatus) {
                        alert(jqXHR.responseText);
                    } else {
                        alert('Error adding / update data');
                    }
                },
                'complete': function () {
                    $('#btnSave').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_aluno(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('cd/alunos/ajax_delete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function (json) {
                        if (json.erro) {
                            alert(json.erro);
                        }
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>