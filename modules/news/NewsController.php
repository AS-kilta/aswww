<?php
include_once 'modules/news/News.php';

class NewsController extends ModuleController {
    function __construct() {
        $this->moduleName = 'news';
    }

    function renderDefault() {
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        $view = $this->loadView('news');

        // Get news
        $news = News::getNews(getLanguage());
        $view->setData('news', $news);

        // Check editing privileges
        if ($auth->hasPrivilege($user, 'news', null, 'edit')) {
            $view->setData('editable', true);
        }

        return $view->render();
    }

    function renderEdit() {
        // Check editing privileges
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        if (!$auth->hasPrivilege($user, 'news', null, 'edit')) {
            redirect('admin/login');
            return;
        }

        $view = $this->loadView('edit');

        // Load the news to be edited
        $id = getGetOrPost('id');
        if ($id != false && $id != 'new') {
            $versions = News::loadAll($id);
            $view->setData('id', $id);
        } else {
            $fi = new News();
            $fi->setLang('fi');
            $en = new News();
            $en->setLang('en');
            $versions = Array('fi'=>$fi, 'en'=>$en);
            $view->setData('id', 'new');
        }
        $view->setData('versions', $versions);

        // Check that items were found
        if (count($versions) < 1) {
            $view = new View('views/error.php');
            $view->setData('heading', "News $id not found");
            $view->setData('back', 'news/list');
            return $view->render();
        }

        // Read postdata
        foreach($versions as $version) {
            $this->parsePost($version->getLang(), $version);
        }

        // Save edited content
        if (getPost('save')) {
            if ($id == 'new') {
                $id = $version->nextVal();
            }

            foreach($versions as $version) {
                if ($version->save($id) == false) {
                    $failed = true;
                }
            }

            if ($failed) {
                $view->setData('warning', 'Failed to save');
            } else {
                $view->setData('success', 'Page saved');
            }
        }

        return $view->render();
    }

    public function renderList() {
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        $view = $this->loadView('list');

        // Get news
        $news = News::getNews();
        $view->setData('news', $news);

        // Check editing privileges
        if ($auth->hasPrivilege($user, 'news', null, 'edit')) {
            $view->setData('editable', true);
        }

        return $view->render();
    }

    private function parsePost($prefix, $news) {
        $heading = getPost($prefix . '-heading');
        if ($heading !== false) {
            $news->setHeading($heading);
        }

        $content = getPost($prefix . '-content');
        if ($content !== false) {
            $news->setContent($content);
        }
    }
}

?>
