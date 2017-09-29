<?php

class RegisterView {
	private static $register = 'RegisterView::Login';
	private static $name = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $repeatPassword = 'RegisterView::RepeatPassword';
	//private static $cookieName = 'RegisterView::CookieName';
	//private static $cookiePassword = 'RegisterView::CookiePassword';
	private static $messageId = 'RegisterView::Message';

	public function response($message) {
        return $this->generateRegisterForm($message);
	}
	
	private function generateRegisterForm($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$repeatPassword . '">Repeat password :</label>
					<input type="password" id="' . self::$repeatPassword . '" name="' . self::$repeatPassword . '" />
					
					<input type="submit" name="' . self::$register . '" value="register" />
				</fieldset>
			</form>
		';
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