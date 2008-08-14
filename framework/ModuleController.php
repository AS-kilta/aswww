<?php

class ModuleController {
  var $moduleName;

  var $naviNode;
  var $requestedAction = false;
  var $requestedContent;

  /**
    * Returns a new View object.
    */
  function loadView($viewName) {
      return new View("modules/{$this->moduleName}/views/$viewName.php", $this);
  }

  function render() {
      // Action specified in th url
      $requestedAction = $this->getRequestedAction();

      // Check if action is specified in GET or POST
      // (this overrides the url)
      $action = getGetOrPost($this->moduleName . 'Action');

      if ($action != false) {
          $method .= 'render' . ucfirst($action);
      } else if ($requestedAction != false) {
          $method .= 'render' . ucfirst($requestedAction);
      } else {
          $method .= 'renderDefault';
      }

      // Call the requested method
      if (method_exists($this, $method)) {
          return $this->$method();
      } else {
          // Action requested, but is not implemented.
          return "<h1>Unknown action</h1>";
      }
  }

  function setNaviNode($node) {
      $this->naviNode = $node;
  }

  /**
    * Returns content id that can be used for fetching data from the database.
    * @return int contentId or false if not set
    */
  function getContentId() {
      if ($this->naviNode != null) {
          return $this->naviNode->getId();
      } else {
          return false;
      }
  }

  function getNaviNode() {
      return $this->naviNode;
  }

  function setRequestedAction($action) {
      $this->requestedAction = $action;
  }

  function getRequestedAction() {
      return $this->requestedAction;
  }

  function getModuleName() {
      return $this->moduleName;
  }

  /*
  function getCurrentAction() {
      return $this->currentAction;
  }
  */

  /**
    * Returns the name of the preferred skin or false.
    */
  function getPreferredSkin() {
      return false;
  }

  /**
    * Returns a list of available modules as an array.
    * @return array of strings
    */
  static function getAvailableModules() {
    // TODO: Any way to optimize this method?
    $list = Array();

    if ($directory = opendir('modules/')) {
      while (false !== ($file = readdir($directory))) {
        if (is_dir('modules/' . $file) && $file{0} != ".") {
            $list[] = $file;
        }
      }

      closedir($directory);
    }

    return $list;
  }
}

?>
