<?php

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	public function generateLogoutButtonFormHTML(string $message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	public function generateLoginFormHTML(string $message, string $userName) : string {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $userName . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	public function keepLoggedIn() : bool {
		if (isset($_REQUEST[self::$keep])) {
			return true;
		}
		return false;
	}

	public function loginAttempted() : bool {
		return isset($_REQUEST[self::$login]);
	}

	public function logoutAttempted() : bool {
		return isset($_REQUEST[self::$logout]);
	}

	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName() : string {
		//RETURN REQUEST VARIABLE: USERNAME
		if (isset ($_REQUEST[self::$name])){
			return $_REQUEST[self::$name];
		}
		throw new Exception("No request username available");
	}

	public function getRequestPassword() : string{
		if (isset ($_REQUEST[self::$password])) {
			return $_REQUEST[self::$password];
		}
		throw new Exception("No request username available");
	}

	
/*
getRequestCookieName() or getCookieName()
	public function getRequestCookieName(){
		return $_REQUEST[self::$cookieName];
		return self::$cookieName;
	}
getRequestCookiePassword or getCookiePassword
	public function getRequestCookiePassword(){
		return $_REQUEST[self::$cookiePassword];
		return self::$cookiePassword;
	}
	*/
	
	
}