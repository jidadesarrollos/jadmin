<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 10/01/19
 * Time: 03:15 PM
 */

namespace Jadmin\Modulos\Usuario\Controllers;

use Jadmin\Controllers\Control;
use Jadmin\Modulos\Usuario\Controllers\Usuario\Usuarios;
use Jida\Manager\Estructura;
use Jida\Medios\Debug;
use Jida\Medios\Mensajes;
use Jida\Medios\Sesion;
use JidaRender\Formulario;
use Jida\Modulos\Usuarios\Usuario as Persona;

class Usuario extends Control {

    use Usuarios;

    public function login() {

        $this->layout('login');

        $formLogin = new Formulario('jida/Login');
        $formLogin->boton('principal', 'Iniciar sesión');
        $formLogin->boton('principal')->attr('class', 'btn btn-primary btn-block');

        if ($this->post('btnLogin')) {

            $usuario = $this->post('usuario');
            $clave = $this->post('clave');

            if ($formLogin->validar() and Persona::iniciarSesion($usuario, $clave)) {
                $this->redireccionar('/jadmin');
            }

            Formulario::msj('error', 'Datos incorrectos');

        }

        $this->data([
            'logo'       => Estructura::$urlBase . '/htdocs/img/logo.png',
            'formulario' => $formLogin->render()
        ]);


    }

    public function cambioClave() {

        $formCambioClave = new Formulario('jida/CambioClave');
        $formCambioClave->boton('principal', 'Cambiar Clave');

        $this->data(['formulario' => $formCambioClave->render()]);

        if ($this->post('btnCambioClave')) {

            if (!$formCambioClave->validar()) {
                Mensajes::almacenar(Mensajes::error('Los datos introducidos no son validos.'));
                return null;

            }

            if ($this->post('nueva_clave') !== $this->post('confirmacion_clave')) {
                Mensajes::almacenar(Mensajes::error('La contraseña actual no corresponde con la confirmacion.'));
                return;
            }

            $claveVieja = $this->post('clave_actual');
            $claveNueva = $this->post('nueva_clave');

            if (!Sesion::$usuario->cambiarClave($claveVieja, $claveNueva)) {
                Mensajes::almacenar(Mensajes::error('La contraseña actual que colocó es incorrecta.'));
                return;
            }

            Mensajes::almacenar(Mensajes::suceso('Cambió su constraseña satisfactoriamente'));

        }

    }
}