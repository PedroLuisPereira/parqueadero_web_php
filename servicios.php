<?php
require_once "core/app.php";
verificar_login();

//importar clases
require_once 'modelos/Servicio_modelo.php';
require_once "core/Validaciones.php";


$servicio_modelo = new Servicio_modelo();

$buscar = '';
//
////buscar
//if (isset($_GET['buscar'])) {
//    $buscar = $_GET['buscar'];
//    //limpiar datos
//    $buscar = $validaciones->limpiar($buscar);
//    //realizar consulta
//    $datos = $cliente_modelo->select_buscar($buscar);
//    //llamar a la vista
//    include_once "vistas/header.php";
//    include_once "vistas/cliente_listar.php";
//    include_once "vistas/footer.php";
//    exit();
//}


//consultar todos los registros
$datos = $servicio_modelo->select();
//llamar a la vista
include_once "vistas/header.php";
include_once "vistas/servicio_listar.php";
include_once "vistas/footer.php";
exit();