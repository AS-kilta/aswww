<?php
include_once 'modules/admin/Admin.php';

class MainController {

  /**
    *
    * @param $path Path as string.
    */
  public function show($path) {
    $parts = explode('/', $path);

    // Load navitree
    $navi = Navi::getInstance();
    $navi->resolve($parts);

    $node = $navi->getSelectedNode();

    // Get installed modules
    $modules = ModuleController::getAvailableModules();

    if (count($parts) > 0 && in_array($parts[0], $modules)) {
      // modulename/action
      if (count($parts) > 1) {
        $action = $parts[1];
      }

      $this->showModule(null, $parts[0], $action);
    } else if ($node != null) {
      // path
      $this->showModule($node, false, false);
    } else {
      $this->show404();
    }
  }

  private function show404() {
      $this->showModule(null, 'page', '404');
  }

  /**
   * $moduleName has precedence if both $node and $moduleName are specified.
   */
  private function showModule($node, $moduleName, $action) {
    global $defaultSkin;

    // Load the requested module
    if (strlen($moduleName) < 1 && $node != null) {
        $moduleName = $node->getContentModule();
    }
    $module = $this->loadModule($moduleName);

    if ($node != null) {
        $module->setNaviNode($node);
    }

    if (strlen($action) > 0) {
        $module->setRequestedAction($action);
    }

    // Load the skin
    $skinName = $module->getPreferredSkin();
    if ($skinName == false) {
        $skinName = Admin::getSkin();

        if (strlen($skinName) < 1) {
            $skinName = $defaultSkin;
        }
    }

    $skin = new Skin($skinName);
    $contentMap = $skin->getContentMapping($moduleName);

    // Render main content
    $skin->setContent('content', $module->render());

    // Render auxiliary content defined in the content map
    foreach ($contentMap as $region => $content) {
        $auxModule = $this->loadModule($content[0]);
        $auxModule->setRequestedAction($content[1]);

        $regionName = explode('|', $region, 2);
        $regionName = $regionName[0];

        $skin->appendContent($regionName, $auxModule->render());
    }

    // Show
    $skin->show();
  }


  /**
    * Loads a module by name. Returns a ModuleController object.
    */
  private function loadModule($moduleName) {
    global $_;  // Translation strings

    // Include class definition
    $className = ucfirst($moduleName) . 'Controller';
    include_once("modules/$moduleName/$className.php");

    // Include translation strings
    include_once("modules/$moduleName/strings-" . getLanguage() . '.php');

    return new $className();
  }

}

?>
