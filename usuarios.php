<?php
//verfificar que este logiado
require_once "core/app.php";
verificar_login();
verificar_administrador();

//importar clases
require_once "modelos/Usuario_modelo.php";
require_once "core/Validaciones.php";


$usuario_modelo = new Usuario_modelo();
$validaciones = new Validaciones();

$buscar = '';

//buscar
if (isset($_GET['buscar'])) {
    $buscar = $_GET['buscar'];
    $buscar = $validaciones->limpiar($buscar);
    $datos = $usuario_modelo->select_buscar($buscar);
    include_once "vistas/header.php";
    include_once "vistas/usuario_listar.php";
    include_once "vistas/footer.php";
    exit();
}


//consultar todos los registros
$datos = $usuario_modelo->select();

//llamar a la vista
include_once "vistas/header.php";
include_once "vistas/usuario_listar.php";
include_once "vistas/footer.php";
exit();