(function ($) {
    'use strict';

    const RUTAS = {
        'EDITAR': `${jida.urls.actual}/gestion`,
        'ELIMINAR': `${jida.urls.actual}/eliminar`,
        'ENVIO': `${jida.urls.actual}/carga`
    };

    let $btn = $('#btnCargaImagen');
    let $galeria = $('.jida-galeria-media');
    let urlEnvio = $btn.data('url-envio');

    let galeria = new Galeria($galeria, RUTAS);
    let acciones = new Acciones(galeria, RUTAS);

    $(window).on('load', function () {
        let secciones = $galeria.find('section');
        if (secciones.length !== 0) {
            $('.msj-no-img').hide();
        }
    });

    $galeria.on('click', '[data-accion="editar"]', evento => acciones.editar(evento));
    $galeria.on('click', '[data-accion="eliminar"]', evento => acciones.eliminar(evento));

    $btn.jCargaFile({
        'name': 'imagen',
        'multiple': true,
        'parametros': {'modelo': 'este es el modelo'},
        'url': urlEnvio,
        'onLoad': galeria.renderizar,
        'postCarga': galeria.actualizar
    });

})(jQuery);