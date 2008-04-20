<?php
class Page extends Model {

    /**
     * Constructor
     */
    public function __construct() {
        // Columns that are automatically saved
        $this->columns = array('lang','content');
        $this->key = array('id','lang');
        $this->tableName = 'pages';
        $this->sequenceName = 'content';

        parent::__construct();
    }

}

?>
