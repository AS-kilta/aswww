<?php

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
        global $_;  // Translation strings

        // Convert the array into separate variables for easier referencing.
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
        } else if ($action != false) {
            return '<form method="post" action="' . getCurrentUrl()
                    . '?' . $this->controller->getModuleName() . 'Action='
                    . $action
                    .  "\">\n";
        } else {
            return '<form method="post" action="' . getCurrentUrl() . "\">\n";
        }
    }

    function formEnd() {
        return "</form>\n";
    }

    /**
     * Renders a submit button. The action specified here overrides the one
     * specified in startForm().
     * @param $parameters Example: 'param1=qwerty&param2=asdfgh'
     */
    function submitButton($action, $text, $parameters = false) {
        $html = '<button name="'
                . $this->controller->getModuleName() . 'Action" value="'
                . $action;

        if ($parameters) {
            $html .= '&' . $parameters;
        }

        $html .= '" type="submit" />' . $text . "</button>\n";

        return $html;
    }

    function link($path, $text) {
        return '<a href=\'' . baseUrl() . "/$path'>$text</a>\n";
    }

    /**
     * @param parameters Get parameters as a string. Example 'param1=qwerty&param2=15'
     */
    function actionLink($action, $text, $parameters = false) {
        $html = "<a href='" . getCurrentUrl()
                    . '?' . $this->controller->getModuleName() . 'Action=' . $action;

        if ($parameters) {
            $html .= '&' . $parameters;
        }

        $html .= "'>$text</a>";

        return $html;
    }
}

?>
