<?php

class FormTemplates {
	private static $login = 'LoginView::Login';
	private static $register = 'RegisterView::Register';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $registerName = 'RegisterView::UserName';
	private static $registerPassword = 'RegisterView::Password';
	private static $repeatPassword = 'RegisterView::PasswordRepeat';
	private static $registerMessageId = 'RegisterView::Message';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	public function __construct(){
		
	}
	

	public function generateRegisterFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$registerMessageId . '">' . $message . '</p>
					
					<label for="' . self::$registerName . '">Username :</label>
					<input type="text" id="' . self::$registerName . '" name="' . self::$registerName . '" value="" />

					<label for="' . self::$registerPassword . '">Password :</label>
					<input type="password" id="' . self::$registerPassword . '" name="' . self::$registerPassword . '" />

					<label for="' . self::$repeatPassword . '">Repeat password :</label>
					<input type="password" id="' . self::$repeatPassword . '" name="' . self::$repeatPassword . '" />
					
					<input type="submit" name="' . self::$register . '" value="register" />
				</fieldset>
			</form>
		';
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	public function generateLogoutButtonFormHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	public function getLoginForm(){
		return "";
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	public function generateLoginFormHTML($message) : string {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $_SESSION['UserName'] . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	public function keepLoggedIn() {
		if (isset($_REQUEST[self::$keep])) {
			return true;
		}
		return false;
	}

	public function loginAttempted() {
		return isset($_REQUEST[self::$login]);
	}

	public function logoutAttempted() {
		return isset($_REQUEST[self::$logout]);
	}

	public function registerAttempted() {
		return isset($_REQUEST[self::$register]);
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName(){
		//RETURN REQUEST VARIABLE: USERNAME
		if (isset ($_REQUEST[self::$name])){
			return $_REQUEST[self::$name];
		}
		throw new Exception("No request username available");
	}

	public function getRequestPassword(){
		if (isset ($_REQUEST[self::$password])) {
			return $_REQUEST[self::$password];
		}
		throw new Exception("No request username available");
	}

	public function getRequestRegisterPassword(){
		if (isset ($_REQUEST[self::$registerPassword])) {
			return $_REQUEST[self::$registerPassword];
		}
		throw new Exception("No request password available");
	}

	public function getRequestRegisterUserName(){
		if (isset ($_REQUEST[self::$registerName])) {
			return $_REQUEST[self::$registerName];
		}
		throw new Exception("No request password available");
	}

	public function getRequestRepeatPassword(){
		if (isset ($_REQUEST[self::$repeatPassword])) {
			return $_REQUEST[self::$repeatPassword];
		}
		throw new Exception("No request password available");
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



	//private static $messageId = 'LoginView::Message';
	
	
}