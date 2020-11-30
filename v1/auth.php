<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

//importar las clases 
require_once "modelos/Usuario_modelo.php";
require_once "core/Respuestas.php";
require_once "core/Token.php";
require_once "core/Validaciones.php";

//crear los objetos 
$usuario_modelo = new Usuario_modelo();
$respuesta = new Respuestas();
$validaciones = new Validaciones();
$obj_token = new Token();

//verificar metodo 
$metodo = $_SERVER["REQUEST_METHOD"];


switch ($metodo) {
    case 'POST':
        //capturar datos
        $json = file_get_contents("php://input");

        //pasar de json a array, true para hacerlo asosiativo
        $obj_datos = json_decode($json);

        //validar solicitud bien formada
        if ($obj_datos == NULL) {
            $respuesta->respuesta("error", null, "Solicitud errada");
        }

        //capturar datos
        $correo = isset($obj_datos->correo) ? $obj_datos->correo : null;
        $contra = isset($obj_datos->contra) ? $obj_datos->contra : null;
        
        //validaciones
        $errores = array();

        if ($validaciones->requerido($correo) == FALSE) {
            $errores[] = 'El campo correo es requerido';
        }

        if ($validaciones->requerido($contra) == FALSE) {
            $errores[] = 'El campo contra es requerido';
        }

        if ($validaciones->email($correo) == FALSE) {
            $errores[] = 'El campo email no es viable';
        }

        if (count($errores) > 0) {
            $respuesta->respuesta("error", null, "Faltan datos", $errores);
        }

        //limpiar datos 
        $correo = $validaciones->limpiar($correo);
        $contra = md5($contra);

        //buscar usuario
        $registro = $usuario_modelo->select_correo($correo);

        if ($registro) {
            //comparar contraseñas
            if ($contra == $registro[0]['contra']) {

                if ($registro[0]['estado'] == 'Activo') {
                    $id = $registro[0]['id'];
                    $nombre = $registro[0]['nombre'];
                    $correo = $registro[0]['correo'];
                    $rol = $registro[0]['rol'];

                    //crear token
                    $token = $obj_token->crear_token($id, $nombre, $correo, $rol);
                    $respuesta->respuesta("success", $token, "Usuario loguiado", null);
                } else {
                    $respuesta->respuesta("error", NULL, "Usuario inactivo", null);
                }
            } else {
                $respuesta->respuesta("error", null, "Password incorrecto", null);
            }
        } else {
            $respuesta->respuesta("error", null, "Usuario no registrado", null);
        }

        break;
    default:
        $respuesta->respuesta("error", 400, "Método no permitido");
        break;
}
