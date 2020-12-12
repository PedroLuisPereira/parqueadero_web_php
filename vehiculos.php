<?php
require_once "core/app.php";
verificar_login();

//importaciones
require_once 'modelos/Vehiculo_modelo.php';
require_once "core/Validaciones.php";

//objetos
$vehiculo_modelo = new Vehiculo_modelo();
$validaciones = new Validaciones();

$buscar = '';

//buscar
if (isset($_GET['buscar'])) {
    $buscar = $_GET['buscar'];
    $buscar = $validaciones->limpiar($buscar);
    $datos = $vehiculo_modelo->select_buscar($buscar);
    include_once "vistas/header.php";
    include_once "vistas/vehiculo_listar.php";
    include_once "vistas/footer.php";
    exit();
}


//consultar todos los registros
$datos = $vehiculo_modelo->select();

//llamar a la vista
include_once "vistas/header.php";
include_once "vistas/vehiculo_listar.php";
include_once "vistas/footer.php";
exit();