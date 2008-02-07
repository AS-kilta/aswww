<?php
include_once 'modules/ilmo/Ilmo.php';

class IlmoController {

    public function __construct() {
    }

    public function showPage() {
        $navi = Navi::getInstance();

        $page = new Ilmo();
        $page->load($navi->getSelectedNode()->getContentId(), getLanguage());

        // Load skin
        $skin = new Skin('ilmo');
        $skin->setContent('heading', $page->getTitle());
        $skin->setContent('menu', $this->renderTopMenu());
        $skin->setContent('content', $page->getDescription());
        $skin->show();
    }

    private function renderTopMenu() {
        $html = '<a href="#">Etusivu</a>';
        $html .= ' | <a href="#">Ilmoittaudu</a>';
        $html .= ' | <a href="#">N&auml;yt&auml; ilmoittautuneet</a>';

        return $html;
    }


}


?>
