/**
 * Plugin para carga de archivos
 *
 * jCargaFile
 * @author julio Rodriguez @jr0driguez
 * @version 0.1 2017
 */
(function ($) {

    let jCargaFile = function (objeto, config, event) {
        this.objeto = objeto;
        this.configuracion = config;
        this.$obj = $(this.objeto);
        this.init();
        this._FileReader = new FileReader();
        this._data = {};
        let that = this;
    };
    let selector = '[data-jida="cargaFile"]';

    jCargaFile.prototype = {
        regExps: {
            'imagen': /\.(jpe?g|png|gif)$/i

        },
        /**
         * Numero de archivos cargados exitosamente
         * @property int _archivosCargados
         */
        _archivosCargados: 0,
        /**
         * Nro de archivos seleccionados por el usuario
         * @property int _archivosSeleccionados default 0
         */
        _archivosSeleccionados: 0,
        _data: {},

        _obtConfiguracion: function () {
            let defaultConfig = {
                'preCarga': function () {
                },
                'onLoadArchivo': this._defaultOnload,
                'multiple': false,
                'name': '_jcargaArchivo',
                'btnCarga': false,
                'onLoad': false
            };
            this._configuracion = $.extend(defaultConfig, this.configuracion);
        },

        init: function () {

            this._obtConfiguracion();

            $file = $('<input>').attr({
                'type': 'file',
                'id': this._configuracion.name,
                'name': this._configuracion.name,
                'style': 'display:none',
                'multiple': this._configuracion.multiple,
                'accept': 'image/*'

            });
            this.$obj.after($file);
            this.$file = $file;
            this.file = $file.get(0);
            this._manejarEventos();

        },
        /**
         *
         */
        _manejarEventos: function () {

            let plugin = this;

            this.$obj.on('click', function (e) {
                this.$file.off();
                this.$file.trigger('click')
                    .on('change', this._managerChange.bind(this));

            }.bind(this));
        },
        /**
         *
         */
        _managerChange: function (e) {

            let ele = e.target;
            let plugin = this;
            this._archivosSeleccionados = ele.files.length;
            this._defaultPrecarga.call(plugin, e);

            //this.$file.off();
        },
        _managerLoadEnd: function (e) {
            let ele = e.currentTarget;
            let plugin = this;
            ++plugin._archivosCargados;

            if (this._configuracion.btnCarga) {
                $(this._configuracion.btnCarga).on('click', this._postData.bind(this));
            }
            else if (
                (plugin._archivosCargados == plugin._archivosSeleccionados)
                && this._configuracion.url
            ) {
                this._postData();

            }
        },
        _managerOnLoad: function (e) {
            let ele = e.target;
            let plugin = this;

            ele.removeEventListener('load', plugin._managerOnLoad);

            plugin._configuracion.onLoad.call(plugin, e);
        },
        _postData: function () {

            let form = new FormData();
            let plugin = this;
            let name = (plugin._archivosCargados > 1) ? plugin._configuracion.name + '[]' : plugin._configuracion.name;

            [].forEach.call(plugin._archivos, (archivo) => form.append(name, archivo));

            for (let key in plugin._data) form.append(key, plugin._data[key]);

            $.ajax({
                'url': this._configuracion.url,
                'type': 'post',
                'processData': false,
                'contentType': false,
                'data': form,
                'dataType': 'json',
                'success': function (r) {
                    plugin.file.value = '';
                    plugin._archivosCargados = 0;
                    plugin._configuracion.postCarga(r);
                },
                'error': e => console.log('error', e)
            });

        },

        _defaultPrecarga: function (event) {

            let ele = event.target;
            let plugin = this;
            let archivos = ele.files;
            this._archivos = archivos;
            plugin._configuracion.preCarga.call(plugin, event);

            if (archivos) {

                band = 0;
                [].forEach.call(archivos, function (archivo) {

                    archivo.id_app = band;
                    ++band;
                    let reader = new FileReader();
                    reader.addEventListener('load', this._managerOnLoad.bind(plugin), false);
                    reader.addEventListener('loadend', this._managerLoadEnd.bind(plugin), false);
                    reader.readAsDataURL(archivo);

                }.bind(plugin));

            }

        },

        _defaultOnload: function (e) {

            let image = new Image();
            let ele = e.target;
            let plugin = this;

            image.height = 150;
            image.title = ele.title;
            image.src = ele.result;
            $li = $('<li>').html(image);
            $('#imagenes').append($li);

            ++this._archivoCargados;

        },
        _previewImage: function () {

        }
    };

    /**
     *   =============================
     *   DECLARACION DEL PLUGIN
     *  ============================
     *
     */
    function jPlugin(config, e) {
        let $this = $(this);
        return this.each((i, ele) => new jCargaFile(ele, config, e));
    }

    $.fn.jCargaFile = jPlugin;

    $(selector).each((i, elem) => new jCargaFile(elem));

})(jQuery);

