<?php
class View{

    function __construct(){

    }

    private function handleMessages(){
        if(isset($_GET['success']) && isset($_GET['error']) ){
         //Esta condicion no puede suceder ya que no es posibpe que venga dentro de la URL un mensaje de satisfaccion y uno de error.   
        } 
        else if(isset($_GET['success'])){
            $this->handleSuccess();
        }
        else if(isset($_GET['error'])){
            $this->handleError();
        }

        }
       //La siguiente funcion ejecuta el handleErerror. Lo que hace es que le adsigna a la variable hash el valor de $_GET. Posteriormente, crea una nuevo objeto a partir de el. 
    private function handleError(){
        $hash=$_GET['error'];
        $error= new ErrorMessages();
        if($error->existsKey($hash)){//Esta funcion toma el objeto $error y ejecuta $hash en la funcion existKey (definida en las clases). Si arroja true ejecuta los atributos de if.
            $this->d['error']=$error->get($hash);// El string 'error' dentro del arreglo d es equivalente a ejecutar la funcion de get($hash) definida en las clases.
        }

    }
    private function handleSuccess(){
        $hash=$_GET['success'];
        $success= new SuccessMessages();
        if($success->existsKey($hash)){
            $this->d['success']=$success->get($hash);
        }

    }
    public function showMessages(){
        $this->showErrors();
        $this->showSuccess();
    }
    public function showErrors(){
        $validar=array_key_exists('error', $this->d);
        if(isset($validar)){
            echo '<div class="error">'.$this->d['error'].'</div>';
        }
    }

   
    public function showSuccess(){
        $validar=array_key_exists('success',$this->d);
    if(isset($validar)){
            echo '<div class="success">'.$this->d['success'].'</div>';
        }
    }

    

    function render($nombre,$data=[]){//La funcion de render va a ser un metodo que muestre informacion. Por lo tanto nombre va a ser el nombre de una pagina donde se muestre informacion.
        $this->d=$data;//La variable d toma los valores del array $data la cual va a tener todos los mensajes que deseamos que se muestren en la pantalla.

        $this->handleMessages();

        require 'views/'.$nombre.'.php';
    }
    

}
?>