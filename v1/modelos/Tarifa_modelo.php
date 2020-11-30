<?php

require_once "core/Conexion.php";

class Tarifa_modelo {

    public $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function select() {
        $query = "SELECT * FROM tarifas;";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function insert($datos) {
        $minuto_autos = $datos['minuto_autos'];
        $minuto_bicicletas = $datos['minuto_bicicletas'];
        $minuto_motos = $datos['minuto_motos'];
        $descuento = $datos['descuento'];
        $minutos = $datos['minutos'];

        $query = "INSERT INTO tarifas (minuto_autos, minuto_bicicletas, minuto_motos,descuento,minutos)
         VALUES ('$minuto_autos', '$minuto_bicicletas' , '$minuto_motos','$descuento','$minutos');";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    public function update($id, $datos) {
        $minuto_autos = $datos['minuto_autos'];
        $minuto_bicicletas = $datos['minuto_bicicletas'];
        $minuto_motos = $datos['minuto_motos'];
        $descuento = $datos['descuento'];
        $minutos = $datos['minutos'];

        $query = "UPDATE tarifas SET 
        minuto_autos ='$minuto_autos', 
        minuto_bicicletas = '$minuto_bicicletas', 
        minuto_motos = '$minuto_motos', 
        descuento = '$descuento',
        minutos = '$minutos'
        WHERE  id = $id;";
        
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    public function delete($id) {
        $query = "DELETE FROM tarifas WHERE  id=$id;";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

}
