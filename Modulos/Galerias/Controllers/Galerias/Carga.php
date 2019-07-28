<?php
/**
 * @see \Jida\Core\Controlador
 */

namespace Jadmin\Modulos\Galerias\Controllers\Galerias;

use Jadmin\Modulos\Galerias\Modelos\Media;
use Jida\Configuracion\Config;
use Jida\Manager\Estructura;
use Jida\Medios\Archivos\Imagen;
use Jida\Medios\Archivos\ProcesadorCarga;

Trait Carga {

    private function _procesarCarga() {

        $imagen = $this->files('imagen');

        $procesador = new ProcesadorCarga('imagen');
        $banner = new Media();

        $configuracion = Config::obtener();

        if ($procesador->validar()) {

            $path = Estructura::$directorio;
            $directorio = "{$path}/htdocs/img/medias/";
            $archivos = $procesador->mover($directorio)->archivos();

            $ok = true;
            $datos = $imagenes = [];

            foreach ($archivos as $item => $archivo) {

                $imagen = new Imagen($archivo->directorio());

                if (!$imagen->redimensionar($configuracion::REDIMENSION_IMAGEN)) {
                    $ok = false;
                    continue;
                }

                array_push($datos, $this->_data($imagen));
                array_push($imagenes, ['urls' => $imagen->obtUrls()]);

            }

            $banner->salvarTodo($datos);
            $ids = $banner->obtIdsResultados();
            foreach ($ids as $key => $id) {
                $imagenes[$key]['id'] = $id;
            }

            $this->respuestaJson([
                'procesado'  => $ok,
                'data'       => $imagenes,
                'directorio' => $directorio
            ]);

        }

    }

    private function _data(Imagen $imagen) {

        return [
            'objeto_media' => $imagen->nombre,
            'tipo_media'   => 1,
            'directorio'   => str_replace(Estructura::$directorio, "", $imagen->directorio),
            'meta_data'    => json_encode(['urls' => str_replace(Estructura::$urlBase, "", $imagen->obtUrls())]),
            'id_idioma'    => 'esp'
        ];

    }

}