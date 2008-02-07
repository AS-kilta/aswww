<?php

class Controller {
    var $skin;
    var $menu;
    var $page;

    function __construct($page = false) {
        if ($page) {
            $this->page = new View($page);
        }
    }

    function execute() {
        if (Auth::isAdmin()) {
            $this->skin = new Skin('adminskin');
            $this->menu = new View('adminmenu');
            $this->skin->addData('admin', true);
        } else {
            $this->skin = new Skin('skin');
            $this->menu = new View('menu');
        }

        $this->skin->setContent('menu', $this->menu);
        $this->skin->setContent('content', $this->page);
        $this->skin->render();
    }
}

?>
