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

    /**
     * Retruns a list of all pages.
     */
    public static function getPages($lang = false) {
        $query = 'SELECT id, lang FROM news';

        if ($lang != false) {
            $query .= ' WHERE lang=\'' . escapeSql($lang) . '\'';
        }

        $query .= ' ORDER BY timestamp DESC';

        $result = queryTable($query);

        return $result;
    }

}

?>
