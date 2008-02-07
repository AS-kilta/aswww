<?php
//include_once 'modules/frontpage/Frontpage.php';

class FrontpageController {

    function showPage() {
        $navi = Navi::getInstance();

        //$page = new Page();
        //$page->load($navi->getSelectedNode()->getContentId(), getLanguage());

        // Load skin
        $skin = new Skin('aski');
        $skin->setContent('topnavi', $navi->renderTopNavi());

        
        
        $skin->setContent('left', '<h1>Events</h1><h1>Gallup</h1>');


        $skin->setContent('content','<h1>Front Page</h1>');
        $skin->show();
    }

}

?>
