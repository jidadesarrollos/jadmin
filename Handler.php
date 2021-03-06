<?php

namespace Jadmin;

use Jida\Manager\Estructura;
use Jida\Manager\Url\Definicion;
use Jida\Manager\Vista\Tema;

class Handler extends \Jida\Manager\Url\Handler {

    protected $path = 'jadmin';
    protected $nombre = 'jadmin';

    private $modulos = [
        'formularios' => 'formularios',
        'menus'       => 'menus',
        'galerias'    => 'galerias',
        'usuario'     => 'usuario'
    ];

    function definir() {

        if (!self::$aplica) return false;

        $this->definirTema();

        $parametro = $this->url->proximoParametro();

        $config = \Jida\Configuracion\Config::obtener();
        $modulos = $config::$modulos;

        if (isset($modulos[$parametro]) or in_array(Definicion::objeto($parametro), $modulos)) {
            return $this->_moduloApp($parametro);
        }

        if (isset($this->modulos[$parametro])) {
            return $this->_definirModulo($parametro);
        }

        return $this->definirJadmin($parametro);

    }

    private function definirTema() {

        $tema = Tema::obtener();
        $config = \Jida\Configuracion\Config::obtener();

        if (isset($config->temaJadmin)) {
            $config->tema = $config->temaJadmin;
            $tema->definir(['tema' => $config->temaJadmin]);
            return true;
        }

        $config->tema = 'jadmin';
        $directorio = __DIR__ . "/Layout/jadmin";
        $url = str_replace(Estructura::$directorio, "", $directorio);

        $configuracion = [
            'directorio' => $directorio,
            'url'        => Estructura::$urlBase . str_replace("\\", "/", $url),
            'tema'       => 'jadmin'
        ];

        $tema->definir($configuracion);

    }

    private function _moduloApp($modulo) {

        $modulo = Definicion::objeto($modulo);
        $ds = DIRECTORY_SEPARATOR;

        $this->url->modulo = $modulo;

        Estructura::$modulo = $modulo;
        Estructura::$namespace = "App\\Modulos\\{$modulo}\\Jadmin\\Controllers";
        Estructura::$ruta = Estructura::$rutaAplicacion . "{$ds}Modulos{$ds}{$modulo}{$ds}Controllers";
        Estructura::$rutaModulo = Estructura::$rutaAplicacion . "{$ds}Modulos{$ds}{$modulo}{$ds}Jadmin";
        Estructura::$urlModulo = Estructura::$urlBase . "/Aplicacion/Modulos/{$modulo}";

    }

    private function _definirModulo($modulo) {

        $modulo = Definicion::objeto($modulo);
        $this->url->modulo = $modulo;
        Estructura::$modulo = $modulo;
        Estructura::$namespace = "Jadmin\\Modulos\\{$modulo}\\Controllers";
        Estructura::$ruta = __DIR__ . DS . "Modulos" . DS . "Controllers";
        Estructura::$rutaModulo = __DIR__ . DS . "Modulos" . DS . $modulo;
        Estructura::$urlModulo = Estructura::$urlBase . '/Jadmin/Modulos' . DS . $modulo;

    }

    private function definirJadmin($parametro) {

        $posClass = Definicion::objeto($parametro);
        $posMetodo = Definicion::metodo($parametro);
        $jadminApp = "\App\Jadmin\Controllers\Jadmin";
        $esObjeto = class_exists("\App\\Jadmin\\Controllers\\$posClass");
        Estructura::$modulo = 'Jadmin';
        //se setea el modulo general para evitar la ejecucion del handler modulo.|
        $this->url->modulo = 'jadmin';

        if ($esObjeto or (class_exists($jadminApp) and method_exists($jadminApp, $posMetodo))) {

            $this->url->reingresarParametro($parametro);
            Estructura::$namespace = "\\App\Jadmin\\Controllers";
            Estructura::$rutaModulo = Estructura::$rutaAplicacion . "/Jadmin";
            Estructura::$ruta = Estructura::$rutaAplicacion . "/Jadmin";

            return;

        }

        Estructura::$namespace = "Jadmin\\Controllers";
        Estructura::$ruta = __DIR__;
        Estructura::$rutaModulo = __DIR__;

    }
}