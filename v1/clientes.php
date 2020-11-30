<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

require_once "modelos/Cliente_modelo.php";
require_once 'modelos/Vehiculo_modelo.php';
require_once 'modelos/Servicio_modelo.php';
require_once "core/Token.php";
require_once "core/Respuestas.php";
require_once "core/Validaciones.php";


$cliente_modelo = new Cliente_modelo();
$vehiculo_modelo = new Vehiculo_modelo();
$servicio_modelo = new Servicio_modelo();
$validaciones = new Validaciones();
$respuesta = new Respuestas();
$obj_token = new Token();

//http://localhost/parqueadero_api_php/v1/clientes.php


//obtener cabeceras
$header = getallheaders();
//verificar si existe token
if (isset($header['Authorization']) == FALSE) {
    $respuesta->respuesta("error", null, "Autenticar", null);
}
//extrar el token 
$token = sscanf($header['Authorization'], 'Bearer %s')[0];
//validar token 
$obj_token = $obj_token->validar_token($token);
//validar si el token
if ($obj_token == null) {
    $respuesta->respuesta("error", null, "Autenticar", null);
}
//pasar a array
$array_datos = (array) $obj_token;
//extraer datos del usuario 
$id = $array_datos['data']->id;
$nombre = $array_datos['data']->nombre;
$rol = $array_datos['data']->rol;


