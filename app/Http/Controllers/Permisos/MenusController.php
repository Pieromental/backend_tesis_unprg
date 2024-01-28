<?php

namespace App\Http\Controllers\Permisos;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\Table;
use App\Utils\Response;

class MenusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getListOpcionesCmb(Request $request) {
        try {
            $data = DB::select('exec listOpcionesCmb');
            return Response::response(code:200,data:$data,message:"Listado de Opciones");
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

    public function getListMenus(Request $request) {
        try {
            $activo = $request->input('activo');
            $data = DB::select('exec listMenus ?', [$activo]);
            $data = Table::convertTable($data);
            return Response::response(code:200,data:$data,message:"Listado de Menus");
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

    public function setMenus(Request $request) {
        try {
            $id_usuario = $request->input('idUsuario');
            $arbol_sistema = $request->input('menu');
            $array = [];
            $array = $this->arbol_lista($arbol_sistema[0]['children'], $array);
            usort($array, function($a, $b) {
                return $a['nivel'] <=> $b['nivel'];
            });

            $array_menu = [];
    
            foreach ($array as $indice => $valor) {
                unset($array[$indice]['children']);
                $array_menu[] = $array[$indice];
            }
    
            array_unshift($array_menu, [
                'idMenu' => $arbol_sistema[0]['idMenu'],
                'nombre' => $arbol_sistema[0]['nombre'],
                'idPadre' => $arbol_sistema[0]['idPadre'],
                'tipo' => $arbol_sistema[0]['tipo'],
                'nivel' => $arbol_sistema[0]['nivel'],
                'idSlugFrontend' => $arbol_sistema[0]['idSlugFrontend'],
                'icono' => $arbol_sistema[0]['icono'],
                'code' => $arbol_sistema[0]['code'],
                'idOpcion' => $arbol_sistema[0]['idOpcion'],
                'descripcion' => $arbol_sistema[0]['descripcion']
            ]);
    
            $data = DB::select('exec SetNuevoMenu ?, ?', [json_encode($array_menu), $id_usuario]);
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

    private function arbol_lista($tree, &$array) {
        foreach ($tree as $node) {
            $array[] = $node;
            if (isset($node['children']) && !empty($node['children'])) {
                $this->arbol_lista($node['children'], $array);
            }
        }
        return $array;
    }
}
