<?php
//verfificar que este logiado
require_once "core/app.php";
verificar_login();

//importar clases
require_once "modelos/Cliente_modelo.php";
require_once "core/Validaciones.php";


//crear objetos
$cliente_modelo = new Cliente_modelo();
$validaciones = new Validaciones();

//variables
$errores = array();
$respuesta = null;

//verificar metos
$metodo = $_SERVER["REQUEST_METHOD"];
if ($metodo == 'POST') {
    //capturar datos
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $numero_documento = isset($_POST['numero_documento']) ? $_POST['numero_documento'] : null;
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : null;

    //validaciones
    if ($validaciones->requerido($numero_documento) == FALSE) {
        $errores[] = 'El campo numero_documento es requerido';
    }

    if ($validaciones->requerido($nombre) == FALSE) {
        $errores[] = 'El campo nombre es requerido';
    }


    if ($validaciones->requerido($apellidos) == FALSE) {
        $errores[] = 'El campo apellidos es requerido';
    }


    if ($validaciones->entero($numero_documento) == FALSE) {
        $errores[] = 'El campo numero_documento es númerico';
    }


    //limpiar datos 
    $id = $validaciones->limpiar($id);
    $numero_documento = $validaciones->limpiar($numero_documento);
    $nombre = $validaciones->limpiar($nombre);
    $apellidos = $validaciones->limpiar($apellidos);

    //validar que exista id del cliente
    $registro = $cliente_modelo->select_id($id);
    if (count($registro) == 0) {
        $errores[] = 'Id del cliente no existe';
    }

    //validar numero_documentos único 
    $registro = $cliente_modelo->select_numero_documento($numero_documento);
    if (count($registro) > 0) {
        if ($id != $registro[0]['id']) {
            $errores[] = 'Numero documento ya existe';
        }
    }

    //llamar a la vista
    if (count($errores) > 0) {
        include_once "vistas/header.php";
        include_once "vistas/cliente_editar.php";
        include_once "vistas/footer.php";
        exit();
    }

    //actualizar 
    $resultado = $cliente_modelo->update($id, array(
        "numero_documento" => $numero_documento,
        "nombre" => $nombre,
        "apellidos" => $apellidos
    ));

    //respuestas 
    if ($resultado >= 0) {
        $respuesta = "Cliente actualizado";
    } else {
        $respuesta = "Error en el servidor";
    }

}


//capturar id
$id = isset($_GET['id']) ? $_GET['id'] : null;

//validar que exista id del cliente
$registro = $cliente_modelo->select_id($id);
if (count($registro) == 0) {
     header("Status: 301 Moved Permanently");
     header("Location:" . URL_BASE . 'error_404.php');
     exit;
}


//buscar datos
$numero_documento = $registro[0]['numero_documento'];
$nombre = $registro[0]['nombre'];
$apellidos = $registro[0]['apellidos'];

//llamar a la vista
include_once "vistas/header.php";
include_once "vistas/cliente_editar.php";
include_once "vistas/footer.php";
exit();
