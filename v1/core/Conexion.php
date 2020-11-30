<?php

class Conexion
{

   private $server = 'localhost';
   private $user = "root";
   private $password = '';
   private $database = "parqueadero";

   // private $server = 'localhost';
   // private $user = "id15379431_root";
   // private $password = 'Base-parqueadero1*';
   // private $database = "id15379431_base";
   
   // private $server = 'sql312.tonohost.com';
   // private $user = "ottos_27156106";
   // private $password = 'pedroluis01';
   // private $database = "ottos_27156106_parqueadero";

   private $port;
   private $conexion;

   function __construct()
   {
      $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->database);
      if ($this->conexion->connect_errno) {
         echo "Fallo al conectar a MySQL: (" . $this->conexion->connect_errno . ") " . $this->conexion->connect_error;
      }
   }

   /**
    * Recibe un String con la consulta y devuelve un array con los datos 
    * @param type $query
    * @return Array
    */
   public function getDatos($query)
   {
      $results = $this->conexion->query($query);

      $arrayDatos = array();

      if ($results) {
         foreach ($results as $key) {
            $arrayDatos[] = $key;
         }
      }

      return $arrayDatos;
   }

   /**
    * Recibe la consulta y retorna las numero de filas afectadas
    */
   public function setDatos($query)
   {
      $results = $this->conexion->query($query);
      $registros = $this->conexion->affected_rows;
      return $registros;
   }

   /**
    * Recibe la consulta y retorna ultimo id
    */
   public function setDatosId($query)
   {
      $results = $this->conexion->query($query);
      $registros = $this->conexion->affected_rows;

      if ($registros > 0) {
         return $this->conexion->insert_id;
      } else {
         return 0;
      }
   }


   // private function getConexion()
   // {
   //    $direccion = dirname(__FILE__); //retorna la carpeta actual 
   //    $jsonData = file_get_contents($direccion . "\\" . "config");
   //    //return json_decode($jsonData, true);
   //    return $direccion . "\\" . "config";
   // }
}

$conexion = new Conexion();
