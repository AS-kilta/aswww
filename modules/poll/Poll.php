<?php
class Poll extends Model {
    var $totalVotes;
    var $options;    // Array of strings [pollOptionId => string]
    var $votes;      // Array of integers [pollOptionId => int]
    var $question;
    var $lang;

    /**
     * Constructor
     */
    public function __construct($row = false) {
        parent::__construct();

        // Columns that are automatically saved
        $this->columns = array('lang','question');
        $this->tableName = 'poll';
        $this->key = array('id','lang');

        $this->options = array('','','','');
        $this->totalVotes = 0;

        if ($row != false) {
            $this->loadRow($row);
        }
    }

    /**
     * Returns an array of strings.
     */
    public function getOptions() {
      return $this->options;
    }

    public function getResults() {
      return null;
    }

    public function vote($pollOptionId, $ip) {
        if ($this->hasVoted($ip)) {
            return;
        }

        query('INSERT INTO pollVote(polloption_id, ip) VALUES(' . escapeSql($pollOptionId) . ', \'' . escapeSql($ip). '\')');
        $this->votes[$pollOptionId]++;
        $this->totalVotes++;
        $this->calculatePercentages();
    }

    /**
     * Loads all language versions.
     * @returns Array of objects. Array indexes are language codes.
     */
    public function loadAll($id) {
        $query = 'SELECT * FROM poll WHERE id=' . escapeSql($id);

        $result = queryTable($query);
        $versions = Array();

        foreach ($result as $row) {
            $versions[$row['lang']] = new Poll($row);
        }

        return $versions;
    }

    /**
     * If $id is not specified, loads the active poll.
     * @returns Array of objects. Array indexes are language codes.
     */
    public function load($lang, $id = false) {
        // Load poll
        $query = "SELECT * FROM poll WHERE lang='" . escapeSql($lang) . '\' ';

        if ($id === false) {
            $query .= 'ORDER BY id DESC LIMIT 1';
        } else {
            $query .= 'AND id=' . escapeSql($id);
        }

        $result = queryTable($query);
        if ($result == false || count($result) < 1) {
          return false;
        }

        $poll = new Poll($result[0]);

        // Load options
        $query = "SELECT id, content FROM polloption WHERE poll_id={$poll->id} AND lang='" . escapeSql($lang) . "' ORDER BY position";
        $result = queryTable($query);

        $poll->options = array();
        foreach ($result as $row) {
          $poll->options[$row['id']] = $row['content'];
        }

        // Load votes
        $query = 'SELECT polloption_id, count(polloption_id) FROM pollVote'
                . ' WHERE polloption_id IN (' . implode(', ', array_keys($poll->options)) . ')'
                . 'GROUP BY polloption_id';
        $result = queryTable($query);

        foreach ($result as $row) {
            $poll->votes[$row['polloption_id']] = $row['count'];

            //echo $row['position'] . ' ' . $row['count'] . ' ';
            $poll->totalVotes += $row['count'];
            //echo $poll->totalVotes . '<br />';
        }

        $poll->calculatePercentages();

        return $poll;
    }

    public function save() {
        $failed = false;

        if (parent::save() === false) {
            return false;
        }

        // Save options
        $query = "DELETE FROM pollOption WHERE poll_id=" . escapeSql($this->id)
            . ' AND lang=\'' . escapeSql($this->lang) . '\'';
        $result = query($query);

        $i = 0;
        foreach ($this->options as $option) {
            $query = "INSERT INTO pollOption(poll_id, lang, position, content) VALUES("
                . escapeSql($this->id)
                . ', \'' . escapeSql($this->lang) . '\', '
                . $i++
                . ', \'' . $option . '\')';

            if (query($query) === false) {
                $failed = true;
            }
        }

        return !$failed;
    }

    public function hasVoted($ip) {
        $query = 'SELECT * FROM pollVote WHERE polloption_id IN (' . implode(', ', array_keys($this->options)) . ')'
            . ' AND ip=\'' . escapeSql($ip) . '\'';

        $result = queryTable($query);

        return $result !== false && count($result) > 0;
    }

    private function calculatePercentages() {
      foreach ($this->options as $key => $value) {
        //echo $i . ' ' . $this->votes[$key] . '<br />';
        if ($this->totalVotes > 0) {
            $this->percentages[$key] = (int)($this->votes[$key] * 100 / $this->totalVotes);
        } else {
            $this->percentages[$key] = 0;
        }
      }
    }

}

?>
