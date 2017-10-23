<?php

class RegisterView {
	private $session;

	private static $register = 'RegisterView::Register';
	private static $registerName = 'RegisterView::UserName';
	private static $registerPassword = 'RegisterView::Password';
	private static $repeatPassword = 'RegisterView::PasswordRepeat';
	private static $registerMessageId = 'RegisterView::Message';

	/**
	* Constructor initiates this RegisterView object
	* @param Session $session model representation of this server session.
	*/
	public function __construct(Session $session) {
		$this->session = $session;
	}

	/**
	 * Create HTTP response for register action and for Get-request to register
	 * @return string representation of a form 
	 */
	public function response() : string {
		return $this->generateRegisterFormHTML();
	}

	/**
	* Generate HTML code on the output buffer for the register form
	* @return string representation of a form 
	*/
	public function generateRegisterFormHTML() : string {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$registerMessageId . '">' . $this->session->getMessage(). '</p>
					
					<label for="' . self::$registerName . '">Username :</label>
					<input type="text" id="' . self::$registerName . '" name="' . self::$registerName . '" value="' . $this->session->getUserName() . '" />

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
	 * Returns true if last user request is a submit of the register-form
	 * @return bool 
	 */
    public function registerAttempted() : bool {
		return isset($_REQUEST[self::$register]);
    }
	
	/**
	 * Returns submitted password if set, else emty string
	 * @return string Password - $_REQUEST[self::$registerPassword];
	 */
    public function getRequestRegisterPassword() : string{
		if (isset ($_REQUEST[self::$registerPassword])) {
			return $_REQUEST[self::$registerPassword];
		}
		return "";
	}

	/**
	 * Returns submitted user name if set, else emty string
	 * @return string UserName - $_REQUEST[self::$registerName];
	 */
	public function getRequestRegisterUserName() : string{
		if (isset ($_REQUEST[self::$registerName])) {
			return $_REQUEST[self::$registerName];
		}
		return "";
	}

	/**
	 * Returns submitted repeated password if set, else emty string
	 * @return string pwrepeat - for safety reasons
	 */
	public function getRequestRepeatPassword() : string{
		if (isset ($_REQUEST[self::$repeatPassword])) {
			return $_REQUEST[self::$repeatPassword];
		}
		return "";
	}
    
}