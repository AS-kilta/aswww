<?php

include 'framework/framework.inc';

// Read path
$path = rtrim($_GET['q'], '/');

// Show
$mainController = new MainController();
$mainController->show($path);

?>
