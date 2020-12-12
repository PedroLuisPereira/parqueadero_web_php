<?php 
session_start();

//url base
define("URL_BASE", 'http://localhost/parqueadero_web_php/');

//verificar login
function verificar_login() {
    if (isset($_SESSION["login"]) == FALSE) {
        //redireccionar
        header("Status: 301 Moved Permanently");
        header("Location:" . URL_BASE . 'login.php');
        exit();
    }
}

//verificar login
function verificar() {
    if (isset($_SESSION["login"]) == true) {
        //redireccionar
        header("Status: 301 Moved Permanently");
        header("Location:" . URL_BASE);
        exit();
    }
}


//verificar si es asministrador
function verificar_administrador() {
    if ($_SESSION["usuario_rol"] != "Administrador") {
        //redireccionar
        header("Status: 301 Moved Permanently");
        header("Location:" . URL_BASE . 'error_404.php');
        exit();
    }
}

