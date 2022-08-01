<?php

class App{

    function __construct(){
       $url=isset($_GET['url']) ? $_GET['url']:null;
       $url=rtrim($url,'/');
       $url=explode('/',$url);


    if(empty($url[0])){
        error_log('App::construct-->No hay controlador');
        $archivoController='controllers/login.php';
        require_once $archivoController;
        $controller= new Login();
        $controller->loadModel('login');
        $controller->render();
        return false;
    }
    $archivoController='controllers/'.$url[0].'.php';
    if(file_exists($archivoController)){
      require_once($archivoController);
      $controller= new $url[0]; //Aqui se crea un objeto con la clase $url[0].
      $controller->loadModel($url[0]);// Dado que u=$url[0] hace referencia a un controlador, esta funcion va a ejecutar el modelo de dicho controlador.
      if(isset($url[1])){
        if(method_exists($controller,$url[1])){

           if(isset($url[2])){//Si es que, ademas del controlador y del metodo, existen parametros a ejecutar en el metodo $url[1] se va a ejecutar lo siguiente:
            $nparam=count($url)-2;// Se le resta 2 porque el primer objeto dentro de la url es el controlador y el segundo es el metodo. Por lo tanto, para determinar el numero de parameteos depues de el controlador y del metodo se establece un conteo. 
            $params=[];//Este array va a contener todos los parametros de la url depues de un controlador o metodo. 
            for($i=0;$i<$nparam;$i++){// Este bucle for se inicializa en 0 y va hasta $nparamd-1. Lo que hace es que inserta los objetos de la url denro del array. NOTA: se le suma 2 porque no se estan contando el controlador ni el metodo inicial.
                array_push($params,$url[$i+2]);
            }error_log($params.'EN APP PARAMS');
            $controller->{$url[1]}($params);// Aqui estamos ejecutando el metodo  $url[1]. Los aprametros que se van a pasar estan definidos en el array de $params 
           }
           

           else{
            $controller->{$url[1]}(); //Si no hay un segundo metodo se va a llamar al controlador con el metodo url[1]
           }
           
        }
      }
      else{// Esta logica se aplica si es que no hay mas objetos debpues de $url[1].
        $controller->render();//Esta funcion muestra la pagina del controlador identificado por $url[0] si es que no hay mas objetos dentro de la url.
      }
    }
    else{//Else de if(file_exists)
        $controller = new Errores();
        $controller->render();
    }
  }
}
?>