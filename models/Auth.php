<?php

class Auth {
    private static $instance;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {
    }

    /**
     *
     * @param $username username
     * @param $password password (plaintext)
     * @return id of the user or false if login failed
     */
    function login($username, $password) {
        if (strlen($password) == 0) {
            return false;
        }

        $passwordCipher = md5($salasana);

        // Query
        $query = 'SELECT id FROM users '
                   . "WHERE username = '" . escape($username) . "' "
                   . "AND password = '$passwordCipher'";
        $result = query($query);

        if ($result != false && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            $_SESSION['userId'] = $row['oid'];
            return $row['oid'];
        } else {
            return false;
        }
    }

    /**
     * Logs out the current user
     */
    function logout() {
        $_SESSION['userId'] = -1;
    }

    function loginAdmin() {
        $_SESSION['admin'] = true;
    }

    function logoutAdmin() {
        $_SESSION['admin'] = false;
    }

    function isAdmin() {
        if ($_SESSION['admin']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns current user
     * @return mixed Current user or null is no user is logged in
     */
    function getCurrentUser() {
        if ($_SESSION['userId'] > 0) {
            $user = new Ilmo;
            $user->load($_SESSION['userId']);
            return $user;
        } else {
            return null;
        }
    }

}

?>
