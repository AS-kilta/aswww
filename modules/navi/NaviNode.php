<?php

/**
 * Represents a node in the navigation tree. Note: this does not map to any
 * single table in database, but is joined from NaviNodes and NaviTitles.
 */

class NaviNode extends Model {
    var $id;            // id in database
    var $url;           // url name of the node (folder) (array containing language versions)
    var $cumulativeUrl; // cumulative url (array containing language versions)
    var $title;         // human-readable title (array containing language versions)

    var $module;        // module that must be used for rendering

    var $parentId;
    var $parentNode;
    var $children;      // array of child nodes
    var $onPath;        // true if node is on the path requested by the user
    var $selected;      // true if node is the node requested by the user

    public function __construct($row = null) {
        // Columns that are automatically saved
        $this->tableName = 'navinodes';
        $this->sequenceName = 'navi';

        $this->children = array();
        $this->onPath = false;

        $this->weight = 0;

        if (is_array($row)) {
            $this->id = $row['id'];
            $this->parentId = $row['parent'];
            $this->url[$row['lang']] = $row['url'];
            $this->title[$row['lang']] = $row['title'];
            $this->module = $row['module'];
        } else {
            $this->id = 0;
        }
    }

    public function getChildren() {
        return $this->children;
    }

    public function getCumulativeUrl($lang) {
        return $this->cumulativeUrl[$lang];
    }

    public function setOnPath($boolean) {
        $this->onPath = $boolean;
    }

    public function isOnPath() {
        return $this->onPath;
    }

    public function setTitle($lang, $title) {
        $this->title[$lang] = $title;
    }

    public function getTitle($lang) {
        return $this->title[$lang];
    }

    public function setUrl($lang, $url) {
        $this->url[$lang] = $url;

        if ($this->parentNode != null) {
            $this->cumulativeUrl[$lang] = $this->parentNode->getCumulativeUrl($lang) . '/' . $url;
        }
    }

    public function getUrl($lang) {
        return $this->url[$lang];
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

    public function save($newId = false, $module = false) {

        if ($this->id == 'new') {
            $this->id = $newId;

            $query = "INSERT INTO naviNodes(id, parent, module, weight) VALUES ("
                . escapeSql($this->id) . ', ';
                
                if ($this->parentId == 0) {
                    $query .= 'null, ';
                } else {
                    $query .= escapeSql($this->parentId) . ', ';
                }
                
                $query .= '\'' . escapeSql($this->module) . '\', '
                    . escapeSql($this->weight)
                    . ')';
            
            if (query($query) === false) {
                return false;
            }
        } else {
            // Update an existing
            $query = 'UPDATE naviNodes SET';

            if ($this->parentId == 0) {
                $query .= ' parent=null, ';
            } else {
                $query .= ' parent=' . escapeSql($this->parentId) . ', ';
            }

            $query .= ' weight=' . escapeSql($this->weight)
                . ' WHERE id=' . escapeSql($this->id);

            if (query($query) === false) {
                return false;
            }
        }

        // Update titles
        $query = 'DELETE FROM naviTitles WHERE id=' . escapeSql($this->id);
        query($query);
        //echo $query . '<br />';

        foreach (array_keys($this->url) as $lang) {
            if (strlen($this->url[$lang]) < 1) {
                continue;
            }

            $query = "INSERT INTO naviTitles(id, lang, url, title) VALUES ("
                . escapeSql($this->id) . ', '
                . '\'' . escapeSql($lang) . '\', '
                . '\'' . escapeSql($this->url[$lang]) . '\', '
                . '\'' . escapeSql($this->title[$lang]) . '\''
                . ')';

            query($query);
            //echo $query . '<br />';
        }

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
        $query = 'INSERT INTO navinodes(id, parent, module) VALUES ('
            . $newId . ', '
            . (int)$parentId . ', \''
            . $module
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
     * Returns the nearest node available in the specified language.
     * Travels up the tree if this node has no translation.
     */
    public function getTranslation($lang) {
        if (isset($this->url[$lang])) {
            return $this;
        } else if ($this->parentNode != null) {
            return $this->parentNode->getTranslation($lang);
        } else {
            return null;
        }
    }

    /**
     * Recursive method that renders the tree.
     * @return HTML representation of the tree
     */
    function renderTree($lang, $startDepth) {
        //if ($this->cumulativeUrl[$lang])

        if ($this->selected) {
            $class = ' class="current"';
        }

        if ($startDepth < 1) {
            if (isset($this->url[$lang])) {
                $html = "<li$class><a$class href='" . baseUrl() . $this->getCumulativeUrl($lang) . "'>{$this->title[$lang]}</a>\n";
            }
        }

        foreach($this->children as $child) {
            $html .= $startDepth < 1 ? "<ul>\n" : '';

            if (isset($this->url[$lang])) {
                $html .= $child->renderTree($lang, $startDepth - 1);
            }

            $html .= $startDepth < 1 ? "</ul>\n" : '';
        }

        $html .= $startDepth < 1 ? "</li>\n" : '';

        return $html;
    }

    /**
     * Recursive method that renders the tree.
     * @return HTML representation of the tree
     */
    function renderFullTree($lang, $startDepth) {
        if ($startDepth < 1) {
            $html = "<li><a href='" . baseUrl() . $this->getCumulativeUrl($lang) . "'>{$this->title[$lang]}" 
                //. ' (' . getCumulativeUrl($lang) . ')'
                . "</a>\n";
        }

        foreach($this->children as $child) {
            $html .= $startDepth < 1 ? "<ul>\n" : '';
            $html .= $child->renderFullTree($lang, $startDepth - 1);
            $html .= $startDepth < 1 ? "</ul>\n" : '';
        }

        $html .= $startDepth < 1 ? "</li>\n" : '';

        return $html;
    }

    /**
     * @return HTML containing option tags
     */
    function renderParentSelector($currentId, $currentParentId, $recursionDepth = 0) {
        // Do not allow a node to be a descendant of itself
        if ($this->id == $currentId && $this->id != 'new') {
            return;
        }

        // Mark current parent
        if ($this->id == $currentParentId) {
            $selected = 'selected="true"';
        }

        if ($this->id == 0) {
            $html = "<option $selected value='0'>root</option>\n";
        } else {
            $html = "<option $selected value='{$this->id}'>";
            $html .= $this->getCumulativeUrl(getLanguage()) . "</option>\n";
        }

        // Print children
        foreach($this->children as $child) {
            $html .= $child->renderParentSelector($currentId, $currentParentId, $recursionDepth + 1);
        }

        return $html;
    }
}

?>