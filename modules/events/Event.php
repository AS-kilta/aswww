<?php
class Event extends Model {

  /**
    * Constructor
    */
    public function __construct($row = false) {
        parent::__construct();

        // Columns that are automatically saved
        $this->columns = array('lang','timestamp','heading','time','place','description');
        $this->tableName = 'events';
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
        $query = 'SELECT * FROM events WHERE id=' . escapeSql($id);

        $result = queryTable($query);
        $versions = Array();

        foreach ($result as $row) {
            $versions[$row['lang']] = new Event($row);
        }

        return $versions;
    }

    public static function getEvents($lang = false) {
        $query = 'SELECT * FROM events';

        if ($lang != false) {
            $query .= ' WHERE lang=\'' . escapeSql($lang) . '\'';
        }

        $query .= ' ORDER BY timestamp ASC';

        $result = queryTable($query);
        $events = array();

        foreach ($result as $row) {
            $events[] = new Event($row);
        }

        return $events;
    }

}

?>
