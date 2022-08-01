<?php
require_once 'classes/sessioncontroller.php';
require_once 'models/loginmodel.php';
require_once 'classes/session.php';
class Login extends SessionController{//El controlador de login se va a extender del padre 'Controller'

function __construct(){
    parent::__construct();//Se llama el constructor de la clase padre. Es decir, el controlador Controller.
error_log('Login::__contruct--> Se esta ejecutando la clase login');
}

 function render(){
    $this->view->render('login/index');//Se va a renderizar la vista que deseamos tener. En este caso, se llama a la variable view y se ejecuta la funcion render haciendo atributo a la vista de login/index.
 }

 function authenticate(){
    if($this->existPOST(['username','password'])){
        $username= $this->getPost('username');
        $password=$this->getPost('password');
    if($username=='' || empty($username) || $password=='' || empty($password)){ //Si esta vacio los imputs de texto, entonces emite un error.
            $this->redirect('',['error'=>ErrorMessages::ERROR_LOGIN_EMPTY]);
        }
    $user=$this->model->login($username,$password);//this->model esta llamando a un objeto que va a pertenecer al modelo login, el cual esta definido dentro de otra carpeta. 
     if($user!==NULL){
        $this->initialize($user);// Initialize esta localizaErrorMessagesdo en la clase SessionCOntroller.
     }else{
        $this->redirect('',['error'=>ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA]); 
     }
   }else{
    $this->redirect('',['error'=>::ERROR_LOGIN_AUTHENTICATE]);

   }
 }

}
?>