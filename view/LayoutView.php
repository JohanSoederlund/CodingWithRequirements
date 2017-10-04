<?php


class LayoutView {
  
  //Views inherit from View with abstract method response????
  //param: LoginView $v CHANGE TO: $v
  //$v can be RegisterView, LoginView, LogoutView 
  public function render($message, $register, $isLoggedIn, LoginView $v, DateTimeView $dtv) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderLink($register) . '
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $v->response($register, $isLoggedIn, $message) . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }
  private function renderLink($register) {
    if ($register) {
      return '<a href="?">Back to login</a>';
    }
    else {
      return '<a href="?register=1">Register a new user</a>';
    }
  }
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }


}
