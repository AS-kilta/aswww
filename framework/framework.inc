<?php

include_once 'config.php';
include_once 'framework/Model.php';
include_once 'framework/ModuleController.php';
include_once 'framework/MainController.php';
include_once 'framework/View.php';
include_once 'framework/Skin.php';
include_once 'modules/navi/Navi.php';
include_once 'models/Auth.php';

if ($db_adapter == 'pgsql') {
  include 'framework/pgsql.php';
} else if ($db_adapter == 'mysql') {
  addLogEntry('WARN', 'MySQL support is experimental');
  include 'framework/mysql.php';
}

$logfile = false;
$mailfile = false;

// Initialize session
session_start();


/**
 * Remove special characters to prevent XSS.
 */
function escapeHtml($string) {
    addLogEntry('ERROR', "htmlEscape is not implemented");
    return $string;
}


/**
 * Class autoloader
 */
/*
function __autoload($class_name) {
   require_once 'models/' . $class_name . '.php';
}
*/

/**
 * Redirects
 */
function redirect($location) {
    header("Location: " . baseUrl() . "/$location");
}

/**
 * Gets getdata
 * @return false if variable is not set
 */
function getGet($variableName) {
    if (isset($_GET[$variableName])) {
        return $_GET[$variableName];
    } else {
        return false;
    }
}

/**
 * Gets postdata
 * @return false if variable is not set
 */
function getPost($variableName) {
    if (isset($_POST[$variableName])) {
        return $_POST[$variableName];
    } else {
        return false;
    }
}

function getGetOrPost($variableName) {
    if (isset($_GET[$variableName])) {
        return $_GET[$variableName];
    } else if (isset($_POST[$variableName])) {
        return $_POST[$variableName];
    } else {
        return false;
    }
}

function getPostOrGet($variableName) {
    if (isset($_POST[$variableName])) {
        return $_POST[$variableName];
    } else if (isset($_GET[$variableName])) {
        return $_GET[$variableName];
    } else {
        return false;
    }
}

function getUserIP() {
    return $_SERVER['REMOTE_ADDR'];
}

function addLogEntry($level, $message) {
    global $logfile, $logfilename, $email_onError, $email_tekniikka;

    // Send mail
    if ($email_onError && $level == 'ERROR') {
        mail($email_tekniikka, 'ERROR', $timestamp . "\n". wordwrap($message, 70));
    }

    // Open logfile
    if (!$logfile) {
        $logfile = fopen($logfilename, 'a');
    }

    if (!$logfile) {
        return;
    }

    $timestamp = date('Y-m-d H:i:s');

    // Write to file
    if (flock($logfile, LOCK_EX)) {
        fwrite($logfile, "$level $timestamp: $message\n");
        flock($logfile, LOCK_UN);
    }
}

function sendEmail($address, $subject, $message, $from) {
    global $enableEmail, $mailfilename;

    // Send mail
    if ($enableEmail) {
        mail($address, $subject, wordwrap($message, 70), "From: $from");
    }

    // Save the mail
    if (!$mailfile) {
        $mailfile = fopen($mailfilename, 'a');
    }

    if (!$mailfile) {
        return;
    }

    // Write to file
    if (flock($mailfile, LOCK_EX)) {
        fwrite($mailfile, date('Y-m-d H:i:s') . "\n");
        fwrite($mailfile, "To: $address\n");
        fwrite($mailfile, "From: $from\n");
        fwrite($mailfile, "Subject: $subject\n");
        fwrite($mailfile, wordwrap($message, 70) . "\n\n");
        fwrite($mailfile, "**************************************************\n\n");

        flock($mailfile, LOCK_UN);
    }
}


function setLanguage($language) {
    global $lang;
    $lang = $language;
}

function getLanguage() {
    global $lang, $configuredLanguages;

    if (isset($lang)) {
        return $lang;
    } else {
        return $configuredLanguages[0];
    }
}

/**
 * Returns a list of configured (ie. available) languages.
 * @return Array of strings (language codes)
 */
function getConfiguredLanguages() {
    global $configuredLanguages;

    return $configuredLanguages;
}

function baseUrl() {
    // Remove index.php
    // strlen('index.php') + 1 = 10
    return substr($_SERVER['PHP_SELF'], 0, -10);
}

function getCurrentUrl() {
    // Remove everything after '?'
    $length = strpos($_SERVER['REQUEST_URI'], '?');

    if ($length === false) {
        return $_SERVER['REQUEST_URI'];
    } else {
        return substr($_SERVER['REQUEST_URI'], 0, $length);
    }
}

?>
