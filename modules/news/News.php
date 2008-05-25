<?php
class News extends Model {

    /**
     * Constructor
     */
    public function __construct($row = false) {
        parent::__construct();

        // Columns that are automatically saved
        $this->columns = array('heading','content','lang');
        $this->tableName = 'news';
        $this->sequenceName = 'news';
        $this->key = array('id','lang');

        if ($row != false) {
            $this->loadRow($row);
        }
    }

    /**
     * Loads all language versions.
     * @returns Array of objects. Array indexes are language codes.
     */
    public function loadAll($id) {
        $query = 'SELECT * FROM news WHERE id=' . escapeSql($id);

        $result = queryTable($query);
        $versions = Array();

        foreach ($result as $row) {
            $versions[$row['lang']] = new News($row);
        }

        return $versions;
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
