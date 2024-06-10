<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => '', 'middleware' => ['auth.jwt']], function () use ($router) {
    $router->get('/listar-tipousuarios', 'Usuarios\UsuariosController@listarTipoUsuario');
    $router->get('/logout', 'Login\LoginController@logout');
    $router->get('/listar-usuarios-especialista', 'Terapista\TerapistaController@listarUsuariosxEspecialista');
    $router->get('/list-acceso-usuario', 'Infante\InfanteController@listAccesosUsuario');
    $router->get('/list-actividad-recurso', 'Usuarios\UsuariosController@listarActividadRecurso');
    $router->post('/registro-acceso', 'Terapista\TerapistaController@setAccesoUsuario');
    $router->post('/registro-recurso-actividad', 'Usuarios\UsuariosController@setActividadRecurso');
    $router->post('/registro-recurso-actividad', 'Usuarios\UsuariosController@setActividadRecurso');
    
    // PERMISOS
    $router->get('/configuraciones-usuario/', 'Permisos\PermisosController@getListPermisos');
    //MENUS
    $router->get('/opciones-list-combo/', 'Permisos\MenusController@getListOpcionesCmb');
    $router->get('/menus-list/', 'Permisos\MenusController@getListMenus');
    $router->post('/menus/create/', 'Permisos\MenusController@setMenus');
    $router->get('/menus-list-id/', 'Permisos\MenusController@getMenusxId');
    $router->put('/menus/update/', 'Permisos\MenusController@updateMenus');
    // PERMISOS - USUARIO
    $router->get('/tipo-usuarios-list-combo/', 'Permisos\UsuariosController@listarComboTipoUsuario');
    $router->get('/usuarios-list/', 'Permisos\UsuariosController@getListUsuarios');
    $router->get('/menus-list-arboles/', 'Permisos\UsuariosController@getListMenusArbol');
    $router->get('/get-persona/', 'Permisos\UsuariosController@getPersona');
    $router->post('/persona/create/', 'Permisos\UsuariosController@setPersona');
    $router->get('/usuario/check/', 'Permisos\UsuariosController@checkUsuario');
    $router->get('/responsables-list-combo/', 'Permisos\UsuariosController@getListComboResponsables');
    $router->post('/usuario/set/', 'Permisos\UsuariosController@setUsuarioPermisos');
    $router->get('/usuario-permiso/get/', 'Permisos\UsuariosController@getPermisosUsuario');
    $router->put('/usuario/upd/', 'Permisos\UsuariosController@updUsuarioPermisos');
    //JUEGOS
    $router->get('/juegos-list/', 'Juegos\JuegosController@getListJuegos');
    $router->post('/juegos/create/', 'Juegos\JuegosController@setJuegos');
    $router->get('/recursos-juegos-list/', 'Juegos\JuegosController@getListRecursosJuegos');

});

$router->post('/registro-usuario', 'Login\LoginController@registroUsuario');
$router->post('/login/', 'Login\LoginController@login');
$router->post('/login-mobile/', 'Login\LoginController@loginMobile');
