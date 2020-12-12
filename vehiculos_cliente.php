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


$id_cliente = isset($_GET['id']) ? $_GET['id'] : null;

//limpiar id
$id_cliente = $validaciones->limpiar($id_cliente);

//validar que exista id del cliente
$datos = $vehiculo_modelo->select_id_cliente($id_cliente);
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
