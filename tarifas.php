<?php
require_once "core/app.php";
verificar_login();
verificar_administrador();

//importar clases
require_once 'modelos/Tarifa_modelo.php';
require_once "core/Validaciones.php";


$tarifa_modelo = new Tarifa_modelo();
$validaciones = new Validaciones();


$errores = array();
$respuesta = NULL;

$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == 'POST') {

    //capturar datos
    $minuto_autos = isset($_POST['minuto_autos']) ? $_POST['minuto_autos'] : null;
    $minuto_bicicletas = isset($_POST['minuto_bicicletas']) ? $_POST['minuto_bicicletas'] : null;
    $minuto_motos = isset($_POST['minuto_motos']) ? $_POST['minuto_motos'] : null;
    $descuento = isset($_POST['descuento']) ? $_POST['descuento'] : null;
    $minutos = isset($_POST['minutos']) ? $_POST['minutos'] : null;

    if ($validaciones->requerido($minuto_autos) == FALSE) {
        $errores[] = 'El campo minuto_autos es requerido';
    }

    if ($validaciones->requerido($minuto_bicicletas) == FALSE) {
        $errores[] = 'El campo minuto_bicicletas es requerido';
    }

    if ($validaciones->requerido($minuto_motos) == FALSE) {
        $errores[] = 'El campo minuto_motos es requerido';
    }

    if ($validaciones->requerido($descuento) == FALSE) {
        $errores[] = 'El campo descuento es requerido';
    }

    if ($validaciones->requerido($minutos) == FALSE) {
        $errores[] = 'El campo minutos es requerido';
    }

    if (count($errores) > 0) {
        //consultar todos los registros
        $datos = $tarifa_modelo->select();

        include_once "vistas/header.php";
        include_once "vistas/tarifa_listar.php";
        include_once "vistas/footer.php";
        exit();
    }

    //limpiar datos 
    $minuto_autos = $validaciones->limpiar($minuto_autos);
    $minuto_bicicletas = $validaciones->limpiar($minuto_bicicletas);
    $minuto_motos = $validaciones->limpiar($minuto_motos);
    $descuento = $validaciones->limpiar($descuento);
    $minutos = $validaciones->limpiar($minutos);

    $resultado = $tarifa_modelo->update(1, array(
        "minuto_autos" => $minuto_autos,
        "minuto_bicicletas" => $minuto_bicicletas,
        "minuto_motos" => $minuto_motos,
        "descuento" => $descuento,
        "minutos" => $minutos
    ));

    if ($resultado >= 0) {
        $respuesta = "Tarifas actualizadas";
    } else {
        $respuesta = "Error en el servidor";
    }
}


//consultar todos los registros
$datos = $tarifa_modelo->select();

//llamar a la vista
include_once "vistas/header.php";
include_once "vistas/tarifa_listar.php";
include_once "vistas/footer.php";
exit();
