<?php

function getContentMapping($moduleName) {
    switch ($moduleName) {
        default:
            return Array(
                "menu" => Array('ilmo', 'topMenu'),
            );
    }
}

?>
