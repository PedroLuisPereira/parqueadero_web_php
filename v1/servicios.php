<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

require_once 'modelos/Servicio_modelo.php';
require_once 'modelos/Vehiculo_modelo.php';
require_once 'modelos/Parqueadero_modelo.php';
require_once 'modelos/Tarifa_modelo.php';
require_once "core/Validaciones.php";
require_once "core/Token.php";
require_once "core/Respuestas.php";
date_default_timezone_set('America/Bogota');

$servicio_modelo = new Servicio_modelo();
$vehiculo_modelo = new Vehiculo_modelo();
$parqueadero_modelo = new Parqueadero_modelo();
$tarifa_modelo = new Tarifa_modelo();
$validaciones = new Validaciones();
$obj_token = new Token();
$respuesta = new Respuestas();

//obtener cabeceras
$header = getallheaders();
//verificar si existe autorizacio 
if (isset($header['Authorization']) == FALSE) {
    $respuesta->respuesta("error", null, "Autenticar", null);
}
//extrar el token 
$token = sscanf($header['Authorization'], 'Bearer %s')[0];
//validar token 
$obj_datos = $obj_token->validar_token($token);

//si se valida 
if ($obj_datos == null) {
    $respuesta->respuesta("error", null, "Autenticar", null);
}

//pasar a array
$array_datos = (array) $obj_datos;
//datos del usuario 
$id = $array_datos['data']->id;
$nombre = $array_datos['data']->nombre;
$rol = $array_datos['data']->rol;

//verificar metodo

$metodo = $_SERVER["REQUEST_METHOD"];

