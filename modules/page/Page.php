<?php
class Page extends Model {

    /**
     * Constructor
     */
    public function __construct($row = false) {
        parent::__construct();

        // Columns that are automatically saved
        $this->columns = array('lang','content');
        $this->key = array('id','lang');
        $this->tableName = 'pages';
        $this->sequenceName = 'navi';

        if ($row != false) {
            $this->loadRow($row);
        }
    }

    /**
     * Loads all language versions.
     * @returns Array of objects. Array indexes are language codes.
     */
    public function loadAll($id) {
        $query = 'SELECT * FROM pages WHERE id=' . escapeSql($id);

        $result = queryTable($query);
        $versions = Array();

        foreach ($result as $row) {
            $versions[$row['lang']] = new Page($row);
        }

        return $versions;
    }

}

?>
