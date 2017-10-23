<?php

class LayoutView {

  private $loginView;
  private $registerView;
  private $dateTimeView;

  private $session;

  /**
  * Constructor initiates this LayoutView object
  * @param LoginView $loginView object responsible for login/logout form
  * @param RegisterView $registerView object responsible for register form
  * @param DateTimeView $dateTimeView object responsible for date and time output
	* @param Session $session model representation of this server session
	*/
  public function __construct (LoginView $loginView, RegisterView $registerView, DateTimeView $dateTimeView, Session $session) {
    $this->loginView = $loginView;
    $this->registerView = $registerView;
    $this->dateTimeView = $dateTimeView;
    $this->session = $session;
  }

  /**
  * Directing routing to register form
	*/
  public function renderRegister() {
    $form = $this->registerView->response();
    $link = '<a href="?">Back to login</a>';
    $this->render($form, $link);
  }

  /**
  * Directing routing to LogOut form
	*/
  public function renderLogOut() {
    $form = $this->loginView->response();
    $link = '';
    $this->render($form, $link);
  }
  
  /**
  * Directing routing to LogIn form
	*/
  public function renderLogIn() {
    $form = $this->loginView->response();
    $link = '<a href="?register">Register a new user</a>';
    $this->render($form, $link);
  }

  /**
  * Final rendering of response document
  * @param string $form login/logout/register
  * @param string $link Html a-tag, href for query-string
	*/
  private function render(string $form, string $link){
    echo '<!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8">
        <title>Login Example</title>
      </head>
      <body>
        <h1>Assignment 2</h1>
        ' . $link . '
        ' . $this->renderIsLoggedIn() . '
        
        <div class="container">
            ' . $form . '
            ' . $this->dateTimeView->show() . '
        </div>
       </body>
    </html>
  ';
  }

  /**
  * Html Header for logged in/out state
	*/
  private function renderIsLoggedIn() : string {
    if ($this->session->getLoggedIn()) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }

}
