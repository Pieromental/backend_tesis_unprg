<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Utils\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function registroUsuario(Request $request)
    {
        try {
            $idusuario = $request->input('idusuario');
            $jsonPersona = $request->input('jsonPersona');
            $jsonUsuario = $request->input('jsonUsuario');
            $jsonUsuario['password'] = Hash::make($jsonUsuario['password']);
            $results = DB::select('exec setNuevoUsuario ?,?,?', [$idusuario, json_encode($jsonPersona), json_encode($jsonUsuario)]);
            return Response::response(code: $results[0]->code, title: $results[0]->titulo, message: $results[0]->contenido, otherMessage: $results[0]->clase);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e);
        }
    }
    public function login(Request $request)
    {
        try {
            $usuario = $request->input('idusuario');
            $password = $request->input('password') ? $request->input('password') : '';


            $storedPasswordHash = DB::table('usuario')
                ->where('idusuario', $usuario)
                ->where('activo', 1)
                ->value('password');
            if (Hash::check($password, $storedPasswordHash)) {
                $results = DB::select('exec getUsuario ?,?', [$usuario, $storedPasswordHash]);
                $token = [];
                $usuarioData = json_decode($results[0]->contenido)[0];
                if ($results[0]->code == 200) {
                    $user = new User(); // Asegúrate de que tu User implementa JWTSubject
                    $user->idusuario = $usuarioData->idusuario;

                    // Generar un token JWT para el usuario
                    $token = array('token' => JWTAuth::fromUser($user));
                }
                return Response::response(code: $results[0]->code, title: $results[0]->titulo, message: $results[0]->clase, data: $token);
            } else {
                return Response::response(code: 400, message: 'Usuario y/o Contraseña Inválidos');
            }
        } catch (GeneralException $e) {
            return Response::error(code: $e->getCode(), message: $e);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(true);
            return Response::response(code: 200, message: 'El usuario cerro sesión exitosamente');
        } catch (\Exception $e) {
            return Response::error(code: $e->getCode(), message: $e);
        }
    }
}
