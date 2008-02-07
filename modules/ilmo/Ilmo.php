<?php

class Ilmo extends Model {

    /**
     * Constructor
     */
    public function __construct() {
        // Columns that are automatically saved
        $this->columns = array('lang','title','description','opens','closes','capacity');
        $this->tableName = 'signup';

        parent::__construct();
    }


}

?>
