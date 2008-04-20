<?php

/**
 * NaviController acts as a renderable interface to Navi
 */
class NaviController extends ModuleController {

    public function __construct() {
        $this->moduleName = 'navi';
    }


    public function renderDefault() {
        $navi = Navi::getInstance();
        $naviTree = $navi->getNaviTree();

        $html .= "<ul>\n";
        $html .= $naviTree->renderFullTree(1);
        $html .= "</ul>\n";
        
        return $html;
    }

    /**
     * @return HTML representation of the navitree
     */
    public function renderNaviTree($startDepth = 2) {
        $navi = Navi::getInstance();
        $naviTree = $navi->getNaviTree();

        $branch = $naviTree->getSelectedBranch();

        if ($branch != null) {
            $html = '<h1>' . $branch->getTitle() . "</h1>\n";
        }

        $html .= "<ul>\n";
        $html .= $naviTree->renderTree($startDepth);
        $html .= "</ul>\n";

        // Admin
        // TODO: check privileges properly
        /*
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();

        if ($user != null) {
            $html .= "<p><a href='" . baseUrl() . "/admin/newpage'>New page</a></p>";
        }
        */

        return $html;
    }


    public function renderTopNavi() {
        $navi = Navi::getInstance();
        $naviTree = $navi->getNaviTree();
        
        
        $html = "<ul>";

        foreach ($naviTree->getChildren() as $child) {
            if ($child->getLang() == getLanguage()) {
                $html .= "<li><a href='" . baseUrl() . $child->cumulativeUrl . "'>{$child->title}</a></li>";
            }
        }

        // Admin
        // TODO: check privileges properly
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();

        if ($user != null) {
            $html .= "<li><a href='" . baseUrl() . "/admin'>Admin</a></li>";
            $html .= "<li><a href='" . baseUrl() . "/logout'>Logout</a></li>";
        }

        $html .= "</ul>";
        return $html;
    }

}

