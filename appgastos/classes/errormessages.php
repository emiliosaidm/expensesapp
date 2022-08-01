<?php
// En las siguientes lineas de codigo se va a ejecutar si se realizo exitosamente una operacion. Las clases van a ser llamadas cuando se requieran. 
 class ErrorMessages{
    //ERROR_CONTROLLER_METODO_ACTION
    const ERROR_ADMIN_NEWCATEGORY_EXISTS= 'eb8189f43ee1da36c211f05effbc0b8f'; //En este caso se estblece una constante con un string en un cifrado MD5 para que no aparezca en la URL. 
    const ERROR_SIGNUP_NEWUSER='eb8189f43ee1da36cf2113idaf05effbc0';
    const ERROR_SIGNUP_NEWUSER_EMPTY='eb8189tyf41da36cf2113idaf05effbc0';
    const ERROR_SIGNUP_NEWUSER_EXISTS='eb8189tyf41da3exi2113idaf05aeffbc0';
    const ERROR_LOGIN_EMPTY='eb8189tyf41da3log21eme2idaf05aeffbc0';
    const ERROR_LOGIN_AUTHENTICATE='eb8189tyf41da3log21eme2idaf8hj05bc0';
    const ERROR_LOGIN_AUTHENTICATE_DATA='eb8189tyf41dt3xlog21eme2itanam5bc0';
    
    private $errorList=[];
// La siguiente funcion hace un transcript del valor de la constante y la define como un string legible. Sin embargo, la informacion en el url se manda en md5. Existen metodologias para poder hacer este proceso automaticamente.
    public function __construct(){
        $this->errorList=[
            ErrorMessages::ERROR_ADMIN_NEWCATEGORY_EXISTS=>'El nombre de la categoría ya existe, intenta otra.',
            ErrorMessages::ERROR_SIGNUP_NEWUSER=>'Hubo un error al validar la información. Por favor, intenta de nuevo.',
            ErrorMessages::ERROR_SIGNUP_NEWUSER_EMPTY=>'Por favor, llena los campos para poder registrarte.',
            ErrorMessages::ERROR_SIGNUP_NEWUSER_EXISTS=>'Uy... Ese nombre de usuario ya existe, intenta con otro ;).',
            ErrorMessages::ERROR_LOGIN_EMPTY=>'El nombre de usuario y contraseña son campos obligatorios para poder iniciar sesión. Por favor, llenalos.',
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE=>'No se puede procesar la solicitud. Intenta de nuevo.',
            ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA=>'El nombre de usuario o contraseña son invalidos. Por favor, intenta de nuevo.'];
    }
 //Dado que la clave ficticia hash es equivalente a la constante ERROR_ADMIN_NEWCATEGORY_EXISTS, cuando se ejecute la siguiente funcion el return va a ser el string de texto. 
    public function get($hash){
        return $this->errorList[$hash];
    }

//Las siguiente funcion va a tomar la clave $key y va a validar si es que existe o no dentro del array errorList.
    public function existsKey($key){
        if(array_key_exists($key,$this->errorList)){
            return true;
        }
        else{
            return false;
        }
    }
 }

?>