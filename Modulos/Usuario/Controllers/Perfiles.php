<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/02/19
 * Time: 02:09 PM
 */

namespace Jadmin\Modulos\Usuario\Controllers;

use Jadmin\Controllers\Control;
use Jida\Medios\Mensajes;
use Jida\Modulos\Usuarios\Modelos\Perfil;
use JidaRender\Formulario;
use JidaRender\JVista;

class Perfiles extends Control {

    public function index(){

        $listaPerfiles = new Perfil();
        $listaPerfiles = $listaPerfiles->consulta(['id_perfil','perfil'])->obt();
        $parametros = ['titulos' => ['Perfiles']];
        $vista = new JVista($listaPerfiles, $parametros);

        $vista->accionesFila([
            ['span'  => 'fa fa-edit',
             'title' => "Editar Perfil",
             'href'  => '/jadmin/usuario/perfiles/gestion/{clave}'],
            ['span'        => 'fa fa-trash',
             'title'       => 'Eliminar Perfil',
             'href'        => '/jadmin/usuario/perfiles/eliminar/{clave}',
             'data-jvista' => 'confirm',
             'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el cliente seleccionado?']
        ]);
        $vista->acciones([
            'Nuevo Perfil' => ['href' => '/jadmin/usuario/perfiles/gestion/']
        ]);

        $render = $vista->render();

        $this->data([
            'vista' => $render
        ]);

    }

    public function gestion($id_perfil = ""){

        $form = new Formulario('jida/Usuarios/Perfiles', $id_perfil);
        $perfil = new Perfil($id_perfil);

        if ($this->post('btnPerfiles')) {

            if ($form->validar()) {

                if ($perfil->salvar($this->post())) {

                    Mensajes::almacenar(Mensajes::suceso('Perfil almacenado con exito'));
                    $this->redireccionar('/jadmin/usuarios/perfiles');

                }else {
                    Mensajes::almacenar(Mensajes::error('Perfil no se pudo almacenar'));
                    $this->redireccionar('/jadmin/usuarios/perfiles');
                }

            }
            else {
                Mensajes::almacenar(Mensajes::error('Los datos no son validos.'));
                $this->redireccionar('/jadmin/usuarios/perfiles');
            }
        }

        $this->data([
            'vista' => $form->render(),
        ]);
    }

    public function eliminar($id_perfil){

        if (!empty($id_perfil)) {

            $perfil = new Perfil($id_perfil);
            if (!empty($perfil->id_usuario) and $perfil->eliminar()) {
                Mensajes::almacenar(Mensajes::suceso('El perfil ha sido eliminado correctamente'));
                $this->redireccionar('/jadmin/usuario/perfiles');
            }
            else {
                Mensajes::almacenar(Mensajes::error('El perfil no ha sido eliminado'));
                $this->redireccionar('/jadmin/usuario/perfiles');
            }

        }
        else {
            Mensajes::almacenar(Mensajes::error('El usuario indicado no existe.'));
            $this->redireccionar('/jadmin/usuario/perfiles');
        }
    }
}