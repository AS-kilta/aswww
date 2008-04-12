<?php

class ModuleController {

    var $currentAction;
    var $moduleName;
    var $contentNode;
    var $path;

    /**
     * Returns a new View object.
     */
    function loadView($viewName) {
        return new View("modules/{$this->moduleName}/views/$viewName.php", $this);
    }

    function render() {
        // Check if action is specified in GET
        $getAction = getGet($this->moduleName . 'Action');
        $postAction = getPost($this->moduleName . 'Action');



        if ($postAction != false) {
            $action = $postAction;
        } else if ($getAction != false) {
            $action = $getAction;
        } else {
            // No action requested. Render default.
            return $this->renderDefault();
        }

        $this->currentAction = $action;

        // Call the requested method
        $method = 'render' . ucfirst($action);
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            // Action requested, but is not implemented.
            return "<h1>Invalid action requested</h1>";
        }
    }

    function setContentNode($node) {
        $this->contentNode = $node;
    }

    /**
     * Returns the naviNode requested by the user.
     */
    function getContentNode() {
        return $this->contentNode;
    }

    /**
     * Returns content id that can be used for fetching data from the database.
     * @return mixed contentId or false if not set
     */
    function getContentId() {
        if ($this->contentNode != null) {
            return $this->contentNode->getContentId();
        } else {
            return false;
        }
    }

    function setPath($pathArray) {
        $this->path = $pathArray;
    }

    function getPath() {
        return $this->path;
    }

    function getModuleName() {
        return $this->moduleName;
    }

    function getCurrentAction() {
        return $this->currentAction;
    }
}

?>
