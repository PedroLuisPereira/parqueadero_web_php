<?php
require_once "core/app.php";
verificar_login();

//importar clientes
require_once "modelos/Servicio_modelo.php";
require_once 'modelos/Vehiculo_modelo.php';
require_once "core/Validaciones.php";


//objetos
$servicio_modelo = new Servicio_modelo();
$vehiculo_modelo = new Vehiculo_modelo();
$validaciones = new Validaciones();


//captar id del cliente
$id = isset($_GET['id']) ? $_GET['id'] : null;

//limpiar datos 
$id = $validaciones->limpiar($id);

//array de errores
$errores = array();


//verificar si el id existe
$vehiculo = $vehiculo_modelo->select_id($id);
if (count($vehiculo) == 0) {
    header("Status: 301 Moved Permanently");
    header("Location:" . URL_BASE . 'error_404.php');
    exit;
}

//verificar que no tenga servicio
$servicio = $servicio_modelo->select_vehiculo_id($id);
if (count($servicio) > 0) {
    $respuesta = "No se puede eliminar el registro";
} else {
    //eliminar registro
    $resultado = $vehiculo_modelo->delete($_GET['id']);
    if ($resultado > 0) {
        $respuesta = "Vehiculo eliminado";
    } else {
        $respuesta = "Error en el servidor, no se puede elimiar registro";
    }
}

//llamar a vista
include_once "vistas/header.php";
include_once "vistas/vehiculo_eliminar.php";
include_once "vistas/footer.php";
exit();
