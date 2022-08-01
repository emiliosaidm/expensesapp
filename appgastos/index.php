<?php
error_reporting(E_ALL);
ini_set('ignore_repeated_errors',TRUE);
ini_set('display_errors',FALSE);
ini_set('log_errors',TRUE);
ini_set('error_log',"C:\Program Files\Ampps\www\appgastos\pshp-error.log");
error_log('Programa corriendo');
require_once 'classes/errormessages.php';
require_once 'controllers/errores.php';
require_once 'libs/controller.php';
require_once 'libs/model.php';
require_once 'libs/app.php';
require_once 'libs/view.php';
require_once 'libs/database.php';
require_once 'config/config.php';
require_once 'classes/successmessages.php';
require_once 'classes/sessioncontroller.php';
$app=new App();


?>