<?php

function getContentMapping($moduleName) {
    switch ($moduleName) {
        case 'news':
            return Array(
                'topnavi' => Array('navi', 'topNavi'),
                'left' => Array('poll', 'default')
            );
            break;

        case 'askicam':
            return Array(
                'topnavi' => Array('navi', 'topNavi'),
                'left' => Array('askicam', 'menu')
            );
            break;

        default:
            return Array(
                "topnavi" => Array('navi', 'topNavi'),
                "left" => Array('navi', 'naviTree')
            );
    }
}

function getMainNaviRegion() {
    return 'left';
}

?>
