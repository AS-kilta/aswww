<?php

/**
 * NaviController acts as a renderable interface to Navi
 */
class NaviController extends ModuleController {

    public function __construct() {
        $this->moduleName = 'navi';
    }

    public function renderAdmin() {
        return $this->renderDefault();
    }

    public function renderDefault() {
        $navi = Navi::getInstance();
        $naviTree = $navi->getNaviTree();

        $html .= "<h1>Navigation hierarchy</h1>";
        $html .= "<ul>\n";
        $html .= $naviTree->renderFullTree(1);
        $html .= "</ul>\n";

        return $html;
    }

    /**
     * @return HTML representation of the navitree
     */
    public function renderNaviTree() {
        $navi = Navi::getInstance();
        $naviTree = $navi->getNaviTree();
        $lang = getLanguage();

        $branch = $naviTree->getSelectedBranch();

        if ($branch != null) {
            $html = '<h1>' . $branch->getTitle($lang) . "</h1>\n";
            $html .= "<ul>\n";
            $html .= $branch->renderTree($lang, 1);
            $html .= "</ul>\n";
        } else {
            $html .= "<ul>\n";
            $html .= $naviTree->renderTree($lang, 1);
            $html .= "</ul>\n";
        }

        // Admin
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();

        if ($user != null) {
            $html .= $this->renderAdminMenu();
        }

        return $html;
    }


    public function renderTopNavi() {
        global $_;  // Translation strings
        $navi = Navi::getInstance();
        $naviTree = $navi->getNaviTree();

        $lang = getLanguage();

        $html = "<ul>";

        foreach ($naviTree->getChildren() as $child) {
            if ($child->onPath) {
                $class = 'class="current"';
            } else {
                $class = '';
            }

            if (isset($child->title[$lang]) && !$child->hidden) {
                $html .= "<li $class><a href='" . baseUrl() . $child->getCumulativeUrl($lang) . "'>{$child->title[$lang]}</a></li>";
            }
        }

        // Language selector
        $node = $navi->getSelectedNode();
        if ($node != null) {
            $translatedNode = $navi->getSelectedNode()->getTranslation($_['otherLangCode-' . $lang]);
        }

        if ($translatedNode != null) {
            $html .= '<li><a href="' . baseUrl() . $translatedNode->getCumulativeUrl($_['otherLangCode-' . $lang]) . '">'
                . $_['otherLangText-' . $lang]
                . '</a></li>';
        } else {
            $html .= '<li><a href="' . baseUrl() . '/">' . $_['otherLangText-' . $lang] . '</a></li>';
        }

        // Admin
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();

        if ($user != null) {
            $html .= "<li><a href='" . baseUrl() . "/admin'>Admin</a></li>";
            $html .= "<li><a href='" . baseUrl() . "/admin/logout'>Logout</a></li>";
        }

        $html .= "</ul>";
        return $html;
    }

  private function renderAdminMenu() {
    //$modules = ModuleController::getAvailableModules();

    $html = "<h1>Admin</h1>\n";
    $html .= "<ul>\n";
    //foreach ($modules as $module) {
    //    $html .= "<li><a href='" . baseUrl() . "/$module/admin'>" . ucfirst($module) . "</a></li>\n";
    //}
    $html .= "<li><a href='" . baseUrl() . "/page/edit'>New page</a></li>\n";
    $html .= "<li><a href='" . baseUrl() . "/navi'>Navigation hierarchy</a></li>\n";
    $html .= "<li><a href='" . baseUrl() . "/admin?adminAction=skinSelector'>Skin</a></li>\n";
    $html .= "</ul>\n";

    return $html;
  }

}

