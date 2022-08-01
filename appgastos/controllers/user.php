<?php
require_once 'models/usermodel.php';
require_once 'classes/sessioncontroller.php';
require_once 'libs/controller.php';
class User extends SessionController{

    private $user;

    function __construct(){
        parent::__construct();
        $this->user = $this->getUserSessionData();
    }
function render(){
    $this->view->render('user/index',[
        'user'=> $this->user
    ]);
}

function updateBudget(){
    if($this->existPOST('budget')){
        $budget=$this->getPOST('budget');
        error_log('BUDGET ES:'.$budget);
    if(empty($budget)){
        $this->redirect('user',['error'=>SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
                return;
    }
    $this->user->setBudget($budget);
    if($this->user->update()){
        $this->redirect('user',['error'=>SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
        return; 
    }
    else{
        $this->redirect('user',['error'=>SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
        return; 
    }
    }
    else if(!$this->existPOST('budget')){
        $this->redirect('user',['error'=>SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
        return;
        error_log('NO HAY BUDGET');
    }
}

public function updateName(){
    if($this->existPOST('name')){
        $name=$this->getPost('name');
            if(empty($name)){
                $this->redirect('user',['error'=>SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
                return;
            }
        $this->user->setName($name);
        if($this->user->update()){
            $this->redirect('user',['success'=>SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
        } 
        else{
            $this->redirect('user',['error'=>SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
            return;
        }
        }
    else if(!$this->existPOST('name')){
        $this->redirect('user',['error'=>SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
            return;
    }
} 

function updatePassword(){
    if($this->existPOST(['current_password','new_password'])){
        $current=$this->getPost('current_password');
        $new= $this->getPost('new_password');
    if(empty($current)||empty($new)){
        $this->redirect('user',['error'=>SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
            return;
    }
    if($current===$new){
        $this->redirect('user',['error'=>'hola']); //TODO:
        return;
    }
    $id=$this->user->getId();
    $verify=$this->model->comparepasswords($current,$this->user->getId());
    if($verify){
    $this->user->setPassword($new);
    if($this->user->update()){
        $this->redirect('user',['error'=>'hola']); //TODO:
        return;
    }
    else{
        $this->redirect('user',['error'=>'hola']); //TODO:
        return;
    }
    }
else{
    $this->redirect('user',['error'=>'hola']); //TODO:
        return;
}
}
}



function updatePhoto(){
    if(!isset($_FILES['photo'])){// _FILES es un array asociativo de elementos subidos al script en curso a través del método POST.
        $this->redirect('user',['error'=>'hola']); //TODO:
        return;
    }
    $photo=$_FILES['photo'];
    $targetDir= '../gpublico/img/photos/photos';
    $extension = explode('.',$photo['name']);//NOTA: $photo es una variable que hace referencia al array superglobal _FILES. Por tanto, e; atributo ['name'] cambia los metadatos del archivo tomado. Con la funcion de explote estamos separando en un array los strings.
    $filename=$extension[sizeof($extension)-2];
    $ext=$extension[sizeof($extension)-1];
    $hash=md5(Date('Ymdgi').$filename).'.'.$ext;
    $targetFile=$targetDir.$hash;
    $uploadOk=true;
    $imageFileType= strtolower(pathinfo($targetFile,PATHINFO_EXTENSION)); //NOTA: la funcion strtolower hace misnusculas todo un string. Pathinfo otroga la informacion de un archivo. En este caso, va a dar la extension del archivo.
    $check=getimagesize($photo['tmp_name']);
    if($check!==false){
        $uploadOk=true;
    }
    else{
        $uploadOk=false;
    }
    if($uploadOk==false){
        $this->redirect('user',['error'=>'hola']); //TODO:
        return;

    }
      if(move_uploaded_file($photo['tmp_name'],$targetFile)){
        $this->user->setPhoto($hash);
        $this->user->update();
        $this->redirect('user',['success'=>'hola']); //TODO:
        return;
      }
      else{
        $this->redirect('user',['error'=>'hola']); //TODO:
        return;
      }
}
}



?>