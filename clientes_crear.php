<?php
//verfificar que este logiado
require_once "core/app.php";
verificar_login();

//importar clases
require_once "modelos/Cliente_modelo.php";
require_once 'modelos/Vehiculo_modelo.php';
require_once "core/Validaciones.php";

//crear objetos
$cliente_modelo = new Cliente_modelo();
$vehiculo_modelo = new Vehiculo_modelo();
$validaciones = new Validaciones();

//variables
$numero_documento = '';
$nombre = '';
$apellidos = '';
$placa = '';
$tipo = '';
$respuesta = null;
$errores = array();

//verficar metodo
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == 'POST') {

    //capturar datos
    $numero_documento = isset($_POST['numero_documento']) ? $_POST['numero_documento'] : null;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : null;
    $placa = isset($_POST['placa']) ? $_POST['placa'] : null;
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;

    //realizar validaciones
    if ($validaciones->requerido($numero_documento) == FALSE) {
        $errores[] = 'El campo numero_documento es requerido';
    }

    if ($validaciones->requerido($nombre) == FALSE) {
        $errores[] = 'El campo nombre es requerido';
    }

    if ($validaciones->requerido($apellidos) == FALSE) {
        $errores[] = 'El campo apellidos es requerido';
    }

    if ($validaciones->requerido($placa) == FALSE) {
        $errores[] = 'El campo placa es requerido';
    }

    if ($validaciones->requerido($tipo) == FALSE) {
        $errores[] = 'El campo tipo es requerido';
    }

    if ($validaciones->entero($numero_documento) == FALSE) {
        $errores[] = 'El campo numero_documento es númerico';
    }

    if ($validaciones->tipo_vehiculo($tipo) == FALSE) {
        $errores[] = 'Tipo de vehiculo no permitido';
    }


    //limpiar datos 
    $numero_documento = $validaciones->limpiar($numero_documento);
    $nombre = $validaciones->limpiar($nombre);
    $apellidos = $validaciones->limpiar($apellidos);
    $placa = $validaciones->limpiar($placa);
    $tipo = $validaciones->limpiar($tipo);

    //validar numero_documentos unico
    $registro = $cliente_modelo->select_numero_documento($numero_documento);
    if (count($registro) > 0) {
        $errores[] = 'Numero documento ya existe';
    }

    //validar placa vehiculo única
    $registro = $vehiculo_modelo->select_placa($placa);
    if (count($registro) > 0) {
        $errores[] = 'Placa ya existe';
    }
    
    //ver si existen erreores
    if (count($errores) > 0) {
        include_once "vistas/header.php";
        include_once "vistas/cliente_crear.php";
        include_once "vistas/footer.php";
        exit();
    }

    //crear nuevo cliente
    $cliente_id = $cliente_modelo->insert_id(array(
        "numero_documento" => $numero_documento,
        "nombre" => $nombre,
        "apellidos" => $apellidos
    ));

    //crear vehiculo
    $resultado = $vehiculo_modelo->insert($cliente_id, array(
        "placa" => strtoupper($placa),
        "tipo" => $tipo
    ));
    
    //obtener respuesta
    if ($resultado > 0) {
        $respuesta = "Cliente creado";
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

include_once "vistas/header.php";
include_once "vistas/cliente_crear.php";
include_once "vistas/footer.php";
exit();
