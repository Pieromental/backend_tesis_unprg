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
    $router->patch('/usuario/update-password/', 'Permisos\UsuariosController@updUsuarioPassword');
    $router->patch('/usuario/enable/', 'Permisos\UsuariosController@usuarioEnable');
    $router->patch('/usuario/disable/', 'Permisos\UsuariosController@usuarioDisable');
    //JUEGOS
    $router->get('/juegos-list/', 'Juegos\JuegosController@getListJuegos');
    $router->post('/juegos/create/', 'Juegos\JuegosController@setJuegos');
    $router->get('/recursos-juegos-list/', 'Juegos\JuegosController@getListRecursosJuegos');
    $router->get('/carga-personalizacion/', 'Juegos\JuegosController@getCargaPersonalizacion');

});

$router->post('/registro-usuario', 'Login\LoginController@registroUsuario');
$router->post('/login/', 'Login\LoginController@login');
$router->post('/login-mobile/', 'Login\LoginController@loginMobile');
$router->get('/config-user-mobile/', 'Permisos\UsuariosController@configUserMobileList');
$router->patch('/usuario/update-password/', 'Permisos\UsuariosController@updUsuarioPassword');