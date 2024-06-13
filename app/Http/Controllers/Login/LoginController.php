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
            $idespecialista = $request->input('idespecialista');
            $jsonPersona = $request->input('jsonPersona');
            $jsonUsuario = $request->input('jsonUsuario');
            $jsonUsuario['password'] = Hash::make($jsonUsuario['password']);
            $tipo = $request->input('tipo');
            $results = DB::select('exec setNuevoUsuario ?,?,?,?,?', [$idusuario, $idespecialista, json_encode($jsonPersona), json_encode($jsonUsuario), $tipo]);
            return Response::response(code: $results[0]->code, title: $results[0]->titulo, message: $results[0]->contenido, otherMessage: $results[0]->clase);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e);
        }
    }
    public function login(Request $request)
    {
        try {
            $usuario = $request->input('idUsuario');
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

                $dataEnviar = [];

                array_push($dataEnviar, [
                    'usuario' => $usuarioData,
                    'token' => $token
                ]);

                return Response::response(code: $results[0]->code, title: $results[0]->titulo, message: $results[0]->clase, data: $dataEnviar);
            } else {
                return Response::response(code: 400,title:'Usuario No Encontrado', message: 'Usuario y/o Contraseña Inválidos');
            }
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }
    public function loginMobile(Request $request)
    {
        try {

            $usuario = $request->input('idUsuario');
            $password = $request->input('password') ? $request->input('password') : '';

            $storedPasswordHash = DB::table('usuario')
                ->where('idusuario', $usuario)
                ->where('activo', 1)
                ->value('password');
            if (Hash::check($password, $storedPasswordHash)) {
                $results = DB::select('exec MobGetUsuarioMobile ?,?', [$usuario,$storedPasswordHash]);
   
                $usuarioData = json_decode($results[0]->contenido)[0];
               
                return Response::response(code: $results[0]->code, title: $results[0]->titulo, message: $results[0]->clase, data: [$usuarioData]);
            } else {
                return Response::response(code: 300,title:'Usuario No Encontrado', message: 'Usuario y/o Contraseña Inválidos');
            }
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(true);
            return Response::response(code: 200, message: 'El usuario cerro sesión exitosamente');
        } catch (\Exception $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }
}
