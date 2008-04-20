<?php

class MainController {

    /**
     * 
     * @param $path Path as an array.
     */
    function show($path) {
        // Load navitree
        $navi = Navi::getInstance();
        $navi->resolve($path);
        $node = $navi->getSelectedNode();

        // Get installed modules
        $modules = ModuleController::getAvailableModules();


        // Hard-coded paths
        /*
        if ($path[0] == 'login') {
            echo "<h1>Login not implemented</h1>";
            return;
        } else if ($path[0] == 'logout') {
            $auth = Auth::getInstance();
            $auth->logout();
            $path = Array();
        }
        */

        if (count($path) > 0 && in_array($path[0], $modules)) {
            $this->showModule(null, $path[0], $path[1]);
        } else if ($node != null) {
            $this->showModule($node, false, false);
        } else {
            $this->show404();
        }
    }
    
    function show404() {
        $navi = Navi::getInstance();
        echo "<h1>Page not found</h1>";
        // Load skin
        /*
        $skin = new Skin('aski');
        $skin->setContent('topnavi', $navi->renderTopNavi());
        $skin->setContent('left', $navi->renderNaviTree());
        $skin->setContent('content', "<h1>Page not found</h1>");
        $skin->show();
        */
    }
    
    
    function showModule($node, $moduleName, $controllerName) {
        global $defaultSkin;

        // Load the requested module
        if (strlen($moduleName) < 1 && $node != null) {
            $moduleName = $node->getModule();
        }
        $module = $this->loadModule($moduleName);

        if ($node != null) {
            $module->setNaviNode($node);
        }

        if (strlen($controllerName) > 0) {
            $module->setRequestedController($controllerName);
        }

        // Load the skin
        $skinName = $module->getPreferredSkin();
        if ($skinName == false) {
            $skinName = $defaultSkin;
        }

        $skin = new Skin($skinName);
        $contentMap = $skin->getContentMapping($moduleName);

        // Render main content
        $skin->setContent('content', $module->render());

        // Render auxiliary content defined in the content map
        foreach ($contentMap as $region => $content) {
            $auxModule = $this->loadModule($content[0]);
            $auxModule->setRequestedController($content[1]);
            $skin->setContent($region, $auxModule->render());
        }

        // Show
        $skin->show();
    }


    /**
     * Loads a module by name. Returns a ModuleController object.
     */
    function loadModule($moduleName) {
        $className = ucfirst($moduleName) . 'Controller';
        include_once('modules/' . $moduleName . '/' . $className . '.php');
        return new $className();
    }


    function showLogin() {
        $navi = Navi::getInstance();
        $skin = new Skin('aski');
    
        $module = new AdminController();
        $topNavi = $module->renderTopNavi();
        //$leftNavi = $navi->renderNaviTree();
    
        $skin->setContent('content', $content);
        //$skin->setContent('left', $leftNavi);
        $skin->setContent('topnavi', $topNavi);
        $skin->show();
    }
    

}

?>
