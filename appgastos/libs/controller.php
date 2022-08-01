<?php
class Controller{

    function __construct(){
        $this->view= new View();// Esta funcion va  acrear una variable con la clase view y va a indicar que vista va a cargar. 
    }

    function loadModel($model){//Esta funcion va a encargar cargar el archivo deo modelo de cada controlador.
        $url='models/'.$model.'model.php';//Va a crear la variable $url la cual se va a encargar de buscar en la carpeta de model el archivo "$mode'model.php
     if(file_exists($url)){
        require_once $url;
        $modelName=$model.'Model';
        $this->model= new $modelName();
     }

    }
    function existPOST($params){// NOTA: se estan validando atributos que llegan por POST.
        foreach($params as $param){
            if(!isset($_POST[$param])){
                error_log('Controller::existPost--> No existe el parametro.'.$param);// Lo que esta ejecutando este bucle es que si no existe un  parametro dentro del array $params, entonces sale de la funcion.
                return false;//Le da un valor falso a la funcion existPOST.
            }
        }
        return true; //Si todos los condicionales del bucle existen entonces se ejecuta un retur true. Es decir, le da un valor Booleano de true a la funcion existsPosts.
    }
    //Se repite la logica de existPOST, pero ahora con el metodo GET,
    function existGET($params){
        foreach($params as $param){
            if(!isset($_GET[$param])){
                error_log('Controller::existGET--> No existe el parametro.'.$param);
                return false;
            }
        }return true;
    }
// Las siguientes variables otorgan el valor del metodo get y post, respectivamente, para poder acotar la nomenclatura, 
    function getGet($name){
        return $_GET[$name];
    }

    function getPost($name){
        return $_POST[$name];
    }

    //La siguiente funcion permite hacer redirecionamientos. Ademas, la url va a incluir un mensaje para determinar si fue o no exitosa la operacion.
    public function redirect($route,$mensajes){
    $data=[];
    $params='';
    foreach($mensajes as $key=>$mensaje){// Lo que realiza este bucle es insertar la informacion de mensajes dentro del array de data. Ademas, concadena un key a cada mensaje.
        array_push($data,$key. '='.$mensaje);
    }
    $params=join('&',$data);// Lo que hace esta funcion es unir los elementos del array de data. Los elementos los va a unir con el caracter "&".

    if($params !=''){
        $params='?'.$params;// Lo que hace esta parte de la funcion es agregar un signo de interrogacion al inicio de la cadena de texto params. Ej: /?nombre=Emilio&apellido=Said.
    }

    header('Location:'.constant('URL'). $route.$params); //Esta funcion de header perite redirigir a los usuarios a una url especifica. Determinada por $route y $params.
    exit();
}
}
?>