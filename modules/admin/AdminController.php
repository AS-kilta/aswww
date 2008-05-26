<?php

include_once 'modules/admin/Admin.php';

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
        $html = '<ul>';
        $html .= '<li><a href="' . baseUrl() . '">Front page</a></li>';
        $html .= '<li><a href="' . baseUrl() . '/admin/users">Users</a></li>';
        $html .= '<li><a href="' . baseUrl() . '/admin/pages">Pages</a></li>';
        $html .= '<li><a href="' . baseUrl() . '/admin/navi">Navi</a></li>';
        $html .= '<li><a href="' . baseUrl() . '/logout">Logout</a></li>';
        $html .= '</ul>';

        return $html;
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
                redirect('admin');
            } else {
                $view->setData('invalidLogin', true);
                return $view->render();
            }
        }
    }

    public function renderLogout() {
        $auth = Auth::getInstance();
        $auth->logout();
        redirect('');
    }

    public function renderUser() {
        $user = Auth::getCurrentUser();

        if ($user == null) {
            return "<h1>Not logged in</h1>";
        } else {
            return "<h1>" . $user->getRealname() . "</h1>";
        }
    }

    public function renderUsers() {
        $user = Auth::getCurrentUser();
        $users = User::getUsers();

        $view = $this->loadView('users');
        $view->setData('users', $users);
        return $view->render();
    }


    /**
     * Edit user
     */
    public function renderEditUser() {
        // Check privileges
        $auth = Auth::getInstance();

        if (!$auth->hasPrivilege($auth->getCurrentUser(), 'user', false, 'edit')) {
            redirect('admin/login');
            return;
        }

        $user = new User();
        $userId = getGetOrPost('userId');

        $view = $this->loadView('editUser');

        // Load user, if id is known
        if (strlen($userId) > 0 && $userId != 'new') {
            if ($user->load($userId) == false) {
                return '<h1>User not found</h1>';
            }
        }

        if (getPost('save')) {
            $user->setRealname(getPost('realname'));
            $user->setUsername(getPost('username'));

            $password = getPost('password');
            if (strlen($password) > 0) {
                $user->setPassword($password);
            }

            $user->save();
            $view->setData('message', 'User saved');
        }

        $view->setData('user', $user);
        return $view->render();
    }

    /**
     * Delete user
     */
    public function renderUsersDeleteUser() {
        // Check privileges
        $auth = Auth::getInstance();

        if (!$auth->hasPrivilege($auth->getCurrentUser(), 'user', false, 'edit')) {
            redirect('admin/login');
            return;
        }

        $userId = getGetOrPost('userId');
        if ($userId == false) {
            return "<h1>No userId specified</h1>";
        }

        // Delete user
        $user = new User();
        $user->load($userId);
        $user->delete();

        redirect('admin/users');
    }

    public function renderSkinSelector() {
        global $configuredSkins;

        // Check privileges
        $auth = Auth::getInstance();

        if (!$auth->hasPrivilege($auth->getCurrentUser(), 'admin', false, 'edit')) {
            redirect('admin/login');
            return;
        }


        $view = $this->loadView('skinSelector');

        if (getPost('ok')) {
            Admin::setSkin(getPost('skin'));
            redirect('admin?adminAction=skinSelector');
            return;
        }

        $view->setData('skins', $configuredSkins);
        return $view->render();
    }

    public function renderNavi() {
        $navi = Navi::getInstance();
        $root = $navi->getNaviTree();

        $view = $this->loadView('navi');
        $view->setData('navi', $navi);
        return $view->render();
    }
}

?>
