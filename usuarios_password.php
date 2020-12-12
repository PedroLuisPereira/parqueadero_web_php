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
$id = '';
$contra = '';
$respuesta = null;
$errores = array();

//metodo
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == 'POST') {

    //capturar datos
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $contra = isset($_POST['contra']) ? $_POST['contra'] : null;
    $confirmar_contra = isset($_POST['confirmar_contra']) ? $_POST['confirmar_contra'] : null;

    //validaciones
    if ($validaciones->requerido($contra) == FALSE) {
        $errores[] = 'El campo Password es requerido';
    }

    if ($validaciones->requerido($confirmar_contra) == FALSE) {
        $errores[] = 'El campo Confirmar password es requerido';
    }

    if ($contra != $confirmar_contra) {
        $errores[] = 'El password no coinciden';
    }


    //validar si existen errores
    if (count($errores) > 0) {
        include_once "vistas/header.php";
        include_once "vistas/usuario_password.php";
        include_once "vistas/footer.php";
        exit();
    }

    //actualizar password
    $resultado = $usuario_modelo->update_password($id, md5($contra));

    if ($resultado >= 0) {
        $respuesta = "Password actualizada";
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

//llamara a la vista
include_once "vistas/header.php";
include_once "vistas/usuario_password.php";
include_once "vistas/footer.php";
exit();
