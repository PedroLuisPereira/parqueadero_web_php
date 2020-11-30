<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

require_once "modelos/Cliente_modelo.php";
require_once "modelos/Parqueadero_modelo.php";
require_once 'modelos/Vehiculo_modelo.php';
require_once 'modelos/Servicio_modelo.php';
require_once "core/Token.php";
require_once "core/Respuestas.php";


$cliente_modelo = new Cliente_modelo();
$parqueadero_modelo = new Parqueadero_modelo();
$vehiculo_modelo = new Vehiculo_modelo();
$servicio_modelo = new Servicio_modelo();
$respuesta = new Respuestas();
$obj_token = new Token();

//http://localhost/parqueadero_api_php/v3/clientes.php

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

//verficar metodo
$metodo = $_SERVER["REQUEST_METHOD"];

switch ($metodo) {
    case 'GET':

        //si la placa existe devuelve un array con los parqueadero disponibles, 
        //si no devuelve un array vacio.
        if (isset($_GET['placa'])) {
            $placa = $_GET['placa'];
            $vehiculo = $vehiculo_modelo->select_placa($placa);
            //si existe la placa
            if (count($vehiculo) > 0) {
                //verificar que el vehiculo no este en servicio
                $registro = $parqueadero_modelo->select_id_vehiculo($vehiculo[0]['id']);

                if (count($registro) == 0) {
                    //buscar tipo de vehiculo
                    $tipo = $vehiculo[0]['tipo'];
                    //verificar que existen parqueaderos disponibles para ese tipo
                    $parqueaderos = $parqueadero_modelo->select_disponible_tipo($tipo);
                    if (count($parqueaderos) > 0) {
                        $respuesta->respuesta("success", $token, null, null, $parqueaderos);
                    } else {
                        $respuesta->respuesta("success", $token, 'No existen parqueaderos disponibles');
                    }
                } else {
                    $respuesta->respuesta("success", $token, 'Vehiculo en servicio');
                }
            } else {
                $respuesta->respuesta("success", $token, 'Vehiculo no registrado');
            }
        }


        if (isset($_GET['listar'])) {
            //obtener parqueaderos 
            $datos['automoviles'] = cliente($parqueadero_modelo->select_automovil());
            $datos['bicicletas'] = cliente($parqueadero_modelo->select_bicicleta());
            $datos['motos'] = cliente($parqueadero_modelo->select_moto());
        }

        $respuesta->respuesta("success", $token, null, null,$datos);

        break;
    case 'POST':


        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
    default:
        $respuesta->respuesta("error", null, "Método no permitido");
        break;
}

//agregar informacion del cliente al json
function cliente($array_vehiculo) {

    for ($i = 0; $i < count($array_vehiculo); $i++) {
        //verificar si el parqueadero esta ocupado
        if ($array_vehiculo[$i]['estado'] == "No disponible") {
            //buscar id del vehiculo parqueado
            $id_vehiculo = $array_vehiculo[$i]['id_vehiculo'];
            //crear objeto vehiculo
            $vehiculo_modelo = new Vehiculo_modelo();
            //buscar informacion del dueño del vehiculo
            $registro = $vehiculo_modelo->select_cliente($id_vehiculo);
            //colocar placa
            $array_vehiculo[$i]['placa'] = $registro[0]['placa'];
            //llenar datos del cliente
            $array_vehiculo[$i]['cliente']['nombre'] = $registro[0]['nombre'];
            $array_vehiculo[$i]['cliente']['apellidos'] = $registro[0]['apellidos'];
            $array_vehiculo[$i]['cliente']['numero_documento'] = $registro[0]['numero_documento'];
        }
    }
    //retornar array completo
    return $array_vehiculo;
}
