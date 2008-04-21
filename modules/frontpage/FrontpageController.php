<?php
//include_once 'modules/frontpage/Frontpage.php';

class FrontpageController extends ModuleController {
    function __construct() {
        $this->moduleName = 'frontpage';
    }

    function renderDefault() {
        $auth = Auth::getInstance();

        $view = $this->loadView('news');
        return $view->render();
    }

}

?>
