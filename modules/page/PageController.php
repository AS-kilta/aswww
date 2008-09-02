<?php
include_once 'modules/page/Page.php';

class PageController extends ModuleController {
    function __construct() {
        $this->moduleName = 'page';
    }

    function renderDefault() {
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();

        $page = new Page();
        $page->load($this->getContentId(), getLanguage());

        $view = $this->loadView('show');
        $view->setData('htmlContent', $page->getContent());

        // Check privileges
        if ($auth->hasPrivilege($user, 'page', false, 'edit')) {
            $view->setData('editable', true);
        }

        return $view->render();
    }

    function renderEdit() {
        $view = $this->loadView('edit');

        // Check privileges
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();

        if (!$auth->hasPrivilege($user, 'page', false, 'edit')) {
            redirect('/admin/login');
            return;
        }

        // Load navi
        $navi = Navi::getInstance();
        $naviTree = $navi->getNaviTree();
        $naviNode = $this->getNaviNode();

        // Load the navinode
        if ($naviNode == null) {
            $naviNode = new NaviNode();
            $naviNode->setId('new');
            $naviNode->setContentModule($this->moduleName);
        }
        $view->setData('naviNode', $naviNode);

        // Load the page to be edited (all language versions)
        $id = $this->getContentId();
        if ($id !== false && $id != 'new') {
            $pageVersions = Page::loadAll($id);
        } else {
            $redirectNeeded = true;
        }

        // If language versions are missing, create empty objects
        $languages = getConfiguredLanguages();
        foreach ($languages as $language) {
            if (!isset($pageVersions[$language])) {
                $page = new Page();
                $page->setLang($language);
                $pageVersions[$language] = $page;
            }
        }
        $view->setData('pageVersions', $pageVersions);
        $view->setData('naviNode', $naviNode);

        // Read content from postdata
        foreach($pageVersions as $version) {
            $content = getPost($version->getLang() . '-content');
            if ($content !== false) {
                $version->setContent($content);
            }
        }

        if (getPost('position') !== false) {
            $naviNode->position = getPost('position');
        }

        if (getPost('preview') !== false || getPost('save') !== false) {
            $naviNode->hidden = getPost('hidden') == '1';
        }


        // Read navisettings from postdata
        if (getPost('preview') || getPost('save')) {
            foreach ($languages as $language) {
                $title = getPost($language . '-title');
                $naviNode->setTitle($language, $title);

                $url = getPost($language . '-url');
                $naviNode->setUrl($language, $url);

                if (strlen($url) > 0) {
                    $redirectDestination = $naviNode->getCumulativeUrl($language);
                }
            }

            $parent = getPost('parent');
            if ($parent != $naviNode->getParentId()) {
                //addLogEntry('INFO', "new parent: $parent, old parent: " . $naviNode->getParentId());
                $redirectNeeded = true;
            }

            $naviNode->setParentId($parent);
        }


        // Save edited content
        if (getPost('save')) {
            foreach($pageVersions as $version) {
                $version->save($id);
                $id = $version->getId();
            }

            $naviNode->setContentId($id);
            $naviNode->save();

            if ($redirectNeeded) {
                redirect($redirectDestination);
                return;
            }

            $view->setData('success', 'Page saved');
        }

        // Delete if requested
        if (getPost('delete') && $id != false) {
            foreach($pageVersions as $version) {
                $version->delete();
            }

            $naviNode->delete();

            redirect('/navi');
            return;
        }

        // Load the navi tree
        $treeComponent = $naviTree->renderParentSelector($naviNode->getId(), $naviNode->getParentId());

        // Load view
        $view->setData('naviTree', $treeComponent);
        $view->setData('naviNode', $naviNode);

        return $view->render();
    }

    public function render404() {
        return "<h1>Page not found</h1>\n";
    }

}

?>
