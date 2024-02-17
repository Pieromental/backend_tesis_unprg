<?php

namespace App\Http\Controllers\Permisos;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\Table;
use App\Utils\Response;

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

    public function listarComboTipoUsuario(Request $request)
    {
        try {
            $results = DB::select('exec lisTipoUsuario', []);
            return Response::response(code: 200, data: $results);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e);
        }
    }

    public function getListUsuarios(Request $request) {
        try {
            $activo = $request->input('activo');
            $correo = $request->input('correo');
            $data = DB::select('exec WebGetListUsuario ?, ?', [$activo, $correo]);
            $data = Table::convertTable($data);
            return Response::response(code:200,data:$data,message:"Listado de Usuarios");
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }
}
