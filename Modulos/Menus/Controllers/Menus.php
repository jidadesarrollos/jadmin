<?PHP
/**
 * Arranque de modulo de Menus
 *
 * @package Framework
 * @subpackage Jadmin
 * @author  kerby Tabares <krtabares@gmail.com>
 * @version 0.5.1
 */

namespace Jadmin\Modulos\menus\Controllers;

use Exception;
use Jadmin\Controllers\Control;
use Jida\Medios as Medios;
use Render as Render;
use Jida\Modelos as Modelos;

class Menus extends Control {

    var $manejoParams = true;

    function __construct() {
        $this->layout = "jadmin.tpl.php";
        $this->url = "/jadmin/menus/";
        parent::__construct();
    }

    public function index() {

        $tabla = new JidaRender\JVista('Jida\Modelos\Menus.obtMenus',
            ['titulos' => ['nombre']], 'Menus'
        );

        $tabla->accionesFila([
            ['span' => 'glyphicon glyphicon-folder-open', 'title' => 'Opciones menu', 'href' => URL_BASE . '/jadmin/menus/opciones/{clave}/'],
            ['span' => 'glyphicon glyphicon-edit', 'title' => 'Modificar menu', 'href' => $this->obtUrl('gestionMenu', ['{clave}'])],
            ['span'        => 'glyphicon glyphicon-trash', 'title' => 'Eliminar menu', 'href' => $this->obtUrl('eliminarMenu', ['{clave}']),
             'data-jvista' => 'confirm', 'data-msj' => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el menu seleccionado?']
        ]);

        $tabla->addMensajeNoRegistros('No hay Menus Registrados',
            ['link'    => $this->obtUrl(''),
             'txtLink' => 'Crear Menu']
        );
        $tabla->acciones(['Nuevo' => ['href' => $this->obtUrl('gestionMenu')]]);

        $this->data(['tablaVista' => $tabla->render()]);

    }

    public function gestionMenu($id = '') {

        $form = new JidaRender\Formulario('Menus', $id);
        $classMenu = new Modelos\Menus($id);

        $form->boton('principal')->attr('value', "Crear menu");

        if ($this->post('btnMenus')) {
            if ($form->validar()) {

                $this->post('identificador', Medios\Cadenas::guionCase($this->post('menu')));

                if ($classMenu->salvar($this->post())->ejecutado()):
                    $tipo = 'suceso';
                    $msj = 'Menu <strong>' . $classMenu->menu . '</strong> creado exitosamente';
                else:
                    $tipo = 'error';
                    $msj = 'No se pudo crear el menu, por favor, vuelva a intentarlo';
                endif;

                JidaRender\JVista::msj('menus', 'suceso', 'Menu <strong>' . $classMenu->menu . '</strong> creado exitosamente', $this->obtUrl('index'));
            }
        }

        $this->dv->usarPlantilla('form');

        $this->data(['form' => $form->armarFormulario()]);
    }

    function eliminarMenu($id = '') {

        if ($this->entero($id)) {

            $cMenu = new Modelos\Menus($id);

            if (!empty($cMenu->id_menu)) {
                $cMenu->eliminar($id);
                // JidaRender\Vista::msj('menus','suceso', 'Menu eliminado');

            }
            else {
                // JidaRender\Vista::msj('menus',"error","No se ha eliminado menu");
            }

            $this->redireccionar('/jadmin/menus/');

        }
        else {
            throw new Exception("Debe seleccionar un menu", 1);
        }
    }//fin funcion

}
