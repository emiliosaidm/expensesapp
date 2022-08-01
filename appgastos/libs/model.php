<?php
require_once 'libs/database.php';
class Model{
    function __construct(){
        $this->db= new Database();
    }
    function query($query){
    return $this->db->connect()->query($query);// Lo que hace esta funcion es que ejecuta las funcionalidades PDO de php. la funcion hace referencia a una variable llamada db en la clase y ejecuta la conecion a la base de datos. Posteriormente ejecuta los comandos de $query.
    }
    function prepare($query){
    return $this->db->connect()->prepare($query);
    }

    
}


?>