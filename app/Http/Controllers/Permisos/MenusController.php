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

    public function getListOpcionesCmb(Request $request)
    {
        try {
            $data = DB::select('exec listOpcionesCmb');
            return Response::response(code: 200, data: $data, message: "Listado de Opciones");
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

    public function getListMenus(Request $request)
    {
        try {
            $activo = $request->input('activo');
            $data = DB::select('exec listMenus ?', [$activo]);
            $data = Table::convertTable($data);
            return Response::response(code: 200, data: $data, message: "Listado de Menus");
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

    public function setMenus(Request $request)
    {
        try {
            $id_usuario = $request->input('idUsuario');
            $arbol_sistema = $request->input('menu');
            $array = [];
            $array = $this->arbol_lista($arbol_sistema[0]['children'], $array);
            usort($array, function ($a, $b) {
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

    private function arbol_lista($tree, &$array)
    {
        foreach ($tree as $node) {
            $array[] = $node;
            if (isset($node['children']) && !empty($node['children'])) {
                $this->arbol_lista($node['children'], $array);
            }
        }
        return $array;
    }

    public function getMenusxId(Request $request)
    {
        try {
            $id_menu = $request->input('idMenu');

            $data = DB::select('exec WebGetListMenuPorId ?', [$id_menu]);
            if (empty($data)) {
                return Response::response(code: 300, data: $data, message: "No existe data");
            } else {
                $items = [];
                foreach ($data as $k => $v) {
                    $items[] = [
                        '_key' => $k,
                        'idMenu' => $v->idMenu,
                        'nombre' => $v->nombre,
                        'idPadre' => $v->idPadre,
                        'nivel' => (int) $v->nivel,
                        'idSlugFrontend' => $v->idSlugFrontend,
                        'tipo' => $v->tipo,
                        'icono' => $v->icono,
                        'code' => $v->code,
                        'label' => $v->nombre,
                        'descripcion' => $v->descripcion,
                        'activo' => (int) $v->activo,
                        'idOpcion' => $v->idOpcion,
                        'descripcionOpcion' => $v->descripcionOpcion,
                        'children' => []
                    ];
                }

                $constructed_tree = $this->construir_arbol($items);
                // $filtered_tree = $this->ordenar_arbol($constructed_tree);
            }
            return Response::response(code: 200, data: $constructed_tree, message: "Listado de Menus");
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

    function construir_arbol($elements, $parent_id = null)
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['idPadre'] == $parent_id) {
                $children = $this->construir_arbol($elements, $element['idMenu']);
                if (!empty($children)) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    function ordenar_arbol($tree)
    {
        // Ordenar los hijos del árbol actual por el campo "sequencia"
        // usort($tree, function ($a, $b) {
        //     return $a['sequencia'] <=> $b['sequencia'];
        // });

        foreach ($tree as &$node) {
            if (!empty($node['children'])) {
                $node['children'] = $this->ordenar_arbol($node['children']);
            }
        }
        return $tree;
    }
}
