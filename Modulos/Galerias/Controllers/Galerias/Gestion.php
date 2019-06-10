<?php

namespace Jadmin\Modulos\Galerias\Controllers\Galerias;

use Jadmin\Modulos\Galerias\Modelos\Medias;
use Jida\Medios\Debug;
use Jida\Medios\Sesion;
use JidaRender\Formulario;

Trait Gestion {

    function _gestion($id_media) {

        $form = new Formulario('jadmin/galerias/Media', $id_media);
        //$form->action = $this->obtUrl('', ['id' => $id_media]);

        $form->boton('principal')->attr('type', 'button');

        if ($this->post('btnFormularioMedia')) {
            $this->gestionForm($form, $id_media);
        }

        if (empty($id_media)) {
            $this->redireccionar('/jadmin/galerias/');
        }

        $this->data([
            'id'   => $id_media,
            'form' => $form->render()
        ]);

    }

    private function gestionForm(Formulario $form, $id_media) {

        if (!$form->validar()) {
            //la clase formulario genera la variable de sesion __msjForm automaticamente
            //debe limpiarse para que no aparezca.
            Sesion::destruir('__msjForm');
            $this->respuestaJson(['estatus' => false]);
        }
        $banner = new Medias($id_media);

        if (!$banner->salvar($this->post())) {
            $this->respuestaJson(['estatus' => false]);
        }

        $this->respuestaJson(['estatus' => true]);

    }

}