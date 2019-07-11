<?php

namespace Jadmin\Modulos\Galerias\Controllers\Galerias;

use Jida\JidaRender\JVista;

Trait Vista {

    function _vista($data, $titulo) {

        $parametros = [
            'titulos' => ['Foto', 'Nombre', 'Descripcion', 'Origen']
        ];
        $vista = new JVista($data, $parametros, $titulo);

        $vista->acciones([
            'Agregar Fotos' => ['href' => "/jadmin/banner/subir-imagenes/"]
        ]);

        $vista->addMensajeNoRegistros('No hay materia multimedia registrado.',
            [
                'link'    => "/jadmin/banner/carga/",
                'txtLink' => 'Subir Fotografias'
            ]);

        $vista->accionesFila([
            [
                'span'  => 'fa fa-edit',
                'title' => "Editar Fotografia",
                'href'  => "/jadmin/banner/gestion/{clave}"
            ],
            [
                'span'        => 'fa fa-trash',
                'title'       => 'Eliminar Fotografia',
                'href'        => "/jadmin/banner/eliminar/{clave}",
                'data-jvista' => 'confirm',
                'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar la categoria seleccionada?'
            ]
        ]);

        return $vista;

    }
}