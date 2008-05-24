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

        if (count($path) > 0 && in_array($path[0], $modules)) {
            $this->showModule(null, $path[0], $path[1]);
        } else if ($node != null) {
            $this->showModule($node, false, false);
        } else {
            $this->show404();
        }
    }

    function show404() {
        $this->showModule(null, 'page', '404');
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

        // Render admin menu
        if ($node == null) {
            $mainNaviRegion = $skin->getMainNaviRegion();
            $skin->setContent($mainNaviRegion, $this->renderAdminMenu());
        }

        // Show
        $skin->show();
    }


    /**
     * Loads a module by name. Returns a ModuleController object.
     */
    function loadModule($moduleName) {
        global $_;  // Translation strings

        // Include class definition
        $className = ucfirst($moduleName) . 'Controller';
        include_once("modules/$moduleName/$className.php");

        // Include translation strings
        include_once("modules/$moduleName/strings-" . getLanguage() . '.php');

        return new $className();
    }

    private function renderAdminMenu() {
        $modules = ModuleController::getAvailableModules();

        $html = "<h1>Admin</h1>\n";
        $html .= "<ul>\n";
        foreach ($modules as $module) {
            $html .= "<li><a href='" . baseUrl() . "/$module/admin'>" . ucfirst($module) . "</a></li>\n";
        }
        $html .= "</ul>\n";

        return $html;
    }

}

?>
