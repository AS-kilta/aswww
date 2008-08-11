<?php
include_once 'modules/events/Event.php';

class EventsController extends ModuleController {
    function __construct() {
        $this->moduleName = 'events';
    }

    function renderDefault() {
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        $view = $this->loadView('events');

        // Get events
        $events = Event::getCurrentEvents(getLanguage(), 4);
        $view->setData('events', $events);

        // Check editing privileges
        if ($auth->hasPrivilege($user, 'events', null, 'edit')) {
            $view->setData('editable', true);
        }

        return $view->render();
    }

    function renderNew() {
        // Check editing privileges
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        if (!$auth->hasPrivilege($user, 'events', null, 'new')) {
            redirect('admin/login');
            return;
        }

        $view = $this->loadView('edit');

        // Create a new entry. Create language versions.
        $languages = getConfiguredLanguages();
        $versions = Array();
        foreach ($languages as $language) {
            $event = new Event();
            $event->setLang($language);
            $versions[$language] = $event;
        }

        $view->setData('id', 'new');
        $view->setData('versions', $versions);

        return $view->render();
    }

    function renderEdit() {
        // Check editing privileges
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        if (!$auth->hasPrivilege($user, 'events', null, 'edit')) {
            redirect('admin/login');
            return;
        }

        $view = $this->loadView('edit');

        // Load the events to be edited
        $id = getGetOrPost('id');
        if ($id != false && $id != 'new') {
            // Edit an existing entry. Load all language versions.
            $versions = Event::loadAll($id);
            $view->setData('id', $id);
        } else {
            // Create a new entry. Create language versions.
            $languages = getConfiguredLanguages();
            $versions = Array();
            foreach ($languages as $language) {
                $events = new Event();
                $events->setLang($language);
                $versions[$language] = $events;
            }

            $view->setData('id', 'new');
        }
        $view->setData('versions', $versions);

        // Check that items were found
        if (count($versions) < 1) {
            $view = new View('views/error.php');
            $view->setData('heading', "Event $id not found");
            $view->setData('back', 'events/list');
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
                $view->setData('success', 'Event saved');
            }
        }

        // If delete is requested
        if (getPost('delete')) {

            /*foreach($versions as $version) {
                if ($version->getId() != 'new') {
                    $version->delete();
                }
            }*/

            redirect('news/delete?id=' . $id);
            return;
        }

        return $view->render();
    }

    public function renderDelete() {
        $view = $this->loadView('list');

        // Check editing privileges
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        if (!$auth->hasPrivilege($user, 'events', null, 'edit')) {
            redirect('admin/login');
            return;
        }

        // Load all language versions
        $id = getGetOrPost('id');
        if ($id != false && $id != 'new') {
            $versions = Event::loadAll($id);

            if (count($versions) > 0) {
                // Delete
                foreach($versions as $version) {
                    $version->delete();
                }
                $view->setData('success', 'Items deleted');
            }
        }

        // Get events
        $events = Event::getEvents();
        $view->setData('events', $events);
        $view->setData('editable', true);

        return $view->render();
    }

    public function renderList() {
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        $view = $this->loadView('list');

        // Get events
        $events = Event::getEvents();
        $view->setData('events', $events);

        // Check editing privileges
        if ($auth->hasPrivilege($user, 'events', null, 'edit')) {
            $view->setData('editable', true);
        }

        return $view->render();
    }

    private function parsePost($prefix, $events) {
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
