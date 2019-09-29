$(function () {
    $('form.ajax-upload').submit(function () {
        var erro, is_focus = 0;

        $('input.valida, select.valida, textarea.valida', this).each(function () {
            if ($(this).val() === '') {
                $(this).css('border-color', '#fe7b80');
                if (!is_focus) {
                    $(this).focus();
                    is_focus = 1;
                }
                erro = 1;
            } else
                $(this).css('border-color', '#cccccc');
        });

        if (erro) {
            $('html, body').animate({scrollTop: 0}, 1500);
            return false;
        }

        var aviso = $('#' + $(this).data('aviso'));

        var formaction = this.ownerDocument.activeElement.attributes.formaction;

        var options = {
            url: (formaction !== undefined && formaction.value !== undefined) ? formaction.value : this.action,
            dataType: 'json',
            beforeSend: function () {
                aviso.html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
                $("#box-progresso").show();
                $("#box-progresso .pbar .ui-progressbar-value").width('0%');
                $("#box-progresso .pbar").attr('aria-valuenow', '0');
            },
            uploadProgress: function (event, position, total, percentComplete) {
                $("#box-progresso .pbar .ui-progressbar-value").width(percentComplete + '%');
                $("#box-progresso .pbar").attr('aria-valuenow', percentComplete);
            },
            success: function () {
                $("#box-progresso .pbar .ui-progressbar-value").width('100%');
                $("#box-progresso .pbar").attr('aria-valuenow', '100');
            },
            complete: function (data) {
                var data = $.parseJSON(data.responseText);
                $('html, body').animate({scrollTop: 0}, 1500);
                if (parseInt(data['retorno'])) {
                    aviso.html('<div class="alert alert-success">' + data['aviso'] + '</div>').hide().fadeIn('slow', function () {
                        if (parseInt(data['redireciona']))
                            top.location.href = data['pagina'];
                    });
                } else {
                    aviso.html('<div class="alert alert-danger">' + data['aviso'] + '</div>').hide().fadeIn('slow');
                    $("#box-progresso").fadeOut('slow');
                }
            }
        };

        $(this).ajaxSubmit(options);
        return false;
    });
});