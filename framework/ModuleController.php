<?php

class ModuleController {

    var $currentAction;
    var $moduleName;

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

    function getModuleName() {
        return $this->moduleName;
    }

    function getCurrentAction() {
        return $this->currentAction;
    }
}

?>
