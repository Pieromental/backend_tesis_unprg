<?php

namespace App\Http\Controllers\Juegos;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\Table;
use App\Utils\Response;

class JuegosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getListJuegos(Request $request)
    {
        try {
            $id_usuario = $request->input('idUsuario');
            $data = DB::select('exec WebGetListJuegos ?', [$id_usuario]);
            $data = Table::convertTable($data);
            return Response::response(code: 200, data: $data, message: "Listado de Juegos");
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

    public function setJuegos(Request $request)
    {
        try {
            $id_usuario_logueado = $request->input('idUsuarioLogueado');
            $id_usuario = $request->input('idUsuario');
            $id_actividad = $request->input('idActividad');
            $recursos = $request->input('recursos');
            $data = DB::select('exec WebSetNuevoRecurso ?, ?, ?, ?', [json_encode($recursos), $id_usuario, $id_actividad, $id_usuario_logueado]);
            return Response::response(code: $data[0]->code, title: $data[0]->title, message: $data[0]->message, messageError: $data[0]->message_error);
        } catch (\Exception $e) {
            $function_name = __FUNCTION__;
            return response()->json([
                'code' => 500,
                'title' => 'Error',
                'message' => $e->getMessage(),
                'function_name' => $function_name
            ]);
        }
    }

    public function getListRecursosJuegos(Request $request)
    {
        try {
            $id_usuario = $request->input('idUsuario');
            $id_actividad = $request->input('idActividad');
            $data = DB::select('exec WebGetListRecursoPorActividad ?, ?', [$id_usuario, $id_actividad]);

            return Response::response(code: 200, data: $data, message: "Listado de Recursos por juego");
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

}
