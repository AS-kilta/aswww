<?php

function getContentMapping($moduleName) {
    switch ($moduleName) {
        case 'askicam':
            return Array(
                'topnavi' => Array('navi', 'topNavi'),
                'left' => Array('ascicam', 'menu')
            );
            break;

        default:
            return Array(
                "topnavi" => Array('navi', 'topNavi'),
                "left" => Array('navi', 'naviTree')
            );
    }
}

?>
