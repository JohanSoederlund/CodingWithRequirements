<?php

require_once('model/Cookie.php');

class SaveCookie {

private static $pathToDir = "../DB/";
private static $fileEnd = ".txt";
private static $secondsValid = 600;
private static $whiteListCharacters = "/[^a-zA-Z0-9_]/";

    public function __construct() {
        
    }

    public function getNewCookie(string $name) : Cookie {
        $fileName = preg_replace(self::$whiteListCharacters, "a", $name);
        $pathToFile = self::$pathToDir . $fileName . self::$fileEnd;
        
        $cookie = new Cookie($name, password_hash($name, PASSWORD_DEFAULT), self::$secondsValid);
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
            var_dump($cookie);
            $cookieArray = array();
            array_push($cookieArray, explode(' ', $cookie[0]));
            var_dump($cookieArray);
            var_dump(time()-strtotime($cookieArray[0][2]) < self::$secondsValid);
            if ($password == $cookieArray[0][1] && password_verify($name, $cookieArray[0][1])) {
                return true;
            }
        } 
        return false;
    }

}