<?php

require_once "core/Conexion.php";

class Vehiculo_modelo {

    private $conexion;
    private $tipo;
    private $placa;
    private $id_cliente;

    public function __construct() {
        $this->conexion = new Conexion();
    }
    public function select() {
        $query = "SELECT vehiculos.id, vehiculos.placa, vehiculos.tipo, clientes.nombre, clientes.apellidos
                  FROM vehiculos
                  INNER JOIN clientes
                  ON clientes.id = vehiculos.id_cliente
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
                  ON clientes.id = vehiculos.id_cliente
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

    public function select_id_cliente($id_cliente) {
        $query = "SELECT * FROM vehiculos WHERE id_cliente = $id_cliente";
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
                  ON clientes.id = vehiculos.id_cliente
                  WHERE vehiculos.tipo like '%$buscar%' or vehiculos.placa like '%$buscar%'
                  ORDER BY clientes.nombre ASC;";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
        
        
    }

    public function select_cliente($id_vehiculo) {
        $query = "SELECT clientes.id, clientes.numero_documento, clientes.nombre, clientes.apellidos, vehiculos.placa 
                FROM vehiculos
                INNER JOIN clientes
                ON clientes.id = vehiculos.id_cliente
                WHERE vehiculos.id = $id_vehiculo;";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    /**
     * Crea un nuevo vehiculo, retorna filas afectadas
     * @param type $id_cliente
     * @param type $datos
     * @return number
     */
    public function insert($id_cliente, $datos) {
        $tipo = $datos['tipo'];
        $placa = $datos['placa'];

        $query = "INSERT INTO vehiculos (tipo, placa, id_cliente)
         VALUES ('$tipo', '$placa' , '$id_cliente');";
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
