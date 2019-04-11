<?php
require_once "header.php";
?>
    <style>
        <?php foreach($notas_maximas as $nota_maxima): ?>
        .tipo_<?= $nota_maxima ?> {
            color: #fff;
            background-color: #1e3946;
            border-color: #123946;
        }

        <?php /*endforeach; */?>
        <!--
        <?php /*foreach($notas_maximas2 as $nota_maxima2): */?>
        .tipo_<?/*= $nota_maxima2 */?> {
            background-color: #417c92;
            border-color: #347892;
            color: #fff;
        }

        <?php /*endforeach; */?>
        <?php /*foreach($notas_maximas3 as $nota_maxima3): */?>
        .tipo_<?/*= $nota_maxima3 */?> {
            background-color: #afdade;
            border-color: #90ccde;
            color: #fff;
        }

        -->

        <?php endforeach; ?>

        .btn-success {
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

        .text-nowrap {
            white-space: nowrap;
        }

        tr.group, tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div style="color: #000;">
                <table class="table table-condensed recrutamento">
                    <thead>
                    <tr style='border-top: 5px solid #ddd;'>
                        <th colspan="3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <img src="<?= base_url($foto) ?>" align="left"
                                         style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                    <p class="text-left">
                                        <img src="<?= base_url($foto_descricao) ?>" align="left"
                                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                    </p>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr style='border-top: 5px solid #ddd;'>
                        <th colspan="3">
                            <h2 class="text-center" style="margin-top: 10px;">AVALIAÇÃO DE PERSONALIDADE</h2>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    </tr>
                    <tr style='border-top: 5px solid #ddd;'>
                        <td nowrap>
                            <h5><strong>Teste aplicado: </strong><?= $teste->modelo ?></h5>
                            <h5><strong>Data atual: </strong><?= date('d/m/Y') ?></h5>
                        </td>
                        <td nowrap>
                            <h5><strong>Data início teste: </strong><?= $teste->data_inicio ?></h5>
                            <h5><strong>Data término teste: </strong><?= $teste->data_termino ?></h5>
                        </td>
                        <td class="text-right">
                            <a id="pdf" class="btn btn-sm btn-danger"
                               href="<?= site_url('recrutamento/pdfEneagrama/' . $this->uri->rsegment(3)); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                            <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        </td>
                    </tr>
                    <tr style='border-top: 5px solid #ddd;'>
                        <td><strong>Profissional avaliado:</strong> <?= $teste->candidato ?></td>
                        <td colspan="2"><strong>Cargo/função alvo:</strong> <?= $teste->cargo ?></td>
                    </tr>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-md-6 text-center">
                        <h4>Tipos de Personalidade</h4>
                        <div id="chart_div"></div>
                        <!--<img src="<? /*= base_url('assets/images/eneagrama.jpg'); */ ?>" class="img-responsive"
                             alt="Eneagrama" style="margin-left: auto; margin-right: auto;">-->
                    </div>
                    <div class="col-md-6">
                        <h4>Perfil x Introversão-Extroversão</h4>
                        <table id="table" class="table table-bordered" cellspacing="0" width="100%"
                               style="border-radius: 0 !important;">
                            <thead>
                            <tr class="active">
                                <th>Perfil</th>
                                <th class="text-center">Extrovertido</th>
                                <th class="text-center">Ambivalente</th>
                                <th class="text-center">Introvertido</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="active">Instintivo</td>
                                <td class="text-center tipo_8">8</td>
                                <td class="text-center tipo_9">9</td>
                                <td class="text-center tipo_1">1</td>
                            </tr>
                            <tr>
                                <td class="active">Emocional</td>
                                <td class="text-center tipo_2">2</td>
                                <td class="text-center tipo_3">3</td>
                                <td class="text-center tipo_4">4</td>
                            </tr>
                            <tr>
                                <td class="active">Racional</td>
                                <td class="text-center tipo_7">7</td>
                                <td class="text-center tipo_6">6</td>
                                <td class="text-center tipo_5">5</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <h3>Distribuição de Traços de Personalidade Mapeados no Avaliado</h3>
                <div class="row">
                    <div class="col-md-12">
                        <table id="table_aspectos" class="table table-striped table-bordered" cellspacing="0"
                               width="100%"
                               style="border-radius: 0 !important;">
                            <thead>
                            <tr class="success">
                                <th colspan="6" class="text-center" style="font-size: large;"><strong>Aspectos
                                        Comportamentais</strong></th>
                            </tr>
                            <tr class="active">
                                <th colspan="2" rowspan="2" class="text-center" style="vertical-align: middle;">Tipos
                                    eneagramáticos
                                </th>
                                <th rowspan="2" class="text-center" style="vertical-align: middle;">Ponto de foco</th>
                                <th colspan="2" class="text-center">Agentes Motivadores de Ações</th>
                                <th class="text-center">Elemento</th>
                            </tr>
                            <tr class="active">
                                <th class="text-center">Positivos</th>
                                <th class="text-center">Negativos</th>
                                <th class="text-center">Compulsivo</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (in_array(1, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_1"><?= $eneagrama[1]; ?></td>
                                    <td>Perfeccionista | Reformista</td>
                                    <td>A organização</td>
                                    <td>Ser correto</td>
                                    <td>Ira</td>
                                    <td>Perfeccionismo/Crítica</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(2, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_2"><?= $eneagrama[2]; ?></td>
                                    <td>Prestativo | Manipulador</td>
                                    <td>Os outros</td>
                                    <td>Ser querido</td>
                                    <td>Orgulho</td>
                                    <td>Auto-engano</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(3, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_3"><?= $eneagrama[3]; ?></td>
                                    <td>Motivador | Competitivo</td>
                                    <td>A auto-imagem</td>
                                    <td>Ser admirado</td>
                                    <td>Vaidade</td>
                                    <td>Mentira</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(4, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_4"><?= $eneagrama[4]; ?></td>
                                    <td>Romântico | Idealista | Individualista</td>
                                    <td>As formas</td>
                                    <td>Ser diferente</td>
                                    <td>Inveja</td>
                                    <td>Insatisfação</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(5, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_5"><?= $eneagrama[5]; ?></td>
                                    <td>Analítico | Observador | Questionador</td>
                                    <td>O conhecimento</td>
                                    <td>Ter conhecimento</td>
                                    <td>Avareza</td>
                                    <td>Isolamento</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(6, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_6"><?= $eneagrama[6]; ?></td>
                                    <td>Precavido | Questionador</td>
                                    <td>A autoridade</td>
                                    <td>Estar seguro</td>
                                    <td>Medo</td>
                                    <td>Dúvida</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(7, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_7"><?= $eneagrama[7]; ?></td>
                                    <td>Entusiasta | Sonhador | Impulsivo</td>
                                    <td>A palavra</td>
                                    <td>Ter satisfação</td>
                                    <td>Gula</td>
                                    <td>Charlatanismo</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(8, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_8"><?= $eneagrama[8]; ?></td>
                                    <td>Confrontador | Líder</td>
                                    <td>A justiça</td>
                                    <td>Ser respeitado</td>
                                    <td>Luxúria</td>
                                    <td>Vingança</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(9, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_9"><?= $eneagrama[9]; ?></td>
                                    <td>Pacificador | Preservacionista</td>
                                    <td>O corpo</td>
                                    <td>Estar tranquilo</td>
                                    <td>Preguiça</td>
                                    <td>Indolência/Apatia</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table id="table_caracteristicas" class="table table-striped table-bordered" cellspacing="0"
                               width="100%"
                               style="border-radius: 0 !important;">
                            <thead>
                            <tr class="success">
                                <th colspan="4" class="text-center" style="font-size: large;"><strong>Características
                                        Majoritárias</strong></th>
                            </tr>
                            <tr class="active">
                                <th colspan="2" rowspan="2" class="text-center" style="vertical-align: middle;">Tipos
                                    eneagramáticos
                                </th>
                                <th colspan="2" class="text-center">Características</th>
                            </tr>
                            <tr class="active">
                                <th class="text-center">Positivos</th>
                                <th class="text-center">Negativos</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (in_array(1, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_1"><?= $eneagrama[1]; ?></td>
                                    <td>Perfeccionista | Reformista</td>
                                    <td>Determinação | Praticidade | Responsabilidade</td>
                                    <td>Irritabilidade | Hostilidade | Teimosia</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(2, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_2"><?= $eneagrama[2]; ?></td>
                                    <td>Prestativo | Manipulador</td>
                                    <td>Carisma | Disposição | Espírito envolvente</td>
                                    <td>Apego | Propensão para incriminar | Prepotência</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(3, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_3"><?= $eneagrama[3]; ?></td>
                                    <td>Motivador | Competitivo</td>
                                    <td>Flexibilidade | Foco | Espírito motivador</td>
                                    <td>Baixa autoestima | Tendência à manipulação</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(4, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_4"><?= $eneagrama[4]; ?></td>
                                    <td>Romântico | Idealista | Individualista</td>
                                    <td>Criatividade | Detalhista | Sensibilidade</td>
                                    <td>Espírito crítico | Depressão | Tendência a ser trágico</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(5, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_5"><?= $eneagrama[5]; ?></td>
                                    <td>Analítico | Observador | Questionador</td>
                                    <td>Analista | Especialista | Ponderação</td>
                                    <td>Calculista | Distante | Frio</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(6, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_6"><?= $eneagrama[6]; ?></td>
                                    <td>Precavido | Questionador</td>
                                    <td>Consequente | Lealdade | Espírito de equipe</td>
                                    <td>Apego | Moralidade | Rigidez de postura</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(7, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_7"><?= $eneagrama[7]; ?></td>
                                    <td>Entusiasta | Sonhador | Impulsivo</td>
                                    <td>Bom humor | Poder de improviso | Otimismo</td>
                                    <td>Alienação | Fanatismo | Utópico</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(8, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_8"><?= $eneagrama[8]; ?></td>
                                    <td>Confrontador | Líder</td>
                                    <td>Assertividade | Objetividade | Realizador</td>
                                    <td>Agressividade | Intolerância | Postura vingativa</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (in_array(9, $descritivos)): ?>
                                <tr>
                                    <td class="tipo_9"><?= $eneagrama[9]; ?></td>
                                    <td>Pacificador | Preservacionista</td>
                                    <td>Calma | Flexibilidade | Poder de mediação</td>
                                    <td>Apatia | Insegurança | Reduzido senso de direção</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php foreach ($descritivos as $descritivo): ?>
                    <br>
                    <div>
                        <?php switch ($descritivo): case 1: ?>
                            <h3>Personalidade Tipo - Perfeccionista</h3>
                            <hr class="tipo_1">
                            <h4>Ação-Prioridade</h4>
                            <p>
                                Pessoas perfeccionistas são centradas na ação, tendo forte senso prático, exigente dando
                                grande prioridade às tarefas a serem realizadas.
                            </p>
                            <h4>Vício</h4>
                            <p>
                                Pessoas perfeccionistas tem como vício emocional a “Raiva”. Essa característica, oriunda
                                do alto nível de exigência as torna frias e objetivas quanto ao que tem que ser feito
                                independentemente de quem, como, e custos para se atingir os objetivos
                            </p>
                            <h4>Desdobramentos negativos</h4>
                            <p>
                                A consequência negativa mais significativa do perfil perfeccionista é que o
                                posicionamento “duro” , intransigente e exigente; assumido como premissa para
                                praticamente tudo e todos, quer seja do lado pessoal quanto profissional. Como
                                desdobramento secundário temos em muitas situações o aparecimento da arrogância face à
                                falsa sensação de se estar o tempo todo corretos.
                            </p>
                            <h4>Áreas de atuação</h4>
                            <p>
                                Perfeccionistas se adaptam bem a áreas tais como : Contabilidade, financeiro,
                                organização e métodos, produção, engenharia. O seu senso prático e exigente é bastante
                                útil nas situações em que os temas principais são a organização, foco e realização;
                                contudo quando apresentam grande dificuldade de trabalho em equipe face ao seu elevado o
                                grau de exigência que normalmente transcende para os demais membros da equipe tornando
                                pesado o clima de trabalho.
                            </p>
                            <?php break; ?>
                        <?php case 2: ?>
                            <h3>Personalidade Tipo - Prestativo</h3>
                            <hr class="tipo_2">
                            <h4>Ação-Prioridade</h4>
                            <p>
                                Pessoas perfeccionistas são centradas na emoção, apresentando grande percepção dos
                                sentimentos das outras pessoas, o que lhes confere grande poder de persuasão.
                            </p>
                            <h4>Vício</h4>
                            <p>
                                Pessoas prestativas tem como vício emocional o “Orgulho” muitas vezes inconsciente, mas
                                que sustenta um comportamento de auto-suficiência (“Eu posso, eu sei, eu faço”) e
                                capacidade.
                            </p>
                            <h4>Desdobramentos negativos</h4>
                            <p>
                                A consequência negativa mais significativa do perfil “prestativo” é a dificuldade de
                                reconhecer suas debilidades e necessidades. Como desdobramento desta dificuldades
                                desenvolvem baixa tolerância a críticas ou observações negativas o que pode gerar
                                posicionamentos agressivos. Seu alto nível de empolgação e envolvimento usualmente
                                fomenta atitude e movimento em situações de marasmo, despertando nas pessoas a vontade
                                de agir e se envolver nos processos, contudo existe paralelamente um potencial para
                                desenvolvimento de sua compulsão, tornando os manipuladores agressivos, que cobram cada
                                movimento que tenham feito em direção ao outro, levando em condições extremas a colocar
                                as pessoas umas contra as outras.
                            </p>
                            <h4>Áreas de atuação</h4>
                            <p>
                                Perfeccionistas se adaptam bem a áreas tais como : Vendas, Recursos Humanos,
                                Secretariado posições em áreas assistenciais.
                            </p>
                            <?php break; ?>
                        <?php case 3: ?>
                            <h3>Personalidade Tipo – Competitivo</h3>
                            <hr class="tipo_3">
                            <h4>Ação-Prioridade</h4>
                            <p>
                                Pessoas deste perfil são centradas no planejamento e ação visando o reconhecimento e
                                sucesso próprios.
                            </p>
                            <h4>Vício</h4>
                            <p>
                                Pessoas perfeccionistas tem como vício emocional a “Vaidade”. Essa característica,
                                oriunda do alto nível de exigência interior pelo reconhecimento e sucesso.
                            </p>
                            <h4>Desdobramentos negativos</h4>
                            <p>
                                A consequência negativa mais significativa do perfil “prestativo” é a dificuldade de
                                reconhecer suas debilidades e necessidades. Como desdobramento desta dificuldades
                                desenvolvem posicionamento frio, impessoais, e calculistas, focados a resultados.
                                Usualmente permeiam essa característica para as pessoas envolvidas com os mesmos.
                                Assumem com frequência a filosofia “Os fins justificam os meios” o que os podem
                                torna-los perigosos quando não bem administrados.
                            </p>
                            <h4>Áreas de atuação</h4>
                            <p>
                                Competitivos se adaptam bem a áreas tais como : Vendas, advocacia, administração,
                                autônomos, consultores e assessores.
                            </p>
                            <?php break; ?>
                        <?php case 4: ?>
                            <h3>Personalidade Tipo - Romântico</h3>
                            <hr class="tipo_4">
                            <h4>Ação-Prioridade</h4>
                            <p>
                                Pessoas perfeccionistas são centradas na emoção, sendo bastante sensíveis ao clima do
                                ambiente o que as leva a certa instabilidade emocional, especialmente porque apresentam
                                paralelamente o sentimento de falta, de algo estar incompleto.
                            </p>
                            <h4>Vício</h4>
                            <p>
                                Pessoas românticas tem como vício emocional a “Inveja” inconsciente, contudo essa inveja
                                usualmente não é um elemento catalizador para ações negativas. Possuem senso crítico
                                apurado e o gosto pelo diferente criando um ambiente humano agradável. Quando sentem
                                liberdade para se expressar, inundam o ambiente com cores. Mas em sua compulsão,
                                tornam-se melancólicos, carregando o ambiente com sua sensação de insatisfação.
                            </p>
                            <h4>Desdobramentos negativos</h4>
                            <p>
                                A consequência negativa mais significativa do perfil romântico é insatisfação consigo
                                mesmo e com a situação, o que as torna queixosos, críticos e irônicos.
                            </p>
                            <h4>Áreas de atuação</h4>
                            <p>
                                Românticos se adaptam bem a áreas tais como : Estilistas, decoradores, psicólogos,
                                jornalistas.
                            </p>
                            <?php break; ?>
                        <?php case 5: ?>
                            <h3>Personalidade Tipo - Observador</h3>
                            <hr class="tipo_5">
                            <h4>Ação-Prioridade</h4>
                            <p>
                                Pessoas perfeccionistas são centradas na mente, apresentando grande compulsão pelo
                                entendimento de tudo; são extremamente racionais e planejadores.
                            </p>
                            <h4>Vício</h4>
                            <p>
                                Pessoas prestativas tem como vício emocional a “Avareza”; Observadores podem apresentar
                                uma atitude de não-envolvimento, como se preferisse estar em segundo plano, de onde pode
                                ver melhor sem perder seu senso crítico.
                            </p>
                            <h4>Desdobramentos negativos</h4>
                            <p>
                                A consequência negativa mais significativa do perfil “Observador” é que usualmente são
                                frios e calculistas, visto que creem que que a razão supera a emoção como meio para se
                                conseguir atingir o que desejam. Sua compulsão, as tornam distantes e inacessíveis; com
                                respostas curtas e diretas afastam as pessoas, mostrando pouco ou nenhum apreço pela
                                presença delas.
                            </p>
                            <h4>Áreas de atuação</h4>
                            <p>
                                Perfeccionistas se adaptam bem a áreas tais como : Engenharia, pesquisa, informática.
                            </p>
                            <?php break; ?>
                        <?php case 6: ?>
                            <h3>Personalidade Tipo - Questionador</h3>
                            <hr class="tipo_6">
                            <h4>Ação-Prioridade</h4>
                            <p>
                                Pessoas perfeccionistas são centradas na emoção ou emoção dependendo do que julgam ser
                                necessário para obter o controle das situações. Usualmente são atentas e levemente
                                desconfiadas.
                            </p>
                            <h4>Vício</h4>
                            <p>
                                Pessoas prestativas tem como vício emocional o “Medo” associado a proteção da
                                autoimagem.
                            </p>
                            <h4>Desdobramentos negativos</h4>
                            <p>
                                A consequência negativa mais significativa do perfil “questionador” é desconfiança que
                                os mesmos tem das pessoas e das situações que os cercam. A lealdade é uma marca
                                registrada deste padrão de comportamento. Mas na compulsão, tornam-se rígidos cobradores
                                de normas e procedimentos, como maneira de garantir o controle.
                            </p>
                            <h4>Áreas de atuação</h4>
                            <p>
                                Perfeccionistas se adaptam bem a áreas tais como : Produção, financeiro, Recursos
                                Humanos.
                            </p>
                            <?php break; ?>
                        <?php case 7: ?>
                            <h3>Personalidade Tipo - Sonhador</h3>
                            <hr class="tipo_7">
                            <h4>Ação-Prioridade</h4>
                            <p>
                                Pessoas sonhadoras são centradas na mente, apresentando grande agilidade mental para
                                lidar com múltiplas situações ao mesmo tempo. Usualmente dão prioridade às atividades
                                que lhes dão prazer.
                            </p>
                            <h4>Vício</h4>
                            <p>
                                Pessoas prestativas tem como vício emocional a “Gula” muitas vezes inconsciente. A
                                “gula” mencionada não é só a gula por alimentos, mas principalmente pela quantidade de
                                atividades que estas pessoas se permitem envolver-se com.
                            </p>
                            <h4>Desdobramentos negativos</h4>
                            <p>
                                A consequência negativa mais significativa do perfil “prestativo” é a dificuldade de
                                reconhecer suas debilidades e necessidades. Como desdobramento desta dificuldades
                                desenvolvem um posicionamento superficial, face a volume de coisas que desenvolvem em
                                paralelo, o que as sobrecarrega. O otimismo exagerado também revela pessoas que evitam o
                                desprazer, olhando para o mundo com óculos cor-de-rosa. Este otimismo e criatividade são
                                muito úteis nas situações em que o tema principal é a busca de novas soluções. Mas em
                                sua compulsão, são indisciplinados e irresponsáveis, fugindo da rotina por meio de
                                argumentos manipuladores. Chocam-se com aqueles que são mais rígidos e querem seguir os
                                passos previstos
                            </p>
                            <h4>Áreas de atuação</h4>
                            <p>
                                Perfeccionistas se adaptam bem a áreas tais como : Marketing, vendas, planejamento,
                                negociação.
                            </p>
                            <?php break; ?>
                        <?php case 8: ?>
                            <h3>Personalidade Tipo - Confrontador</h3>
                            <hr class="tipo_8">
                            <h4>Ação-Prioridade</h4>
                            <p>
                                Pessoas confrontadoras são centradas na ação, apresentando grande facilidade em mandar e
                                liderar; usualmente são muito focadas em resultados.
                            </p>
                            <h4>Vício</h4>
                            <p>
                                Pessoas prestativas tem como vício emocional a “Luxuria” na maioria das vezes
                                inconsciente. Tudo ao seu redor tem de ser intenso e desafiador; visto que apresentam
                                significativa facilidade com que se posicionam a respeito do que querem, expressando-se
                                de forma direta e objetiva, intimidando com sua aparente segurança.
                            </p>
                            <h4>Desdobramentos negativos</h4>
                            <p>
                                A consequência negativa mais significativa do perfil “prestativo” é a dificuldade de
                                reconhecer suas debilidades e necessidades. Como desdobramento desta dificuldades
                                desenvolvem-se como pessoas insensíveis e apegadas à força e ao poder. Dominadores
                                agressivos, tornam-se conhecidos como verdadeiros rolos-compressores. Facilmente tendem
                                ao exagero, desconsiderando o que os outros pensam e sentem. Podem assumir um perfil de
                                empresário megalômano, que cresce rapidamente e centraliza em si todo o poder.
                            </p>
                            <h4>Áreas de atuação</h4>
                            <p>
                                Perfeccionistas se adaptam bem a áreas tais como : Liderança, Planejamento estratégico,
                                Financeiro.
                            </p>
                            <?php break; ?>
                        <?php case 9: ?>
                            <h3>Personalidade Tipo - Preservacionista</h3>
                            <hr class="tipo_9">
                            <h4>Ação-Prioridade</h4>
                            <p>
                                Pessoas preservacionistas são centradas na emoção ou na mente, apresentando atitudes
                                mediadoras. Assumem como prioridade o bem comum.
                            </p>
                            <h4>Vício</h4>
                            <p>
                                Pessoas prestativas tem como vício emocional a “Indolência” (Que se põe acima das
                                paixões, de forma indiferente para com outras pessoas). O Preservacionista busca
                                preservar o status, evitando conflito em prol da paz e da tranquilidade.
                            </p>
                            <h4>Desdobramentos negativos</h4>
                            <p>
                                A consequência negativa mais significativa do perfil “preservacionista” é a dificuldade
                                de reconhecer suas debilidades e necessidades. São pessoas apáticas, que desenvolveram
                                um estado de anestesia para não sofrerem atritos com a realidade. A atitude de
                                hiper-flexibilidade os deixa amorfos, adequando-os facilmente ao ambiente. São pessoas
                                que expressam serenidade e calma, mesmo não sendo estes seus sentimentos reais. A apatia
                                emocional os deixa indecisos, a ponto de serem conhecidos como “tanto faz”.
                            </p>
                            <h4>Áreas de atuação</h4>
                            <p>
                                Perfeccionistas se adaptam bem a áreas tais como : Administração, secretariado,
                                atendimento ao público, funções auxiliares.
                            </p>
                        <?php endswitch; ?>
                    </div>
                    <br>
                <?php endforeach; ?>
            </div>
        </section>
    </section>
    <!--main content end-->

    <!-- Js -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - AVALIAÇÃO DE PERSONALIDADE - <?= $teste->candidato ?>';

            google.charts.load('current', {packages: ['corechart', 'bar']});
            google.charts.setOnLoadCallback(drawBasic);
        });

        function drawBasic() {

            var tipos = [['Descritivo', 'Nota', {role: 'nodeClass'}]];
            <?php foreach($tipos as $k => $tipo): ?>
            tipos.push(['<?= $k . ' - ' . $tipo; ?>', <?= $eneagrama[$k]; ?>, '<?= 'tipo_' . $k; ?>']);
            <?php endforeach; ?>
            var data = google.visualization.arrayToDataTable(tipos);

            var options = {
                legend: {position: "none"},
                width: '100%',
                chartArea: {width: '50%', height: '100%'},
                annotations: {
                    alwaysOutside: true,
                    textStyle: {
                        fontSize: 12,
                        auraColor: 'none',
                        color: '#555'
                    },
                    boxStyle: {
                        stroke: '#ccc',
                        strokeWidth: 1,
                        gradient: {
                            color1: '#f3e5f5',
                            color2: '#f3e5f5',
                            x1: '0%', y1: '0%',
                            x2: '100%', y2: '100%'
                        }
                    }
                },
                hAxis: {
                    minValue: 0
                }
            };

            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

            chart.draw(data, options);
        }

        function gerarRelatorio() {
            $('#pdf').prop('disabled', true);
            $.ajax({
                url: "<?= site_url('recrutamento/pdfEneagrama/' . $this->uri->rsegment(3)); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    chart: $('#chart_div').html(),
                },
                success: function (dat) {
                    location.href = '<?= site_url('recrutamento/downloadPdf'); ?>/' + dat.pacote;
                    setTimeout(function () {
                        $('#pdf').prop('disabled', false);
                    }, 3000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Não foi possível gerar o relatório.');
                    $('#pdf').prop('disabled', false);
                }
            });
        }
    </script>
<?php
require_once "end_js.php";
require_once "end_html.php";
?>