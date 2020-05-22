<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 25/02/19
 * Time: 03:52 PM
 */

namespace Jadmin\Modulos\Usuario\Controllers\Usuario;

use Jida\Manager\Estructura;
use Jida\Manager\Textos;
use Jida\Medios\Debug;
use Jida\Medios\Mensajes;
use Jida\Modulos\Usuarios\Modelos\Perfil;
use Jida\Modulos\Usuarios\Modelos\Usuario;
use Jida\Modulos\Usuarios\Modelos\UsuarioPerfil;
use JidaRender\Formulario;
use JidaRender\JVista;

trait Usuarios {

    public function index() {

        $textos = Textos::obtener();
        $listaUsuarios = Usuario::listaUsuarios();
        $parametros = ['titulos' => [
            $textos->texto('th1'),
            $textos->texto('th2'),
            $textos->texto('th3'),
            $textos->texto('th4'),
            $textos->texto('th5'),
            $textos->texto('th6')
        ]];
        $vista = new JVista($listaUsuarios, $parametros);

        $vista->accionesFila([
            [
                'span'  => 'fas fa-user-alt',
                'title' => $textos->texto('actionTitle1'),
                'href'  => '/jadmin/usuario/perfil/{clave}'
            ],
            [
                'span'  => 'fas fa-edit',
                'title' => $textos->texto('actionTitle2'),
                'href'  => '/jadmin/usuario/gestion/{clave}'
            ],
            [
                'span'         => 'fas fa-trash',
                'title'        => $textos->texto('actionTitle3'),
                'href'         => '/jadmin/usuario/eliminar/{clave}',
                'data-confirm' => 'delete'
            ]
        ]);
        $vista->acciones([
            $textos->texto('btnAddNew') => ['href' => '/jadmin/usuario/gestion/']
        ]);

        $render = $vista->render(
            function ($datos) {

                foreach ($datos as $key => &$users) {
                    $listaPerfiles = '<ul>';
                    foreach ($users['perfiles'] as $perfil) {
                        $listaPerfiles .= "<li>{$perfil['perfil']}</li>";
                    }
                    $listaPerfiles .= '</ul>';
                    $users['perfiles'] = $listaPerfiles;
                    $users['id_estatus'] = $users['id_estatus'] == 1 ? 'Active' : 'Inactive';
                }
                return $datos;
            }
        );

        $this->data([
            'vista' => $render
        ]);

    }

    public function perfil($id_usuario) {

        $textos = Textos::obtener();
        $usuarioPerfil = new UsuarioPerfil();
        $usuarioPerfil2 = $usuarioPerfil
            ->consulta(['id_usuario_perfil', 'id_perfil'])
            ->filtro(['id_usuario' => $id_usuario])
            ->obt();
        $listaPerfiles = [];
        foreach ($usuarioPerfil2 as $fila) {
            $listaPerfiles[] = $fila['id_perfil'];
        }
        $perfiles = new Perfil();
        $perfiles = $perfiles->consulta()->obt();
        $usuario = new Usuario($id_usuario);

        if ($this->post('btnGestionPerfiles')) {
            foreach ($usuarioPerfil2 as $fila) {
                $usuarioPerfil->eliminar($fila['id_usuario_perfil']);
            }

            $nuevosPerfiles = [];
            foreach ($this->post('id_perfil') as $list) {
                $nuevosPerfiles[] = ['id_perfil' => $list, 'id_usuario' => $id_usuario];
            }
            $usuarioPerfil->salvarTodo($nuevosPerfiles);
            Mensajes::almacenar(Mensajes::suceso($textos->texto('msjSuccess')));
            $this->redireccionar('/jadmin/usuario');

        }

        $this->data([
            'listaPerfiles' => $listaPerfiles,
            'perfiles'      => $perfiles,
            'id_usuario'    => $id_usuario,
            'name'          => "{$usuario->nombres} {$usuario->apellidos}"
        ]);
    }

    public function gestion($id_usuario = "") {

        $textos = Textos::obtener();
        $formName = (Estructura::$idioma == 'es') ? 'GestionUsuarios' : 'ManageUsers';
        $form = new Formulario('jida/Usuarios/' . $formName, $id_usuario);
        $form->boton('principal')->attr('value', $textos->texto('btn'));
        $usuario = new Usuario($id_usuario);

        if ($this->post('btnUsuarios')) {

            if ($form->validar()) {

                if (!empty($id_usuario)) {
                    $this->post('clave', $usuario->clave);
                }
                else {
                    $this->post('clave', md5('123456'));
                }

                $this->post('activo', 1);
                $this->post('validacion', 1);

                if ($usuario->salvar($this->post())) {

                    $accion = (empty($id)) ? $textos->texto('actionSave') : $textos->texto('actionEdit');
                    Mensajes::almacenar(Mensajes::suceso(sprintf($textos->texto('msjSuccess'), $accion)));
                    $this->redireccionar('/jadmin/usuario');
                }
                else {
                    echo 'ERROR';
                }
            }
            else {
                echo 'ERROR';
            }
        }

        $this->data([
            'vista' => $form->render(),
        ]);
    }

    public function eliminar($id_usuario) {

        $textos = Textos::obtener();

        if (!empty($id_usuario)) {

            $usuario = new Usuario($id_usuario);
            if (!empty($usuario->id_usuario) and $usuario->eliminar()) {
                Mensajes::almacenar(Mensajes::suceso($textos->texto('msjSuccess')));
                $this->redireccionar('/jadmin/usuario');
            }
            else {
                Mensajes::almacenar(Mensajes::error($textos->texto('msjError1')));
                $this->redireccionar('/jadmin/usuario');
            }

        }
        else {
            Mensajes::almacenar(Mensajes::error($textos->texto('msjError2')));
            $this->redireccionar('/jadmin/usuario');
        }
    }
}