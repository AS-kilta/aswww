<?php
include_once 'modules/page/Page.php';

class PageController extends ModuleController {
    function __construct() {
        $this->moduleName = 'page';
    }
    
    function render($node) {
        // XXX: Old fashion stuff. This should be automtic.
        $action = getGet('pageAction');

        switch ($action) {
            case 'edit':
                return $this->renderEdit($node);
                break;

            default:
                return $this->renderDefault($node);
        }
    }

    function renderDefault($node) {
        $page = new Page();
        $page->load($node->getContentId(), getLanguage());

        return $page->getContent();
    }

    function renderEdit($node) {
        // TODO: check privileges

        
        // Load the page to be edited
        $page = new Page();
        $page->load($node->getContentId(), getLanguage());

        if (getPost('save')) {
            // Save edited content
            $page->setContent(getPost('content'));
            $page->save();
            return $page->getContent();
        } else if (getPost('preview')) {
            // Preview edited content
            $page->setContent(getPost('content'));
        }

        // Load view
        $view = new View('modules/page/views/edit.php');
        $view->setData('htmlContent', $page->getContent());
        $view->setData('editableContent', $page->getContent());

        return $view->render();
    }


}

?>
