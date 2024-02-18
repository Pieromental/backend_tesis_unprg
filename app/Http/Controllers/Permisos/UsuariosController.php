<?php

namespace App\Http\Controllers\Permisos;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\Table;
use App\Utils\Response;
use Illuminate\Support\Arr;

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

    public function getListMenusArbol(Request $request) {
        try {
            $data = DB::select('exec listMenusArbol');
            $tree = $this->buildTree(json_decode($data[0]->menusWeb));
            $arr = [];
            array_push($arr, $tree);
            array_push($arr, json_decode($data[0]->menusMobil));
            // dd($arr);
            return Response::response(code:200,data:$arr,message:"Listado de Menus para Arbol");
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

    function buildTree($data, $parentId = 0) {
        $tree = array();
        // dd($data);
        foreach ($data as $item) {
            if ($item->idPadre == $parentId) {
                $children = $this->buildTree($data, $item->idMenu);
                if (!empty($children)) {
                    $item->children = $children;
                }
                $tree[] = $item;
            }
        }
    
        return $tree;
    }

    public function getPersona(Request $request) {
        try {
            $nroDocumento = $request->input('nroDocumento');

            $data = DB::select('exec GenGetTrabajador ?', [$nroDocumento]);
            if($data){
                return Response::response(code:200,data:$data,message:"Se Encontro la Persona");
            } else{
                return Response::response(code:300,data:$data,message:"No se Encontraron Datos");
            }
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

    public function setPersona(Request $request) {
        try {
            $nroDocumento = $request->input('nroDocumento');
            $nombres = $request->input('nombres');
            $apPaterno = $request->input('apPaterno');
            $apMaterno = $request->input('apMaterno');
            $fechaNacimiento = $request->input('fechaNacimiento');
            $sexo = $request->input('sexo');
            $celular = $request->input('celular');
            $email = $request->input('email');
            $usuario = $request->input('usuario');

            $data = DB::select('exec setPersona ?, ?, ?, ?, ?, ?, ?, ?, ?', [$nroDocumento, $nombres, $apPaterno, $apMaterno, $fechaNacimiento, $sexo, $celular, $email, $usuario]);
            return Response::response(code: $data[0]->code, title: $data[0]->title, message: $data[0]->message, messageError: $data[0]->message_error);
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }

    public function checkUsuario(Request $request) {
        try {
            $usuario = $request->input('idUsuario');

            $data = DB::select('exec chekUsuario ?', [$usuario]);
            return Response::response(code: $data[0]->code, title: $data[0]->title, message: $data[0]->message, messageError: $data[0]->message_error);
        } catch (GeneralException $e) {
            $functionName = __FUNCTION__;
            return Response::error(code: $e->getCode(), message: $e, functionName: $functionName);
        }
    }
}
