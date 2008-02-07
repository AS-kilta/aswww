<?php

class Model {
    public $id;
    protected $columns;       // Columns that are automatically saved
    protected $tableName;

    function __construct() {
        $this->id = 'new';
    }

    public function load($id, $lang=false) {
        $query = "SELECT * FROM {$this->tableName} WHERE id=" . pg_escape_string($id);
        if ($lang !== false) {
            $query .= ' AND lang=\'' . pg_escape_string($lang) . '\'';
        }

        $result = queryTable($query);

        if (count($result) < 1) {
            return false;
        } else {
            foreach ($result[0] as $key => $value) {
                $this->$key = $value;
            }

            return true;
        }
    }

    /**
     * Saves the object into database if it does not exist, or updates an existing entry.
     */
    function save() {
        if (count($this->columns) < 1) {
            addLogEntry('WARN', 'Attempting to save an empty object');
            return false;
        }

        /// TODO: use sequences here
        // http://www.postgresql.org/docs/8.1/static/sql-createsequence.html
        // http://www.postgresql.org/docs/8.1/static/functions-sequence.html

        if ($this->oid == 'new') {
            // Create a new entry
            $query = "INSERT INTO {$this->tableName} (" . implode(', ', $this->columns) . ') VALUES (';

            $columnName = $this->columns[0];
            $query .= "'" . pg_escape_string($this->$columnName) . "'";
            for ($i = 1; $i < count($this->columns); $i++) {
                $columnName = $this->columns[$i];

                if ($this->$columnName === null) {
                    $query .= ", null";
                } else if (!is_numeric($this->$columnName) || $this->$columnName === false) {
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
                $this->oid = pg_last_oid($result);
                return $this->oid;
            }

        } else {
            // Update an existing entry
            $query = "UPDATE {$this->tableName} SET ";

            $columnName = $this->columns[0];
            $query .= $this->columns[0] . "='" . pg_escape_string($this->$columnName) . "'";
            for ($i = 1; $i < count($this->columns); $i++) {
                $columnName = $this->columns[$i];
                if ($this->$columnName === null) {
                    $query .= ", {$this->columns[$i]}=null";
                } else if (!is_numeric($this->$columnName) || $this->$columnName === false) {
                    $query .= ", {$this->columns[$i]}='" . pg_escape_string($this->$columnName) . "'";
                } else {
                    $query .= ", {$this->columns[$i]}=" . pg_escape_string($this->$columnName);
                }
            }

            $query .= " WHERE oid = " . pg_escape_string($this->oid);

            //echo "<p>$query</p>\n";
            if (query($query) == false) {
                return false;
            } else {
                //echo "Update successful<br />\n";
                return $this->oid;
            }
        }
    }

    function delete() {
        if ($this->id == 'new') {
            addLogEntry('WARN', 'Attempting to delete an object that has not been saved');
            return false;
        }

        $result = query("DELETE FROM {$this->tableName} WHERE id={$this->id}");
        if ($result == false) {
            return false;
        }

        return true;
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
