<?php
class Admin extends Model {

    /**
     * Constructor
     */
    public function __construct($row = false) {
        parent::__construct();

        // Columns that are automatically saved
        /*
        $this->columns = array('lang','content');
        $this->key = array('id','lang');
        $this->tableName = 'pages';
        $this->sequenceName = 'navi';
        */
    }

    public static function setSkin($skinName) {
        $query = 'UPDATE site SET skin=\'' . escapeSql($skinName) . '\'';

        return query($query);
    }

    public static function getSkin() {
        $query = 'SELECT DISTINCT skin FROM site';

        $result = queryTable($query);

        return $result[0]['skin'];
    }

}

?>
