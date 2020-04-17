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
use Jida\Manager\Textos;
use Jida\Medios\Debug;
use Jida\Medios\Mensajes;
use Jida\Medios\Sesion;
use JidaRender\Formulario;
use Jida\Modulos\Usuarios\Usuario as Persona;

class Usuario extends Control {

    use Usuarios;

    public function login() {

        $textos = Textos::obtener();
        $this->layout('login');
        $formName = (Estructura::$idioma === 'en') ? 'jida/Login_en' : 'jida/Login';
        $formLogin = new Formulario($formName);
        $formLogin->boton('principal', $textos->texto('btn'));
        $formLogin->boton('principal')->attr('class', 'btn btn-primary btn-block');

        if ($this->post('btnLogin')) {

            $usuario = $this->post('usuario');
            $clave = $this->post('clave');

            if ($formLogin->validar() and Persona::iniciarSesion($usuario, $clave)) {
                $this->redireccionar('/jadmin');
            }

            Formulario::msj('error', $textos->texto('errorForm'));

        }

        $this->data([
            'logo'       => Estructura::$urlBase . '/htdocs/img/logo.png',
            'formulario' => $formLogin->render()
        ]);

    }

    public function cambioClave() {

        $textos = Textos::obtener();
        $formName = (Estructura::$idioma === 'en') ? 'jida/ChangePasword' : 'jida/CambioClave';
        $formCambioClave = new Formulario($formName);
        $formCambioClave->boton('principal', $textos->texto('btn'));

        $this->data(['formulario' => $formCambioClave->render()]);

        if ($this->post('btnCambioClave')) {

            if (!$formCambioClave->validar()) {
                Mensajes::almacenar(Mensajes::error($textos->texto('errorForm1')));
                return null;

            }

            if ($this->post('nueva_clave') !== $this->post('confirmacion_clave')) {
                Mensajes::almacenar(Mensajes::error($textos->texto('errorForm2')));
                return;
            }

            $claveVieja = $this->post('clave_actual');
            $claveNueva = $this->post('nueva_clave');

            if (!Sesion::$usuario->cambiarClave($claveVieja, $claveNueva)) {
                Mensajes::almacenar(Mensajes::error($textos->texto('errorForm3')));
                return;
            }

            Mensajes::almacenar(Mensajes::suceso($textos->texto('msjSuccess')));

        }

    }
}