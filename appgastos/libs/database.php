<?php
 class Database{// La propiedad private hace posible que las variables/funciones que sean definidas solo puedan ser definidas en una clase. Las variables/funcio0nes con la propiedad de protected pueden ser heredadas.
    private $host;
    private $db;
    private $user;
    private $password;
    private $charset;

public function __construct(){
    $this->host=constant('HOST');
    $this->db=constant('DB');
    $this->user=constant('USER');
    $this->password=constant('PASSWORD');
    $this->charset=constant('CHARSET');
}

function connect(){
    try{
        $connection='mysql:host='.$this->host.';dbname='.$this->db.';charset='.$this->charset;
        $options=[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES=>false,
        ];
    $pdo= new PDO($connection,$this->user,$this->password,$options);
    error_log('Conexion con la base de datos exitosa');
    return $pdo;
    }
    catch(PDOException $e){
        error_log('Error de conexion:'.$e->getMessage());

    }
}

}


?>