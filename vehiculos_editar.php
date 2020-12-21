<?php
require_once "core/app.php";
verificar_login();

//importaciones
require_once "modelos/Vehiculo_modelo.php";
require_once "modelos/Parqueadero_modelo.php";
require_once "core/Validaciones.php";

//objetos
$vehiculo_modelo = new vehiculo_modelo();
$validaciones = new Validaciones();
$parqueadero_modelo = new Parqueadero_modelo();

//variables
$errores = array();
$respuesta = null;

//verificar metodo
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == 'POST') {
    //capturar datos
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $placa = isset($_POST['placa']) ? $_POST['placa'] : null;
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;

    if ($validaciones->requerido($placa) == FALSE) {
        $errores[] = 'El campo placa es requerido';
    }

    if ($validaciones->requerido($tipo) == FALSE) {
        $errores[] = 'El campo tipo es requerido';
    }

    //limpiar datos 
    $id = $validaciones->limpiar($id);
    $placa = $validaciones->limpiar($placa);
    $tipo = $validaciones->limpiar($tipo);

    //validar si existe el id
    $vehiculo = $vehiculo_modelo->select_id($id);
    if (count($vehiculo) == 0) {
        $errores[] = 'Id no existe';
    }

    //validar que la placa no exista en otro id
    $vehiculo = $vehiculo_modelo->select_placa($placa);
    if (count($vehiculo) > 0) {
        if ($id != $vehiculo[0]['id']) {
            $errores[] = 'Placa ya existe';
        }
    }

    //no se puede editar vehiculo en parqueadero
    $registro = $parqueadero_modelo->select_vehiculo_id($id);
    if (count($registro) > 0) {
        $errores[] = 'No se puede actualizar el registro';
    }

    if (count($errores) > 0) {
        include_once "vistas/header.php";
        include_once "vistas/vehiculo_editar.php";
        include_once "vistas/footer.php";
        exit();
    }

    //actualizar vehiculo
    $resultado = $vehiculo_modelo->update($id, array(
        "placa" => strtoupper($placa),
        "tipo" => $tipo
    ));

    if ($resultado >= 0) {
        $respuesta = "Vehiculo actualizado";
    } else {
        $respuesta = "Error en el servidor";
    }
}

//buscar id
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

//llamar a vista
include_once "vistas/header.php";
include_once "vistas/vehiculo_editar.php";
include_once "vistas/footer.php";
exit();
