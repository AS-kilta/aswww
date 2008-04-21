<?php
//include_once 'modules/frontpage/Frontpage.php';

class PollController extends ModuleController {
    function __construct() {
        $this->moduleName = 'poll';
    }

    function renderDefault() {
        $auth = Auth::getInstance();

        $view = $this->loadView('poll');
        return $view->render();
    }

}

?>
