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

        foreach ($this->content as $region => $content) {
            $$region = $content;
        }

        include('skins/' . $this->skinName . '/page.php');
    }
}

?>
