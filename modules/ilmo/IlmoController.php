<?php
include_once 'modules/ilmo/Ilmo.php';

class IlmoController {

    public function __construct() {
    }

    public function render($node) {
        $navi = Navi::getInstance();

        $page = new Ilmo();
        $page->load($node->getContentId(), getLanguage());

        return $page->getDescription();
    }

    public function renderEvents() {
        $html = '<h1>Events</h1>';
        $html .= '<ul>';
        $html .= '<li>Tapahtuma 1</li>';
        $html .= '<li>Tapahtuma 2</li>';
        $html .= '</ul>';

        return $html;
    }

    private function renderTopMenu() {
        $html = '<a href="#">Etusivu</a>';
        $html .= ' | <a href="#">Ilmoittaudu</a>';
        $html .= ' | <a href="#">N&auml;yt&auml; ilmoittautuneet</a>';

        return $html;
    }


}


?>
