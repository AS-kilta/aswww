<?php

/**
 * Navigation services (singleton)
 */
class Navi {
    var $naviTree;
    var $selectedNode;    // The node reqested by the user
    var $selectedBranch;  // The upmost node of the brach requested by the user

    private static $instance;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    private function __construct() {
        $this->naviTree = new NaviNode();
        $this->selectedNode = null;
        $this->selectedBranch = null;
    }

    private function __clone() {
    }

    /**
     * Resolves the given url and populates navitree. This should be
     * called once at the beginning.
     */
    public function resolve($urlParts) {
        $this->queryLevel($urlParts, $this->naviTree);
    }

    /**
     * Recursive method that populates the navitree.
     * @param @urlParts array containing the path
     */
    private function queryLevel($urlParts, $parent) {
        // Select nodes are children of our parent
        $query = 'SELECT * FROM naviNodes AS node, naviTitles AS title WHERE node.id=title.id';

        if ($parent->getId() == null) {
            $query .= ' AND parent IS NULL';
        } else {
            $query .= ' AND parent=' . $parent->getId();
        }

        $result = queryTable($query);

        // If we have reacheced a leaf node but the user request has
        // subfolders left, store the remaining path
        if (count($result) < 1) {
            $this->remainingPath = $urlParts;
        }

        // Add children to the navi tree
        foreach ($result as $row) {
            // Add the node to the tree
            $node = new NaviNode($row);
            $parent->addChild($node);

            // Is this node on the path requested by the user?
            if (count($urlParts) > 0 && $row['url'] == $urlParts[0]) {
                $node->setOnPath(true);

                // Is this the node that the user requested?
                if (count($urlParts) == 1) {
                    $this->selectedNode = $node;
                }

                // Is this the upmost node of the path?
                if ($parent->getId() == null) {
                    $this->selectedBranch = $node;
                }

                // Set language
                // XXX: this may not be the best place for this
                setLanguage($row['lang']);
                $this->naviTree->setLang($row['lang']);

                // Descend
                $this->queryLevel(array_slice($urlParts,1), $node);
            }
        }
    }

    public function getAvailableBlocks() {
        return array(
            'NaviTree',
            'TopNavi'
        );
    }

    /**
     * @return HTML representation of the navitree
     */
    public function renderNaviTree($startDepth = 2) {
        if ($this->selectedBranch != null) {
            $html = '<h1>' . $this->selectedBranch->getTitle() . "</h1>\n";
        }

        // XXX: that class='menu' should be removed
        $html .= "<ul class='menu'>\n";
        $html .= $this->naviTree->renderTree($startDepth);
        $html .= "</ul>\n";

        // Admin
        // TODO: check privileges properly
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();

        if ($user != null) {
            $html .= "<p><a href='" . baseUrl() . "/admin/newpage'>New page</a></p>";
        }

        return $html;
    }


    public function renderTopNavi() {
        $html = "<ul>";

        foreach ($this->naviTree->getChildren() as $child) {
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

    public function getSelectedNode() {
        return $this->selectedNode;
    }
}

class NaviNode {
    var $id;            // id in database
    var $lang;          // language
    var $url;           // url name of the node (folder)
    var $cumulativeUrl;
    var $title;         // human-readable title

    var $module;        // module that must be used for rendering
    var $contentId;     // id of the content node

    var $children;      // array of child nodes
    var $onPath;        // true if node is on the path requested by the user

    public function __construct($row = null) {
        $this->children = array();
        $this->onPath = false;

        if (is_array($row)) {
            $this->id = $row['id'];
            $this->lang = $row['lang'];
            $this->url = $row['url'];
            $this->title = $row['title'];
            $this->module = $row['module'];
            $this->contentId = $row['nodeid']; // TODO: rename to contentId
        } else {
            $this->id = 0;
        }
    }

    public function addChild($child) {
        $this->children[] = $child;

        $child->cumulativeUrl = $this->cumulativeUrl . '/' . $child->url;
    }

    public function getId() {
        return $this->id;
    }

    public function getLang() {
        return $this->lang;
    }

    public function setLang($lang) {
        $this->lang = $lang;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getModule() {
        return $this->module;
    }

    public function getContentId() {
        return $this->contentId;
    }

    public function getOnPath() {
        return $this->onPath;
    }

    public function setOnPath($boolean) {
        $this->onPath = $boolean;
    }

    public function getChildren() {
        return $this->children;
    }

    /**
     * Recursive method that renders the tree.
     * @return HTML representation of the tree
     */
    function renderTree($startDepth) {
        if (getLanguage() != $this->lang) {
            return;
        }

        if ($this->onPath) {
            $class = ' style="font-weight: bold"';
        }

        if ($startDepth < 1) {
            $html = "<li$class><a href='" . baseUrl() . $this->cumulativeUrl . "'>{$this->title}</a>\n";
        }

        foreach($this->children as $child) {
            $html .= $startDepth < 1 ? "<ul>\n" : '';
            $html .= $child->renderTree($startDepth - 1);
            $html .= $startDepth < 1 ? "</ul>\n" : '';
        }

        $html .= $startDepth < 1 ? "</li>\n" : '';

        return $html;
    }
}


?>
