<?php
require_once 'libs/model.php';
require_once 'models/usermodel.php';
class LoginModel extends Model{
    function __construct(){
      parent::__construct();
    }
 function login($username,$password){
    try{
   $query=$this->prepare('SELECT * FROM users WHERE username=:username');
   $query->execute(['username'=>$username]);
   
   if($query->rowCount()==1){
    $item=$query->fetch(PDO::FETCH_ASSOC); //NOTA: la funcion fetch con la opcion ASSOC devuelce la inforcion de la columna en un array. 
   $user = new UserModel();
   $user->from($item); //Asigna todos los elementos del array a los objetos que se definieron en la clase Users.
   if(password_verify($password,$user->getPassword())){
    error_log('LoginModel::Login->success');
    return $user;
   }
   else{
    return NULL;
}
   }
}
   
catch(PDOException $e){
        error_log('LoginModel::login-->Exception'.$e);
        return NULL;
    }

   
}
}


