<?php

require_once "core/Conexion.php";

class Usuario_modelo {

    public $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function select() {
        $query = "SELECT * FROM usuarios";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_id($id) {
        $query = "SELECT * FROM usuarios WHERE id = $id";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_buscar($buscar) {
        $query = "SELECT * FROM usuarios WHERE nombre like '%$buscar%' or correo like '%$buscar%' or rol like '%$buscar%' or estado like '%$buscar%' ";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_pagina($pagina = 1) {
        $inicio = 0;
        $cantidad = 50;
        if ($pagina > 1) {
            $inicio = $cantidad * ($pagina - 1);
        }
        $query = "SELECT * FROM usuarios ORDER BY nombre ASC LIMIT $inicio, $cantidad;";
        $resultado = $this->conexion->getDatos($query);
        return $resultado;
    }

    public function select_correo($correo) {
        $query = "SELECT * FROM usuarios WHERE correo = '$correo';";
        $registro = $this->conexion->getDatos($query);
        return $registro;
    }

    /**
     * insert
     *
     * @param  mixed $datos
     * @return int  numero de filas afectadas
     */
    public function insert($datos) {
        $nombre = $datos["nombre"];
        $correo = $datos["correo"];
        $contra = $datos["contra"];
        $rol = $datos["rol"];
        $estado = $datos["estado"];

        $query = "INSERT INTO usuarios (nombre, correo, contra,rol,estado)
         VALUES ('$nombre', '$correo' , '$contra','$rol','$estado');";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $datos
     * @return int numero de filas afectadas
     */
    public function update($id, $datos) {
        $nombre = $datos["nombre"];
        $correo = $datos["correo"];
        $rol = $datos["rol"];
        $estado = $datos["estado"];

        $query = "UPDATE usuarios SET 
        nombre ='$nombre', 
        correo = '$correo', 
        rol = '$rol',
        estado = '$estado'
        WHERE  `id`=$id;";

        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $datos
     * @return int numero de filas afectadas
     */
    public function update_password($id, $contra) {

        $query = "UPDATE usuarios SET 
        contra ='$contra' 
        WHERE  id=$id;";

        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

    public function delete($id) {
        $query = "DELETE FROM usuarios WHERE  id = $id;";
        $resultado = $this->conexion->setDatos($query);
        return $resultado;
    }

}
