<?php

class RegisterView {
	private static $register = 'RegisterView::Register';
	private static $registerName = 'RegisterView::UserName';
	private static $registerPassword = 'RegisterView::Password';
	private static $repeatPassword = 'RegisterView::PasswordRepeat';
	private static $registerMessageId = 'RegisterView::Message';

	public function generateRegisterFormHTML(string $message, string $userName) : string {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$registerMessageId . '">' . $message . '</p>
					
					<label for="' . self::$registerName . '">Username :</label>
					<input type="text" id="' . self::$registerName . '" name="' . self::$registerName . '" value="' . $userName . '" />

					<label for="' . self::$registerPassword . '">Password :</label>
					<input type="password" id="' . self::$registerPassword . '" name="' . self::$registerPassword . '" />

					<label for="' . self::$repeatPassword . '">Repeat password :</label>
					<input type="password" id="' . self::$repeatPassword . '" name="' . self::$repeatPassword . '" />
					
					<input type="submit" name="' . self::$register . '" value="register" />
				</fieldset>
			</form>
		';
    }


    public function registerAttempted() : bool {
		return isset($_REQUEST[self::$register]);
    }
    
    public function getRequestRegisterPassword() : string{
		if (isset ($_REQUEST[self::$registerPassword])) {
			return $_REQUEST[self::$registerPassword];
		}
		throw new Exception("No request password available");
	}

	public function getRequestRegisterUserName() : string{
		if (isset ($_REQUEST[self::$registerName])) {
			return $_REQUEST[self::$registerName];
		}
		throw new Exception("No request password available");
	}

	public function getRequestRepeatPassword() : string{
		if (isset ($_REQUEST[self::$repeatPassword])) {
			return $_REQUEST[self::$repeatPassword];
		}
		throw new Exception("No request password available");
	}
    
}