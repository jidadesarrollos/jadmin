(function ($) {
    'use strict';

    let btnDelete = $('[data-jvista="confirm"]');

    btnDelete.click(event => {

        event.preventDefault();

        let mensaje =
            `El proceso que esta por realizar, eliminará de manera definitiva los archivos asociados. 
            '¿Esta seguro de continuar la operación?`;

        bootbox.confirm({
            'title': '¡Atención!',
            'message': mensaje,
            'buttons': {
                'confirm': {
                    'label': 'Eliminar',
                    'className': 'btn-danger'
                },
                'cancel': {
                    'label': 'Cerrar',
                    'className': 'btn-default'
                }
            },
            'callback': function (ejecutar) {
                if (ejecutar) {
                    $.ajax({
                        'url': btnDelete.attr('href'),
                        'type': 'post',
                        'data': {'eliminar': true},
                        'dataType': 'json'

                    }).done(respuesta => {
                        location.reload()
                    });
                }
            }

        });

    });

})(jQuery);