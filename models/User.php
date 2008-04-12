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
}
?>
