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
});

$router->post('/registro-usuario', 'Login\LoginController@registroUsuario');
$router->post('/login', 'Login\LoginController@login');
