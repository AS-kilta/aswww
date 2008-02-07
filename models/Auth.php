<?php

class Auth {

    /**
     * 
     * @param $username username
     * @param $password password (plaintext)
     * @return oid of the user or false if login failed
     */
    static function login($etunimi, $sukunimi, $salasana) {
        if (strlen($salasana) == 0) {
            return false;
        }

        $passwordCipher = md5($salasana);

        // Query
        $query = 'SELECT oid FROM ilmo '
                   . "WHERE LOWER(etunimi) = '" . strtolower($etunimi) . "' "
                   . "AND LOWER(sukunimi) = '" . strtolower($sukunimi) . "' "
                   . "AND salasana = '$passwordCipher'";
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
    static function logout() {
        $_SESSION['userId'] = -1;
    }

    static function loginAdmin() {
        $_SESSION['admin'] = true;
    }

    static function logoutAdmin() {
        $_SESSION['admin'] = false;
    }

    static function isAdmin() {
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
    static function getCurrentUser() {
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
