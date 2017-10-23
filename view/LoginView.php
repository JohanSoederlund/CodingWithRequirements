<?php

//require_once('view/LayoutView.php');
require_once('model/SaveCookie.php');

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = "LoginView::CookiePassword";
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	private $session;
	private $saveCookie;

	public function __construct(Session $session) {
		//get_resource_type($session);
		$this->saveCookie = new SaveCookie();
		$this->session = $session;
	}

	public function response() {
		if ($this->session->getLoggedIn()) {
			if ($this->isCookieSet() || $this->isKeepLoggedInSet()) {
				$this->setCookie();
			} 
			return $this->generateLogoutButtonFormHTML();
		} elseif ($this->isCookieSet()) {
			$this->unsetCookie();
		}
		
		return $this->generateLoginFormHTML();
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonFormHTML() {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $this->session->getMessage() .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	private function generateLoginFormHTML() : string {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $this->session->getMessage() . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->session->getUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	private function isKeepLoggedInSet() : bool {
		if (isset($_REQUEST[self::$keep])) {
			return true;
		}
		return false;
	}

	public function isLoginAttempted() : bool {
		return isset($_REQUEST[self::$login]);
	}

	public function isLogoutAttempted() : bool {
		return isset($_REQUEST[self::$logout]);
	}

	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName() : string {
		//RETURN REQUEST VARIABLE: USERNAME
		if (isset ($_REQUEST[self::$name])){
			return $_REQUEST[self::$name];
		}
		return "";
	}

	public function getRequestPassword() : string{
		if (isset ($_REQUEST[self::$password])) {
			return $_REQUEST[self::$password];
		}
		return "";
	}

	private function setCookie() {
		$cookie = $this->saveCookie->getNewCookie($this->session->getUserName());
		setcookie(self::$cookieName, $cookie->getName(), time() + $cookie->getTimeValid());
		setcookie(self::$cookiePassword, $cookie->getPassword(), time() + $cookie->getTimeValid());
		if ($this->isKeepLoggedInSet()) {
			$this->session->setMessage("Welcome and you will be remembered");
		}
	}

	private function unsetCookie() {
		setcookie(self::$cookieName, "", time() - (2*60*60));
		setcookie(self::$cookiePassword, "", time() - (2*60*60));
	}
	
	public function isCookieValid() : bool {
		if ($this->isCookieSet()) { 
			if ($this->saveCookie->validateCookie($_COOKIE[self::$cookiePassword], $_COOKIE[self::$cookieName])) {
				$this->session->setMessage("Welcome back with cookies");
				$this->session->createFromCookie($_COOKIE[self::$cookiePassword]);
				return true;
			}
			$this->session->setMessage("Wrong information in cookies");
		}
		return false;
	}

	public function isCookieSet() : bool {
		return isset ($_COOKIE[self::$cookiePassword]) && isset ($_COOKIE[self::$cookieName]);
	}
	
}