<?php

require_once "core/Conexion.php";

class TokenModelo
{

    public $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }


    public function listarToken($token)
    {
        $query = "SELECT * FROM tokens WHERE token = '$token' and estado = 'Activo'";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    
    public function crearToken($id_usuario)
    {
        $var = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $var));
        date_default_timezone_set('America/Bogota');
        $fecha = date("Y-m-d H:i:s");
        $estado = "Activo";
        $query = "INSERT INTO tokens (token, estado, fecha, id_usuario) 
        VALUES ('$token', '$estado', '$fecha', '$id_usuario');";
        $resultado = $this->conexion->setDatos($query);
       
        if($resultado == -1){
            return $resultado;
        }else{
            return $token;
        }
    }

    public function actualizarToken($token)
    {
        date_default_timezone_set('America/Bogota');
        $fecha = date("Y-m-d H:i:s");
        $query = "UPDATE tokens SET  fecha ='$fecha  WHERE token = '$token';";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    public function eliminarToken($id)
    {
        $query = "DELETE FROM tokens WHERE  id = $id;";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }
}
