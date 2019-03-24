<?php

require('model.php');
require('controller.php');
require('view.php');

$model = new websiteModel();
$controller = new Controller($model);
$view = new View($controller, $model);
$view->outputLogin();

?>