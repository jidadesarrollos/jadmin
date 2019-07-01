<?php

/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 19/11/2018
 * Time: 10:41
 */

namespace Jadmin\Controllers;

use App\Config\Configuracion;
use Jida\Configuracion\Config;
use Jida\Core\Controlador;
use Jida\Manager\Estructura;
use Jida\Medios\Directorios;
use Jida\Medios\Sesion;
use JidaJidaRender\Menu;

class Control extends Controlador {

    protected $_perfiles = ['jadmin', 'administrador'];
    protected $_usuario;

    function __construct() {

        parent::__construct();

        $this->layout('jadmin');
        $this->_usuario = Sesion::$usuario;
        $ruta = strtolower("/" . Estructura::$modulo . '/' . Estructura::$metodo);

        if (!(strtolower($ruta) == "/usuario/login") and !Sesion::es($this->_perfiles)) {
            $this->redireccionar("/jadmin/usuario/login");
        }

        $this->_inicializar();
    }

    private function _inicializar() {

        $this->data('nombreApp', "Jida");
        $layout = ($this->solicitudAjax()) ? 'ajax' : 'jadmin';
        $this->layout($layout);

        $config = Config::obtener();
        $nombreApp = Configuracion::NOMBRE_APP;
        $urlBase = Estructura::$urlBase;
        $urlTema = Estructura::$urlJida . '/Jadmin/Layout/' . $config->tema . "/";

        $path = \Jadmin\Jadmin::$directorio;
        $menuApp = Estructura::$rutaAplicacion . "/Jadmin/menu.json";
        if (Directorios::validar($menuApp)) $path = Estructura::$rutaAplicacion . "/Jadmin";

        $menu = new Menu('menu', $path);
        $menu->addClass('navigation-left');

        $this->data([
            'menu'      => $menu->render(),
            'nombreApp' => $nombreApp,
            'urlBase'   => $urlBase,
            'urlTema'   => $urlTema
        ]);
    }

    public function logout() {

        Sesion::destruir();
        $this->redireccionar('/jadmin');
    }

    function phpInfo() {

        echo phpinfo();
        exit;
    }

    protected function _validarSesion() {

        $metodo = Estructura::$metodo;
        $aceptados = ['login', 'logout'];

        if (in_array($metodo, $aceptados)) {
            return true;
        }

        if (!Sesion::activa()) {
            $this->redireccionar('/jadmin/login');
        }
    }

}
