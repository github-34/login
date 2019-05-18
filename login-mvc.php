<?php


define('DEBUG',1);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Declaration of Classes
require('model.php');
require('controller.php');
require('view.php');

$model = new Model();
$view = new View();
$controller = new Controller($view, $model);

echo $controller->loginPage();

?>