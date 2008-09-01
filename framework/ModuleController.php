<?php

class ModuleController {
  var $moduleName;

  var $naviNode;
  var $requestedAction = false;

  public function render() {
      // Action specified in the url
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

  public function setNaviNode($node) {
      $this->naviNode = $node;
  }

  /**
    * Returns a new View object.
    */
  protected function loadView($viewName) {
      return new View("modules/{$this->moduleName}/views/$viewName.php", $this);
  }

  /**
    * Returns content id that can be used for fetching data from the database.
    * @return int contentId or false if not set
    */
  protected function getContentId() {
      if ($this->naviNode != null) {
          return $this->naviNode->getContentId();
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

  public function getModuleName() {
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
  public function getPreferredSkin() {
      return false;
  }

  /**
    * Returns a list of available modules as an array.
    * @return array of strings
    */
  public static function getAvailableModules() {
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
