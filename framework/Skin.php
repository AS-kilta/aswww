<?php
include 'config.php';

class Skin {

    var $skinName;
    var $content;

    function __construct($skinName) {
        $this->skinName = $skinName;
        $this->content = array();
    }

    function getContentMapping($moduleName) {
        include_once('skins/' . $this->skinName . '/config.php');

        return getContentMapping($moduleName);
    }

    function getMainNaviRegion() {
        include_once('skins/' . $this->skinName . '/config.php');

        return getMainNaviRegion();
    }

    function setContent($region, $html) {
        $this->content[$region] = $html;
    }

    function show() {
        global $skin, $_;  // Translation strings

        // Include translation strings
        include('skins/' . $this->skinName . '/strings-' . getLanguage() . '.php');

        // Load data array into variables
        foreach ($this->content as $r => $c) {
            $$r = $c;
        }

        include('skins/' . $this->skinName . '/page.php');
    }
}

?>
