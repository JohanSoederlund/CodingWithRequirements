<?php

class LoginView {
	//request key-names
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

	/**
	* Constructor initiates this LoginView object
	* @param Session $session model representation of this server session.
	*/
	public function __construct(Session $session) {
		$this->saveCookie = new SaveCookie();
		$this->session = $session;
	}

	/**
	 * Create HTTP response for logOut- and logIn action, and for Get-request to index
	 * @return string representation of a form 
	 */
	public function response() : string {
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
	* @return string representation of a form 
	*/
	private function generateLogoutButtonFormHTML() : string {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $this->session->getMessage() .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	/**
	 * Generate HTML code on the output buffer for the login form
	 * @return string representation of a form 
	 */
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

	/**
	 * Returns user-toggle for keep logged in, (used to create cookies)
	 * @return bool
	 */
	private function isKeepLoggedInSet() : bool {
		return (isset($_REQUEST[self::$keep])); 
	}

	/**
	 * Returns true if last user request is a submit of the login-form
	 * @return bool 
	 */
	public function isLoginAttempted() : bool {
		return isset($_REQUEST[self::$login]);
	}

	/**
	 * Returns true if last user request is a submit of the logout-form
	 * @return bool 
	 */
	public function isLogoutAttempted() : bool {
		return isset($_REQUEST[self::$logout]);
	}

	/**
	 * Returns submitted username if set, else emty string
	 * @return string username - $_REQUEST[self::$name]
	 */
	public function getRequestUserName() : string {
		if (isset ($_REQUEST[self::$name])){
			return $_REQUEST[self::$name];
		}
		return "";
	}

	/**
	 * Returns submitted password if set, else emty string
	 * @return string password - $_REQUEST[self::$password];
	 */
	public function getRequestPassword() : string{
		if (isset ($_REQUEST[self::$password])) {
			return $_REQUEST[self::$password];
		}
		return "";
	}

	/**
	 * Sets randomized-hash "password"-cookie for secure validation of cookie integrity, finite active time
	 * Sets username cookie for username recovery, if session is lost.
	 */
	private function setCookie() {
		$cookie = $this->saveCookie->getNewCookie($this->session->getUserName());
		setcookie(self::$cookieName, $cookie->getName(), time() + $cookie->getTimeValid());
		setcookie(self::$cookiePassword, $cookie->getPassword(), time() + $cookie->getTimeValid());
		if ($this->isKeepLoggedInSet()) {
			$this->session->setMessage("Welcome and you will be remembered");
		}
	}

	/**
	 * Force deletions of cookies
	 */
	private function unsetCookie() {
		setcookie(self::$cookieName, "", time() - (2*60*60));
		setcookie(self::$cookiePassword, "", time() - (2*60*60));
	}
	
	/**
	 * Returns submitted true if validation is successful, by cookie-model.
	 * @return bool valid, affects routing and session state
	 */
	public function isCookieValid() : bool {
		if ($this->isCookieSet()) { 
			if ($this->saveCookie->validateCookie($_COOKIE[self::$cookiePassword], $_COOKIE[self::$cookieName])) {
				$this->session->setMessage("Welcome back with cookie");
				$this->session->createFromCookie($_COOKIE[self::$cookieName]);
				return true;
			}
			$this->session->setMessage("Wrong information in cookies");
		}
		return false;
	}

	/**
	 * Checks if cookies exist at all
	 * @return bool true if cookies contain any value
	 */
	public function isCookieSet() : bool {
		return isset ($_COOKIE[self::$cookiePassword]) && isset ($_COOKIE[self::$cookieName]);
	}
	
}