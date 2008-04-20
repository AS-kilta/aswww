<?php

include 'framework/framework.inc';

// Read path
$path = rtrim($_GET['q'], '/');
$parts = explode('/', $path);

// Show
$mainController = new MainController();
$mainController->show($parts);

?>
