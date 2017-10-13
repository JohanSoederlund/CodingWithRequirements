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
        if (isset($_GET)) {
            $this->routeGetCall();
        } if (isset($_POST)) {
            $this->routePostCall();
        }
    }

    private function routePostCall(){
        if ($this->loginView->loginAttempted() && !$this->session->getLoggedIn()) {
            $this->user = new User($this->loginView->getRequestUserName(), $this->loginView->getRequestPassword(), "", false, $this->loginView->keepLoggedIn());
            $this->session->setMessage($this->user->getMessage());
            $this->session->setUser($this->loginView->getRequestUserName(), $this->loginView->getRequestPassword(), $this->loginView->keepLoggedIn(), $this->user->getInvalidCredentials());
            header('Location: /index.php');
        } elseif ($this->loginView->logoutAttempted() && $this->session->getLoggedIn()) {
            $this->session->terminateSession();
            header('Location: /index.php');
        } elseif ($this->registerView->registerAttempted() && !$this->session->getLoggedIn()) {
            $this->user = new User($this->registerView->getRequestRegisterUserName(), $this->registerView->getRequestRegisterPassword(), $this->registerView->getRequestRepeatPassword(), true, false);
            $this->session->setMessage($this->user->getMessage());
            if ($this->user->getInvalidCredentials()) {
                header('Location: /index.php?register');
                //header("Location: /index.php?". $_SERVER['?register']);
                //header('Location: /index.php');
                //$this->layoutView->renderLogIn();
            } else {
                header('Location: /index.php?');
                $this->layoutView->renderRegister();
            }

        }


                /*
                if ($this->formTemplates->loginAttempted() && !$this->session->getLoggedIn()) {
                    
                    $this->user = new User($this->formTemplates->getRequestUserName(), $this->formTemplates->getRequestPassword(), "", false, $this->formTemplates->keepLoggedIn());
                    $this->session->setMessage($this->user->getMessage());
                    $this->session->setUser($this->formTemplates->getRequestUserName(), $this->formTemplates->getRequestPassword(), $this->formTemplates->keepLoggedIn(), $this->user->getInvalidCredentials());
                    //header('Location: /index.php');
                    if ($this->session->getLoggedIn()) {
                        $this->layoutView->renderLogOut(); 
                    } else {
                        throw new Exception("INVALID login? why?");
                    }
                } elseif ($this->formTemplates->logoutAttempted() && $this->session->getLoggedIn()) {
                    $this->session->terminate();
                    //header('Location: /index.php');
                    $this->layoutView->renderLogIn(); 
                } elseif ($this->formTemplates->registerAttempted()) {
                    $this->user = new User($this->formTemplates->getRequestRegisterUserName(), $this->formTemplates->getRequestRegisterPassword(), $this->formTemplates->getRequestRepeatPassword(), true, false);
                    $this->session->setMessage($this->user->getMessage());
                    if (!$this->user->getInvalidCredentials()) {
                        //header('Location: /index.php');
                        $this->layoutView->renderLogIn();
                    } else {
                        //header("Location: /index.php?". $_SERVER['?register']);
                        $this->layoutView->renderRegister();
                    }
                } */
            }

    private function routeGetCall(){
        
        //var_dump(urldecode($_SERVER['QUERY_STRING']));
        if (isset($_SERVER['QUERY_STRING']) && explode("=", $_SERVER['QUERY_STRING'])[0] == "register") {
            var_dump($this->session->getLoggedIn());
            if ($this->session->getLoggedIn()){
                $this->session->setMessage("You cannot register when logged in.");
                header('Location: /index.php');
            }
            $this->layoutView->renderRegister();
        } elseif ($this->session->getLoggedIn()) {
            $this->layoutView->renderLogOut();
        } else {
            $this->layoutView->renderLogIn();
        }
        
/*
        if ($this->session->isLoggedInCookieValid() && !$this->session->getLoggedIn()) {
            $this->session->logInWithCookies();
            if ($this->session->getLoggedIn()){
                //header('Location: /index.php');
                $this->layoutView->renderLogOut();
            } else {
                throw new Exception("BAD COOKIES");
            }
        } elseif (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] == "register"){
            $this->layoutView->renderRegister();
        } else {
            //header('Location: /index.php');
            $this->layoutView->renderLogin();
        }*/
    }

    

}
