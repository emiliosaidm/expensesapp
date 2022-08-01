<?php
require_once 'libs/controller.php';
require_once 'models/usermodel.php';
class Signup extends SessionController{
    function __construct(){
        parent::__construct();
    }
    function render(){
        $this->view->render('login/signup',[]);
    }

    function newUser(){
        if($this->existPOST(['username','password','name'])){
            error_log('EL username es:'.$username);
            $username=$this->getPost('username');
            $password=$this->getPost('password');
            $name=$this->getPost('name');

            if($username=='' || empty($username) || $password=='' || empty($password)){ //Si esta vacio los imputs de texto, entonces emite un error.
                return $this->redirect('signup',['error'=>ErrorMessages::ERROR_SIGNUP_NEWUSER_EMPTY]);
            }
        //Si es que si determino un usuario y una contraseña, entonces crea un nuevo objeto  de la clase userModel.
        $user= new UserModel();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRole('user');
        $user->setName($name);

         if($user->exists($username)){// Valida si existe en la base de datos ese nombre de usuario.
            $this->redirect('signup',['error'=>ErrorMessages::ERROR_SIGNUP_NEWUSER_EXISTS]);

        }else if($user->save()){
            $this->redirect('',['success'=>SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
        }else{
            $this->redirect('signup',['error'=>ErrorMessages::ERROR_SIGNUP_NEWUSER_EMPTY]);
        }  
        }else{
            $this->redirect('signup',['error'=>ErrorMessages::ERROR_SIGNUP_NEWUSER]);
        }
    }
}

?>