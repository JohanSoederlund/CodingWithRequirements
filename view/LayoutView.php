<?php


class LayoutView {

  private $loginView;
  private $registerView;
  private $dateTimeView;
  private $session;

  public function __construct (LoginView $loginView, RegisterView $registerView, DateTimeView $dateTimeView, Session $session) {
    $this->loginView = $loginView;
    $this->registerView = $registerView;
    $this->dateTimeView = $dateTimeView;
    $this->session = $session;
  }

  public function renderRegister() {
    $form = $this->registerView->generateRegisterFormHTML($this->session->getMessage(), $this->session->getUserName());
    $this->render($form);
  }

  public function renderLogOut() {
    $form = $this->loginView->generateLogoutButtonFormHTML($this->session->getMessage());
    $this->render($form);
  }
  
  public function renderLogIn() {
    $form = $this->loginView->generateLoginFormHTML($this->session->getMessage(), $this->session->getUserName());
    $this->render($form);
  }

  private function render(string $form){
    echo '<!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8">
        <title>Login Example</title>
      </head>
      <body>
        <h1>Assignment 2</h1>
        ' . $this->renderLink() . '
        ' . $this->renderIsLoggedIn() . '
        
        <div class="container">
            ' . $form . '
            ' . $this->dateTimeView->show() . '
        </div>
       </body>
    </html>
  ';
  }

  private function renderLink() {
    if (isset($_SERVER['QUERY_STRING']) && explode("=", $_SERVER['QUERY_STRING'])[0] == "register" ) {
      return '<a href="index.php?">Back to login</a>';
    } else if (!$this->session->getLoggedIn()) {
      return '<a href="index.php?' . htmlentities("register") . '">Register a new user</a>';
    }
    return "";
  }
  
  private function renderIsLoggedIn() {
    if ($this->session->getLoggedIn()) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }

}
