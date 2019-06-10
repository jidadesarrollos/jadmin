($ => {
    'use strict';

    let $totalImagenes = $('#total-imas');

    function postCarga(respuesta) {

        $('#spanCargaImg').remove();
        let $imagenes = $('.jcargafile');

        if (respuesta.error) {
            $listaImagenes.before(`<div class="alert alert-warning">${respuesta.msj}</div>`);
            $imagenes.remove();

            return;
        }

        let total = $totalImagenes.data('total') + parseInt(respuesta.data.length);
        $totalImagenes.attr('data-total', total);
        $totalImagenes.html(total);

        function renderizar(key, item) {

            if (key in respuesta.data) {

                let $item = $(item);
                let img = JSON.parse(respuesta.data[key].meta_data);
                let parametros = respuesta.ids[key];

                $item.attr('data-imagen', img.sm);
                $item.attr('data-parametros', parametros);
                $item.removeClass('jcargafile');

            }

        }

        $imagenes.each(renderizar());

    }

    function onload(e) {

    }

})(jQuery);