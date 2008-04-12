<?php
include 'config.php';

class View {

    var $data;
    var $viewName;     // Name of the view (including path and .php)
    var $contoller;    // Controller that created this View

    function __construct($viewName, $controller = null) {
        $this->viewName = $viewName;
        $this->controller = $controller;
        $this->data = array();
    }

    /**
     * Imports data to the view for rendering.
     * @param key Variable name used in the view template
     * @param data Any object or variable
     */
    function setData($key, $data) {
        $this->data[$key] = $data;
    }

    /**
     * Renders the view.
     * @return A string containing HTML.
     */
    function render() {
        // Convert array into separate variables for easier referencing.
        // This can cause variable name conflicts, that's why the stupid
        // variable names.
        foreach ($this->data as $k => $d) {
            $$k = $d;
        }

        ob_start();
        include($this->viewName);
        $c = ob_get_contents();
        ob_end_clean();

        return $c;
    }

    /**
     * Renders the starting form tag
     * @param $action
     */
    function formStart($action = false) {
        if ($this->controller == null) {
            // Controller not known. Just make the url stay the same.
            return '<form method="post" action="' . getCurrentUrl() . "\">\n";
        } else if ($action === false) {
            // No action specified. Make it the same as currently.
            return '<form method="post" action="' . getCurrentUrl()
                    . '?' . $this->controller->getModuleName() . 'Action='
                    . $this->controller->getCurrentAction()
                    .  "\">\n";
        } else {
            // Action specified
            return '<form method="post" action="' . getCurrentUrl()
                    . '?' . $this->controller->getModuleName() . 'Action='
                    . $action
                    .  "\">\n";
        }
    }

    function formEnd() {
        return "</form>\n";
    }

    /**
     * Renders a submit button. The action specified here overrides the one
     * specified in startForm().
     */
    function submitButton($action, $text) {
        return '<button name="'
                . $this->controller->getModuleName() . 'Action" value="'
                . $action . '" type="submit" />'
                . "$text</button>\n";
    }

    function actionLink($action, $text) {
        return "<a href='" . getCurrentUrl()
                    . '?' . $this->controller->getModuleName() . 'Action=' . $action . "'>$text</a>";
    }
}

?>
