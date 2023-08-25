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

    //
}
