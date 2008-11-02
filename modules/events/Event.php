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

    public static function getEvents($lang = false, $limit = false, $startDate = false, $endDate = false) {
        $query = 'SELECT * FROM events';

        if ($lang != false) {
            $query .= ' WHERE lang=\'' . escapeSql($lang) . '\'';
        }

        if ($startDate != false) {
            $query .= ' AND timestamp > \'' . escapeSql($startDate) . '\'';
        }

        if ($endDate != false) {
            $query .= ' AND timestamp < \'' . escapeSql($endDate) . '\'';
        }

        $query .= ' ORDER BY timestamp ASC';

        if ($lang != false) {
            $query .= ' LIMIT ' . escapeSql($limit);
        }

        $result = queryTable($query);
        $events = array();

        foreach ($result as $row) {
            $events[] = new Event($row);
        }

        return $events;
    }

    /**
     * Returns the events that have not passed.
     * @param $limit maximum number of events
     */
    public static function getFutureEvents($lang, $limit = false) {
        return Event::getEvents($lang, $limit, date('Y-m-d'));
    }

    public function setTimestamp($time) {
        $array = date_parse($time);

        if (!$array) {
            $this->timestamp = false;
            return;
        }

        $this->timestamp = sprintf('%04d-%02d-%02d %02d:%02d', $array['year'], $array['month'], $array['day'], $array['hour'], $array['minute']);
    }

    public function isValid() {
        return strlen($this->heading) > 0 && $this->timestamp !== false;
    }

}

?>
