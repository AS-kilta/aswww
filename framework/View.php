<?php
include 'config.php';

class View {

    var $data;
    var $viewName;            // Name of the view

    function __construct($viewName) {
        $this->viewName = $viewName;
        $this->data = array();
    }

    /**
     * Imports data to the view for rendering.
     * @param data Any object or variable
     */
    function addData($key, $data) {
        $this->data[$key] = $data;
    }

    /**
     * Renders the view.
     * @return A string containing HTML.
     */
    function render() {
        // Convert array into separate variables for easier referencing
        foreach ($this->data as $key => $data) {
            $$key = $data;
        }

        ob_start();
        include('views/' . $this->viewName . '.php');
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}

?>
