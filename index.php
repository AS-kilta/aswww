<?php

include 'config.php';
include 'framework/framework.inc';

include 'Navi.php';

// XXX: these should be automatic
include 'modules/admin/AdminController.php';
include 'modules/page/PageController.php';
include 'modules/frontpage/FrontpageController.php';
include 'modules/ilmo/IlmoController.php';


// Parse path
$path = rtrim($_GET['q'], '/');
$parts = explode('/', $path);

// Admin (this has to work even if database is empty)
if ($parts[0] == 'admin') {
    showAdmin();
    exit();
}


$navi = Navi::getInstance();
$navi->resolve($parts);


// Load the correct module
$node = $navi->getSelectedNode();
if ($node == null) {
    show404();
} else {
    showModule($node);
}



function show404() {
    $navi = Navi::getInstance();

    // Load skin
    $skin = new Skin('aski');
    $skin->setContent('topnavi', $navi->renderTopNavi());
    $skin->setContent('left', $navi->renderNaviTree());
    $skin->setContent('content', "<h1>Page not found</h1>");
    $skin->show();

}


function showModule($node) {
    // TODO: something more automatic here
    switch ($node->getModule()) {
        case 'page':
            $module = new PageController();
            break;

        case 'frontpage':
            $module = new FrontpageController();
            break;

        case 'ilmo':
            $module = new IlmoController();
            break;

        default:
            echo "<h1>Module " . $node->getModule() . " not supported</h1>";
            return;
    }

    // Select the right action
    $action = get('action');
    $method = 'show' . $action;

    if ($action === false || !method_exists($module, $method)) {
        $method = 'showPage';
    }

    $module->$method();
}

function showAdmin() {
    $module = new AdminController();
    $module->showPage();
}

function showFrontpage() {
    $module = new FrontpageController();
    $module->showPage();
}

?>
