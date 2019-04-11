<!--right sidebar start-->
<div class="right-sidebar">
    <div class="right-stat-bar">
        <ul class="right-side-accordion" id="informacoes-curso">
            <?php
            setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
            date_default_timezone_set('America/Sao_Paulo');

            @$localização = $this->session->userdata('localizacao');
            if (empty($localização)) {
                $previsao_json = file_get_contents("http://api.openweathermap.org/data/2.5/forecast/daily?q=Sao Paulo-SP&lang=pt&units=metric&cnt=7&mode=json");
            } else {
                $latitude = $localização['latitude'];
                $longitude = $localização['longitude'];
                $previsao_json = file_get_contents("http://api.openweathermap.org/data/2.5/forecast/daily?lat=$latitude&lon=$longitude&lang=pt&units=metric&cnt=7&mode=json");
            }
            $previsao_decoded = json_decode($previsao_json);

            $this->load->helper(array('date'));

            $html = null;

            # Relógio
            $html .= "<li class=\"widget-collapsible\"><a href=\"#\" class=\"head widget-head red-bg active clearfix\"><span class=\"pull-left\">Relógio</span><span class=\"pull-right widget-collapse\"><i class=\"ico-minus\"></i></span></a><ul class=\"widget-container\" style=\"margin: 4px 0 5px 0px !important;\"><li>";

            $html .= "<section class=\"panel\" style=\"background-color: transparent;\"><ul id=\"clock\" style=\"margin-top: 0 !important;\"><li id=\"sec\"></li><li id=\"hour\"></li><li id=\"min\"></li></ul><ul class=\"clock-category\"></ul></section>";

            $html .= "</li></ul></li>";

            # Previsão do tempo
            if (!empty($previsao_decoded)) {

                $html .= "<li class=\"widget-collapsible\"><a href=\"#\" class=\"head widget-head red-bg active clearfix\"><span class=\"pull-left\">Previsão do tempo</span><span class=\"pull-right widget-collapse\"><i class=\"ico-minus\"></i></span></a><ul class=\"widget-container\"><li>";

                $html .= "<div class=\"prog-row side-mini-stat\"><div class=\"col-md-12\"><h4>{$previsao_decoded->city->name}</h4></div></div>";

                $html .= "<div class=\"prog-row side-mini-stat\"><div class=\"side-graph-info\"><h4>Hoje</h4><p>" . floor($previsao_decoded->list['0']->temp->day) . "º</p></div><div class=\"side-mini-graph\"><div class=\"p-delivery\"><img src=\"http://openweathermap.org/img/w/{$previsao_decoded->list['0']->weather['0']->icon}.png\" class=\"text-primary\" style=\"margin-top: -8%;\"></div></div></div>";


                for ($x = 1; $x <= 5; $x++) {
                    if ($x == 1) {
                        $data_previsao = "$x day";
                    } else {
                        $data_previsao = "$x days";
                    }

                    $html .= "<div class=\"prog-row side-mini-stat\"><div class=\"side-graph-info\"><h4>" . strftime('%a', strtotime("+$data_previsao")) . "</h4><p>" . floor($previsao_decoded->list[$x]->temp->day) . "º</p></div><div class=\"side-mini-graph\"><div class=\"p-delivery\"><img src=\"http://openweathermap.org/img/w/{$previsao_decoded->list[$x]->weather['0']->icon}.png\" class=\"text-primary\" style=\"margin-top: -8%;\"></div></div></div>";
                }

                $html .= "</li></ul></li>";
            }
            echo $html;
            ?>
        </ul>
    </div>
</div>
<!--right sidebar end-->