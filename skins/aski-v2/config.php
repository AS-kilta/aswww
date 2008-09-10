<?php

function getContentMapping($moduleName) {
    switch ($moduleName) {
        case 'news':
            return Array(
                'topnavi' => Array('navi', 'topNavi'),
                'topbanner' => Array('randomimage', 'default'),
                'left|1' => Array('poll', 'default'),
                'left|2' => Array('events', 'short')
            );
            break;

        case 'events':
            return Array(
                'topnavi' => Array('navi', 'topNavi'),
                'topbanner' => Array('randomimage', 'default'),
                'left' => false,
            );
            break;

        default:
            return Array(
                "topnavi" => Array('navi', 'topNavi'),
                'topbanner' => Array('randomimage', 'default'),
                "left" => Array('navi', 'naviTree')
            );
    }
}

function getMainNaviRegion() {
    return 'left';
}

?>
