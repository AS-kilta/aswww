<?php
class Frontpage extends Model {

    /**
     * Constructor
     */
    public function __construct() {
        // Columns that are automatically saved
        //$this->columns = array('lang','content');
        //$this->tableName = 'pages';

        parent::__construct();
    }

    public static function getNews($lang = false) {
        $query = 'SELECT * FROM news';

        if ($lang != false) {
            $query .= ' WHERE lang=\'' . escapeSql($lang) . '\'';
        }

        $query .= ' ORDER BY timestamp DESC';

        $result = queryTable($query);

        return $result;
    }
}

?>
