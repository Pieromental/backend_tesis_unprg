<?php
namespace App\Utils;

use Illuminate\Http\Request;
use stdClass;

class Table
{
    public static function convertTable($data)
    {
        if ($data) {
            $resultsCero = (array) $data[0];
            $arrayKeys = array_keys($resultsCero);
            $newData = [];
            $headers = [];
            foreach($arrayKeys as $k) {
                $explode = explode('#', $k);
                array_push($headers, [
                    'name' => lcfirst(str_replace(' ','',ucwords(str_replace("_", " ", $explode[1])))),
                    'field' => lcfirst(str_replace(' ','',ucwords(str_replace("_", " ", $explode[1])))),
                    'label' => strtoupper(str_replace("_", " ", $explode[1])),
                    'align' => $explode[2],
                    'style' => 'minWidth:'. $explode[3] .'px',
                    'sortable' => true,
                    'visible' => ($explode[3]>0) ? true : false
                ]);
            };
            $newData = array_map(function ($item) use ($arrayKeys) {
                $newItem = [];
                foreach ($arrayKeys as $k) {
                    $explode = explode('#', $k);
                    $newKey = lcfirst(str_replace(' ','',ucwords(str_replace("_", " ", $explode[1]))));
                    switch ($explode[0]) {
                        case 'int':
                            $newItem[$newKey] =  intval($item->$k);
                            break;
                        case 'boo':
                            $newItem[$newKey] = $item->$k == "1" or $item->$k == 'True' ? true : false;
                            break;
                        case 'jso':
                            $newItem[$newKey] = json_decode($item->$k);
                            break;
                        default:
                            $newItem[$newKey] = ($item->$k == 'None') ? '' : strval($item->$k);
                    }
                }
                return $newItem;
            }, $data);
            // dd($newData);
            return ['data' => $newData, 'header' => $headers];
        } else {
            return ['data' => [], 'header' => []];
        }
    }
}