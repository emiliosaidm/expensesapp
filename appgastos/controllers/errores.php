<?php
require_once 'libs/controller.php';
require_once 'libs/view.php';
class Errores extends Controller{
   function __construct(){
    parent:: __construct();
     error_log('ERRORES::construct--> inicio de la clase errores');
   }
   function render(){
    error_log('ERRORES::render--> Se esta renderizando el programa');
    $this->view->render('errores/index');
   }

}

?>