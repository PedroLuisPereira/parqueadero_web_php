<?php
///use Firebase\JWT;
// require php-jwt por no usar composer
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;



class Token
{
    private $key = "*****";


    /**
     * crear_token
     *
     * @param  mixed $id
     * @param  mixed $nombre
     * @param  mixed $rol
     * @return string $token
     */
    public function crear_token($id, $nombre, $correo,$rol)
    {
        //registros 
        $jti = base64_encode(random_bytes(32));
        $iat = time();
        $exp = time() + 60 * 60 * 24;


        $datos = [
            'id' => $id,
            'nombre' => $nombre,
            'correo' => $correo,
            'rol' => $rol,
        ];

        $data = [
            'iat' => $iat, // cuando se genero el token
            'exp' => $exp, // cuando expira
            'jti' => $jti, // identificador del token
            'data' => $datos
        ];

        //$secretKey = base64_decode(SECRET_KEY);

        //crear token
        $token = JWT::encode($data, $this->key);
        return $token;
    }



    /**
     * validar_token, éxito return array lleno, fracaso return array vacio 
     *
     * @param  mixed $token
     * @return array
     */
    public function validar_token($token)
    {
        try {
            //$secretKey = base64_decode(SECRET_KEY);
            $obj_datos = JWT::decode($token, $this->key, array('HS256'));
            return $obj_datos;
        } catch (Exception $e) {
            return null;
        }
    }
}
