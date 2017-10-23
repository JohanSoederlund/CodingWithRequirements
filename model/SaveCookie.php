<?php

class SaveCookie {

private static $pathToDir = "../databasecookies/";
private static $fileEnd = ".txt";
//Predetermined maximum cookie life
private static $secondsValid = 600;
//Allowed regex-chars, for security and stability
private static $whiteListCharacters = "/[^a-zA-Z0-9_]/";

    public function __construct() {}

    /**
	* Modelling values to use when creating cookies and saves the data for revalidation 
    * @param string $name - name of the cookie and used in randomize hashing of password cookie
    * @return Cookie cookie - model object for cookie-values 
	*/
    public function getNewCookie(string $name) : Cookie {
        $cookie = new Cookie($name, password_hash($name, PASSWORD_DEFAULT), self::$secondsValid);

        $fileName = preg_replace(self::$whiteListCharacters, "a", $name);
        $pathToFile = self::$pathToDir . $fileName . self::$fileEnd;
        $expire = date('YmdHis', time() + (self::$secondsValid));
        $data = sprintf("%s %s %s ", $cookie->getName(), $cookie->getPassword(), $expire);
        file_put_contents($pathToFile, $data);
        return $cookie;
    }

    /**
    * Same operation on name param as in cookie creation, hash verification.
    * @param string $password - hashed cookie password to verify
    * @param string $name - name to validate against saved cookies
    * @return bool true when matching a file in DB, password is verifyable with name param,
    *               cookie timestamp is not outdated.
    */
    public function validateCookie(string $password, string $name) : bool {
        $fileName = preg_replace(self::$whiteListCharacters, "a", $name);
        $pathToFile = self::$pathToDir . $fileName . self::$fileEnd;
        
        if (file_exists($pathToFile)) {
            $cookie = file($pathToFile);
            $cookieArray = explode(' ', $cookie[0]);
            if (time()-strtotime($cookieArray[2]) < 0 && $password == $cookieArray[1] && password_verify($name, $cookieArray[1])) {
                return true;
            }
        } 
        return false;
    }
}