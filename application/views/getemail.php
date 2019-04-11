<?php

$html = null;
$nao_lidas = null;
# Verifica o resultado
if ($total_rows > 0) {
    $html .= <<<HTML
<tr style='font-weight: bolder;'>
    <td class='inbox-small-cells'>
    </td>
    <td class='inbox-small-cells'>&nbsp;</td>
    <td class='view-message  dont-show'>Remetente</td>
    <td class='view-message'>
        Assunto
    </td>
    <td class='view-message text-right'>Data | Hora</td>
</tr>
HTML;
    foreach ($busca->result() as $row) {
        $row->datacadastro = date('d/m/Y H:i', strtotime($row->datacadastro));

        # Verifica se já foi lido
        if ($row->status == 0) {
            $row->status = "unread";
            $nao_lidas += 1;
        }

        $url = site_url('email/visualizarentrada/') . "/$row->id";

        $html .= <<<HTML
<tr class='$row->status'>
    <td class='inbox-small-cells'>
        <input type='checkbox' class='mail-checkbox' value='$row->id' name='idEmail[]'>
    </td>
    <td class='inbox-small-cells'>&nbsp;</td>
    <td class='view-message  dont-show'><a href='{$url}'>$row->remetente_mensagem</a></td>
    <td class='view-message'>
        <a href='{$url}'>$row->titulo</a>
    </td>
    <td class='view-message  text-right'>$row->datacadastro</td>
</tr>
HTML;
    }
} else {
    $html .= <<<HTML
<tr class=''>
    <td class='view-message view-message'></td>
    <td class='view-message view-message' colspan='5'>
        Nenhuma mensagem localizada
    </td>
</tr>
HTML;
}

$html = str_replace("\n", "", $html);

echo <<<HTML
        <script>
            $("#html-emails").html("$html");
            $(".total").html("$total_rows");
            $(".nao-lidas").html("$nao_lidas");
        </script>
HTML;
?>