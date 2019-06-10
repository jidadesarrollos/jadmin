(function ($) {
    'use strict';

    let page = $('#gestion-media-page').get(0);
    let $form = $(page.querySelector('form'));

    let btnCierre = page.querySelector('.btn-cierre');
    let btnGuardar = page.querySelector('#btnFormularioMedia');

    btnGuardar.addEventListener('click', (e) => enviarForm(e, $form));

    btnCierre.addEventListener('click', () => bootbox.hideAll());

    function imprimirMensaje(tipo, mensaje) {

        let alert = page.querySelector('.alert');
        if (alert) {
            alert.innerHTML = '';
            alert.insertAdjacentHTML('afterbegin', mensaje);
            return;
        }

        let titulo = page.querySelector('.titulo');
        let plantilla = Mustache.render(tplMensaje, {
            'mensaje': mensaje,
            'css': CSS_MENSAJES[tipo]
        });

        titulo.insertAdjacentHTML('afterend', plantilla);

    }

    function enviarForm(evento, form, boton) {

        evento.preventDefault();
        let url = form.attr('action');
        let formData = form.serializeArray();
        let data = {};
        $(formData ).each(function(index, obj){
            data[obj.name] = obj.value;
        });
        data.btnFormularioMedia = 'send';

        $.ajax({
            'url': url,
            'data': data,
            'type': 'post',
            'dataType': 'json'

        }).done(respuesta => {

            bootbox.hideAll();

        });

    }

    $form.on('jida:form.validado', enviarForm);

})(jQuery);