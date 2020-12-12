<?php
require_once "core/app.php";
verificar_login();

//importaciones
require_once 'modelos/Vehiculo_modelo.php';
require_once "core/Validaciones.php";

//objetos
$vehiculo_modelo = new Vehiculo_modelo();
$validaciones = new Validaciones();


$id = isset($_GET['id']) ? $_GET['id'] : null;

//limpiar
$id = $validaciones->limpiar($id);

//validar que exista id del cliente
$registro = $vehiculo_modelo->select_id($id);
if (count($registro) == 0) {
    header("Status: 301 Moved Permanently");
    header("Location:" . URL_BASE . 'error_404.php');
    exit;
}


//buscar datos
$placa = $registro[0]['placa'];
$tipo = $registro[0]['tipo'];

//llamar a la vista
include_once "vistas/header.php";
include_once "vistas/vehiculo_ver.php";
include_once "vistas/footer.php";
exit();
