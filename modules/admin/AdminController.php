<?php

class AdminController {

    function showPage() {
        $navi = Navi::getInstance();

        //$page = new Page();
        //$page->load($navi->getSelectedNode()->getContentId(), getLanguage());

        // Load skin
        $skin = new Skin('aski');
        $skin->setContent('topnavi', $navi->renderTopNavi());
        $skin->setContent('left', $navi->renderNaviTree());
        $skin->setContent('content', '<h1>Admin</h1>');
        $skin->show();
    }

}

?>
