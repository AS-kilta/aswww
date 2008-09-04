<?php
include_once 'modules/poll/Poll.php';

class PollController extends ModuleController {
    function __construct() {
        $this->moduleName = 'poll';
    }

    function renderDefault() {
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();

        // Get poll
        $poll = Poll::load(getLanguage());

        if ($poll->hasVoted(getUserIP())) {
            $view = $this->loadView('results');
        } else {
            $view = $this->loadView('poll');
        }

        $view->setData('poll', $poll);

        // Check editing privileges
        if ($auth->hasPrivilege($user, 'poll', null, 'edit')) {
            $view->setData('editable', true);
        }

        return $view->render();
    }

    function renderEdit() {
        // Check editing privileges
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();
        if (!$auth->hasPrivilege($user, 'poll', null, 'edit')) {
            redirect('/admin/login');
            return;
        }

        // Check that language is specified
        $lang = getGetOrPost('lang');
        if (strlen($lang) < 1) {
            $view = new View('views/error.php');
            $view->setData('heading', 'Language not specified');
            return $view->render();
        }

        // Load the poll to be edited
        $id = getGetOrPost('id');
        $view = $this->loadView('edit');

        if ($id != false && $id != 'new') {
            // Edit an existing entry
            $poll = Poll::load($lang, $id);

            if ($poll == null) {
                $view = new View('views/error.php');
                $view->setData('heading', "Poll $id not found");
                return $view->render();
            }
        }

        if (!$poll) {
            // Create a new entry
            $poll = new Poll();
            $poll->setLang($lang);
        }

        $view->setData('id', $poll->id);
        $view->setData('poll', $poll);

        // Read postdata
        $this->parsePost($poll);

        // Save edited content
        if (getPost('save')) {
            if ($poll->save() == false) {
                $view->setData('warning', 'Failed to save');
            } else {
                $view->setData('success', 'Poll saved');
            }
        }

        // If delete is requested
        if (getPost('delete')) {
            $poll->delete();
            redirect('/');
        }

        return $view->render();
    }

    function renderVote() {
        $auth = Auth::getInstance();
        $user = $auth->getCurrentUser();

        // Load poll
        $poll = Poll::load(getLanguage());

        // Vote
        $selection = getPost('poll');
        if ($selection) {
            $poll->vote($selection, getUserIP());
            $view = $this->loadView('results');
        } else {
            $view = $this->loadView('poll');
        }

        $view->setData('poll', $poll);

        return $view->render();
    }

    function renderResults() {
        $view = $this->loadView('results');
        $poll = Poll::load(getLanguage());
        $view->setData('poll', $poll);

        return $view->render();
    }


    private function parsePost($poll) {
        $question = getPost('question');
        if ($question == false) {
            return;
        }

        $poll->setQuestion($question);

        $poll->options = array();
        for ($i = 0; ; $i++) {
            $option = getPost("option-$i");
            if ($option === false) {
                break;
            }

            $poll->options[$i] = $option;
        }
    }

}

?>
