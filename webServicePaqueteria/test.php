<?php

/* 
 * Test to metodo de datos en formato JSON con PHP
 */

$arreglo[0]['nombre'] = "Cesar";
$arreglo[0]['carrera'] = "ISW";
$arreglo[0]['materias'] = "9";

$arreglo[1]['nombre'] = "Denir";
$arreglo[1]['carrera'] = "ISW";
$arreglo[1]['materias'] = "7";

//print_r($arreglo);

//Codificar a JSON y mostrarlo en pantalla
header('Content-type:application/json;charset=utf-8');
//pretty json
//print json_encode($arreglo, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

//CODIFICAR JSON
$miJSON = json_encode($arreglo,JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
print $miJSON;

//DECODIFICAR JSON
$miArreglo = json_decode($miJSON);
print_r($miArreglo);

