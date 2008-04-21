<?php

/**
 * Represents a node in the navigation tree. Note: this does not map to any
 * single table in database, but is joined from NaviNodes and NaviTitles.
 */

class NaviNode extends Model {
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
        // Columns that are automatically saved
        $this->tableName = 'navinodes';
        $this->sequenceName = 'navinodes';


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

    public function getChildren() {
        return $this->children;
    }

    public function setOnPath($boolean) {
        $this->onPath = $boolean;
    }

    public function isOnPath() {
        return $this->onPath;
    }

    /**
     * Returns the child that is on the path requested by user.
     * @return NaviNode or null
     */
    public function getSelectedBranch() {
        foreach($this->children as $child) {
            if ($child->isOnPath()) {
                return $child;
            }
        }

        return null;
    }

    /**
     * Moves this node to a new position. The database is updated immediately.
     */
    public function updateNode($parentId) {
        // Update navinode
        if ($parentId > 0) {
            $query = 'UPDATE navinodes SET parent=';

            if (is_numeric($parentId)) {
                $query .= $parentId;
            } else {
                $query .= 'NULL';
            }

            $query .= ' WHERE id=' . $this->id;

            query($query);
        }

        // Update navititle
        $query = "UPDATE navititles SET url='{$this->url}', title='{$this->title}'"
            . " WHERE id={$this->id} AND lang='{$this->lang}'";

        query($query);
    }

    /**
     * Creates a new navinode and a navititle. The insert is performed immediately.
     */
    public function createNode($parentId, $module, $contentId) {
        $newId = $this->nextVal();

        // Create a new navinode
        $query = 'INSERT INTO navinodes(id, parent, module, nodeId) VALUES ('
            . $newId . ', '
            . (int)$parentId . ', \''
            . $module . '\', '
            . (int)$contentId
            . ')';

        if (query($query) === false) {
            return false;
        }

        // Create a new navitile
        $query = 'INSERT INTO navititles(id, lang, url, title) VALUES ('
            . $newId . ', \''
            . escapeSql($this->lang) . '\', \''
            . escapeSql($this->url) . '\', \''
            . escapeSql($this->title)
            . '\')';

        if (query($query) === false) {
            return false;
        }

        return true;
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

    /**
     * Recursive method that renders the tree.
     * @return HTML representation of the tree
     */
    function renderFullTree($startDepth) {
        if ($startDepth < 1) {
            $html = "<li><a href='" . baseUrl() . $this->cumulativeUrl . "'>{$this->title}</a>\n";
        }

        foreach($this->children as $child) {
            $html .= $startDepth < 1 ? "<ul>\n" : '';
            $html .= $child->renderFullTree($startDepth - 1);
            $html .= $startDepth < 1 ? "</ul>\n" : '';
        }

        $html .= $startDepth < 1 ? "</li>\n" : '';

        return $html;
    }

    /**
     * @return HTML containing option tags
     */
    function renderParentSelector($recursionDepth = 0) {
        $html = "<option value='{$this->id}'>";
        $html .= " {$this->cumulativeUrl}</option>\n";

        // Print children
        foreach($this->children as $child) {
            $html .= $child->renderParentSelector($recursionDepth + 1);
        }

        return $html;
    }
}

?>