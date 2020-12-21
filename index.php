<?php
require_once "core/app.php";
verificar_login();

//importacioens
require_once "modelos/Parqueadero_modelo.php";
require_once 'modelos/Vehiculo_modelo.php';
require_once 'modelos/Tarifa_modelo.php';
require_once 'modelos/Servicio_modelo.php';
require_once "core/Validaciones.php";
require_once "core/Respuestas.php";


$parqueadero_modelo = new Parqueadero_modelo();
$vehiculo_modelo = new Vehiculo_modelo();
$servicio_modelo = new Servicio_modelo();
$tarifa_modelo = new Tarifa_modelo();
$validaciones = new Validaciones();
$respuesta = new Respuestas();

//variables
$placa = '';
$parqueadero = '';
$respuestas = null;
$errores = array();
$metodo = $_SERVER["REQUEST_METHOD"];

//obtener parqueaderos 
$datos['automoviles'] = cliente($parqueadero_modelo->select_automovil());
$datos['bicicletas'] = cliente($parqueadero_modelo->select_bicicleta());
$datos['motos'] = cliente($parqueadero_modelo->select_moto());

switch ($metodo) {
    case 'GET':

        //metodo por ajax
        if (isset($_GET['placa'])) {
            //capturar placa 
            $placa = $_GET['placa'];
            //limpiar
            $placa = $validaciones->limpiar($placa);
            //ver si existe la placa
            $vehiculo = $vehiculo_modelo->select_placa($placa);
            if (count($vehiculo) > 0) {
                //verificar que el vehiculo no este en servicio
                $registro = $parqueadero_modelo->select_vehiculo_id($vehiculo[0]['id']);

                if (count($registro) == 0) {
                    //buscar tipo de vehiculo
                    $tipo = $vehiculo[0]['tipo'];
                    //verificar que existen parqueaderos disponibles para ese tipo
                    $parqueaderos = $parqueadero_modelo->select_disponible_tipo($tipo);
                    if (count($parqueaderos) > 0) {
                        $respuesta->respuesta("success", null, null, null, $parqueaderos);
                    } else {
                        $respuesta->respuesta("success", null, 'No existen parqueaderos disponibles');
                    }
                } else {
                    $respuesta->respuesta("success", null, 'Vehiculo en servicio');
                }
            } else {
                $respuesta->respuesta("success", null, 'Vehiculo no registrado');
            }
        }

        if (isset($_GET['tipo'])) {
            //capturar placa 
            $tipo = $_GET['tipo'];
            //limpiar
            $tipo = $validaciones->limpiar($tipo);
            //ver si existe la tipo
            $parqueaderos = $parqueadero_modelo->select_tipo($tipo);

            if (count($parqueaderos) > 0) {
                $respuesta->respuesta("success", null, null, null, $parqueaderos);
            } else {
                $respuesta->respuesta("success", null, 'No existen parqueaderos');
            }
        }

        break;
    case 'POST':
        if (isset($_GET['nuevo_servicio'])) {
            //capturar datos
            $placa = isset($_POST['placa']) ? $_POST['placa'] : null;
            $parqueadero = isset($_POST['parqueadero']) ? $_POST['parqueadero'] : null;


            if ($validaciones->requerido($placa) == FALSE) {
                $errores[] = 'El campo placa es requerido';
            }

            if ($validaciones->requerido($parqueadero) == FALSE) {
                $errores[] = 'El campo parqueadero es requerido';
            }

            if (count($errores) > 0) {
                include_once "vistas/header.php";
                include_once "vistas/parqueadero.php";
                include_once "vistas/footer.php";
                exit();
            }

            //limpiar datos 
            $placa = $validaciones->limpiar($placa);
            $parqueadero = $validaciones->limpiar($parqueadero);

            //validar que existe la placa
            $vehiculo = $vehiculo_modelo->select_placa($placa);

            if (count($vehiculo) > 0) { //si existe la placa
                //validar que la placa no este en servicio
                $vehiculo_id = $vehiculo[0]['id'];
                $registro = $parqueadero_modelo->select_vehiculo_id($vehiculo_id);

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
                            'vehiculo_id' => $vehiculo_id,
                            'parqueadero' => $parqueadero,
                        );

                        $resultado = $servicio_modelo->insert($datos);


                        //acutualizar parqueadero
                        $resultado = $parqueadero_modelo->update(array(
                            'parqueadero' => $parqueadero,
                            'estado' => "No disponible",
                            'vehiculo_id' => $vehiculo_id,
                        ));

                        $respuestas = "Servicio registrado";
                    } else {
                        $respuestas = "Error - parqueadero no disponible";
                    }
                } else {
                    $respuestas = "Error- vehiculo en servicio";
                }
            } else {
                $respuestas = "Error- vehiculo no registrado";
            }
        }

        if (isset($_GET['terminar_servicio'])) {
            //capturar datos
            $placa = isset($_POST['placa']) ? $_POST['placa'] : null;


            if ($validaciones->requerido($placa) == FALSE) {
                $errores[] = 'El campo placa es requerido';
            }


            //limpiar datos 
            $placa = $validaciones->limpiar($placa);

            //verifica si existe la placa
            $vehiculo = $vehiculo_modelo->select_placa($placa);
            if (count($vehiculo) == 0) {
                $errores[] = "Placa no registrada";
            }
            //buscar id del vehiculo
            $vehiculo_id = $vehiculo[0]['id'];

            //verificar si el vehiculo esta en servicio 
            $registro = $parqueadero_modelo->select_vehiculo_id($vehiculo_id);
            if (count($registro) == 0) {
                $errores[] = "Vehiculo no esta en servicio";
            }

            if (count($errores) > 0) {
                include_once "vistas/header.php";
                include_once "vistas/parqueadero.php";
                include_once "vistas/footer.php";
                exit();
            }


            //buscar servicio 
            $servicio = $servicio_modelo->select_activo_vehiculo_id($vehiculo_id);

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
                'vehiculo_id' => null,
            ));

            $respuestas = "Servicio terminado";
        }
        if (isset($_GET['mover'])) {
            //capturar datos
            $parqueadero_viejo = isset($_POST['parqueadero_viejo']) ? $_POST['parqueadero_viejo'] : null;
            $parqueadero_nuevo = isset($_POST['parqueadero_nuevo']) ? $_POST['parqueadero_nuevo'] : null;


            if ($validaciones->requerido($parqueadero_viejo) == FALSE) {
                $errores[] = 'El campo parqueadero_viejo es requerido';
            }

            if ($validaciones->requerido($parqueadero_nuevo) == FALSE) {
                $errores[] = 'El campo parqueadero_nuevo es requerido';
            }

            //buscar parqueadero viejo
            $registro_viejo = $parqueadero_modelo->select_parqueadero($parqueadero_viejo);
            if (count($registro_viejo) == 0) {
                $errores[] = "Parqueadero viejo no existe";
            }

            //buscar parqueadero nuevo
            $registro_nuevo = $parqueadero_modelo->select_parqueadero($parqueadero_nuevo);
            if (count($registro_nuevo) == 0) {
                $errores[] = "Parqueadero nuevo no existe";
            }


            if ($registro_viejo[0]['tipo'] != $registro_nuevo[0]['tipo']) {
                $errores[] = "Parqueadero nuevo es diferente tipo";
            }

            if (count($errores) > 0) {
                //llamar a la vista
                include_once "vistas/header.php";
                include_once "vistas/parqueadero.php";
                include_once "vistas/footer.php";
                exit();
            }

            if ($registro_nuevo[0]['estado'] == "Disponible") { // nuevo parqeadero disponible
                //actualizar parqueadero viejo
                $resultado = $parqueadero_modelo->update(array(
                    'parqueadero' => $parqueadero_viejo,
                    'estado' => 'Disponible',
                    'vehiculo_id' => NULL,
                ));
                //actualizar parqueadero nuevo
                $resultado = $parqueadero_modelo->update(array(
                    'parqueadero' => $parqueadero_nuevo,
                    'estado' => 'No disponible',
                    'vehiculo_id' => $registro_viejo[0]['vehiculo_id'],
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
                    'vehiculo_id' => $registro_nuevo[0]['vehiculo_id'],
                ));

                $resultado = $parqueadero_modelo->update(array(
                    'parqueadero' => $parqueadero_nuevo,
                    'estado' => 'No disponible',
                    'vehiculo_id' => $registro_viejo[0]['vehiculo_id'],
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

            $respuestas = "Vehiculo movido";
        }
        break;
}

//consultar parqueaderos
$datos['automoviles'] = cliente($parqueadero_modelo->select_automovil());
$datos['bicicletas'] = cliente($parqueadero_modelo->select_bicicleta());
$datos['motos'] = cliente($parqueadero_modelo->select_moto());


//llamar a la vista
include_once "vistas/header.php";
include_once "vistas/parqueadero.php";
include_once "vistas/footer.php";
exit();


//agregar informacion del cliente al json
function cliente($array_vehiculo)
{

    for ($i = 0; $i < count($array_vehiculo); $i++) {
        //verificar si el parqueadero esta ocupado
        if ($array_vehiculo[$i]['estado'] == "No disponible") {
            //buscar id del vehiculo parqueado
            $vehiculo_id = $array_vehiculo[$i]['vehiculo_id'];
            //crear objeto vehiculo
            $vehiculo_modelo = new Vehiculo_modelo();
            //buscar informacion del dueño del vehiculo
            $registro = $vehiculo_modelo->select_cliente($vehiculo_id);
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
