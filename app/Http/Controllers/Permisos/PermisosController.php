<?php

namespace App\Http\Controllers\Permisos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\Response;

class PermisosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getListPermisos(Request $request) {
        try {
            $idUsuario = $request->input('idUsuario');
            $data = DB::select('exec WebGetListPermisosUsuario ?', [$idUsuario]);
            $menu = $this->menusUsuarioRecursivo(json_decode($data[0]->menu));
            if ($data[0]->permisos !== null) {
                $permisos = explode(',', $data[0]->permisos);
            } else {
                $permisos = [];
            }
            $data[0]->menu = $menu;
            $data[0]->permisos = $permisos;
            return Response::response(code:200,data:$data,message:"Configuraciones Por Usuario");
        } catch (\Exception $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }
     
    public function menusUsuarioRecursivo($data, $parent = null) {
        $arr = [];
        foreach ($data as $val) {
            if ($val->idParent == $parent || ($parent === null && $val->level == 0)) {
                $val->children = $this->menusUsuarioRecursivo($data, $val->id);
                $arr[] = $val;
            }
        }
        return $arr;
    }
}
