<?php

require_once "core/Conexion.php";

class Parqueadero_modelo {

    private $conexion;
    private $estado;
    private $tipo;
    private $parqueadero;
    private $placa;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function select() {
        $query = "SELECT * FROM parqueaderos";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_disponible() {
        $query = "SELECT * FROM parqueaderos WHERE estado = 'Disponible'";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_disponible_tipo($tipo) {
        $query = "SELECT * FROM parqueaderos WHERE estado = 'Disponible' and tipo = '$tipo' ";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_disponible_parqueadero($parqueadero) {
        $query = "SELECT * FROM parqueaderos WHERE estado = 'Disponible' and parqueadero = '$parqueadero' ";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    /**
     * Listar todos los parqueadero de autos
     * @return type
     */
    public function select_tipo($tipo) {
        $query = "SELECT * FROM parqueaderos WHERE tipo = '$tipo' ";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    /**
     * Listar todos los parqueadero de autos
     * @return type
     */
    public function select_automovil() {
        $query = "SELECT * FROM parqueaderos WHERE tipo = 'Automovil' ";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_bicicleta() {
        $query = "SELECT * FROM parqueaderos WHERE tipo = 'Bicicleta' ";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_moto() {
        $query = "SELECT * FROM parqueaderos WHERE tipo = 'Moto' ";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_id_vehiculo($id_vehiculo) {
        $query = "SELECT * FROM parqueaderos WHERE id_vehiculo = $id_vehiculo ";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_parqueadero($parqueadero) {
        $query = "SELECT * FROM parqueaderos WHERE parqueadero = '$parqueadero' ";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function update($datos) {
        $parqueadero = $datos['parqueadero'];
        $estado = $datos['estado'];
        $id_vehiculo = $datos['id_vehiculo'];

        $query = "UPDATE parqueaderos SET 
        estado = '$estado', 
        id_vehiculo = '$id_vehiculo' 
        WHERE  parqueadero ='$parqueadero';";

        $resultado = $this->conexion->setDatos($query);
        return $resultado;
        echo $query;
    }

}
