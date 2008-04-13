<?php
class User extends Model {

    /**
     * Constructor
     */
    public function __construct() {
        $this->tableName = 'users';
        $this->key = array('id');

        // Columns that are automatically saved
        $this->columns = array('username','password','realname');

        parent::__construct();
    }

    public function getUsers() {
        $query = 'SELECT id,username,realname FROM users';

        $result = queryTable($query);

        return $result;
    }

    public function setPassword($plaintext) {
        $this->password = md5($plaintext);
    }
}
?>
