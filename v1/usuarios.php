<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

require_once "modelos/Usuario_modelo.php";
require_once "core/Token.php";
require_once "core/Respuestas.php";
require_once "core/Validaciones.php";

$usuario_modelo = new Usuario_modelo();
$respuesta = new Respuestas();
$validaciones = new Validaciones();
$obj_token = new Token();

//http://localhost/parqueadero_api_php/v3/usuarios.php

//obtener cabeceras
$header = getallheaders();
//verificar si existe autorizacio 
if (isset($header['Authorization']) == FALSE) {
    $respuesta->respuesta("error", null, "Autenticar", null);
}
//extrar el token 
$token = sscanf($header['Authorization'], 'Bearer %s')[0];
//validar token 
$obj_token = $obj_token->validar_token($token);

//si se valida 
if ($obj_token == null) {
    $respuesta->respuesta("error", null, "Autenticar", null);
}


//pasar a array
$array_datos = (array) $obj_token;
//datos del usuario 
$id = $array_datos['data']->id;
$nombre = $array_datos['data']->nombre;
$rol = $array_datos['data']->rol;

//metodo de soliciud 
$metodo = $_SERVER["REQUEST_METHOD"];
//operaciones
switch ($metodo) {
    case 'GET':

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $id = $validaciones->limpiar($id);
            $datos = $usuario_modelo->select_id($id);
            $respuesta->respuesta("success", $token, null, null, $datos);
        }

        if (isset($_GET['buscar'])) {
            $buscar = $_GET['buscar'];
            $buscar = $validaciones->limpiar($buscar);
            $datos = $usuario_modelo->select_buscar($buscar);
            $respuesta->respuesta("success", $token, null, null, $datos);
        }

        if (isset($_GET['pagina'])) {
            $pagina = $_GET['pagina'];
            $pagina = $validaciones->limpiar($pagina);
            $datos = $usuario_modelo->select_pagina($pagina);
            $respuesta->respuesta("success", $token, null, null, $datos);
        }


        $datos = $usuario_modelo->select();
        $respuesta->respuesta("success", $token, null, null, $datos);

        break;
    case 'POST':
        //capturar json
        $json = file_get_contents("php://input");
        //pasar a objeto
        $obj_datos = json_decode($json);

        //validar solicitud bien formada
        if ($obj_datos == NULL) {
            $respuesta->respuesta("error", null, "Solicitud errada");
        }
        //capturar datos
        $nombre = isset($obj_datos->nombre) ? $obj_datos->nombre : null;
        $correo = isset($obj_datos->correo) ? $obj_datos->correo : null;
        $contra = isset($obj_datos->contra) ? $obj_datos->contra : null;
        $rol = isset($obj_datos->rol) ? $obj_datos->rol : null;


        //validaciones
        $errores = array();

        if ($validaciones->requerido($nombre) == FALSE) {
            $errores[] = 'El campo nombre es requerido';
        }

        if ($validaciones->requerido($correo) == FALSE) {
            $errores[] = 'El campo email es requerido';
        }

        if ($validaciones->email($correo) == FALSE) {
            $errores[] = 'El campo email no es viable';
        }

        if ($validaciones->requerido($contra) == FALSE) {
            $errores[] = 'El campo password es requerido';
        }

        if ($validaciones->requerido($rol) == FALSE) {
            $errores[] = 'El campo rol es requerido';
        }

        if (count($errores) > 0) {
            $respuesta->respuesta("error", null, "Faltan datos", $errores);
        }


        //limpiar datos 
        $nombre = $validaciones->limpiar($nombre);
        $correo = $validaciones->limpiar($correo);
        $rol = $validaciones->limpiar($rol);

        //validar si existen errores
        if (count($errores) > 0) {
            $respuesta->respuesta("error", null, "Error en los datos", $errores);
        }

        //validar si existe el correo
        $usuario = $usuario_modelo->select_correo($correo);
        if (count($usuario) > 0) {
            $errores[] = 'Correo ya existe';
            $respuesta->respuesta("error", null, "Error en los datos", $errores);
        }

        //encriptar contraseña
        $contra = md5($contra);
        //valor estado
        $estado = "Activo";

        //crear nuevo cliente
        $resultado = $usuario_modelo->insert(array(
            "nombre" => $nombre,
            "correo" => $correo,
            "contra" => $contra,
            "rol" => $rol,
            "estado" => $estado
        ));

        if ($resultado > 0) {
            $respuesta->respuesta("success", $token, "Usuario creado");
        } else {
            $respuesta->respuesta("error", $token, "Error en el servidor");
        }

        break;
    case 'PUT':
        //capturar json
        $json = file_get_contents("php://input");
        //pasar a objeto
        $obj_datos = json_decode($json);

        //validar solicitud bien formada
        if ($obj_datos == NULL) {
            $respuesta->respuesta("error", null, "Solicitud errada");
        }

        //capturar id
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        //validar id
        if ($validaciones->requerido($id) == FALSE) {
            $errores[] = 'El id es requerido';
            $respuesta->respuesta("error", null, "Error en los datos", $errores);
        }
        //limpiar id
        $id = $validaciones->limpiar($id);
        //ver si existe id
        $usuario = $usuario_modelo->select_id($id);
        if (count($usuario) == 0) {
            $errores[] = 'id del usuario no existe';
            $respuesta->respuesta("error", null, "Error en los datos", $errores);
        }

        //actualizar password http://localhost/parqueadero_api_php/v3/usuarios.php?id=5&password
        
        if (isset($_GET['password'])) {
            $contra = isset($obj_datos->password) ? $obj_datos->password : null;

            //validaciones
            $errores = array();

            if ($validaciones->requerido($contra) == FALSE) {
                $errores[] = 'El campo password es requerido';
                $respuesta->respuesta("error", null, "Error en los datos", $errores);
            }
            //encriptar 
            $contra = md5($obj_datos->password);
            //actualizar
            $resultado = $usuario_modelo->update_password($id, $contra);
            //comprobar resultado
            if ($resultado >= 0) {
                $respuesta->respuesta("success", null, "Password actualizada");
            } else {
                $respuesta->respuesta("error", null, "Error en el servidor");
            }
        }


        //actualizar usuario, capturar datos
        $nombre = isset($obj_datos->nombre) ? $obj_datos->nombre : null;
        $correo = isset($obj_datos->correo) ? $obj_datos->correo : null;
        $rol = isset($obj_datos->rol) ? $obj_datos->rol : null;
        $estado = isset($obj_datos->estado) ? $obj_datos->estado : null;

        //validaciones
        $errores = array();

        if ($validaciones->requerido($nombre) == FALSE) {
            $errores[] = 'El campo nombre es requerido';
        }

        if ($validaciones->requerido($correo) == FALSE) {
            $errores[] = 'El campo email es requerido';
        }

        if ($validaciones->email($correo) == FALSE) {
            $errores[] = 'El campo email no es viable';
        }

        if ($validaciones->requerido($rol) == FALSE) {
            $errores[] = 'El campo rol es requerido';
        }

        if ($validaciones->requerido($estado) == FALSE) {
            $errores[] = 'El campo estado es requerido';
        }

        //validar si existen errores
        if (count($errores) > 0) {
            $respuesta->respuesta("error", null, "Error en los datos", $errores);
        }

        //validar si existe el correo en otro usuairo
        $usuario = $usuario_modelo->select_correo($correo);
        if (count($usuario) > 0) {
            if ($id != $usuario[0]["id"]) {
                $errores[] = 'Correo ya existe';
                $respuesta->respuesta("error", null, "Error en los datos", $errores);
            }
        }

        //actualizar password
        $resultado = $usuario_modelo->update($id, array(
            "nombre" => $nombre,
            "correo" => $correo,
            "rol" => $rol,
            "estado" => $estado
        ));

        if ($resultado >= 0) {
            $respuesta->respuesta("success", $token, "Usuario actualizado");
        } else {
            $respuesta->respuesta("error", $token, "Error en el servidor");
        }

        break;
    case 'DELETE':

        //validaciones-----------------------------------------

        if (isset($_GET['id'])) {

            $resultado = $usuario_modelo->delete($_GET['id']);

            if ($resultado > 0) {
                $respuesta->respuesta("success", null, "Usuario eliminado");
            } else {
                $respuesta->respuesta("error", null, "Error en el servidor, no se puede elimiar registro");
            }
        } else {
            $respuesta->respuesta("error", null, "Falta id");
        }

        break;
    default:
        $respuesta->respuesta("error", null, "Método no permitido");
        break;
}
    
