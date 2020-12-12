<!DOCTYPE html>
<html>

    <head>
        <title>Parqueadero</title>
        <meta charset="UTF-8">
        <link rel="shortcut icon" type="image/png" href="public/img/logo.jpg" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="public/css/estilos.css">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-highway.css">
        <style>
            body,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-family: "Raleway", Arial, Helvetica, sans-serif
            }
        </style>
    </head>

    <body>

        <!-- Navegacion -->
        <div class="w3-bar w3-light-grey w3-large menu">
            <a href="<?php echo URL_BASE ?>" class="w3-bar-item w3-button w3-dark-grey w3-mobile">Parqueadero</a>
            <a href="<?php echo URL_BASE . 'clientes.php' ?>" class="w3-bar-item w3-button w3-mobile">Clientes</a>
            <a href="<?php echo URL_BASE . 'vehiculos.php' ?>" class="w3-bar-item w3-button w3-mobile">Vehículos</a>
            <a href="<?php echo URL_BASE . 'servicios.php' ?>" class="w3-bar-item w3-button w3-mobile">Servicios</a>
            <?php if ($_SESSION["usuario_rol"] == "Administrador") : ?>
                <a href="<?php echo URL_BASE . 'tarifas.php' ?>" class="w3-bar-item w3-button w3-mobile">Tarifas</a>
                <a href="<?php echo URL_BASE . 'usuarios.php' ?>" class="w3-bar-item w3-button w3-mobile">Usuarios</a>
            <?php endif; ?>
            <a href="<?php echo URL_BASE . 'usuarios_cuenta.php' ?>" class="w3-bar-item w3-button w3-right w3-mobile">Cuenta</a>
        </div>

        <div class="contenido">