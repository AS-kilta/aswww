<?php
include_once 'modules/page/Page.php';

class PageController extends ModuleController {
    function __construct() {
        $this->moduleName = 'page';
    }

    function renderDefault() {
        $auth = Auth::getInstance();
        $page = new Page();
        $page->load($this->getContentId(), getLanguage());

        $view = $this->loadView('show');
        $view->setData('editable', true);  // XXX: only if admin
        $view->setData('htmlContent', $page->getContent());

        return $view->render();
    }

    function renderEdit() {
        // TODO: check privileges
        $navi = Navi::getInstance();
        $view = $this->loadView('edit');
        $naviNode = $this->getNaviNode();

        // Load the page to be edited
        $page = new Page();

        if ($naviNode != null) {
            $page->load($naviNode->getContentId(), getLanguage());
        }

        if ($naviNode == null) {
            $naviNode = new NaviNode();
        }

        // Read Postdata
        if (getPost('save') || getPost('preview')) {
            $page->setContent(getPost('content'));
            $naviNode->setLang(getPost('language'));
            $naviNode->setTitle(getPost('title'));
            $naviNode->setUrl(getPost('url'));
        }

        // Save edited content
        if (getPost('save')) {
            // Set parent
            if ($page->getId() == 'new') {
                $page->setLang(getPost('language'));

                if ($page->save() == false) {
                    return "<h1>Query failed</h1>";
                }

                $naviNode->createNode(getPost('parent'), $this->moduleName, $page->getId());
            } else {
                if ($page->save() == false) {
                    return "<h1>Query failed</h1>";
                }

                $naviNode->updateNode(getPost('parent'));
            }

            $view->setData('success', 'Page saved');
        }

        // Load the navi tree
        $naviTree = $navi->getNaviTree();
        $treeComponent = $naviTree->renderParentSelector();

        // Load view
        $view->setData('htmlContent', $page->getContent());
        $view->setData('editableContent', $page->getContent());
        $view->setData('naviTree', $treeComponent);
        $view->setData('naviNode', $naviNode);

        return $view->render();
    }

}

?>
