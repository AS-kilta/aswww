<?php
class Event extends Model {

  /**
    * Constructor
    */
  public function __construct($row = false) {
      parent::__construct();

      // Columns that are automatically saved
      $this->columns = array('heading','content','timestamp','lang');
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
      $query = 'SELECT * FROM ' . escapeSql($this->tableName) .' WHERE id=' . escapeSql($id);

      $result = queryTable($query);
      $versions = Array();

      foreach ($result as $row) {
          $versions[$row['lang']] = new Event($row);
      }

      return $versions;
  }

  public static function getCurrentEvents($lang = false, $limit = 0) {
    $query = 'SELECT * FROM news';

    if ($lang != false) {
      $query .= ' WHERE lang=\'' . escapeSql($lang) . '\'';
    }

    $query .= ' ORDER BY timestamp DESC';

    if ($limit > 0) {
      $query .= ' LIMIT ' . $limit;
    }

    $result = queryTable($query);

    return $result;
  }

}

?>
