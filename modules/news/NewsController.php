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

        // Limit the number of news
        $view->setData('numNews', 4);

        return $view->render();
    }

    function renderEdit() {
        // Check editing privileges
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        if (!$auth->hasPrivilege($user, 'news', null, 'edit')) {
            redirect('/admin/login');
            return;
        }

        $view = $this->loadView('edit');

        $id = getGetOrPost('id');
        $view->setData('id', $id);

        // Load the news to be edited (all language versions)
        if ($id != false && $id != 'new') {
            $versions = News::loadAll($id);
        }

        // Create those language versions that are missing.
        // (all if this is a new entry)
        $languages = getConfiguredLanguages();
        foreach ($languages as $language) {
            if (!isset($versions[$language])) {
                $news = new News();
                $news->setLang($language);
                $versions[$language] = $news;
            }
        }

        $view->setData('versions', $versions);

        // Read postdata into $versions
        foreach($versions as $version) {
            $this->parsePost($version->getLang(), $version);
        }

        // Save edited content
        if (getPost('save')) {

            foreach($versions as $version) {
                if ($version->save($id) == false) {
                    $failed = true;
                }
                $id = $version->getId();
            }

            if ($failed) {
                $view->setData('warning', 'Failed to save');
            } else {
                $view->setData('success', 'News saved');
            }
        }

        // If delete is requested
        if (getPost('delete')) {
            /*foreach($versions as $version) {
                if ($version->getId() != 'new') {
                    $version->delete();
                }
            }*/

            redirect('/news/delete?id=' . $id);
            return;
        }

        return $view->render();
    }

    public function renderDelete() {
        $view = $this->loadView('list');

        // Check editing privileges
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        if (!$auth->hasPrivilege($user, 'news', null, 'edit')) {
            redirect('/admin/login');
            return;
        }

        // Load all language versions
        $id = getGetOrPost('id');
        if ($id != false && $id != 'new') {
            $versions = News::loadAll($id);

            if (count($versions) > 0) {
                // Delete
                foreach($versions as $version) {
                    $version->delete();
                }
                $view->setData('success', 'Items deleted');
            }
        }

        // Get news
        $news = News::getNews();
        $view->setData('news', $news);
        $view->setData('editable', true);

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
