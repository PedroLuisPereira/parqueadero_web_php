<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

require_once "modelos/Usuario_modelo.php";
require_once "core/Token.php";
require_once "core/Respuestas.php";
require_once "core/Validaciones.php";

$usuario_modelo = new Usuario_modelo();
$respuesta = new Respuestas();
$validaciones = new Validaciones();
$obj_token = new Token();

//http://localhost/parqueadero_api_php/v1/usuarios.php

//metodo de soliciud 
$metodo = $_SERVER["REQUEST_METHOD"];
//obtener cabeceras
$header = getallheaders();
//verificar si existe autorizacio 
if (isset($header['Authorization'])) {
    //extrar el token 
    $token = sscanf($header['Authorization'], 'Bearer %s')[0];
    //validar token 
    $obj_token = $obj_token->validar_token($token);

    //si se valida 
    if ($obj_token != null) {

        //pasar a array
        $array_datos = (array) $obj_token;
        //datos del usuario 
        $id = $array_datos['data']->id;
        $nombre = $array_datos['data']->nombre;
        $correo = $array_datos['data']->correo;
        $rol = $array_datos['data']->rol;
        //operaciones
        switch ($metodo) {
            case 'GET':

                $datos = array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "correo" => $correo,
                    "rol" => $rol
                );
                $respuesta->respuesta("success", $token, null, null, $datos);

                break;
            case 'POST':
                if (!empty($_POST['name']) || !empty($_POST['email']) || !empty($_FILES['file']['name'])) {
                    $uploadedFile = '';
                    if (!empty($_FILES["file"]["type"])) {
                        $fileName = time() . '_' . $_FILES['file']['name'];
                        $valid_extensions = array("jpeg", "jpg", "png");
                        $temporary = explode(".", $_FILES["file"]["name"]);
                        $file_extension = end($temporary);
                        if ((($_FILES["hard_file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) && in_array($file_extension, $valid_extensions)) {
                            $sourcePath = $_FILES['file']['tmp_name'];
                            $targetPath = "uploads/" . $fileName;
                            if (move_uploaded_file($sourcePath, $targetPath)) {
                                $uploadedFile = $fileName;
                            }
                        }
                    }

//                    $name = $_POST['name'];
//                    $email = $_POST['email'];
//
//                    //include database configuration file
//                    include_once 'dbConfig.php';
//
//                    //insert form data in the database
//                    $insert = $db->query("INSERT form_data (name,email,file_name) VALUES ('" . $name . "','" . $email . "','" . $uploadedFile . "')");
//
//                    echo $insert ? 'ok' : 'err';
                    echo "llego";
                }

                break;
            case 'PUT':
                //actualizar password http://localhost/parqueadero_api_php/v3/cuenta.php
                //capturar json
                $json = file_get_contents("php://input");
                //pasar a objeto
                $obj_datos = json_decode($json);

                //validar solicitud bien formada
                if ($obj_datos == NULL) {
                    $respuesta->respuesta("error", null, "Solicitud errada");
                }

                //capturar contra
                $contra = isset($obj_datos->contra) ? $obj_datos->contra : null;

                //validaciones
                $errores = array();

                if ($validaciones->requerido($contra) == FALSE) {
                    $errores[] = 'El campo password es requerido';
                    $respuesta->respuesta("error", null, "Error en los datos", $errores);
                }


                //validar si existen errores
                if (count($errores) > 0) {
                    $respuesta->respuesta("error", null, "Error en los datos", $errores);
                }

                //encriptar 
                $contra = md5($obj_datos->contra);

                //actualizar usuario
                $resultado = $usuario_modelo->update_password($id, $contra);
                //comprobar resultado
                if ($resultado >= 0) {
                    $respuesta->respuesta("success", null, "Password actualizada");
                } else {
                    $respuesta->respuesta("error", null, "Error en el servidor");
                }

                break;
            case 'DELETE':



                break;
            default:
                $respuesta->respuesta("error", null, "Método no permitido");
                break;
        }
    } else {
        $respuesta->respuesta("error", null, "Autenticar", null);
    }
} else {
    $respuesta->respuesta("error", null, "Autenticar", null);
}
