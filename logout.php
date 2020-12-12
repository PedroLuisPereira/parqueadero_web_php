<?php
require_once "core/app.php";

// cerrar la sesión
session_start();
session_unset();
header("Status: 301 Moved Permanently");
header("Location:" . URL_BASE . 'login.php');
