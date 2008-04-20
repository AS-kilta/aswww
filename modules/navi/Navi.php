<?php

include_once 'modules/navi/NaviNode.php';

/**
 * Navigation services (singleton)
 */
class Navi {
    var $naviTree;
    var $selectedNode;         // The node reqested by the user
    var $requestedController;  // The upmost node of the brach requested by the user

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
        $this->requestedController = false;
    }

    private function __clone() {
    }

    /**
     * Resolves the given url and populates the navitree. This should be
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
            $query .= ' AND lang=\'' . $parent->lang . '\'';
        }

        $result = queryTable($query);

        // If we have reacheced a leaf node ont he path, but the user request has
        // subfolders left, store the remaining path
        if (count($result) < 1 && count($urlParts) > 0 && $parent != null && $parent->isOnPath()) {
            $this->requestedController = $urlParts[0];
        }

        // Add children to the navi tree
        foreach ($result as $row) {
            // Add the node to the tree
            $newChild = new NaviNode($row);
            $parent->addChild($newChild);

            // Is this node on the path requested by the user?
            if (count($urlParts) > 0 && $row['url'] == $urlParts[0]) {
                $newChild->setOnPath(true);

                // Is this the node that the user requested?
                if (count($urlParts) == 1) {
                    $this->selectedNode = $newChild;
                }

                // Set language
                // XXX: this may not be the best place for this
                setLanguage($row['lang']);
                $this->naviTree->setLang($row['lang']);
            }

            // Descend
            $this->queryLevel(array_slice($urlParts,1), $newChild);
        }
    }

    public function getSelectedNode() {
        return $this->selectedNode;
    }

    /**
     * Returns the controller rquested by the user.
     * @return string or false if nothing is requested
     */
    public function getRequestedController() {
        return $this->selectedNode;
    }

    /**
     * Retruns the root node of the navitree.
     */
    public function getNaviTree() {
        return $this->naviTree;
    }
}

?>
