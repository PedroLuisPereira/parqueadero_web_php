<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');


require_once 'modelos/Vehiculo_modelo.php';
require_once 'modelos/Cliente_modelo.php';
require_once 'modelos/Servicio_modelo.php';
require_once 'modelos/Parqueadero_modelo.php';
require_once "core/Token.php";
require_once "core/Respuestas.php";
require_once "core/Validaciones.php";


$vehiculo_modelo = new Vehiculo_modelo();
$servicio_modelo = new Servicio_modelo();
$cliente_modelo = new Cliente_modelo();
$parqueadero_modelo = new Parqueadero_modelo();
$validaciones = new Validaciones();
$respuesta = new Respuestas();
$obj_token = new Token();

//http://localhost/parqueadero_api_php/v3/vehiculos.php?pagina=1



//obtener cabeceras
$header = getallheaders();
//verificar si existe autorizacio 
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

//verificar metodo
$metodo = $_SERVER["REQUEST_METHOD"];

switch ($metodo) {
    case 'GET':

        if (isset($_GET['pagina'])) {
            $pagina = $_GET['pagina'];
            $pagina = $validaciones->limpiar($pagina);
            $datos = $vehiculo_modelo->select_pagina($pagina);
            $respuesta->respuesta("success", $token, null, NULL, $datos);
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $id = $validaciones->limpiar($id);
            $datos = $vehiculo_modelo->select_id($id);
            $respuesta->respuesta("success", $token, NULL, NULL, $datos);
        }

        if (isset($_GET['placa'])) {
            $placa = $_GET['placa'];
            $placa = $validaciones->limpiar($placa);
            $datos = $vehiculo_modelo->select_placa($placa);
            $respuesta->respuesta("success", $token, NULL, NULL, $datos);
        }

        if (isset($_GET['id_cliente'])) {
            $id_cliente = $_GET['id_cliente'];
            $id_cliente = $validaciones->limpiar($id_cliente);
            $datos = $vehiculo_modelo->select_id_cliente($id_cliente);
            $respuesta->respuesta("success", $token, NULL, NULL, $datos);
        }

        if (isset($_GET['buscar'])) {
            $buscar = $_GET['buscar'];
            $buscar = $validaciones->limpiar($buscar);
            $datos = $vehiculo_modelo->select_buscar($buscar);
            $respuesta->respuesta("success", $token, NULL, NULL, $datos);
        }


        //listar todos los vehiculos
        $datos = $vehiculo_modelo->select();
        $respuesta->respuesta("success", $token, null, NULL, $datos);

        break;
    case 'POST':
        //capturar datos
        $json = file_get_contents("php://input");
        //pasar a objeto 
        $obj_datos = json_decode($json);

        if ($obj_datos != NULL) {
            //capturar datos
            $id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : null;
            $tipo = isset($obj_datos->tipo) ? $obj_datos->tipo : null;
            $placa = isset($obj_datos->placa) ? $obj_datos->placa : null;

            //limpiar datos
            $id_cliente = $validaciones->limpiar($id_cliente);
            $tipo = $validaciones->limpiar($tipo);
            $placa = $validaciones->limpiar($placa);

            //validar datos
            $errores = array();
            if ($validaciones->requerido($id_cliente) == FALSE) {
                $errores[] = 'El campo id_cliente es requerido';
            }

            if ($validaciones->requerido($tipo) == FALSE) {
                $errores[] = 'El campo tipo es requerido';
            }

            if ($validaciones->requerido($placa) == FALSE) {
                $errores[] = 'El campo placa es requerido';
            }

            if ($validaciones->tipo_vehiculo($tipo) == FALSE) {
                $errores[] = 'Tipo no permitido';
            }

            if (count($errores) > 0) {
                $respuesta->respuesta("error", null, "Faltan datos", $errores);
            }

            //validar si existe el id_cliente
            $cliente = $cliente_modelo->select_id($id_cliente);
            if (count($cliente) == 0) {
                $errores[] = 'Id cliente no existe';
                $respuesta->respuesta("error", null, "Faltan datos", $errores);
            }

            $vehiculo = $vehiculo_modelo->select_placa($placa);
            if (count($vehiculo) > 0) {
                $errores[] = 'Placa ya existe';
                $respuesta->respuesta("error", null, "Faltan datos", $errores);
            }

            //crear vehiculo
            $resultado = $vehiculo_modelo->insert($id_cliente, array(
                "placa" => strtoupper($placa),
                "tipo" => $tipo
            ));

            if ($resultado > 0) {
                $respuesta->respuesta("success", null, "Vehiculo creado");
            } else {
                $respuesta->respuesta("error", null, "Error en el servidor");
            }
        } else {
            $respuesta->respuesta("error", null, "Solicitud errada");
        }

        break;
    case 'PUT':
        //capturar json
        $json = file_get_contents("php://input");
        $obj_datos = json_decode($json);

        if ($obj_datos != NULL) {
            //capturar datos
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $tipo = isset($obj_datos->tipo) ? $obj_datos->tipo : null;
            $placa = isset($obj_datos->placa) ? $obj_datos->placa : null;

            //limpiar datos
            $id = $validaciones->limpiar($id);
            $tipo = $validaciones->limpiar($tipo);
            $placa = $validaciones->limpiar($placa);

            //validar datos
            $errores = array();
            if ($validaciones->requerido($id) == FALSE) {
                $errores[] = 'El campo id es requerido';
            }

            if ($validaciones->requerido($tipo) == FALSE) {
                $errores[] = 'El campo tipo es requerido';
            }

            if ($validaciones->requerido($placa) == FALSE) {
                $errores[] = 'El campo placa es requerido';
            }

            if ($validaciones->tipo_vehiculo($tipo) == FALSE) {
                $errores[] = 'Tipo no permitido';
            }

            if (count($errores) > 0) {
                $respuesta->respuesta("error", null, "Faltan datos", $errores);
            }

            //validar si existe el id
            $vehiculo = $vehiculo_modelo->select_id($id);
            if (count($vehiculo) == 0) {
                $errores[] = 'Id no existe';
                $respuesta->respuesta("error", null, "Faltan datos", $errores);
            }

            //validar que la placa no exista en otro id
            $vehiculo = $vehiculo_modelo->select_placa($placa);
            if (count($vehiculo) > 0) {
                if ($id != $vehiculo[0]['id']) {
                    $errores[] = 'Placa ya existe';
                    $respuesta->respuesta("error", null, "Faltan datos", $errores);
                }
            }

            //no se puede editar vehiculo en parqueadero
            $registro = $parqueadero_modelo->select_id_vehiculo($id);
            if (count($registro) > 0) {
                $errores[] = 'No se puede actualizar el registro';
                $respuesta->respuesta("success", null, "No se puede actualizar el registro", $errores);
            }

            //actualizar vehiculo
            $resultado = $vehiculo_modelo->update($id, array(
                "placa" => strtoupper($placa),
                "tipo" => $tipo
            ));

            if ($resultado >= 0) {
                $respuesta->respuesta("success", null, "Vehiculo actualizado");
            } else {
                $respuesta->respuesta("error", null, "Error en el servidor");
            }
        } else {
            $respuesta->respuesta("error", null, "Solicitud errada");
        }

        break;
    case 'DELETE':

        //validaciones-----------------------------------------
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        //limpiar datos 
        $id = $validaciones->limpiar($id);

        //array de errores
        $errores = array();

        //validaciones
        if ($validaciones->requerido($id) == FALSE) {
            $errores[] = 'El campo id es requerido';
        }

        //validaciones
        if ($validaciones->entero($id) == FALSE) {
            $errores[] = 'El campo id es númerico';
        }

        //verificar errores
        if (count($errores) > 0) {
            $respuesta->respuesta("error", null, "Faltan datos", $errores);
        }

        //verificar si el id existe
        $vehiculo = $vehiculo_modelo->select_id($id);
        if (count($vehiculo) == 0) {
            $errores[] = 'Id no registrado';
            $respuesta->respuesta("error", null, "Faltan datos", $errores);
        }

        //verificar que no tenga servicio
        $servicio = $servicio_modelo->select_id_vehiculo($id);
        if (count($servicio) > 0) {
            $respuesta->respuesta("success", null, "No se puede eliminar el registro");
        }

        //eliminar registro
        $resultado = $vehiculo_modelo->delete($_GET['id']);
        if ($resultado > 0) {
            $respuesta->respuesta("success", null, "Vehiculo eliminado");
        } else {
            $respuesta->respuesta("error", null, "Error en el servidor, no se puede elimiar registro");
        }

        break;
    default:
        $respuesta->respuesta("error", null, "Método no permitido");
        break;
}
     
