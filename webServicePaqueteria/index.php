<?php
//no bloquee el contenido
if (isset($_SERVER['HTTP_ORIGIN']))
{
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials:true');
    header('Access-Control-Allow-Headers:Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With');
    header('Access-Control-Max-Age:86400'); //cache por un dia 
}

//ESTABLECE FORMATO DE ENTRADA PARA APPLICATION/JSON
if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') != 0)
{
    throw new Exception("EL METODO DEBERIA SER POST");
}

//Establece que el formato de entrada será application/json
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
    throw new Exception('Content type must be: application/json');
}


//RECIBE EL RAW
$content = trim(file_get_contents("php://input"));

//transdorma el raw json a php
$decoded = json_decode($content,true); //guarda la peticion

$message = array(); //guardar las respuestas

require 'Config/Conexion.php';
require 'API/apiTest.php';

$miApi = new Consultas();

//tratar la peticion
switch ($decoded['action']) {
    case "insertarUsuario":
            if(!isset($decoded['clave']) || !isset($decoded['nombre']) || !isset($decoded['correo']) || !isset($decoded['direccion'])|| !isset($decoded['telefono']))
            {
                $message["message"] = "FALTAN CAMPOS POR LLENAR.";
            }
            else
            {
                $values = $decoded;
                if(($data = $miApi->insertarUsuario($values))) //VERIFICA QUE ES UN ARREGLO, ES DECIR QUE LA CONSULTA DEVUELVA RESULTADOS
                {
                    $message["message"] = "USUARIO REGISTRO CON EXITO.";
                }
                else
                {
                    $message["message"] = "ERROR EN LA ALTA DE USUARIO.";
                }
            }
        break;
    case "insertarPedido":
            if(!isset($decoded['peso']) || !isset($decoded['dimensiones']) || !isset($decoded['origen']) || !isset($decoded['destino'])|| !isset($decoded['tipo']) || !isset($decoded['fechaEntrega'])|| !isset($decoded['costo']) || !isset($decoded['idusuario']))
            {
                $message["message"] = "FALTAN CAMPOS POR LLENAR.";
            }
            else
            {
                $values = $decoded;
                if(($data = $miApi->insertarPedido($values))) //VERIFICA QUE ES UN ARREGLO, ES DECIR QUE LA CONSULTA DEVUELVA RESULTADOS
                {
                    $message["message"] = "PEDIDO REGISTRO CON EXITO.";
                }
                else
                {
                    $message["message"] = "ERROR EN LA ALTA DE PAQUETE.";
                }
            }
        break;
    case "insertarMovimiento":
            if(!isset($decoded['descripcion']) || !isset($decoded['tipo']) || !isset($decoded['idusuario']) || !isset($decoded['idpedido']))
            {
                $message["message"] = "FALTAN CAMPOS POR LLENAR.";
            }
            else
            {
                $values = $decoded;
                if(($data = $miApi->insertarMovimiento($values))) //VERIFICA QUE ES UN ARREGLO, ES DECIR QUE LA CONSULTA DEVUELVA RESULTADOS
                {
                    $message["message"] = "MOVIMIENTO REGISTRO CON EXITO.";
                }
                else
                {
                    $message["message"] = "ERROR EN LA ALTA DE MOVIMIENTO.";
                }
            }
        break;
        
    case "consultarMovimientos":
            if(is_array($data = $miApi->consultarMovimientos($decoded['id']))) //VERIFICA QUE ES UN ARREGLO, ES DECIR QUE LA CONSULTA DEVUELVA RESULTADOS
            {
                $message = $data;
            }
            else
            {
                $message["message"] = "ERROR EN LA ACCION CANCIONES.";
            }
        break;
        
    case "loggin":
            if(!isset($decoded['usuario']) || !isset($decoded['clave']))
            {
                $message["message"] = "FALTAN CAMPOS POR LLENAR.";
            }
            else
            {
                $values = $decoded;
                if(!empty($data = $miApi->loggin($values)))
                {
                    $message = "LOGUEADO CON EXITO";
                }
                else
                {
                    $message = "DATOS INCORRECTOS, ERROR AL INICIAR SESION.";
                }
            }
        break;

    case "ConsultarPedido":
            if(is_array($data = $miApi->ConsultarPedido())) 
            {
                $message = $data;
            }
            else
            {
                $message["message"] = "ERROR EN LA ACCION CANCIONES.";
            }
        break;
        
    default:
            $message["message"] = "ACCION NO VALIDA";
        break;
}

header('Content-Type:application/json;charset=utf-8');
print json_encode($message, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);