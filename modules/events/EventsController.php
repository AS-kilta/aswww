<?php
include_once 'modules/events/Event.php';

class EventsController extends ModuleController {
    function __construct() {
        $this->moduleName = 'events';
    }

    function renderShort() {
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        $view = $this->loadView('events');

        // Get events
        $events = Event::getFutureEvents(getLanguage());
        $view->setData('events', $events);

        // Check editing privileges
        if ($auth->hasPrivilege($user, 'events', null, 'edit')) {
            $view->setData('editable', true);
        }

        // Limit the number of events
        $view->setData('numEvents', 4);

        return $view->render();
    }

    function renderEdit() {
        // Check editing privileges
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        if (!$auth->hasPrivilege($user, 'events', null, 'edit')) {
            redirect('/admin/login');
            return;
        }

        $view = $this->loadView('edit');

        $id = getGetOrPost('id');
        $view->setData('id', $id);

        // Load the events to be edited (all language versions)
        if ($id != false && $id != 'new') {
            $versions = Event::loadAll($id);
        }

        // Create those language versions that are missing.
        // (all if this is a new entry)
        $languages = getConfiguredLanguages();
        foreach ($languages as $language) {
            if (!isset($versions[$language])) {
                $events = new Event();
                $events->setLang($language);
                $versions[$language] = $events;
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
                if (!$version->isValid()) {
                    continue;
                }

                if ($version->save($id) == false) {
                    $failed = true;
                }
                $id = $version->getId();
            }

            if ($failed) {
                $view->setData('warning', 'Failed to save');
            } else {
                $view->setData('success', 'Event saved');
            }
        }

        // If delete is requested
        if (getPost('delete')) {
            redirect('/events/delete?id=' . $id);
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
            redirect('/admin/login');
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

    public function renderDefault() {
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        $view = $this->loadView('list');

        // Get events
        $events = Event::getFutureEvents(getLanguage());
        $view->setData('events', $events);

        // Check editing privileges
        if ($auth->hasPrivilege($user, 'events', null, 'edit')) {
            $view->setData('editable', true);
        }

        return $view->render();
    }

    private function parsePost($prefix, $event) {
        $heading = getPost($prefix . '-heading');
        if ($heading !== false) {
            $event->setHeading($heading);
        }

        $time = getPost($prefix . '-time');
        if ($time !== false) {
            $event->setTime($time);
        }

        $timestamp = getPost($prefix . '-timestamp');
        if ($timestamp !== false) {
            $event->setTimestamp($timestamp);
        }

        $place = getPost($prefix . '-place');
        if ($place !== false) {
            $event->setPlace($place);
        }

        $description = getPost($prefix . '-description');
        if ($description !== false) {
            $event->setDescription($description);
        }
    }
}
?>
