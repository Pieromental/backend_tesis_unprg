<?php

namespace App\Http\Controllers\Terapista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Utils\Response;
use Illuminate\Support\Facades\DB;

class TerapistaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function listarUsuariosxEspecialista(Request $request)
    {
        try {
            $idEspecialista = $request->input('idEspecialista');
            $results = DB::select('exec listUsuariosxEspecialista ?', [$idEspecialista]);
            $data = [];
            foreach ($results as $val) {
                array_push($data, [
                    'idUsuario' => $val->idUsuario,
                    'imgUser' => $val->imgUser,
                    'nombresAll' => $val->nombresAll,
                    'actividadesUsuario' => json_decode( $val->actividadesUsuario)
                ]);
            }
            return Response::response(code: 200, data: $data);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e);
        }
    }
    public function setAccesoUsuario(Request $request)
    {
        try {
            $idUsuario = $request->input('idUsuario');
            $idActividad = $request->input('idActividad');
            $activar = $request->input('activar');
            $results = DB::select('exec setAccesoUsuario ?,?,?', [$idUsuario, $idActividad,$activar]);
            return Response::response(code: $results[0]->code, title: $results[0]->titulo, message: $results[0]->contenido, otherMessage: $results[0]->clase);
        } catch (GeneralException $e) {
            return Response::response(code: $e->getCode(), message: $e);
        }
    }

}
