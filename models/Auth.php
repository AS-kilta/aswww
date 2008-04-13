<?php
/**
 * Authentication service (singleton)
 */
class Auth {
    private static $instance;
    private $currentUser;

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

        $passwordCipher = md5($password);

        // Query
        $query = 'SELECT id FROM users '
                   . "WHERE username = '" . escapeSql($username) . "' "
                   . "AND password = '$passwordCipher'";

        $result = query($query);

        if ($result != false && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            $_SESSION['userId'] = $row['id'];
            return $row['id'];
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

    /**
     * Returns current user
     * @return Current user or null is no user is logged in
     */
    function getCurrentUser() {
        $userId = $_SESSION['userId'];

        if ($userId > 0) {
            if ($this->currentUser == null) {
                $user = new User();
                $user->load($_SESSION['userId']);
                $this->currentUser = $user;
            }

            return $this->currentUser;
        } else {
            return null;
        }
    }

}

?>
