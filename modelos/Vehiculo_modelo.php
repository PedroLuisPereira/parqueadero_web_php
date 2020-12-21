<?php

require_once "core/Conexion.php";

class Vehiculo_modelo {

    private $conexion;
    private $tipo;
    private $placa;
    private $cliente_id;

    public function __construct() {
        $this->conexion = new Conexion();
    }
    public function select() {
        $query = "SELECT vehiculos.id, vehiculos.placa, vehiculos.tipo, clientes.nombre, clientes.apellidos
                  FROM vehiculos
                  INNER JOIN clientes
                  ON clientes.id = vehiculos.cliente_id
                  ORDER BY clientes.nombre ASC";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_pagina($pagina = 1) {
        $inicio = 0;
        $cantidad = 50;
        if ($pagina > 1) {
            $inicio = $cantidad * ($pagina - 1);
        }
        $query = "SELECT vehiculos.id, vehiculos.placa, vehiculos.tipo, clientes.nombre, clientes.apellidos
                  FROM vehiculos
                  INNER JOIN clientes
                  ON clientes.id = vehiculos.cliente_id
                  ORDER BY clientes.nombre ASC 
                  LIMIT $inicio, $cantidad;";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_id($id) {
        $query = "SELECT * FROM vehiculos WHERE id = $id";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_cliente_id($cliente_id) {
        $query = "SELECT * FROM vehiculos WHERE cliente_id = $cliente_id";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_placa($placa) {
        $query = "SELECT * FROM vehiculos WHERE placa = '$placa'";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_buscar($buscar) {
        $query = "SELECT vehiculos.id, vehiculos.placa, vehiculos.tipo, clientes.nombre, clientes.apellidos
                  FROM vehiculos
                  INNER JOIN clientes
                  ON clientes.id = vehiculos.cliente_id
                  WHERE vehiculos.tipo like '%$buscar%' or vehiculos.placa like '%$buscar%'
                  ORDER BY clientes.nombre ASC;";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
        
        
    }

    public function select_cliente($vehiculo_id) {
        $query = "SELECT clientes.id, clientes.numero_documento, clientes.nombre, clientes.apellidos, vehiculos.placa 
                FROM vehiculos
                INNER JOIN clientes
                ON clientes.id = vehiculos.cliente_id
                WHERE vehiculos.id = $vehiculo_id;";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    /**
     * Crea un nuevo vehiculo, retorna filas afectadas
     * @param type $cliente_id
     * @param type $datos
     * @return number
     */
    public function insert($cliente_id, $datos) {
        $tipo = $datos['tipo'];
        $placa = $datos['placa'];

        $query = "INSERT INTO vehiculos (tipo, placa, cliente_id)
         VALUES ('$tipo', '$placa' , '$cliente_id');";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    public function update($id, $datos) {
        $tipo = $datos['tipo'];
        $placa = $datos['placa'];

        $query = "UPDATE vehiculos SET 
        tipo ='$tipo', 
        placa = '$placa' 
        WHERE  id=$id;";
        
        
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    public function delete($id) {
        $query = "DELETE FROM vehiculos WHERE  id=$id;";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

}
