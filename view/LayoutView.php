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
    $form = $this->registerView->response();
    $link = '<a href="?">Back to login</a>';
    $this->render($form, $link);
  }

  public function renderLogOut() {
    $form = $this->loginView->response();
    $link = '';
    $this->render($form, $link);
  }
  
  public function renderLogIn() {
    $form = $this->loginView->response();
    $link = '<a href="?register">Register a new user</a>';
    $this->render($form, $link);
  }

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

  private function renderIsLoggedIn() : string {
    if ($this->session->getLoggedIn()) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }

}
