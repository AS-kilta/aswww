<?php

class AdminController {

    function render() {
        return '<h1>Admin</h1>';
    }

    function renderLogin() {
        $view = new View('login');
        $view->addData('message', 'Hello');

        return $view->render();
    }

}

?>
