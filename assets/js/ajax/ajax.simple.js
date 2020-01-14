$(function() {
    $('form.ajax-simple').submit(function() {
        var erro, is_focus = 0;

        $('input.valida, select.valida, textarea.valida', this).each(function() {
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

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
			dataType: 'json',
            data: $(this).serialize(),
            beforeSend: function() {
                $('html, body').animate({scrollTop: 0}, 1500);                
                if(aviso.html().length === 0) {
                    aviso.html('<div class="alert alert-info">Carregando...</div>').hide().slideDown();
                } else {
                    aviso.html('<div class="alert alert-info">Carregando...</div>');
                }
            },
            error: function() {
                aviso.html('<div class="alert alert-danger">Erro, tente novamente!</div>').fadeIn('slow');
            },
            success: function(data) {
                $('html, body').animate({scrollTop: 0}, 1500);
                if (parseInt(data['retorno'])) {
                    aviso.html('<div class="alert alert-success">' + data['aviso'] + '</div>').fadeIn('slow', function() {
                        if (parseInt(data['redireciona']))
                            window.location = data['pagina'];
                    });
                } else {
                    aviso.html('<div class="alert alert-danger">' + data['aviso'] + '</div>').fadeIn('slow');
                }
            }
        });

        return false;
    });
});
