<?php
require_once "core/app.php";
verificar();

require_once "modelos/Usuario_modelo.php";
require_once "core/Validaciones.php";

$usuario_modelo = new Usuario_modelo();
$validaciones = new Validaciones();


//variables
$errores = array();
$correo = '';
$contra = '';
$respuesta = null;

//metodo
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == 'POST') {

    $correo = isset($_POST['correo']) ? $_POST['correo'] : null;
    $contra = isset($_POST['contra']) ? $_POST['contra'] : null;


    //validaciones
    $errores = array();

    if ($validaciones->requerido($correo) == FALSE) {
        $errores[] = 'El campo correo es requerido';
    }

    if ($validaciones->requerido($contra) == FALSE) {
        $errores[] = 'El campo password es requerido';
    }


    if (count($errores) > 0) {
        include_once "vistas/login.php";
        exit();
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

                //crear sesion
                // Comiendo de la sesión
                session_start();
                // Guardar datos de sesión
                $_SESSION["login"] = "true";
                $_SESSION["usuario_id"] = $id;
                $_SESSION["usuario_nombre"] = $nombre;
                $_SESSION["usuario_correo"] = $correo;
                $_SESSION["usuario_rol"] = $rol;


                //redireccionar
                header("Status: 301 Moved Permanently");
                header("Location:" . URL_BASE );
            } else {
                $respuesta = "Usuario inactivo";
            }
        } else {
            $respuesta = "Password incorrecto";
        }
    } else {
        $respuesta = "Usuario no registrado";
    }
}

include_once "vistas/login.php";
exit();
