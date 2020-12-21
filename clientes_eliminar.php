<?php
//verfificar que este logiado
require_once "core/app.php";
verificar_login();

//importar objetos
require_once "modelos/Cliente_modelo.php";
require_once 'modelos/Vehiculo_modelo.php';
require_once "core/Validaciones.php";


$cliente_modelo = new Cliente_modelo();
$vehiculo_modelo = new Vehiculo_modelo();
$validaciones = new Validaciones();



//varialbes
$respuesta = null;
$errores = array();

//buscar id del cliente
$id = isset($_GET['id']) ? $_GET['id'] : null;
$id = $validaciones->limpiar($id);

//validar que exista id del cliente
$registro = $cliente_modelo->select_id($id);
if (count($registro) == 0) {
    header("Status: 301 Moved Permanently");
    header("Location:" . URL_BASE . 'error_404.php');
    exit;
}


//verificar que no tenga vehiculos
$vehiculo = $vehiculo_modelo->select_cliente_id($id);
if (count($vehiculo) > 0) {
    $respuesta = "No se puede elimiar registro";
} else {
    //eliminar cliente
    $resultado = $cliente_modelo->delete($_GET['id']);

    if ($resultado > 0) {
        $respuesta = "Usuario eliminado";
    } else {
        $respuesta = "Error en el servidor, no se puede elimiar registro";
    }
}

//llamar a la vista
include_once "vistas/header.php";
include_once "vistas/cliente_eliminar.php";
include_once "vistas/footer.php";
exit();
