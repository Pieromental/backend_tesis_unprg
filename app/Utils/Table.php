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
                    'name' => $explode[0],
                    'field' => $explode[0],
                    'label' => strtoupper(str_replace("_", " ", $explode[0])),
                    'align' => $explode[1],
                    'style' => 'width:'. $explode[2] .'px',
                    'sortable' => true,
                    'visible' => ($explode[2]>0) ? true : false
                ]);
            };
            $newData = array_map(function ($item) use ($arrayKeys) {
                $newItem = [];
                foreach ($arrayKeys as $k) {
                    $explode = explode('#', $k);
                    $newKey = $explode[0];
                    $newItem[$newKey] = $item->$k;
                }
                return $newItem;
            }, $data);
            return ['data' => $newData, 'header' => $headers];
        } else {
            return ['data' => [], 'header' => []];
        }
    }
}