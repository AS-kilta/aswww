<?php
include_once 'modules/page/Page.php';

class PageController {

    function showPage() {
        $navi = Navi::getInstance();

        $page = new Page();
        $page->load($navi->getSelectedNode()->getContentId(), getLanguage());

        // Load skin
        $skin = new Skin('aski');
        $skin->setContent('topnavi', $navi->renderTopNavi());
        $skin->setContent('left', $navi->renderNaviTree());
        $skin->setContent('content', $page->getContent());
        $skin->show();
    }

    function showEdit() {
        $navi = Navi::getInstance();

        // Load skin
        $skin = new Skin('aski');
        $skin->setContent('topnavi', $navi->renderTopNavi());
        $skin->setContent('left', $navi->renderNaviTree());
        $skin->setContent('content', "<h1>Edit page</h1>");
        $skin->show();
    }

}

?>
