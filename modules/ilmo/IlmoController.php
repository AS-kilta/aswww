<?php
include_once 'modules/ilmo/Ilmo.php';

class IlmoController extends ModuleController {

    public function __construct() {
        $this->moduleName = 'ilmo';
    }

    public function renderDefault() {
        $ilmo = new Ilmo();
        $ilmo->load($this->getContentId(), getLanguage());

        $view = $this->loadView('frontpage');
        $view->setData('ilmo', $ilmo);

        return $view->render();
    }

    public function renderEvents() {
        $html = '<h1>Events</h1>';
        $html .= '<ul>';
        $html .= '<li>Tapahtuma 1</li>';
        $html .= '<li>Tapahtuma 2</li>';
        $html .= '</ul>';

        return $html;
    }

    public function renderTopMenu() {
        $html = '<a href="#">Etusivu</a>';
        $html .= ' | <a href="#">Ilmoittaudu</a>';
        $html .= ' | <a href="#">N&auml;yt&auml; ilmoittautuneet</a>';

        return $html;
    }

    public function getPreferredSkin() {
        return 'ilmo';
    }
}


?>
