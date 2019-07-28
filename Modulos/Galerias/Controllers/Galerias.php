<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace Jadmin\Modulos\Galerias\Controllers;

use Jadmin\Modulos\Galerias\Controllers\Galerias\Vista;
use Jadmin\Modulos\Galerias\Controllers\Galerias\Gestion;
use Jadmin\Modulos\Galerias\Controllers\Galerias\Carga;
use Jadmin\Modulos\Galerias\Modelos\Media;
use Jadmin\Controllers\Control;
use Jida\Manager\Estructura;
use Jida\Medios\Archivos\Imagen;
use JidaRender\JVista;

class Galerias extends Control {

    use Vista, Gestion, Carga;

    function index() {

        $this->layout()->incluirJS([
            'modulo/generic/lightgallery-all.min.js',
            'modulo/generic/mainGaleria.js',
            'modulo/index/jCargaFile.js',
            'modulo/index/galeria.js',
            'modulo/index/acciones.js',
            'modulo/index/index.js'
        ]);

        $this->layout()->incluirCSS([
            'modulo/lightgallery.min.css',
            'modulo/galerias.css'
        ]);

        $medias = new Media();

        if ($this->files('imagen')) {
            $this->_procesarCarga();
        }

        $this->data([
            'media'    => $medias->media(),
            'urlEnvio' => Estructura::$url
        ]);
    }

    function gestion($id = "") {
        $this->layout()->incluirJSAjax(["modulo/mensajes.js", "modulo/gestion.js"]);
        $this->_gestion($id);

    }

    function eliminar($id = "") {

        $media = new Media($id);
        if (!$media->id_objeto_media) {
            JVista::msj(
                'vistaProyectos',
                'alerta',
                'No existe el objeto media pasado'
            );
            $this->redireccionar('/jadmin/galerias');
        }

        if ($this->post('eliminar')) {
            $dirBase = Estructura::$directorio;

            $urls = json_decode($media->meta_data);
            $imagen = new Imagen("{$dirBase}{$urls->urls->original}");
            $imagen->editarUrls($urls->urls);

            if (!$media->eliminar() or !$imagen->eliminar()) {
                $this->respuestaJson(['procesado' => false, 'error' => 'mensaje error']);
            }

            $this->respuestaJson(['procesado' => true, 'id' => $id]);
        }

        $this->data([
            'id' => $id
        ]);

    }

}