//verificar metodo
$metodo = $_SERVER["REQUEST_METHOD"];
//operaciones
switch ($metodo) {
    case 'GET':
        //consultar por página
        if (isset($_GET['pagina'])) {
            $pagina = $_GET['pagina'];
            $datos = $cliente_modelo->select_pagina();
            $respuesta->respuesta("success", $token, NULL, NULL, $datos);
        }
        //consultar por id
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $datos = $cliente_modelo->select_id($id);
            $respuesta->respuesta("success", $token, null, NULL, $datos);
        }

        //consultar por id
        if (isset($_GET['buscar'])) {
            $buscar = $_GET['buscar'];
            $datos = $cliente_modelo->select_buscar($buscar);
            $respuesta->respuesta("success", $token, null, NULL, $datos);
        }

        //consultar todos los registros
        $datos = $cliente_modelo->select();
        $respuesta->respuesta("success", $token, null, NULL, $datos);

        break;
    case 'POST':
        //capturar datos
        $json = file_get_contents("php://input");
        //pasar a objeto
        $obj_datos = json_decode($json);
        //verificar que el objeto sea correcto
        if ($obj_datos != NULL) {
            //capturar datos
            $numero_documento = isset($obj_datos->numero_documento) ? $obj_datos->numero_documento : null;
            $nombre = isset($obj_datos->nombre) ? $obj_datos->nombre : null;
            $apellidos = isset($obj_datos->apellidos) ? $obj_datos->apellidos : null;
            $placa = isset($obj_datos->placa) ? $obj_datos->placa : null;
            $tipo = isset($obj_datos->tipo) ? $obj_datos->tipo : null;


            //array de errores
            $errores = array();

            if ($validaciones->requerido($numero_documento) == FALSE) {
                $errores[] = 'El campo numero_documento es requerido';
            }

            if ($validaciones->requerido($nombre) == FALSE) {
                $errores[] = 'El campo nombre es requerido';
            }

            if ($validaciones->requerido($apellidos) == FALSE) {
                $errores[] = 'El campo apellidos es requerido';
            }

            if ($validaciones->requerido($apellidos) == FALSE) {
                $errores[] = 'El campo apellidos es requerido';
            }

            if ($validaciones->requerido($placa) == FALSE) {
                $errores[] = 'El campo placa es requerido';
            }

            if ($validaciones->requerido($tipo) == FALSE) {
                $errores[] = 'El campo tipo es requerido';
            }

            if ($validaciones->entero($numero_documento) == FALSE) {
                $errores[] = 'El campo numero_documento es númerico';
            }

            if ($validaciones->tipo_vehiculo($tipo) == FALSE) {
                $errores[] = 'Tipo de vehiculo no permitido';
            }

            if (count($errores) > 0) {
                $respuesta->respuesta("error", null, "Faltan datos", $errores);
            }

            //limpiar datos 
            $numero_documento = $validaciones->limpiar($numero_documento);
            $nombre = $validaciones->limpiar($nombre);
            $apellidos = $validaciones->limpiar($apellidos);
            $placa = $validaciones->limpiar($placa);
            $tipo = $validaciones->limpiar($tipo);

            //validar numero_documentos unico
            $registro = $cliente_modelo->select_numero_documento($numero_documento);
            if (count($registro) > 0) {
                $errores[] = 'Numero documento ya existe';
                $respuesta->respuesta("error", null, "Faltan datos", $errores);
            }

            //validar placa vehiculo única
            $registro = $vehiculo_modelo->select_placa($placa);
            if (count($registro) > 0) {
                $errores[] = 'Placa ya existe';
                $respuesta->respuesta("error", null, "Faltan datos", $errores);
            }

            //si todo pasa ------------transaccion -----------------------------
            //crear nuevo cliente
            $id_cliente = $cliente_modelo->insert_id(array(
                "numero_documento" => $numero_documento,
                "nombre" => $nombre,
                "apellidos" => $apellidos
            ));

            //crear vehiculo
            $resultado = $vehiculo_modelo->insert($id_cliente, array(
                "placa" => $placa,
                "tipo" => $tipo
            ));

            if ($resultado > 0) {
                $respuesta->respuesta("success", $token, "Cliente creado");
            } else {
                $respuesta->respuesta("error", null, "Error en el servidor");
            }
        } else {
            $respuesta->respuesta("error", null, "Solicitud errada");
        }

        break;
    case 'PUT':
        //capturar datos
        $json = file_get_contents("php://input");
        //pasar a objeto
        $obj_datos = json_decode($json);

        if ($obj_datos != NULL) {
            //validacioens---------
            //capturar datos
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $numero_documento = isset($obj_datos->numero_documento) ? $obj_datos->numero_documento : null;
            $nombre = isset($obj_datos->nombre) ? $obj_datos->nombre : null;
            $apellidos = isset($obj_datos->apellidos) ? $obj_datos->apellidos : null;


            //limpiar datos 
            $id = $validaciones->limpiar($id);
            $numero_documento = $validaciones->limpiar($numero_documento);
            $nombre = $validaciones->limpiar($nombre);
            $apellidos = $validaciones->limpiar($apellidos);

            //array de errores
            $errores = array();

            //validaciones
            if ($validaciones->requerido($id) == FALSE) {
                $errores[] = 'El campo id es requerido';
            }

            if ($validaciones->requerido($numero_documento) == FALSE) {
                $errores[] = 'El campo numero_documento es requerido';
            }

            if ($validaciones->requerido($nombre) == FALSE) {
                $errores[] = 'El campo nombre es requerido';
            }

            if ($validaciones->requerido($apellidos) == FALSE) {
                $errores[] = 'El campo apellidos es requerido';
            }

            if ($validaciones->entero($numero_documento) == FALSE) {
                $errores[] = 'El campo numero_documento es númerico';
            }

            if (count($errores) > 0) {
                $respuesta->respuesta("error", null, "Faltan datos", $errores);
            }

            //validar que exista id del cliente
            $registro = $cliente_modelo->select_id($id);
            if (count($registro) == 0) {
                $errores[] = 'Id del cliente no existe';
                $respuesta->respuesta("error", null, "Faltan datos", $errores);
            }

            //validar numero_documentos único 
            $registro = $cliente_modelo->select_numero_documento($numero_documento);
            if (count($registro) > 0) {
                if ($id != $registro[0]['id']) {
                    $errores[] = 'Numero documento ya existe';
                    $respuesta->respuesta("error", null, "Faltan datos", $errores);
                }
            }


            $resultado = $cliente_modelo->update($id, array(
                "numero_documento" => $numero_documento,
                "nombre" => $nombre,
                "apellidos" => $apellidos
            ));

            if ($resultado >= 0) {
                $respuesta->respuesta("success", $token, "Cliente actualizado");
            } else {
                $respuesta->respuesta("error", null, "Error en el servidor");
            }
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
        $cliente = $cliente_modelo->select_id($id);
        if (count($cliente) == 0) {
            $errores[] = 'Id no registrado';
            $respuesta->respuesta("error", null, "Faltan datos", $errores);
        }

        //verificar que no tenga vehiculos
        $vehiculo = $vehiculo_modelo->select_id_cliente($id);
        if (count($vehiculo) > 0) {
            $respuesta->respuesta("error", null, "No se puede elimiar registro");
        }

        //eliminar cliente
        $resultado = $cliente_modelo->delete($_GET['id']);

        if ($resultado > 0) {
            $respuesta->respuesta("success", null, "Usuario eliminado");
        } else {
            $respuesta->respuesta("error", null, "Error en el servidor, no se puede elimiar registro");
        }

        break;
    default:
        $respuesta->respuesta("error", null, "Método no permitido");
        break;
}
 

