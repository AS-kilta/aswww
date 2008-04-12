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
        $view->setData('editable', true);
        $view->setData('htmlContent', $page->getContent());

        return $view->render();
    }

    function renderEdit() {
        // TODO: check privileges


        // Load the page to be edited
        $page = new Page();
        $page->load($this->getContentId(), getLanguage());

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
        $view = $this->loadView('edit');
        $view->setData('htmlContent', $page->getContent());
        $view->setData('editableContent', $page->getContent());

        return $view->render();
    }


}

?>
