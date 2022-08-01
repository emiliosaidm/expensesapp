<?php

 class SuccessMessages{

    const SUCCESS_ADMIN_NEWCATEGORY_EXISTS= 'a68f7a0404ec2a62f7d7fe2b8fd9927a';
    const SUCCESS_SIGNUP_NEWUSER= 'a7a0u9s2e404ec2a62f7d7fe2b8fd9927a';
    
    private $successList=[];
    public function __construct(){
    $this->successList=[
        SuccessMessages::SUCCESS_ADMIN_NEWCATEGORY_EXISTS=>'La clase fue creada exitosamente',
        SuccessMessages::SUCCESS_SIGNUP_NEWUSER=>'Se ha creado exitosamente un usuario.'];
    }
    public function get($hash){
        return $this->successList[$hash];
    }

    public function existsKey($key){
        if(array_key_exists($key,$this->successList)){
            return true;
        }
        else{
            return false;
        }
    }
 }

?>