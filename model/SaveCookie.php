<?php

require_once('model/Cookie.php');

class SaveCookie {

private static $pathToDir = "../databasecookies/";
private static $fileEnd = ".txt";
private static $secondsValid = 600;
private static $whiteListCharacters = "/[^a-zA-Z0-9_]/";

    public function __construct() {
        
    }

    public function getNewCookie(string $name) : Cookie {
        $cookie = new Cookie($name, password_hash($name, PASSWORD_DEFAULT), self::$secondsValid);

        $fileName = preg_replace(self::$whiteListCharacters, "a", $name);
        $pathToFile = self::$pathToDir . $fileName . self::$fileEnd;
        $expire = date('YmdHis', time() + (self::$secondsValid));
        $data = sprintf("%s %s %s ", $cookie->getName(), $cookie->getPassword(), $expire);
        file_put_contents($pathToFile, $data);
        return $cookie;
    }

    public function validateCookie(string $password, string $name) {
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