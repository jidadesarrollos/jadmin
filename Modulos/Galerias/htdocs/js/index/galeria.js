function Galeria($galeria, RUTAS) {
    'use strict';

    let tplMensaje = `<span id="spanCargaImg" class="label label-info">Guardando Imagen...</span>`;
    let $totalImagenes = $('#total-imas');

    function actualizarItems(cantidad) {
        let $total = parseInt($totalImagenes.data('total')) + cantidad;
        //$total.data('total', total).html(total);
    }

    this.actualizar = respuesta => {

        console.log(1, respuesta);
        $('#spanCargaImg').remove();
        let $imagenes = $('.jcargafile');

        if (respuesta.error) {
            $listaImagenes.before(`<div class="alert alert-warning">${respuesta.msj}</div>`);
            $imagenes.remove();

            return;
        }

        actualizarItems(respuesta.data.length);

        $imagenes.each((key, item) => {

            if (key in respuesta.data) {

                let $item = $(item);
                let $btns = $item.find('.disabled');
                let img = respuesta.data[key];

                $item.find('.item').attr('data-src', img.urls.original);
                $item.find('.item').attr('data-id', img.id);
                $item.removeClass('jcargafile');
                $btns.removeClass('disabled');

                let $galeria = $('#lightgallery');
                $galeria.data('lightGallery').destroy(true);
                $galeria.lightGallery({
                    mode: 'lg-fade',
                    selector: '.overlay'
                });

            }

        });

    };

    this.renderizar = e => {

        let image = new Image();
        let ele = e.currentTarget;
        image.src = ele.result;
        image.className = 'responsive';

        if ($('.msj-no-img').length) {
            $('.msj-no-img').hide();
        }

        //this.mostrarLeyenda(textos.guardar);
        $('#mensaje-carga').after(tplMensaje);

        let tpl = $('#imgTemplate').html();
        let render = Mustache.render(tpl,
            {
                'src': ele.result,
                'alt': 'Imagen Preview'
            }
        );

        $galeria.append(render);

    };

    this.eliminarImagen = respuesta => {

        if (respuesta.procesado) {

            let $blocImagen = $(`[data-id=${respuesta.id}]`).closest('section');
            $blocImagen.remove();

            let $galeria = $('#lightgallery');
            $galeria.data('lightGallery').destroy(true);
            $galeria.lightGallery({
                mode: 'lg-fade',
                selector: '.overlay'
            });

            if ($('section').length === 1) {
                $('.msj-no-img').show();
            }

        }

    };

}