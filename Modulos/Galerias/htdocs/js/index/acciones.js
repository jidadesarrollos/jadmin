function Acciones(galeria, RUTAS) {
    'use strict';

    const textos = {
        'eliminar': {
            'titulo': 'Eliminar Imagen',
            'mensaje': ` El proceso que esta por realizar, eliminará de manera definitiva el archivo.
        ¿Esta seguro de continuar la operación?`
        }
    };

    let llamadaAjax = (parametros, callback) => $.ajax(parametros).done(callback);

    this.editar = evento => {

        let $target = $(evento.currentTarget);
        let id = $target.closest('figure').find('.item').attr('data-id');

        llamadaAjax(
            {'url': `${RUTAS.EDITAR}/${id}`},
            respuesta => bootbox.dialog({'message': respuesta})
        );

    };

    this.eliminar = evento => {

        let $target = $(evento.currentTarget);
        let id = $target.closest('figure').find('.item').attr('data-id');
        let tpl = $('#tpl-eliminar').html();

        bootbox.confirm({
            'title': textos.eliminar.titulo,
            'message': textos.eliminar.mensaje,
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
                    llamadaAjax({
                        'url': `${jida.urls.actual}/eliminar/${id}`,
                        'type': 'post',
                        'data': {'eliminar': true},
                        'dataType': 'json'

                    }, galeria.eliminarImagen)
                }
            }

        });
    };

}