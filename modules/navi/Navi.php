<?php

include_once 'modules/navi/NaviNode.php';

/**
 * Navigation services (singleton)
 */
class Navi {
    var $naviTree;
    var $selectedNode;         // The node reqested by the user
    var $requestedAction;

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

      // Add admin menu
      /*
      $auth = Auth::getInstance();
      $user = $auth->getCurrentUser();
      if ($user != null) {
        $this->addAdminMenu();
      }
      */
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
            //$query .= ' AND lang=\'' . $parent->lang . '\'';
        }
        $query .= ' ORDER BY position';

        $result = queryTable($query);

        // If we have reacheced a leaf node on the path, but the user request has
        // subfolders left, store the remaining path
        if (count($result) < 1 && count($urlParts) > 0 && $parent != null && $parent->isOnPath()) {
            $this->requestedAction = $urlParts[0];
        }

        // Add children to the navi tree
        foreach ($result as $row) {
            // Add the node to the tree.
            if (!array_key_exists($row['id'], $parent->children)) {
                // Different language versions have the same id, and they are stored in the same node
                $parent->children[$row['id']] = new NaviNode($row);
                $parent->children[$row['id']]->setParentNode($parent);
            }
            $parent->children[$row['id']]->setTitle($row['lang'], $row['title']);
            $parent->children[$row['id']]->setUrl($row['lang'], $row['url']);

            // Is this node on the path requested by the user?
            if (count($urlParts) > 0 && $row['url'] == $urlParts[0]) {
                $parent->children[$row['id']]->setOnPath(true);

                // Is this the node that the user requested?
                if (count($urlParts) == 1) {
                    $this->selectedNode = $parent->children[$row['id']];
                    $parent->children[$row['id']]->setSelected(true);
                }

                // Set language
                // XXX: this may not be the best place for this
                setLanguage($row['lang']);
                $this->naviTree->setLang($row['lang']);
            }
        }

        // Descend
        foreach ($parent->children as $child) {
            $this->queryLevel(array_slice($urlParts,1), $child);
        }
    }

    public function getSelectedNode() {
        return $this->selectedNode;
    }

    /**
     * Returns the action rquested by the user.
     * @return string or false if nothing is requested
     */
    public function getRequestedAction() {
        return $this->requestedAction;
    }

    /**
     * Retruns the root node of the navitree.
     */
    public function getNaviTree() {
        return $this->naviTree;
    }
}

?>
