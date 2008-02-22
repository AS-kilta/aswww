<?php
include_once 'modules/page/Page.php';

class PageController {

    function render($node) {
        $page = new Page();
        $page->load($node->getContentId(), getLanguage());

        return $page->getContent();
    }

    function renderEdit() {
        return "<h1>Edit page</h1>";
    }

}

?>
