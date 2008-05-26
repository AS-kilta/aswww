<?php
//include_once 'modules/frontpage/Frontpage.php';

class EventsController extends ModuleController {
    function __construct() {
        $this->moduleName = 'events';
    }

    function renderDefault() {
        $view = $this->loadView('events');
        return $view->render();
    }

}

?>
