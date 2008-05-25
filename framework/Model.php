<?php

class Model {
    public $id;
    protected $columns;       // Columns that are automatically saved
    protected $tableName;

    function __construct() {
        $this->id = 'new';
    }

    /**
     * Loads an associative array (a row from database) into member variables.
     */
    protected function loadRow($row) {
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Loads an object from the database.
     * @return id of the object or false on error
     */
    public function load($id, $lang=false) {
        $query = "SELECT * FROM {$this->tableName} WHERE id=" . pg_escape_string($id);
        if ($lang !== false) {
            $query .= ' AND lang=\'' . pg_escape_string($lang) . '\'';
        }

        $result = queryTable($query);

        if (count($result) < 1) {
            return false;
        } else {
            // Load columns into instance variables
            foreach ($result[0] as $key => $value) {
                $this->$key = $value;
            }

            return $this->id;
        }
    }

    /**
     * Saves the object into database if it does not exist, or updates an existing entry.
     * The object must have an instance variable 'columns' containing an array of variable names.
     * Only variables listed in that array are put to the query.
     *
     * If the instance variable id == 'new', an insert is performed and the new id is read from a
     * sequence with postfix 'Seq'. Example: if the table is named 'pages', the sequence
     * is expected to be named pagesSeq.
     *
     * If the instance variable id contains an id, an update is performed.
     *
     * If there is no sequence, and an insert is to be performed, the id
     * must be given in the $newId parameter.
     *
     * @param newId An id for the new entry. This must be given if there is no sequence and id=='new'.
     */
    function save($newId = false) {
        if (count($this->columns) < 1) {
            addLogEntry('WARN', 'Attempting to save an empty object');
            return false;
        }

        if ($this->id == 'new') {
            // Create a new entry

            // Get id from sequence if it's not provided
            if ($newId === false) {
                $newId = $this->nextVal();
            }

            $query = "INSERT INTO {$this->tableName} (id, " . implode(', ', $this->columns) . ') VALUES (' . $newId;

            // Column values
            for ($i = 0; $i < count($this->columns); $i++) {
                $columnName = $this->columns[$i];

                if ($this->$columnName === null) {
                    $query .= ", null";
                } else if (!is_numeric($this->$columnName) || $this->$columnName === false) {
                    // FIXME: phone numbers are interpreted as integers
                    $query .= ", '" . pg_escape_string($this->$columnName) . "'";
                } else {
                    $query .= ', ' . pg_escape_string($this->$columnName) . ' ';
                }
            }
            $query .= ')';

            $result = query($query);
            if ($result == false) {
                return false;
            } else {
                $this->id = $newId;
                return $newId;
            }

        } else {
            // Update an existing entry
            $query = "UPDATE {$this->tableName} SET ";

            for ($i = 0; $i < count($this->columns); $i++) {
                if ($i > 0) {
                    $query .= ', ';
                }

                $columnName = $this->columns[$i];

                if ($this->$columnName === null) {
                    $query .= "$columnName=null";
                } else if (!is_numeric($this->$columnName) || $this->$columnName === false) {
                    $query .= "$columnName='" . pg_escape_string($this->$columnName) . "'";
                } else {
                    $query .= "$columnName=" . pg_escape_string($this->$columnName);
                }
            }

            // Where
            $query .= ' WHERE ' . $this->sqlKey();

            //echo "<p>$query</p>\n";
            if (query($query) == false) {
                return false;
            } else {
                //echo "Update successful<br />\n";
                return $this->id;
            }
        }
    }

    /**
     * Returns the next value from a sequence.
     * @return int new id, or false on error
     */
    function nextVal() {
        if (strlen($this->sequenceName) < 1) {
            addLogEntry('ERROR', "No sequence defined ({$this->tableName}).");
            return false;
        }

        $query = "SELECT nextval('{$this->sequenceName}Seq')";
        $result = queryTable($query);

        if (count($result) < 1) {
            addLogEntry('ERROR', "Error getting next value from sequence {$this->sequenceName}Seq");
            return false;
        } else {
            return $result[0]['nextval'];
        }
    }

    function delete() {
        if ($this->id == 'new') {
            addLogEntry('WARN', 'Attempting to delete an object that has not been saved.');
            return false;
        }

        $query = "DELETE FROM {$this->tableName} WHERE " . $this->sqlKey();

        $result = query($query);
        if ($result == false) {
            return false;
        }

        return true;
    }

    /**
     * Retruns the key string to be used in the query.
     * Example: "id=10 AND lang='fi'"
     */
    private function sqlKey() {
        $sql = '';
        $i = 0;
        foreach ($this->key as $columnName) {
            if ($i > 0) {
                $sql .= ' AND ';
            }

            if (!is_numeric($this->$columnName) || $this->$columnName === false) {
                $sql .= $columnName . "='" . escapeSql($this->$columnName) . "'";
            } else {
                $sql .= $columnName . '=' . escapeSql($this->$columnName);
            }

            $i++;
        }

        return $sql;
    }

    /**
     * Automatic getters and setters
     */
    public function __call($method, $params) {
        if (method_exists($this, $method)) {
            // If the method exists, use it
            return call_user_method_array(array($this, $method), $params);
        } else if (strlen($method) > 3) {
            // Getters and setters
            $prefix = substr($method, 0, 3);
            $property = substr($method, 3);
            $property[0] = strtolower($property[0]);

            if ($prefix == 'get') {
                return $this->$property;
            } else if ($prefix == 'set') {
                $this->$property = $params[0];
            }
        }
    }

}

?>
