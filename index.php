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

if ($parts[0] == 'logout') {
    $auth = Auth::getInstance();
    $auth->logout();
    // FIXME: this causes 'page not found'
    $path = '';
    $parts = Array();
}


// Load navitree
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
    $navi = Navi::getInstance();

    // Select skin according to module
    $skin = new Skin('aski');
    $moduleName = $node->getModule();

    // TODO: this should come from the database
    switch ($moduleName) {
        case 'page':
            $page = new PageController();
            $page->setContentNode($node);
            $content = $page->render();
            $left = $navi->renderNaviTree();
            break;

        case 'frontpage':
            $front = new FrontpageController();
            //$front->setContentNode($node);  // This wouldn't really do anything
            $events = new IlmoController();
            $content = $front->render();
            $left = $events->renderEvents();
            break;

        case 'ilmo':
            $ilmo = new IlmoController($node);
            $content = $ilmo->render();
            break;

        default:
            //$content = "<h1>Module " . $node->getModule() . " not supported</h1>";
            return;
    }
    $topNavi = $navi->renderTopNavi();

    $skin->setContent('content', $content);
    $skin->setContent('topnavi', $topNavi);
    $skin->setContent('left', $left);

    $skin->show();
}

function showAdmin() {
    $navi = Navi::getInstance();
    $skin = new Skin('aski');

    $module = new AdminController();
    $module->setPath($navi->get);
    $content = $module->render();
    $topNavi = $module->renderTopNavi();
    $leftNavi = $navi->renderNaviTree();

    $skin->setContent('content', $content);
    $skin->setContent('left', $leftNavi);
    $skin->setContent('topnavi', $topNavi);
    $skin->show();
}



?>
