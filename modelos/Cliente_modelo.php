<?php

require_once "core/Conexion.php";

class Cliente_modelo {

    public $conexion;
    private $numero_documento;
    private $nombre;
    private $apellidos;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function select() {
        $query = "SELECT * FROM clientes ORDER BY id DESC;";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_pagina($pagina = 1) {
        $inicio = 0;
        $cantidad = 100;
        if ($pagina > 1) {
            $inicio = $cantidad * ($pagina - 1);
        }
        $query = "SELECT * FROM clientes ORDER BY id DESC LIMIT $inicio, $cantidad;";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_id($id) {
        $query = "SELECT * FROM clientes WHERE id = $id";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }
    
    public function select_numero_documento($numero_documento) {
        $query = "SELECT * FROM clientes WHERE numero_documento = '$numero_documento'";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }
    
    public function select_buscar($buscar) {
        $query = "SELECT * FROM clientes WHERE numero_documento like '%$buscar%' or "
                . "nombre like '%$buscar%' or "
                . "apellidos like '%$buscar%' ORDER BY id DESC ";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    /**
     * insert
     *
     * @param  mixed $datos
     * @return Int 
     */
    public function insert($datos) {
        $numero_documento = $datos->numero_documento;
        $nombre = $datos->nombre;
        $apellidos = $datos->apellidos;

        $query = "INSERT INTO clientes (numero_documento, nombre, apellidos)
         VALUES ('$numero_documento', '$nombre' , '$apellidos');";
        $registros = $this->conexion->setDatosId($query);
        return $registros;
    }

    /**
     * crearId
     *
     * @param  mixed $datos
     * @return Int
     */
    public function insert_id($datos) {
        $numero_documento = $datos['numero_documento'];
        $nombre = $datos['nombre'];
        $apellidos = $datos['apellidos'];
        $query = "INSERT INTO clientes (numero_documento, nombre, apellidos)
         VALUES ('$numero_documento', '$nombre' , '$apellidos');";
        $cliente_id = $this->conexion->setDatosId($query);
        return $cliente_id;
    }

    /**
     * update,
     *
     * @param  mixed $id
     * @param  mixed $datos
     * @return void
     */
    public function update($id, $datos) {
        $numero_documento = $datos['numero_documento'];
        $nombre = $datos['nombre'];
        $apellidos = $datos['apellidos'];

        $query = "UPDATE clientes SET 
        numero_documento ='$numero_documento', 
        nombre = '$nombre', 
        apellidos = '$apellidos' 
        WHERE  `id`=$id;";

        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id) {
        $query = "DELETE FROM clientes WHERE  id=$id;";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

}
