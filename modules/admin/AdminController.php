<?php

class AdminController extends ModuleController {

    function __construct() {
        $this->moduleName = 'admin';
    }

    public function renderDefault() {
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();

        if ($user == null) {
            return $this->renderLogin();
        } else {
            return "<h1>Admin</h1>";
        }
    }

    public function renderTopNavi() {
        $view = $this->loadView('topNavi');

        return $view->render();
    }

    public function renderLogin() {
        $view = $this->loadView('login');

        $username = getPost('username');
        $password = getPost('password');

        if ($username == false ) {
            // No postdata, so just show the login form
            return $view->render();
        } else {
            // Attempting login
            $auth = Auth::getInstance();

            if ($auth->login($username, $password) !== false) {
                // Successful login
                redirect('');
            } else {
                $view->setData('invalidLogin', true);
                return $view->render();
            }
        }

    }
}

?>
