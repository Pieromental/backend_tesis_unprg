<?php

namespace App\Http\Controllers\Infante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Utils\Response;
use Illuminate\Support\Facades\DB;

class InfanteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function listAccesosUsuario(Request $request)
    {
        try {
            $idUsuario = $request->input('idUsuario');
            $results = DB::select('exec listAccesoUsuario ?', [$idUsuario]);
            return Response::response(code: 200, data: $results);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e);
        }
    }

}
