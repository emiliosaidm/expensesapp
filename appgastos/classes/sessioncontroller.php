<?php
/*Esta clase va a aller un documento JSON que va a permitir manejar permisos para otorgar permisos para los usuarios. */
require_once 'classes/session.php';
require_once 'models/usermodel.php';
class SessionController extends Controller{
    private $userSession;
    private $username;
    private $userid;
    private $session;
    private $user;

    function __construct(){
       parent::__construct();
       $this->init();
    }


    function init(){
       $this->session=new Session(); // La variable session ahora es un objeto de la clase session. 
       $json= $this->getJSONFileConfig(); //La variable $json va a poseer los datos que se estraigan al ejecutar la funcion getJSONFileConfig().
       $this->sites=$json['sites']; //La variable sites, en este caso, va a tomar todos los datos definidos en el JSON, los cuales son los roles que se establecen en cada pagina.
       $this->defaultSites=$json['default-sites'];// La variable defaultSites va a tomar los valores de JSON que pertenecen a la categoria default-sites.
       $this->validateSession();//Esta funcion va a permitir validar si existe sesion, si la pagina a la que se solicito acesso es privada o publica, etc. 

    }
    
//La siguiente funcion decofdifica os datos del archivo access.json y los inserta como array dentro de la variable $json.
    private function getJSONFileConfig(){
        $string=file_get_contents('config/access.json');
        $json=json_decode($string,true);

        return $json;

    }
//La siguiente funcion valida si hay o no sesion.
    public function validateSession(){
        error_log('SESSIONCONTROLLER::validateSession');
        if($this->existsSession()){
            $role= $this->getUserSessionData()->getRole();// getUserSessionData arroja toda la informacion del usuario. Posteriormente, getRole otorga el rol del usuario como definido en UserModel.
        //La siguiente funcion va a determinar si la pagina a entrar es publica.
        if($this->isPublic()){//Si existe sesion y es publica, entonces:
          $this->redirectDefaultSiteByRole($role);
        }
         else{
            if($this->isAuthorized($role)){
                //Permite el paso a la pagina no publica si es que se cumple con los requisitos. 
            }
            else{
                $this->redirectDefaultSiteByRole($role);//Si es que no se puede dejar pasar a un usuario dentro, entonces se redirecciona a la pagina por defecto del usuario.
            }
            }
        }else{//No existe la sesion
           if($this->isPublic()){
            //Deja al usuario pasar
           }else{
            error_log('ACA');
            header('Location:'.constant('URL').'');//Si no es publica redirecciona al usuario a la pagina principal del sitio. 

           }
        }
    }
    //La funcion existsSession permite validar si hay o no sesion. 
    function existsSession(){
        if(!$this->session->exists()){//NOTA: El objeto $this->session es de la clase Session. Por lo tanto, puede ejecutar la funcion de exists(), la cual valida si hay o no una sesion.
           return false; //Si no existe una sesion, entonces regresa false.
        }
        if($this->session/*EL cual es un objeto de la clase Session */->getCurrentUser()==NULL){// Este if/else determina si hay una sesion abierta pero no tiene informacion.
            return false;

        }
        //Si no se activan las condicionales ^^^^, entonces se activa:
        $userid=$this->session->getCurrentUser();
        //El siguiente codigo valida una vez mas la existencia de sesion:
        if($userid) {return true;}
        else {return false;}
    }

    //La siguiente funcion va a determinar los datos de la sesion abierta

    public function getUserSessionData(){
        $id= $this->session->getCurrentUser();
        $this->user=new UserModel();//Ahora, la variable user de esta clase es un objeto del UserModel.
        $this->user->get($id);//Esta funciom va a devolver al usuario con toda su indormacion.
        return $this-> user;
    }
//La siguiente funcion va a determinar si la pagina es publica o no.
   function isPublic(){
    $currentURL=$this->getCurrentPage();
    $currentURL=preg_replace(" /\?.*/ ",'',$currentURL);// Esta funcion va a remplazar todos los objetos como signos de interrogacion, diagonales, etc por un string vacio.
    for($i=0;$i<sizeof($this->sites);$i++){//Este ciclo for se va a ejecutar cuantas site hayan en la pagina de sites. Si hay 8 paginas, se va a ejecutar 8 veces.
    if($currentURL==$this->sites[$i]['site'] && $this->sites[$i]['access']=='public'){
        return true;//La condicion if solicita 2 condiciones para que pueda arrojar un true: que la pagina obtenida por medio de getCurrentPage sea igual a una pagina definida dentro del archivo JSON (por ejemplo json) y,ademas, que la pagina tenga el atributo de public.
    }

   }} 

   function getCurrentPage(){
    $actualLink=trim("$_SERVER[REQUEST_URI]");//NOTA: $_SERVER es un array el cual tiene informacion del servidor. EL argumento 'REQUEST_URI' otorga todo el URL depues del dominio del servidor. La funcion trim quita los espacios al final y al inicio del URL capturado.
    $url=explode('/',$actualLink);
    return $url[2];//En este caso, estamos regresando de la funcion el tercer elemento de la url Ej.--> elemento1/elemento2/elemento3. Este idicara que pagina esta activa.
   }
private function redirectDefaultSiteByRole($role){
    $url='';
    for($i=0;$i<sizeof($this->sites);$i++){
        if($this->sites[$i]['role']==$role){//En este caso estamos cargando el archivo JSON u vamos hacer un redireccionamiento al usuario. Si el rol del usuario es admin lo llevara a la consola del administrador.
        break;
        $url=" /appgastos/".$this->sites[$i]['site'];
        error_log('ESTE'.$this->sites[$i]['site']);
        }
    }
    header('Location:'.$url);//EL usuario va a ser redireccionado a la url establecida por el rol del usuario.
}
private function isAuthorized($role){
    $currentURL=$this->getCurrentPage();
    $currentURL=preg_replace(" /\?.*/ ",'',$currentURL);
    for($i=0;$i<sizeof($this->sites);$i++){
        if($currentURL==$this->sites[$i]['site'] && $this->sites[$i]['role']==$role);{
            return true;
        }
    }
}
function initialize($user){
    $this->session->setCurrentUser($user->getId());
    $this->autorizeAccess($user->getRole());
}

//La siguiente funcion va a establecer que va a suceder si un usuario hace log in y los autorizara.
function autorizeAccess($role){
   switch($role){
      case 'user':
        $this->redirect($this->defaultSites['user'],[]);
        error_log('SESSIONCONTROLLER::Autoorize');
        break;
     case 'admin':
            $this->redirect($this->defaultSites['admin'],[]);
            break;
   }
}
function logout(){
    $this->session->closeSession();
}


}
?>