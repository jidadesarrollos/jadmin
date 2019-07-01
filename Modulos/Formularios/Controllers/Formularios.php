<?php
/**
 * Clase Controladora
 *
 * @author   Julio Rodriguez
 * @package
 * @version
 * @category Controller
 */

namespace Jadmin\Modulos\Formularios\Controllers;

use Jida\Configuracion\Config;
use Jadmin\Controllers\Control;
use Jida\Medios as Medios;
use Jida\Modelos\Formulario;
use Render as Render;

class Formularios extends Control {

    private $_rutaJida;
    public  $manejoParams = true;

    function __construct() {

        parent::__construct();
        $this->_rutaJida = DIR_FRAMEWORK . DS . 'formularios';
    }

    function index($modulo = "") {

        $this->vista = 'vista';
        $forms = [];
        if ($modulo == 'jida') {

            $forms = [
                'jida' => [
                    'formularios' => Medios\Directorios::listar($this->_rutaJida),
                    'path'        => $this->_rutaJida,
                    'modulo'      => 'Jida'
                ]
            ];

        }
        else {
            $forms = $this->_obtenerFormularios($modulo);
        }

        $formsInvalidos = $data = $params = [];
        $formularios = [];
        foreach ($forms as $modulos => $data) {

            foreach ($data['formularios'] as $index => $formulario) {
                if (!is_dir($this->_rutaJida . DS . $formulario)) {
                    $dataFormulario = $this->_dataVistaFormulario($formulario, $data['modulo']);
                    if ($dataFormulario) {
                        $formularios[] = $dataFormulario;
                    }
                    else {
                        $formsInvalidos[] = $formulario;
                    }
                }
                else {
                    unset($data[$index]);
                }
            }

        }

        $params = [
            'titulos' => [
                'nombre',
                'estructura',
                'ID',
                'Clave Primaria',
                'Total Campos',
                'Modulo'
            ]
        ];

        $jvista = new JidaRender\JVista($formularios, $params, 'Formularios');
        $jvista->accionesFila([
            [
                'span'  => 'fa fa-edit',
                'title' => "Editar",
                'href'  => $this->obtUrl('gestion',
                    [
                        '{clave}',
                        '{modulo}'
                    ])
            ],
            [
                'span'  => 'fa fa-plus-square-o',
                'title' => 'Editar Campos',
                'href'  => $this->obtUrl('campos.gestion',
                    [
                        '{clave}',
                        '{modulo}'
                    ])
            ],
            [
                'span'        => 'fa fa-trash',
                'title'       => "Eliminar Formulario",
                'href'        => $this->obtUrl('eliminar', ['{clave}']),
                'data-jvista' => 'confirm',
                'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el formulario seleccionado?'
            ],

        ]);
        $jvista->acciones([
            'Nuevo Formulario' => ['href' => $this->obtUrl('gestion')]

        ]);
        $this->data([
            'vista' => $jvista->render()
        ]);

    }

    private function _procesarModulo($modulo) {

        if ($modulo == 'app') {
            $path = DIR_APP . 'Formularios';
        }
        else {
            $path = DIR_APP . 'Modulos' . DS . ucwords($modulo) . DS . 'Formularios';
        }

        if (Medios\Directorios::validar($path)) {
            $archivos = Medios\Directorios::listar($path);
            if (!$archivos) {
                return;
            }
            $formularios = [
                'formularios' => $archivos,
                'modulo'      => $modulo,
                'path'        => $path
            ];

            return $formularios;
        }
    }

    /**
     * Retorna un arreglo con el listado de formularios existentes en la aplicación.
     */
    private function _obtenerFormularios($modulo = "") {

        $conf = Config::obtener();
        $modulos = $conf::$modulos;
        $coleccion = [];

        if ($modulo and array_key_exists($modulo, $modulos)) {
            $archivos = $this->_procesarModulo($modulo);
            if ($archivos) {
                $coleccion[$modulo] = $archivos;
            }

        }
        else {
            foreach ($modulos as $modulo) {
                $archivos = $this->_procesarModulo($modulo);
                if ($archivos) {
                    $coleccion[$modulo] = $archivos;
                }

            }
        }

        return $coleccion;

    }

    /**
     * Lee la data del formulario y retorna un arreglo con los valores
     */
    private function _dataVistaFormulario($formulario, $modulo) {

        $data = "";
        if ($data) {
            if (array_key_exists('query', $data)) {

                unset($data['query']);

                return $data;

            }
        }

        return false;

    }

    private function _dataGestion($nombreFormulario, $modulo) {

        $form = $this->_instanciarFormulario($nombreFormulario, $modulo);
        $dataForm = $this->_dataFormulario($nombreFormulario, $modulo);

        $campos = [];
        $formCampos = $form->campos();
        foreach ($formCampos as $id => $campo) {

            if (is_array($campo)) {
                $campos[] = $campo['name'];
            }
            else {
                $campos[] = $campo->name;
            }

        }
        $dataForm['campos'] = implode(", ", $campos);

        return $dataForm;

    }

    function gestion($id = "", $modulo = "") {

        $dataForm = [];
        $nombreFormulario = "";

        if (!empty($id)) {
            $nombreFormulario = $id . '.json';
            $dataForm = $this->_dataGestion($nombreFormulario, $modulo);
            $titulo = 'Editar <strong>' . $dataForm['nombre'] . '</strong>';

        }
        else {
            $this->_formulario = new Formulario();
            $titulo = 'Crear Nuevo Formulario';
        }

        $form = new JidaRender\Formulario('jida/GestionFormulario', $dataForm);
        $form
            ->boton('btnGuardar', 'Guardar y editar campos')
            ->attr('value', 'Guardar')
            ->data('jida', 'validador');
        $form->boton('principal', 'Guardar')->attr('value', 'Guardar');

        $form->titulo($titulo);

        $form->campo('modulo')
            ->agregarOpciones(
                array_merge(
                    ['principal' => 'Principal'],
                    $this->_conf()->modulos
                ),
                true
            );
        if ($modulo) {

            $selector = $form->campo('modulo')->opcion(strtolower($modulo));
            if ($selector) {
                $selector->attr('selected', 'selected');
            }

        }

        if ($this->post('btnGuardar') or $this->post('btnGestionFormulario')) {

            if ($this->_guardarFormulario($nombreFormulario, $modulo)) {

                if ($this->post('btnGestionFormulario')) {
                    $this->redireccionar($this->obtUrl('index'));
                }
                else {

                    $modulo = (!empty($this->post('modulo'))) ? $this->post('modulo') : $modulo;

                    $this->redireccionar($this->obtUrl(
                        'Campos.gestion',
                        [
                            $this->_formulario->identificador,
                            $modulo
                        ]
                    ));
                }

            }
            else {
                exit("no guarda");
            }
        }

        $this->data([
            'form' => $form->render()
        ]);

    }

    /**
     * Gestiona el guardado del formulario
     *
     * @param string $nombreFormulario identificador del formulario en UpperCamelCase
     * @param string $modulo Modulo al cual pertenece el formulario a guardar
     */
    function _guardarFormulario($nombreFormulario, $modulo) {

        $post = $this->post();
        $post['modulo'] = (empty($post['modulo'])) ? $modulo : $post['modulo'];

        if ($this->_formulario->salvar($post)) {

            $msj = Medios\Mensajes::crear('suceso', 'Formulario guardado correctamente');
            Medios\Sesion::editar('__msj', $msj);

            return true;

        }
        else {
            Medios\Debug::imprimir("No se pudo guardar el formulario", true);
        }

        return false;

    }

    function eliminar() {

    }

}