switch ($metodo) {
    case 'GET':

        if (isset($_GET['pagina'])) {
            $pagina = $_GET['pagina'];
            $datos = $servicio_modelo->select_pagina($pagina);
            $respuesta->respuesta("success", $token, null, NULL, $datos);
        }

        $datos = $servicio_modelo->select();
        $respuesta->respuesta("success", $token, null, NULL, $datos);

        break;
    case 'POST':
        /*         * **************nuevo servicio********* */
        //capturar json
        $json = file_get_contents("php://input");
        //pasar a array
        $obj_datos = json_decode($json);
        //validar solicitud bien formada
        if ($obj_datos == NULL) {
            $respuesta->respuesta("error", null, "Solicitud errada");
        }
        //capturar datos
        $placa = isset($obj_datos->placa) ? $obj_datos->placa : null;
        $parqueadero = isset($obj_datos->parqueadero) ? $obj_datos->parqueadero : null;

        //validaciones-----------------------------
        $errores = array();

        if ($validaciones->requerido($placa) == FALSE) {
            $errores[] = 'El campo placa es requerido';
        }

        if ($validaciones->requerido($parqueadero) == FALSE) {
            $errores[] = 'El campo parqueadero es requerido';
        }

        if (count($errores) > 0) {
            $respuesta->respuesta("error", null, "Faltan datos", $errores);
        }

        //limpiar datos 
        $placa = $validaciones->limpiar($placa);
        $parqueadero = $validaciones->limpiar($parqueadero);

        //validar que existe la placa
        $vehiculo = $vehiculo_modelo->select_placa($placa);

        if (count($vehiculo) > 0) {//si existe la placa
            //validar que la placa no este en servicio
            $id_vehiculo = $vehiculo[0]['id'];
            $registro = $parqueadero_modelo->select_id_vehiculo($id_vehiculo);

            if (count($registro) == 0) {
                //validar que el parqueadero este disponible
                $registro = $parqueadero_modelo->select_disponible_parqueadero($parqueadero);

                if (count($registro) > 0) {
                    //hora de entrada
                    $hora_entrada = date("Y-m-d H:i:s");

                    //buscar tipo de vehiculo
                    $tipo = $vehiculo[0]['tipo'];

                    //buscar valor minuto
                    $tarifas = $tarifa_modelo->select();

                    if ($tipo == 'Automovil') {
                        $valor_minuto = $tarifas[0]['minuto_autos'];
                    } else if ($tipo == 'Moto') {
                        $valor_minuto = $tarifas[0]['minuto_motos'];
                    } else {
                        $valor_minuto = $tarifas[0]['minuto_bicicletas'];
                    }

                    //transaccion-------------------------
                    //ingresar nuevo servicio 
                    $datos = array(
                        'hora_entrada' => $hora_entrada,
                        'valor_minuto' => $valor_minuto,
                        'estado' => "Activo",
                        'id_vehiculo' => $id_vehiculo,
                        'parqueadero' => $parqueadero,
                    );

                    $resultado = $servicio_modelo->insert($datos);


                    //acutualizar parqueadero
                    $resultado = $parqueadero_modelo->update(array(
                        'parqueadero' => $parqueadero,
                        'estado' => "No disponible",
                        'id_vehiculo' => $id_vehiculo,
                    ));

                    $respuesta->respuesta("success", $token, "Servicio registrado", null);
                } else {
                    $respuesta->respuesta("error", $token, "Error - parqueadero no disponible", null);
                }
            } else {
                $respuesta->respuesta("error", $token, "Error- vehiculo en servicio", null);
            }
        } else {
            $respuesta->respuesta("error", $token, "Error- vehiculo no registrado", null);
        }

        break;
    case 'PUT':
         //capturar json
        $json = file_get_contents("php://input");
        //pasar a array
        $obj_datos = json_decode($json);
        //validar solicitud bien formada
        if ($obj_datos == NULL) {
            $respuesta->respuesta("error", null, "Solicitud errada");
        }
        
        //capturar datos
        $parqueadero_viejo = isset($obj_datos->parqueadero_viejo) ? $obj_datos->parqueadero_viejo : null;
        $parqueadero_nuevo = isset($obj_datos->parqueadero_nuevo) ? $obj_datos->parqueadero_nuevo : null;

        //validaciones-----------------------------
        $errores = array();

        if ($validaciones->requerido($parqueadero_viejo) == FALSE) {
            $errores[] = 'El campo parqueadero_viejo es requerido';
        }

        if ($validaciones->requerido($parqueadero_nuevo) == FALSE) {
            $errores[] = 'El campo parqueadero_nuevo es requerido';
        }

        if (count($errores) > 0) {
            $respuesta->respuesta("error", null, "Faltan datos", $errores);
        }


        //buscar parqueadero viejo
        $registro_viejo = $parqueadero_modelo->select_parqueadero($parqueadero_viejo);
        if (count($registro_viejo) == 0) {
            $respuesta->respuesta("error", null, "Parqueadero viejo no existe", $errores);
        }
        
        //buscar parqueadero nuevo
        $registro_nuevo = $parqueadero_modelo->select_parqueadero($parqueadero_nuevo);
        if (count($registro_nuevo) == 0) {
            $respuesta->respuesta("error", null, "Parqueadero nuevo no existe", $errores);
        }
        
        if($registro_viejo[0]['estado'] != "No disponible" ){
            $respuesta->respuesta("error", null, "Parqueadero viejo no está ocupado", $errores);
        }
        
        if($registro_viejo[0]['tipo'] != $registro_nuevo[0]['tipo']){
            $respuesta->respuesta("error", null, "Parqueadero nuevo es diferente tipo", $errores);
        }

        if ($registro_nuevo[0]['estado'] == "Disponible") { // nuevo parqeadero disponible
            //actualizar parqueadero viejo
            $resultado = $parqueadero_modelo->update(array(
                'parqueadero' => $parqueadero_viejo,
                'estado' => 'Disponible',
                'id_vehiculo' => NULL,
            ));
            //actualizar parqueadero nuevo
            $resultado = $parqueadero_modelo->update(array(
                'parqueadero' => $parqueadero_nuevo,
                'estado' => 'No disponible',
                'id_vehiculo' => $registro_viejo[0]['id_vehiculo'],
            ));

            //actualizar servicio
            $servicio = $servicio_modelo->select_activo_parqueadero($parqueadero_viejo);
            $id = $servicio[0]['id'];
            //actualizar servicio 
            $resultado = $servicio_modelo->update_mover(array(
                'parqueadero' => $parqueadero_nuevo,
                'id' => $id,
            ));
        } else { // si el nuevo parqueadero esta ocupado
            $resultado = $parqueadero_modelo->update(array(
                'parqueadero' => $parqueadero_viejo,
                'estado' => 'No disponible',
                'id_vehiculo' => $registro_nuevo[0]['id_vehiculo'],
            ));

            $resultado = $parqueadero_modelo->update(array(
                'parqueadero' => $parqueadero_nuevo,
                'estado' => 'No disponible',
                'id_vehiculo' => $registro_viejo[0]['id_vehiculo'],
            ));

            $servicio_a = $servicio_modelo->select_activo_parqueadero($parqueadero_viejo);
            $id_a = $servicio_a[0]['id'];

            $servicio_b = $servicio_modelo->select_activo_parqueadero($parqueadero_nuevo);
            $id_b = $servicio_b[0]['id'];


            //actualizar servicio 
            $resultado = $servicio_modelo->update_mover(array(
                'parqueadero' => $parqueadero_nuevo,
                'id' => $id_a,
            ));

            //actualizar servicio 
            $resultado = $servicio_modelo->update_mover(array(
                'parqueadero' => $parqueadero_viejo,
                'id' => $id_b,
            ));
        }


        $respuesta->respuesta("success", $token, "Vehiculo movido", null);

        break;
    case 'DELETE':
        //terminar servicio---------------------------------------
    
        //capturar datos
        $placa = isset($_GET['placa']) ? $_GET['placa'] : null;

        //validaciones-----------------------------
        $errores = array();

        if ($validaciones->requerido($placa) == FALSE) {
            $errores[] = 'El campo placa es requerido';
        }

        if (count($errores) > 0) {
            $respuesta->respuesta("error", null, "Faltan datos", $errores);
        }

        //limpiar datos 
        $placa = $validaciones->limpiar($placa);


        //verifica si existe la placa
        $vehiculo = $vehiculo_modelo->select_placa($placa);
        if (count($vehiculo) == 0) {
            $respuesta->respuesta("error", null, "Placa no registrada", $errores);
        }
        //buscar id del vehiculo
        $id_vehiculo = $vehiculo[0]['id'];
        
        //verificar si el vehiculo esta en servicio 
        $registro = $parqueadero_modelo->select_id_vehiculo($id_vehiculo);
        if (count($registro) == 0) {
            $respuesta->respuesta("error", null, "Vehiculo no esta en servicio", $errores);
        }
        
        

        //buscar servicio 
        $servicio = $servicio_modelo->select_activo_id_vehiculo($id_vehiculo);

        //hora_entrada
        $hora_entrada = $servicio[0]['hora_entrada'];
        $entrada = strtotime($hora_entrada);

        //hora de salida 
        $hora_salida = date("Y-m-d H:i:s");
        $salida = strtotime($hora_salida);

        //minutos        
        $minutos = round(($salida - $entrada) / 60);

        //tarifa
        $tarifas = $tarifa_modelo->select();

        //valor del minuto 
        if ($vehiculo[0]['tipo'] == 'Automovil') {
            $valor_minuto = $tarifas[0]['minuto_autos'];
        } else if ($vehiculo[0]['tipo'] == 'Moto') {
            $valor_minuto = $tarifas[0]['minuto_motos'];
        } else {
            $valor_minuto = $tarifas[0]['minuto_bicicletas'];
        }

        //descuento
        $minutos_descuento = $tarifas[0]['minutos'];
        if ($minutos >= $minutos_descuento) {
            $descuento = $tarifas[0]['descuento'];
            $valor_minuto = $valor_minuto * (1 - $descuento / 100);
        }

        //valor total
        $total = $minutos * $valor_minuto;

        //transacciones----------------------------
        //terminar servicio
        $resultado = $servicio_modelo->update(array(
            'hora_salida' => $hora_salida,
            'minutos' => $minutos,
            'total' => $total,
            'valor_minuto' => $valor_minuto,
            'estado' => "Terminado",
            'id' => $servicio[0]['id']
        ));


        //acutualizar parqueadero
        $resultado = $parqueadero_modelo->update(array(
            'parqueadero' => $servicio[0]['parqueadero'],
            'estado' => "Disponible",
            'id_vehiculo' => null,
        ));

        $respuesta->respuesta("success", $token, "Servicio terminado", null);
        break;
    default:
        $respuesta->respuesta("error", null, "Método no permitido");
        break;
}
   
