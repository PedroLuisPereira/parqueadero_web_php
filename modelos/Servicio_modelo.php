<?php

require_once "core/Conexion.php";

class Servicio_modelo
{

    public $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function select()
    {
               $query = "SELECT    servicios.id, 				servicios.hora_entrada, 
                            servicios.hora_salida, 	    servicios.minutos, 
                            servicios.valor_minuto, 	servicios.total, 
                            servicios.estado,			servicios.parqueadero,
                            vehiculos.placa
                FROM servicios
                INNER JOIN vehiculos
                ON servicios.vehiculo_id = vehiculos.id
                ORDER BY servicios.estado, servicios.id DESC";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }
    
    public function select_pagina($pagina = 1)
    {
        $inicio = 0;
        $cantidad = 50;
        if ($pagina > 1) {
            $inicio = $cantidad * ($pagina - 1);
        }
        $query = "SELECT    servicios.id, 				servicios.hora_entrada, 
                            servicios.hora_salida, 	    servicios.minutos, 
                            servicios.valor_minuto, 	servicios.total, 
                            servicios.estado,			servicios.parqueadero,
                            vehiculos.placa
                FROM servicios
                INNER JOIN vehiculos
                ON servicios.vehiculo_id = vehiculos.id
                ORDER BY servicios.estado, servicios.id DESC  
                LIMIT $inicio, $cantidad;";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_id($id)
    {
        $query = "SELECT * FROM servicios WHERE id = $id";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }
    
    public function select_vehiculo_id($vehiculo_id)
    {
        $query = "SELECT * FROM servicios WHERE vehiculo_id = $vehiculo_id";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_activo_vehiculo_id($vehiculo_id)
    {
        $query = "SELECT * FROM servicios WHERE estado = 'Activo' AND vehiculo_id = $vehiculo_id";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    /**
     * select el servicio activo y con la placa
     * @param type $placa
     * @return type
     */
    public function select_activo_parqueadero($parqueadero)
    {
        $query = "SELECT * FROM servicios WHERE parqueadero = '$parqueadero' and estado = 'Activo'";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function insert($datos)
    {
        $hora_entrada = $datos['hora_entrada'];
        $valor_minuto = $datos['valor_minuto'];
        $estado = $datos['estado'];
        $parqueadero = $datos['parqueadero'];
        $vehiculo_id = $datos['vehiculo_id'];

        $query = "INSERT INTO servicios (hora_entrada,valor_minuto, estado,parqueadero,vehiculo_id)
                VALUES ('$hora_entrada',$valor_minuto,'$estado','$parqueadero','$vehiculo_id');";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    public function update($datos)
    {
        $hora_salida = $datos['hora_salida'];
        $minutos = $datos['minutos'];
        $total = $datos['total'];
        $valor_minuto = $datos['valor_minuto'];
        $estado = 'Terminado';
        $id = $datos['id'];


        $query = "UPDATE servicios SET 
        hora_salida ='$hora_salida', 
        minutos = '$minutos', 
        valor_minuto = '$valor_minuto',
        total = '$total' ,
        estado = '$estado'
        WHERE  id = $id;";

        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    public function update_mover($datos)
    {
        $parqueadero = $datos['parqueadero'];
        $id = $datos['id'];

        $query = "UPDATE servicios SET 
        parqueadero ='$parqueadero' 
        WHERE  id = $id;";

        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }


    public function eliminar($id)
    {
        $query = "DELETE FROM servicios WHERE  id=$id;";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }
}
