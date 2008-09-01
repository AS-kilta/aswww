<?php
class Poll extends Model {
    var $totalVotes;
    var $options;    // Array of strings


    /**
     * Constructor
     */
    public function __construct($row = false) {
        parent::__construct();

        // Columns that are automatically saved
        $this->columns = array('question');
        $this->tableName = 'poll';
        $this->key = array('id','lang');

        $this->options = array();
        $this->totalVotes = 0;

        if ($row != false) {
            $this->loadRow($row);
        }
    }

    public function getOptions() {
      return $this->options;
    }

    public function getResults() {
      return null;
    }

    public function vote($index, $ip) {
      query("INSERT INTO pollVote(poll, position, ip) VALUES({$this->id}, " . escapeSql($index). ", '" . escapeSql($ip). "')");
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

    /**
     * Loads all language versions.
     * @returns Array of objects. Array indexes are language codes.
     */
    public static function loadActive($lang) {
        // Load poll
        $query = "SELECT * FROM poll WHERE lang='" . escapeSql($lang) . "' ORDER BY id LIMIT 1";

        $result = queryTable($query);
        if ($result == false || count($result) < 1) {
          return false;
        }

        $poll = new Poll($result[0]);

        // Load options
        $query = "SELECT content FROM polloption WHERE poll={$poll->id} AND lang='" . escapeSql($lang) . "' ORDER BY position";
        $result = queryTable($query);

        foreach ($result as $row) {
          $poll->options[] = $row['content'];
        }

        // Load votes
        $query = "SELECT position,count(position) FROM pollVote GROUP BY position ORDER BY position";
        $result = queryTable($query);

        foreach ($result as $row) {
          $poll->votes[$row['position']] = $row['count'];

          echo $row['position'] . ' ' . $row['count'] . ' ';
          $poll->totalVotes += $row['count'];
          echo $poll->totalVotes . '<br />';
        }

        $poll->calculatePercentages();

        return $poll;
    }

    private function calculatePercentages() {
      for ($i = 0; $i < count($this->options); $i++) {
        echo $i . ' ' . $this->votes[$i] . '<br />';
        $this->percentages[$i] = $this->votes[$i] / $this->totalVotes;
      }
    }

}

?>
