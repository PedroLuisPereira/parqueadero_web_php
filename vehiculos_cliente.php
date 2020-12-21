<?php
//verfificar que este logiado
require_once "core/app.php";
verificar_login();

//importar objetos
require_once 'modelos/Vehiculo_modelo.php';
require_once "core/Validaciones.php";
require_once "core/app.php";


//crear objetos
$vehiculo_modelo = new Vehiculo_modelo();
$validaciones = new Validaciones();


$cliente_id = isset($_GET['id']) ? $_GET['id'] : null;

//limpiar id
$cliente_id = $validaciones->limpiar($cliente_id);

//validar que exista id del cliente
$datos = $vehiculo_modelo->select_cliente_id($cliente_id);
if (count($datos) == 0) {
    header("Status: 301 Moved Permanently");
    header("Location:" . URL_BASE . 'error_404.php');
    exit;
}

//llamar a la vista
include_once "vistas/header.php";
include_once "vistas/vehiculo_cliente.php";
include_once "vistas/footer.php";
exit();
