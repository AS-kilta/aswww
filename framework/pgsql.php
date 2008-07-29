<?php

$db_conn = false;

function open_db() {
    global $db_conn, $db_name, $db_user, $db_passwd;

    return $db_conn = pg_connect("host=localhost dbname=$db_name user=$db_user password=$db_passwd");
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
    $result = pg_query($query);
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

    $table = pg_fetch_all($result);
    if ($table == false) {
        return array();
    } else {
        return $table;
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
