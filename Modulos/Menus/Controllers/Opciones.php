<?PHP
/**
 * Arranque de modulo de opciones
 *
 * @package Framework
 * @subpackage Jadmin
 * @author  kerby Tabares <krtabares@gmail.com>
 * @version 0.5.1
 */

namespace Jadmin\Modulos\menus\Controllers;

use Exception;
use Jida\Medios as Medios;
use Jida\Modelos as Modelos;
use JidaRender as Render;

class Opciones extends \Jadmin\Controllers\JController {

    var $manejoParams = true;

    function __construct () {

        $this->layout = "jadmin.tpl.php";
        $this->url = "/jadmin/menus/";
        parent::__construct();

    }

    public function index ($id_menu, $padre = 0) {

        if ($padre == 0) {
            $padre = 'n-a';
        }

        $menu = new Modelos\Menus($id_menu);
        $nombre = $menu->obt();

        $tabla = new Render\JVista('Jida\Modelos\OpcionesMenu.obtOpciones',
                                   [
                                       'titulos' => [
                                           'Url',
                                           'Nombre',
                                           'Orden',
                                           'Estatus'
                                       ]
                                   ],
                                   'Opciones de Menu ' . $nombre[0]['menu']
        );

        $tabla->clausula('filtro',
                         [
                             'id_menu' => $id_menu,
                             'padre'   => $padre
                         ]);

        $tabla->accionesFila([
                                 [
                                     'span'  => 'glyphicon glyphicon-plus',
                                     'title' => 'Agregar Opciones',
                                     'href'  => $this->obtUrl('gestionOpcion',
                                                              [
                                                                  '{clave}',
                                                                  $id_menu
                                                              ])
                                 ],
                                 [
                                     'span'  => 'glyphicon glyphicon-edit',
                                     'title' => 'Modificar opcion',
                                     'href'  => $this->obtUrl('gestionOpcion',
                                                              [
                                                                  $padre,
                                                                  $id_menu,
                                                                  '{clave}'
                                                              ])
                                 ],
                                 [
                                     'span'  => 'glyphicon glyphicon-eye-open',
                                     'title' => 'ver',
                                     'href'  => $this->obtUrl('index',
                                                              [
                                                                  $id_menu,
                                                                  '{clave}'
                                                              ])
                                 ],
                                 [
                                     'span'        => 'glyphicon glyphicon-trash',
                                     'title'       => 'Eliminar opcion',
                                     'href'        => $this->obtUrl('eliminarOpcion',
                                                                    [
                                                                        '{clave}',
                                                                        $id_menu,
                                                                        $padre
                                                                    ]),
                                     'data-jvista' => 'confirm',
                                     'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el menu seleccionado?'
                                 ]
                             ]);

        $tabla->addMensajeNoRegistros('No hay opciones Registradas',
                                      [
                                          'link'    => $this->obtUrl('gestionOpcion',
                                                                     [
                                                                         $padre,
                                                                         $id_menu
                                                                     ]),
                                          'txtLink' => 'Crear Opcion'
                                      ]);
        $tabla->acciones([
                             'Nuevo ' => [
                                 'href' => $this->obtUrl('gestionOpcion',
                                                         [
                                                             $padre,
                                                             $id_menu
                                                         ])
                             ]
                         ]);
        $tabla->acciones(['Volver ' => ['href' => $this->obtUrl('index', [$id_menu])]]);

        $this->data(['tablaOpciones' => $tabla->obtenerVista()]);

    }

    public function gestionOpcion ($padre = 0, $id_menu, $id = '') {

        $modelosPerfiles = new Modelos\OpcionMenuPerfil();

        if ($padre == 'n-a') {

            $padre = 0;

        }
        else {

            $ModeloPadre = new Modelos\OpcionesMenu($padre);
            $nombre = $ModeloPadre->consulta('opcion_menu')->filtro(['id_opcion_menu' => $padre])->obt();
        }

        if ($id != '') {

            $btn = 'Guardar';
            $formulario = new Render\Formulario('RegistroOpcion', $id);
            $opcion = new Modelos\OpcionesMenu($id);

            if ($padre != 0)
                $titulo = 'Modificar Sub Item de ' . $nombre[0]['opcion_menu'];
            else
                $titulo = 'Modificar Item de Menu';

        }
        else {

            $btn = 'Registrar';
            $formulario = new Render\Formulario('RegistroOpcion');
            $opcion = new Modelos\OpcionesMenu();

            if ($padre != 0)
                $titulo = 'Registar Sub item en ' . $nombre[0]['opcion_menu'];
            else
                $titulo = 'Registar Item de Menu';

        }

        $this->data(['titulo' => $titulo]);

        $formulario->boton('principal')->attr('value', $btn);

        if ($this->post('btnRegistroOpcion')) {

            if ($formulario->validar()) {

                $paraGuardar = $this->post();
                $paraGuardar['id_menu'] = $id_menu;
                $paraGuardar['padre'] = $padre;

                if ($opcion->salvar($paraGuardar)) {

                    if ($padre != 0) {
                        $ModeloPadre->salvar(['hijo' => 1]);
                    }

                    if ($id == '')
                        $id = $opcion->getResult()->idResultado();

                    $modelosPerfiles->eliminar($id, 'id_opcion_menu');
                    $id_perfil = $this->post('id_perfil');
                    $matriz = [];

                    foreach ($id_perfil as $key => $value)
                        $matriz[] = [
                            'id_opcion_menu' => $id,
                            'id_perfil'      => $value
                        ];

                    $modelosPerfiles->salvarTodo($matriz);

                    $this->redireccionar($this->obtUrl('index',
                                                       [
                                                           $id_menu,
                                                           $padre
                                                       ]));

                }
            }
        }

        $this->data(['tituloForm' => 'Registro De Opciones']);
        $this->dv->form = $formulario->armarFormulario();
    }

    function eliminarOpcion ($id = '', $id_menu, $padre) {

        if ($this->entero($id)) {

            $padre = ($padre == 'n-a') ? 0 : $padre;
            $cMenu = new Modelos\OpcionesMenu($id);

            if (!empty($cMenu->id_opcion_menu)) {

                $cMenu->eliminar($id);
                $modelosPerfiles = new Modelos\OpcionMenuPerfil();
                $modelosPerfiles->eliminar($id, 'id_opcion_menu');

            }
            else {
                // Render\Vista::msj('menus',"error","No se ha eliminado menu");
            }

            $this->redireccionar($this->obtUrl('index',
                                               [
                                                   $id_menu,
                                                   $padre
                                               ]));

        }
        else {
            throw new Exception("Debe seleccionar un menu", 1);
        }
    }//fin funcion

}

