<?php
include 'config.php';

class Skin {

    var $skinName;
    var $content;

    function __construct($skinName) {
        $this->skinName = $skinName;
        $this->content = array();
    }

    function setContent($region, $html) {
        $this->content[$region] = $html;
    }

    function show() {
        global $skin;

        // Load data array into variables
        foreach ($this->content as $r => $c) {
            $$r = $c;
        }

        include('skins/' . $this->skinName . '/page.php');
    }
}

?>
