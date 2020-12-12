<?php

class Respuestas
{
    public function respuesta($status, $token, $mensaje, $validaciones = array(), $datos = array())
    {
        header("Content-Type: application/json");
        $respuesta = array(
            "status" => $status,
            "token" => $token,
            "mensaje" => $mensaje,
            "validaciones" => $validaciones,
            "datos" => $datos
        );
        echo json_encode($respuesta);
        exit();
    }
}
