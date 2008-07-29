<?php

$db_conn = false;

function open_db() {
    global $db_conn, $db_name, $db_user, $db_passwd;

    $db_conn = mysql_connect('localhost', $db_user, $db_passwd);

    if ($db_conn == false) {
        return false;
    }

    return mysql_select_db($db_name);
}

function query($query) {
    global $db_conn;

    // Open database connection if necessary
    if ($db_conn == false) {
        open_db();

        if ($db_conn == false) {
            return false;
        }
    }

    //$level = error_reporting(8);
    $result = mysql_query($query);
    //error_reporting($level);

    if ($result == false) {
        addLogEntry('ERROR', $query . "\n" . pg_last_error());
        return false;
    }

    return $result;
}

/**
 * Performs the query and returns an associative array
 */
function queryTable($query) {
    $result = query($query);

    if ($result == false) {
        return false;
    }

    $table = array();
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $table[] = $row[0];
    }
}

/**
 * Remove special characters to prevent sql injection.
 */
function escapeSql($string) {
    return pg_escape_string($string);
}

function begin_transaction() {
    query('BEGIN');
}

function end_transaction() {
    query('COMMIT');
}

?>
