<?php
//include_once 'modules/frontpage/Frontpage.php';

class FrontpageController extends ModuleController {
    function __construct() {
        $this->moduleName = 'frontpage';
    }
    
    function renderDefault() {
        return '<h1>Front Page</h1>';
    }

}

?>
