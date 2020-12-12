<?php
//verfificar que este logiado
require_once "core/app.php";
verificar_login();
verificar_administrador();

//importaciones
require_once "modelos/Usuario_modelo.php";
require_once "core/Validaciones.php";

//crear objetos
$usuario_modelo = new Usuario_modelo();
$validaciones = new Validaciones();

//variables
$nombre = '';
$correo = '';
$contra = '';
$rol = '';
$estado = '';
$respuesta = null;
$errores = array();

//metodo
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == 'POST') {

    //capturar datos
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $correo = isset($_POST['correo']) ? $_POST['correo'] : null;
    $rol = isset($_POST['rol']) ? $_POST['rol'] : null;
    $estado = isset($_POST['estado']) ? $_POST['estado'] : null;

    //validaciones
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


    //validar si existe el correo en otro usuairo
    $usuario = $usuario_modelo->select_correo($correo);
    if (count($usuario) > 0) {
        if ($id != $usuario[0]["id"]) {
            $errores[] = 'Correo ya existe';
        }
    }

    //validar si existen errores
    if (count($errores) > 0) {
        include_once "vistas/header.php";
        include_once "vistas/usuario_editar.php";
        include_once "vistas/footer.php";
        exit();
    }

    //actualizar password
    $resultado = $usuario_modelo->update($id, array(
        "nombre" => $nombre,
        "correo" => $correo,
        "rol" => $rol,
        "estado" => $estado
    ));

    if ($resultado >= 0) {
        $respuesta = "Usuario actualizado";
    } else {
        $respuesta = "Error en el servidor";
    }
}

//id del cliente
$id = isset($_GET['id']) ? $_GET['id'] : null;

// //validar que exista id del cliente
$registro = $usuario_modelo->select_id($id);
if (count($registro) == 0) {
    header("Status: 301 Moved Permanently");
    header("Location:" . URL_BASE . 'error_404.php');
    exit;
}

//buscar datos
$nombre = $registro[0]['nombre'];
$correo = $registro[0]['correo'];
$rol = $registro[0]['rol'];
$estado = $registro[0]['estado'];

//llamara a la vista
include_once "vistas/header.php";
include_once "vistas/usuario_editar.php";
include_once "vistas/footer.php";
exit();
