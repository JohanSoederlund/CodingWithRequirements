<?php

require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

require_once('model/Session.php');
require_once('model/User.php');


class Router {
    
    private $loginView;
    private $registerView;
    private $dateTimeView;
    private $layoutView;
    private $user;
    private $session;
   
    public function __construct() {
        $this->session = new Session();
        $this->loginView = new LoginView();
        $this->registerView = new RegisterView();
        $this->dateTimeView = new DateTimeView();
        $this->layoutView = new LayoutView($this->loginView, $this->registerView, $this->dateTimeView, $this->session);
        $this->route();
    }

    private function route() {
        if ($this->loginView->loginAttempted() && !$this->session->getLoggedIn()) {
            $this->user = new User($this->loginView->getRequestUserName(), $this->loginView->getRequestPassword(), "", false, $this->loginView->keepLoggedIn());
            $this->session->setMessage($this->user->getMessage());
            $this->session->setUser($this->loginView->getRequestUserName(), $this->loginView->getRequestPassword(), $this->loginView->keepLoggedIn(), $this->user->getInvalidCredentials());
            if ($this->user->getInvalidCredentials()) {
                $this->layoutView->renderLogIn();
            } else {
                $this->layoutView->renderLogOut();
            }
        } elseif ($this->loginView->logoutAttempted() && $this->session->getLoggedIn()) {
            $this->session->terminateSession();
            $this->layoutView->renderLogin();
        } elseif ($this->registerView->registerAttempted() && !$this->session->getLoggedIn()) {
            $this->user = new User($this->registerView->getRequestRegisterUserName(), $this->registerView->getRequestRegisterPassword(), $this->registerView->getRequestRepeatPassword(), true, false);
            $this->session->setMessage($this->user->getMessage());
            if ($this->user->getInvalidCredentials()) {
                $this->layoutView->renderRegister();
            } else {
                $this->layoutView->renderLogIn();
            }
        } elseif (isset($_SERVER['QUERY_STRING']) && explode("=", $_SERVER['QUERY_STRING'])[0] == "register") {
            if ($this->session->getLoggedIn()){
                $this->session->setMessage("You cannot register when logged in.");
                $this->layoutView->renderLogOut();
            } else {
                $this->layoutView->renderRegister();
            }
        } elseif ($this->session->getLoggedIn()) {
            $this->layoutView->renderLogOut();
        } else {
            $this->layoutView->renderLogIn();
        }

    }

    

}
