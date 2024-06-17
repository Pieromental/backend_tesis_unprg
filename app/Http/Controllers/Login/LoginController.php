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
            $idusuario = $request->input('idUsuario');
            $idespecialista = $request->input('idEspecialista');
            $jsonPersona = $request->input('jsonPersona');
            $jsonUsuario = $request->input('jsonUsuario');
            $jsonUsuario['password'] = Hash::make($jsonUsuario['password']);
            $tipo = $request->input('tipo');
            $idPersona = $request->input('idPersona');
            $results = DB::select('exec setNuevoUsuario ?,?,?,?,?,?', [$idusuario, $idespecialista, json_encode($jsonPersona), json_encode($jsonUsuario), $tipo, $idPersona]);
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
                $results = DB::select('exec MobGetUsuarioMobile ?, ?', [$usuario, $storedPasswordHash]);
    
                // Decodificar el JSON con true para obtener un array asociativo
                $usuarioData = json_decode($results[0]->contenido, true);
    
                 //dd($usuarioData); // Para depuración, puedes eliminar esta línea después
    
                $dataEnviar = [];
    
                // Corrigiendo la construcción del array
                array_push($dataEnviar, [
                    'usuario' => [
                        'IdUsuario' => $usuarioData[0]['idusuario'],
                        'Email' => $usuarioData[0]['email'],
                        'Password' => $usuarioData[0]['password'],
                    ],
                    'persona'=>[
                        'IdPersona'=> $usuarioData[0]['idPersona'],
                        'ApPaterno'=> $usuarioData[0]['apPaterno'],
                        'ApMaterno'=> $usuarioData[0]['apMaterno'],
                        'Nombres'=> $usuarioData[0]['nombres'],
                        'NombresAll'=> $usuarioData[0]['nombresAll'],
                        'NroDocumento'=> $usuarioData[0]['nroDocumento'],
                        'Sexo'=> $usuarioData[0]['sexo'],
                        'Celular'=> $usuarioData[0]['celular'],
                        'Activo'=> $usuarioData[0]['activo'],
                        'FechaNacimiento'=> $usuarioData[0]['fechaNacimiento']
                    ]
                ]);
                
                // dd($dataEnviar);
                return Response::response(
                    code: $results[0]->code,
                    title: $results[0]->titulo,
                    message: $results[0]->clase,
                    data: $dataEnviar

                );
            } else {
                return Response::response(
                    code: 300,
                    title: 'Usuario No Encontrado',
                    message: 'Usuario y/o Contraseña Inválidos'
                );
            }
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(
                code: $e->getCode(),
                message: $e,
                functionName: $functionName
            );
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
