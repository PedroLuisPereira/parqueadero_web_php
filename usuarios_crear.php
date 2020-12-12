<?php
//verfificar que este logiado
require_once "core/app.php";
verificar_login();
verificar_administrador();

require_once "modelos/Usuario_modelo.php";
require_once "core/Validaciones.php";


$usuario_modelo = new Usuario_modelo();
$validaciones = new Validaciones();


//variables
$nombre = '';
$correo = '';
$contra= '';
$rol = '';
$respuesta = null;
$errores = array();


//metodo
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == 'POST') {


    //capturar datos
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $correo = isset($_POST['correo']) ? $_POST['correo'] : null;
    $contra = isset($_POST['contra']) ? $_POST['contra'] : null;
    $rol = isset($_POST['rol']) ? $_POST['rol'] : null;

    //validacioens
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


    //limpiar datos 
    $nombre = $validaciones->limpiar($nombre);
    $correo = $validaciones->limpiar($correo);
    $rol = $validaciones->limpiar($rol);

    //validar si existe el correo
    $usuario = $usuario_modelo->select_correo($correo);
    if (count($usuario) > 0) {
        $errores[] = 'Correo ya existe';
    }

    //validar si existen errores
    if (count($errores) > 0) {
        include_once "vistas/header.php";
        include_once "vistas/usuario_crear.php";
        include_once "vistas/footer.php";
        exit();
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
        $respuesta = "Usuario creado";
    } else {
        $respuesta = "Error en el servidor";
    }

    //limpiar datos 
    $nombre = '';
    $correo = '';
    $rol = '';
}

//llamar a la vista
include_once "vistas/header.php";
include_once "vistas/usuario_crear.php";
include_once "vistas/footer.php";
exit();
