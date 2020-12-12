<?php

class Validaciones {

    function limpiar($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = htmlentities($data, ENT_QUOTES);
        $valores = array("&", ";", "#", "/");
        $data = str_replace($valores, "", $data);
        return $data;
    }

    public function requerido($valor) {
        if (trim($valor) == '' or $valor == NULL) {
            return false;
        } else {
            return true;
        }
    }
    
    
    public function entero($valor, $opciones = null) {
        if (filter_var($valor, FILTER_VALIDATE_INT, $opciones) === FALSE) {
            return false;
        } else {
            return true;
        }
    }
    
    public function decimal($valor, $opciones = null) {
        if (filter_var($valor, FILTER_FLAG_ALLOW_FRACTION, $opciones) === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function email($valor) {
        if (filter_var($valor, FILTER_VALIDATE_EMAIL) === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function tipo_vehiculo($valor) {
        if ($valor == 'Automovil' or $valor == "Bicicleta" or $valor == 'Moto') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

//$errores = array();
////Pregunta si está llegando una petición por POST, lo que significa que el usuario envió el formulario.
//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    //Valida que el campo nombre no esté vacío.
//    if (!validaRequerido($nombre)) {
//        $errores[] = 'El campo nombre es incorrecto.';
//    }
//    //Valida la edad con un rango de 3 a 130 años.
//    $opciones_edad = array(
//        'options' => array(
//            //Definimos el rango de edad entre 3 a 130.
//            'min_range' => 3,
//            'max_range' => 130
//        )
//    );
//    if (!validarEntero($edad, $opciones_edad)) {
//        $errores[] = 'El campo edad es incorrecto.';
//    }
//    //Valida que el campo email sea correcto.
//    if (!validaEmail($email)) {
//        $errores[] = 'El campo email es incorrecto.';
//    }
//    //Verifica si ha encontrado errores y de no haber redirige a la página con el mensaje de que pasó la validación.
//    if (!$errores) {
//        header('Location: validado.php');
//        exit;
//    }
//}