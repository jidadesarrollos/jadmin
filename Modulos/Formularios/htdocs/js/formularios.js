(function ($) {
    'use strict';

    //const URL_BASE = $('body').data('url');
    const URL_BASE = "/";
    var $container = $('#jidaGestionCampos');

    var $listaCampos = $container.find('#listaCamposFormulario');
    var $formContainer = $container.find('.form-campos');
    var $btnOrden = $container.find('#btnEditOrden');

    function addSortable() {
        $listaCampos.find('li').addClass('selecionable');
        $listaCampos.sortable();
    }

    function guardarOrden() {

        var orden = $listaCampos.sortable('toArray');
        var listadoOrdenado = {};
        orden.forEach(function (item, key) {

            var $elemento = $('#' + item);
            if ($elemento.data('campo') != undefined) {
                listadoOrdenado[$elemento.data('campo')] = key;
            }

        })
        var parametros = {
            'campos': listadoOrdenado,
            'formulario': $container.data('formulario'),
            'modulo': $container.data('modulo')
        };

        function response(r) {

            $("#jidaFormConfiguracion").html(r.msj);

            if (r.ejecutado == true) {
                $formContainer.html(r.msj);
                $listaCampos.find('li').removeClass('seleccionable');
                $listaCampos.sortable("destroy");
                $formContainer.html(r);
            }

        }

        function fail(r) {
            $formContainer.html(r);
            console.log("error?", r);

        }

        var config = {
            url: $listaCampos.data('url').replace('configuracion', 'ordenar'),
            method: 'post',
            dataType: 'json',
            data: parametros
        };
        $.ajax(config).done(response).fail(fail);

        return true;
    }//fin guardarOrden

    var ordenar = function (e) {

        var $this = $(this);
        if (this.value == 1) {

            addSortable();
            $this.html("<span class=\"fa fa-save fa-lg\"></span> Finalizar").val(2);

        } else if (this.value == 2) {

            $(this)
                .val(1)
                .html('<span class=\"fa fa-edit fa-lg\"></span> Editar Orden');
            guardarOrden();
        }

    };
    var abrirFormulario = function (e) {

        console.log("abrimos formulario");
        e.preventDefault();
        var $target = $(this);
        var valorSeleccion = $target.data('campo');
        var accion = $target.attr('name');
        var $this = $target;
        var $ul = $this.parent();
        var form = $container.data('formulario');
        var url = $ul.data('url');
        var form;

        /*
        if (URL_BASE) {
            url = URL_BASE + url;
        }*/

        /**
         * Es llamada como callback de la consulta al servidor para obtener el formulario.
         * @param data
         */
        function respuesta(data) {

            var $form = $container.find('.form-campos');
            $form.html(data);

            var $formulario = $form.find('form');
            var $btn = $form.find('#btnCamposFormulario');
            console.log($btn);

            function onClick(e) {

                form = true;
                e.preventDefault();

                $btn.attr('value', 'Guardando...');

                function procesarGuardado(respuesta) {
                    console.log(respuesta);
                    $btn.attr('value', 'Guardar');
                    console.log($form, $formulario, respuesta.msj);
                    $formulario.before(respuesta.mensaje);
                }

                $.ajax({
                    'url': $formulario.attr('action'),
                    'method': 'post',
                    'data': $formulario.serialize(),
                    'dataType': 'json'

                }).done(procesarGuardado);

            }

            $btn.on('click', onClick);

        }

        if (valorSeleccion) {

            url += '/' + valorSeleccion;
            var config = {
                'url': url,
                'dataType': 'html'
            }

            $.ajax(config)
                .done(respuesta);

        }
    };

    $btnOrden.on('click', ordenar);

    $listaCampos.find('a').on({
        'dblclick': abrirFormulario,
        'click': function (e) {
            e.preventDefault();
        }
    });


})(jQuery);
