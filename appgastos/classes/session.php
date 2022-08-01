<?php
  class Session{
    private $sessionName='user';

    function __construct(){
      if(session_status()==PHP_SESSION_NONE){// Esta funcion va a determinar si es que existe o no una sesion abierta. 
        session_start();//Si es que no esta activa, el codigo se va a ejecutar.
      }
    }
   //La siguiente funcion va a establecer un usuario con la sesion correspondiente. 

   public function setCurrentUser($user){
    $_SESSION[$this->sessionName]=$user; //La constante $_SESSION es un array que guarda informacion de las peticiones de un usuario mientras su sesion este abiertta.

   }

   //La siguiente funcion permite llamar al usuario con todos sus datos en el array.
   public function getCurrentUser(){
    return $_SESSION[$this->sessionName];
   }
   
   public function closeSession(){
   //La siguiente funcion se va a llamar para destruir una sesion.
   return session_unset(); // Esta funcion purga el array de $_SESSION. SIn embargo, las variables del usuario siguen existentes.
   session_destroy();//Esta funcion destruye toda la informacion relacionada con una sesion activa. 
   }
   
   public function exists(){
    
    return isset($_SESSION[$this->sessionName]);
   }


  }

?>