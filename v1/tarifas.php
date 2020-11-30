<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');


require_once 'modelos/Tarifa_modelo.php';
require_once "core/Token.php";
require_once "core/Respuestas.php";
require_once "core/Validaciones.php";


$tarifa_modelo = new Tarifa_modelo();
$respuesta = new Respuestas();
$validaciones = new Validaciones();
$obj_token = new Token();

//http://localhost/parqueadero_api_php/v3/vehiculos.php


//obtener cabeceras
$header = getallheaders();
//verificar si existe autorizacion 
if (isset($header['Authorization']) == FALSE) {
    $respuesta->respuesta("error", null, "Autenticar", null);
}
//extrar el token 
$token = sscanf($header['Authorization'], 'Bearer %s')[0];
//validar token 
$obj_datos = $obj_token->validar_token($token);

//si se valida 
if ($obj_datos == null) {
    $respuesta->respuesta("error", null, "Autenticar", null);
}

//pasar a array
$array_datos = (array) $obj_datos;
//datos del usuario 
$id = $array_datos['data']->id;
$nombre = $array_datos['data']->nombre;
$rol = $array_datos['data']->rol;

//validar metodo
$metodo = $_SERVER["REQUEST_METHOD"];

switch ($metodo) {
    case 'GET':

        $datos = $tarifa_modelo->select();
        $respuesta->respuesta("success", $token, null, null, $datos);

        break;

    case 'PUT':
        //capturar datos
        $json = file_get_contents("php://input");
        //pasar a array
        $obj_datos = json_decode($json);
        //validar solicitud bien formada
        if ($obj_datos == NULL) {
            $respuesta->respuesta("error", null, "Solicitud errada");
        }
        
        //capturar datos
        $minuto_autos = isset($obj_datos->minuto_autos) ? $obj_datos->minuto_autos : null;
        $minuto_bicicletas = isset($obj_datos->minuto_bicicletas) ? $obj_datos->minuto_bicicletas : null;
        $minuto_motos = isset($obj_datos->minuto_motos) ? $obj_datos->minuto_motos : null;
        $descuento = isset($obj_datos->descuento) ? $obj_datos->descuento : null;
        $minutos = isset($obj_datos->minutos) ? $obj_datos->minutos : null;

        //validaciones
        $errores = array();

        if ($validaciones->requerido($minuto_autos) == FALSE) {
            $errores[] = 'El campo minuto_autos es requerido';
        }

        if ($validaciones->requerido($minuto_bicicletas) == FALSE) {
            $errores[] = 'El campo minuto_bicicletas es requerido';
        }

        if ($validaciones->requerido($minuto_motos) == FALSE) {
            $errores[] = 'El campo minuto_motos es requerido';
        }

        if ($validaciones->requerido($descuento) == FALSE) {
            $errores[] = 'El campo descuento es requerido';
        }

        if ($validaciones->requerido($minutos) == FALSE) {
            $errores[] = 'El campo minutos es requerido';
        }

        if (count($errores) > 0) {
            $respuesta->respuesta("error", null, "Faltan datos", $errores);
        }

        //limpiar datos 
        $minuto_autos = $validaciones->limpiar($minuto_autos);
        $minuto_bicicletas = $validaciones->limpiar($minuto_bicicletas);
        $minuto_motos = $validaciones->limpiar($minuto_motos);
        $descuento = $validaciones->limpiar($descuento);
        $minutos = $validaciones->limpiar($minutos);

        $resultado = $tarifa_modelo->update(1, array(
            "minuto_autos" => $minuto_autos,
            "minuto_bicicletas" => $minuto_bicicletas,
            "minuto_motos" => $minuto_motos,
            "descuento" => $descuento,
            "minutos" => $minutos
        ));

        if ($resultado >= 0) {
            $respuesta->respuesta("success", null, "Tarifas actualizadas");
        } else {
            $respuesta->respuesta("error", null, "Error en el servidor");
        }

        break;

    default:
        $respuesta->respuesta("error", null, "Método no permitido");
        break;
}
   
