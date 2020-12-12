<?php
//verfificar que este logiado
require_once "core/app.php";
verificar_login();

//importaciones
require_once "modelos/Usuario_modelo.php";
require_once "core/Validaciones.php";

//objetos
$usuario_modelo = new Usuario_modelo();
$validaciones = new Validaciones();


//variables
$errores = array();
$nombre = '';
$correo = '';
$rol = '';
$respuesta = null;

//metodo
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == 'POST') {
    if (isset($_GET['password'])) {

        //capturar datos
        $id = $_SESSION['usuario_id'];
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
            include_once "vistas/usuario_cuenta.php";
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
}

//vistas
include_once "vistas/header.php";
include_once "vistas/usuario_cuenta.php";
include_once "vistas/footer.php";
exit();
