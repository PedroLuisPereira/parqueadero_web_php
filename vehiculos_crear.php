<?php
//verfificar que este logiado
require_once "core/app.php";
verificar_login();

//importaciones
require_once "modelos/Cliente_modelo.php";
require_once 'modelos/Vehiculo_modelo.php';
require_once "core/Validaciones.php";

//crear objetos
$cliente_modelo = new Cliente_modelo();
$vehiculo_modelo = new Vehiculo_modelo();
$validaciones = new Validaciones();

//variables
$placa = '';
$tipo = '';
$respuesta = null;
$errores = array();

//metodo
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == 'POST') {

    //capturar datos
    $cliente_id = isset($_GET['id']) ? $_GET['id'] : null;
    $placa = isset($_POST['placa']) ? $_POST['placa'] : null;
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;

    
    if ($validaciones->requerido($placa) == FALSE) {
        $errores[] = 'El campo placa es requerido';
    }

    if ($validaciones->requerido($tipo) == FALSE) {
        $errores[] = 'El campo tipo es requerido';
    }

    if ($validaciones->tipo_vehiculo($tipo) == FALSE) {
        $errores[] = 'Tipo de vehiculo no permitido';
    }


    //limpiar datos 
    $placa = $validaciones->limpiar($placa);
    $tipo = $validaciones->limpiar($tipo);


    //validar placa vehiculo única
    $registro = $vehiculo_modelo->select_placa($placa);
    if (count($registro) > 0) {
        $errores[] = 'Placa ya existe';
    }

    //lanzan errores
    if (count($errores) > 0) {
        include_once "vistas/header.php";
        include_once "vistas/vehiculo_crear.php";
        include_once "vistas/footer.php";
        exit();
    }


    //crear vehiculo
    $resultado = $vehiculo_modelo->insert($cliente_id, array(
        "placa" => $placa,
        "tipo" => $tipo
    ));

    if ($resultado > 0) {
        $respuesta = "Vehiculo creado";
    } else {
        $respuesta = "Error en el servidor";
    }
    
    //limpiar los datos
    $numero_documento = '';
    $nombre = '';
    $apellidos = '';
    $placa = '';
    $tipo = '';
}

//id del cliente
$cliente_id = isset($_GET['id']) ? $_GET['id'] : null;

// //validar que exista id del cliente
$registro = $cliente_modelo->select_id($cliente_id);
if (count($registro) == 0) {
     header("Status: 301 Moved Permanently");
     header("Location:" . URL_BASE . 'error_404.php');
     exit;
}

include_once "vistas/header.php";
include_once "vistas/vehiculo_crear.php";
include_once "vistas/footer.php";
exit();
