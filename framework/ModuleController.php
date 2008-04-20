<?php

class ModuleController {

    var $moduleName;

    var $naviNode;
    var $requestedController = false;
    var $requestedContent;

    /**
     * Returns a new View object.
     */
    function loadView($viewName) {
        return new View("modules/{$this->moduleName}/views/$viewName.php", $this);
    }

    function render() {
        // Controller specified in th url
        $requestedController = $this->getRequestedController();

        // Check if action is specified in GET or POST
        // (this overrides the url)
        $action = getGetOrPost($this->moduleName . 'Action');

        if ($action != false) {
            $method .= 'render' . ucfirst($action);
        } else if ($requestedController != false) {
            $method .= 'render' . ucfirst($requestedController);
        } else {
            $method .= 'renderDefault';
        }

        // Call the requested method
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            // Action requested, but is not implemented.
            return "<h1>Invalid action requested</h1>";
        }
    }

    function setNaviNode($node) {
        $this->naviNode = $node;
    }

    /**
     * Returns content id that can be used for fetching data from the database.
     * @return int contentId or false if not set
     */
    function getContentId() {
        if ($this->naviNode != null) {
            return $this->naviNode->getContentId();
        } else {
            return false;
        }
    }

    function getNaviNode() {
        return $this->naviNode;
    }

    function setRequestedController($controllerName) {
        $this->requestedController = $controllerName;
    }

    function getRequestedController() {
        return $this->requestedController;
    }

    function getModuleName() {
        return $this->moduleName;
    }

    /*
    function getCurrentAction() {
        return $this->currentAction;
    }
    */

    /**
     * Returns the name of the preferred skin or false if default is ok.
     */
    function getPreferredSkin() {
        return false;
    }

    /**
     * Returns a list of available modules as an array.
     */
    static function getAvailableModules() {
        $list = Array();

        if ($directory = opendir('modules/')) {
            while (false !== ($file = readdir($directory))) {
                if (is_dir('modules/' . $file) && $file{0} != ".") {
                    $list[] = $file;
                }

            }

            closedir($directory);
        }

        return $list;
    }
}

?>
