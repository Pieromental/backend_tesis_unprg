<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Utils\Response;
use Illuminate\Support\Facades\DB;

class UsuariosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function listarTipoUsuario(Request $request)
    {
        try {
            $results = DB::select('exec lisTipoUsuario', []);
            return Response::response(code: 200, data: $results);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e);
        }
    }

    public function setActividadRecurso(Request $request)
    {
        try {
            $idusuario = $request->input('idusuario');
            $idactividad = $request->input('idactividad');
            $idusuarioPersonalizacion = $request->input('idusuarioPersonalizacion');
            $recursosJson = $request->input('recursosJson');
            $results = DB::select('exec setNuevoRecursoActividad ?,?,?,?', [$idusuario,$idactividad, $idusuarioPersonalizacion, json_encode($recursosJson)]);
            return Response::response(code: $results[0]->code, title: $results[0]->titulo, message: $results[0]->contenido, otherMessage: $results[0]->clase);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e);
        }
    }

    //
}
