<?php

namespace Jadmin\Modulos\Galerias\Modelos;

use Jida\BD\DataModel;
use Jida\Manager\Estructura;

class Media extends DataModel {

    public $id_objeto_media;
    public $objeto_media;
    public $directorio;
    public $tipo_media;
    public $interno;
    public $descripcion;
    public $leyenda;
    public $alt;
    public $meta_data;
    public $id_idioma;
    public $texto_original;

    protected $tablaBD = "s_objetos_media";
    protected $pk = 'id_objeto_media';

    public function media($limit = false) {

        $media = [];
        
        $this->consulta();
        if ($limit !== false) $this->limit($limit);
        $data = $this->obt();

        foreach ($data as $id => $mediaItem) {

            $item = array_merge($mediaItem, [
                'url' => json_decode($mediaItem['meta_data'], true)['urls']
            ]);
            array_walk($item['url'],
                function (&$item) {
                    $item = Estructura::$urlBase . $item;
                });

            unset($mediaItem['meta_data']);
            array_push($media, $item);

        }
        return $media;
    }

}